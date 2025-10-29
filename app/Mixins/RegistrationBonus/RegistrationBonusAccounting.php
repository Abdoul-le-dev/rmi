<?php

namespace App\Mixins\RegistrationBonus;

use App\Models\Accounting;
use App\Models\Affiliate;
use App\Models\Sale;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationBonusAccounting
{
    public function __construct()
    {

    }

    public function storeRegistrationBonusInstantly($user)
    {
        $registrationBonusSettings = getRegistrationBonusSettings();

        if (!$user->enable_registration_bonus or empty($registrationBonusSettings['status']) or empty($registrationBonusSettings['registration_bonus_amount'])) {
            return false;
        }

        $bonusAmount = !empty($user->registration_bonus_amount) ? $user->registration_bonus_amount : $registrationBonusSettings['registration_bonus_amount'];
        $bonusWallet = $registrationBonusSettings['bonus_wallet'];

        $typeAccount = ($bonusWallet == 'income_wallet') ? Accounting::$income : Accounting::$asset;

        if (!empty($registrationBonusSettings['unlock_registration_bonus_instantly'])) {
            // As soon as the user registers, the bonus will be activated.

            Accounting::createRegistrationBonusUserAmountAccounting($user->id, $bonusAmount, $typeAccount);
        }
    }


    public function storeRegistrationBonus($user)
    {
        $registrationBonusSettings = getRegistrationBonusSettings();
        // Log::info('inside storeRegistrationBonus');
        // Log::info('registrationBonusSettings', ['setting' => $registrationBonusSettings]);

        if (!$user->enable_registration_bonus or empty($registrationBonusSettings['status']) or empty($registrationBonusSettings['registration_bonus_amount'])) {
            // Log::info('here in false');
            return false;
        }

        $bonusAmount = !empty($user->registration_bonus_amount) ? $user->registration_bonus_amount : $registrationBonusSettings['registration_bonus_amount'];
        // Log::info('bonusAmount', ['data' => $bonusAmount]);
        $bonusWallet = $registrationBonusSettings['bonus_wallet'];
        // Log::info('bonusWallet', ['data' => $bonusWallet]);
        $typeAccount = ($bonusWallet == 'income_wallet') ? Accounting::$income : Accounting::$asset;
        // Log::info('typeAccount', ['data' => $typeAccount]);

        if (empty($registrationBonusSettings['unlock_registration_bonus_instantly'])) {
            // Log::info('here checking unlock_registration_bonus_instantly');
            $numberOfReferredUsers = 0; // How many people must register through the link or individual code to unlock the prize
            $purchaseAmountForUnlockingBonus = 0;
            $checkJustHasPurchase = false;

            if (!empty($registrationBonusSettings['unlock_registration_bonus_with_referral']) and !empty($registrationBonusSettings['number_of_referred_users'])) {
                // Log::info('here checking unlock_registration_bonus_with_referral');
                $numberOfReferredUsers = $registrationBonusSettings['number_of_referred_users'];
            }

            if (!empty($registrationBonusSettings['enable_referred_users_purchase']) and !empty($registrationBonusSettings['purchase_amount_for_unlocking_bonus'])) {
                // Log::info('here checking enable_referred_users_purchase');
                $purchaseAmountForUnlockingBonus = $registrationBonusSettings['purchase_amount_for_unlocking_bonus'];

                /*
                * Users who are referred by the individual link must buy that amount in order for the condition of money release to be established
                * (this amount is calculated separately for each user).
                * Also, if this field is empty, it means that the amount is not a criterion for us,
                * the only thing that matters is that the user has made a purchase.
                *  with any amount (the amount charged to the purchase account is not taken into account)
                * */
            } elseif (!empty($registrationBonusSettings['enable_referred_users_purchase'])) {
                // Log::info('here checking checkJustHasPurchase');
                $checkJustHasPurchase = true;
            }

            $unlockedBonus = true;

            if (!empty($numberOfReferredUsers)) {
                // Log::info('here checking referredUsersCount');
                $referredUsersCount = Affiliate::query()->where('affiliate_user_id', $user->id)->count();
                // Log::info('referredUsersCount', ['data' => $referredUsersCount]);
                // Log::info('numberOfReferredUsers', ['data' => $numberOfReferredUsers]);

                if ($referredUsersCount < $numberOfReferredUsers) {
                    // Log::info('here checking $referredUsersCount < $numberOfReferredUsers');
                    $unlockedBonus = false;
                }

                if ($unlockedBonus and (!empty($purchaseAmountForUnlockingBonus) or $checkJustHasPurchase)) {
                    // Log::info('here checking referredUsersId');
                    $referredUsersId = Affiliate::query()->where('affiliate_user_id', $user->id)
                        ->pluck('referred_user_id')
                        ->toArray();

                    if (!empty($referredUsersId)) {
                        // Log::info('here checking sales');
                        $sales = Sale::query()->select('buyer_id', DB::raw('sum(total_amount) as totalAmount'))
                            ->whereIn('buyer_id', $referredUsersId)
                            ->whereNull('refund_at')
                            ->groupBy('buyer_id')
                            ->orderBy('totalAmount', 'desc')
                            ->get();

                        $reachedCount = 0;

                        foreach ($sales as $sale) {
                            if ($checkJustHasPurchase and $sale->totalAmount > 0) {
                                // Log::info('here checking reachedCount1');
                                $reachedCount += 1;
                            } else if (!empty($purchaseAmountForUnlockingBonus) and $sale->totalAmount >= $purchaseAmountForUnlockingBonus) {
                                // Log::info('here checking reachedCount2');
                                $reachedCount += 1;
                            }
                        }

                        if ($reachedCount < $numberOfReferredUsers) {
                            // Log::info('here checking in if part unlockedBonus');
                            $unlockedBonus = false;
                        }
                    } else {
                        // Log::info('here checking in else part unlockedBonus');
                        $unlockedBonus = false;
                    }
                }
            } else {
                // Log::info('here checking in outer else part unlockedBonus');
                $unlockedBonus = false;
            }

            // Log::info('unlockedBonus', ['data' => $unlockedBonus]);
            if ($unlockedBonus) {
                // Log::info('here checking in last unlockedBonus section');
                // Log::info('data', ['user' => $user]);
                Accounting::createRegistrationBonusUserAmountAccounting($user->id, $bonusAmount, $typeAccount);

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[amount]' => handlePrice($bonusAmount),
                ];
                sendNotification("registration_bonus_unlocked", $notifyOptions, $user->id);
                sendNotification("registration_bonus_unlocked_for_admin", $notifyOptions, 1);
            }
        }
    }

    public function checkBonusAfterSale($buyerId)
    {
        // Log::info('inside checkBonusAfterSale');
        $checkReferred = Affiliate::query()
            ->where('referred_user_id', $buyerId)
            ->first();
        // Log::info('checkReferred', ['data' => $checkReferred]);

        if (!empty($checkReferred)) {
            $affiliateUser = User::query()->where('id', $checkReferred->affiliate_user_id)->first();
            // Log::info('affiliateUser', ['data' => $affiliateUser]);
            if (!empty($affiliateUser)) {
                // Log::info('inside affiliateUser Block');
                $this->storeRegistrationBonus($affiliateUser);
            }
        }
    }
}
