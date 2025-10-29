<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AgoraHistory;
use App\Models\ReserveMeeting;
use App\Models\Session;
use App\Models\Translation\SessionTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use App\Sessions\ZoomOAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Validator;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['new'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:64',
            'date' => 'required|date',
            'duration' => 'required|numeric',
            'link' => ($data['session_api'] == 'local') ? 'required|url' : 'nullable',
            'api_secret' => (in_array($data['session_api'], ['zoom', 'agora', 'jitsi'])) ? 'nullable' : 'required',
            'moderator_secret' => ($data['session_api'] == 'big_blue_button') ? 'required' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        if (!empty($data['session_api']) and $data['session_api'] == 'zoom' and (empty($user->zoomApi) or empty($user->zoomApi->api_key) or empty($user->zoomApi->api_secret))) {
            $error = [
                'zoom-not-complete-alert' => []
            ];

            return response([
                'code' => 422,
                'errors' => $error,
            ], 422);
        }

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $sessionDate = convertTimeToUTCzone($data['date'], $webinar->timezone);

            if ($sessionDate->getTimestamp() < $webinar->start_date) {
                $error = [
                    'date' => [trans('webinars.session_date_must_larger_webinar_start_date', ['start_date' => dateTimeFormat($webinar->start_date, 'j M Y')])]
                ];

                return response([
                    'code' => 422,
                    'errors' => $error,
                ], 422);
            }

            $session = Session::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'date' => $sessionDate->getTimestamp(),
                'duration' => $data['duration'],
                'link' => $data['link'] ?? null,
                'session_api' => $data['session_api'],
                'api_secret' => $data['api_secret'] ?? null,
                'moderator_secret' => $data['moderator_secret'] ?? null,
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'extra_time_to_join' => $data['extra_time_to_join'] ?? null,
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? Session::$Active : Session::$Inactive,
                'created_at' => time()
            ]);

            if (!empty($session)) {
                SessionTranslation::updateOrCreate([
                    'session_id' => $session->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                WebinarChapterItem::makeItem($session->creator_id, $session->chapter_id, $session->id, WebinarChapterItem::$chapterSession);
            }

            if ($data['session_api'] == 'big_blue_button') {
                $this->handleBigBlueButtonApi($session, $user);
            }
            else if ($data['session_api'] == 'zoom') {
                return $this->handleZoomApi($session, $user);
            }
            // else if ($data['session_api'] == 'zoom') {
            //     return $this->createZoomMeeting($session, $webinar);
            // }
            else if ($data['session_api'] == 'agora') {
                $agoraSettings = [
                    'chat' => (!empty($data['agora_chat']) and $data['agora_chat'] == 'on'),
                    'record' => (!empty($data['agora_record']) and $data['agora_record'] == 'on'),
                    'users_join' => true
                ];
                $session->agora_settings = json_encode($agoraSettings);

                $session->save();
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $session = Session::where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (!empty($session)) {
                $session_api = !empty($data['session_api']) ? $data['session_api'] : $session->session_api;

                $validator = Validator::make($data, [
                    'webinar_id' => 'required',
                    'chapter_id' => 'required',
                    'title' => 'required|max:64',
                    'date' => ($session_api == 'local') ? 'required|date' : 'nullable',
                    'duration' => ($session_api == 'local') ? 'required|numeric' : 'nullable',
                    'link' => ($session_api == 'local') ? 'required|url' : 'nullable',
                ]);

                if ($validator->fails()) {
                    return response([
                        'code' => 422,
                        'errors' => $validator->errors(),
                    ], 422);
                }

                if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
                    $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
                    $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
                } else {
                    $data['check_previous_parts'] = false;
                    $data['access_after_day'] = null;
                }


                $sessionDate = $session->date;

                if (!empty($data['date'])) {
                    $sessionDate = convertTimeToUTCzone($data['date'], $webinar->timezone);

                    if ($sessionDate->getTimestamp() < $webinar->start_date) {
                        $error = [
                            'date' => [trans('webinars.session_date_must_larger_webinar_start_date', ['start_date' => dateTimeFormat($webinar->start_date, 'j M Y')])]
                        ];

                        return response([
                            'code' => 422,
                            'errors' => $error,
                        ], 422);
                    }

                    $sessionDate = $sessionDate->getTimestamp();
                }

                $agoraSettings = null;
                if ($session_api == 'agora') {
                    $agoraSettings = [
                        'chat' => (!empty($data['agora_chat']) and $data['agora_chat'] == 'on'),
                        'record' => (!empty($data['agora_record']) and $data['agora_record'] == 'on'),
                        'users_join' => true,
                    ];
                    $agoraSettings = json_encode($agoraSettings);
                }

                $changeChapter = ($data['chapter_id'] != $session->chapter_id);
                $oldChapterId = $session->chapter_id;

                $session->update([
                    'chapter_id' => $data['chapter_id'],
                    'date' => $sessionDate,
                    'duration' => $data['duration'] ?? $session->duration,
                    'link' => $data['link'] ?? $session->link,
                    'session_api' => $session_api,
                    'api_secret' => $data['api_secret'] ?? $session->api_secret,
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? Session::$Active : Session::$Inactive,
                    'agora_settings' => $agoraSettings,
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'extra_time_to_join' => $data['extra_time_to_join'] ?? null,
                    'updated_at' => time()
                ]);

                if ($changeChapter) {
                    WebinarChapterItem::changeChapter($session->creator_id, $oldChapterId, $session->chapter_id, $session->id, WebinarChapterItem::$chapterSession);
                }

                SessionTranslation::updateOrCreate([
                    'session_id' => $session->id,
                    'locale' => mb_strtolower($data['locale']),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                return response()->json([
                    'code' => 200,
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)->first();

        if (!empty($session)) {
            $webinar = Webinar::query()->find($session->webinar_id);

            if ($session->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {
                WebinarChapterItem::where('user_id', $session->creator_id)
                    ->where('item_id', $session->id)
                    ->where('type', WebinarChapterItem::$chapterSession)
                    ->delete();

                $session->delete();
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }

    private function handleZoomApi($session, $user)
    {
        try {
            if (!empty($user->zoomApi) and !empty($user->zoomApi->api_key) and !empty($user->zoomApi->api_secret) and !empty($user->zoomApi->account_id)) {

                $meeting = (new ZoomOAuth())->makeMeeting($session);

                if ($meeting) {
                    return response()->json([
                        'code' => 200,
                    ], 200);
                } else {
                    $session->delete();
                }
            }
        } catch (\Exception $exception) {
            $session->delete();
            //dd($exception);
        }

        return response()->json([
            'code' => 422,
            'status' => 'zoom_token_invalid',
            'zoom_error_msg' => trans('update.zoom_error_msg')
        ], 422);
    }

    private function handleBigBlueButtonConfigs()
    {
        $settings = getFeaturesSettings();

        \Config::set("bigbluebutton.BBB_SECURITY_SALT", !empty($settings['bigbluebutton_security_salt']) ? $settings['bigbluebutton_security_salt'] : '');
        \Config::set("bigbluebutton.BBB_SERVER_BASE_URL", !empty($settings['bigbluebutton_server_base_url']) ? $settings['bigbluebutton_server_base_url'] : '');
    }

    private function handleBigBlueButtonApi($session, $user)
    {
        $this->handleBigBlueButtonConfigs();

        $createMeeting = \Bigbluebutton::initCreateMeeting([
            'meetingID' => $session->id,
            'meetingName' => $session->title,
            'attendeePW' => $session->api_secret,
            'moderatorPW' => $session->moderator_secret,
        ]);

        $createMeeting->setDuration($session->duration);
        \Bigbluebutton::create($createMeeting);

        return true;
    }

    public function joinToBigBlueButton($id)
    {
        $session = Session::where('id', $id)
            ->where('session_api', 'big_blue_button')
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session)) {
            $this->handleBigBlueButtonConfigs();

            $user = auth()->user();

            if ($user->id == $session->creator_id) {
                $url = \Bigbluebutton::join([
                    'meetingID' => $session->id,
                    'userName' => $user->full_name,
                    'password' => $session->moderator_secret
                ]);

                if ($url) {
                    return redirect($url);
                }
            } else {
                $webinar = Webinar::find($session->webinar_id);

                if ($webinar->checkUserHasBought($user)) {

                    $url = \Bigbluebutton::join([
                        'meetingID' => $session->id,
                        'userName' => $user->full_name,
                        'password' => $session->api_secret
                    ]);

                    if ($url) {
                        return redirect($url);
                    }
                }
            }
        }

        abort(404);
    }

    public function joinToAgora($id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)
            ->where('session_api', 'agora')
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session) and !empty($user)) {
            $session->agora_settings = json_decode($session->agora_settings);

            $agoraHistory = AgoraHistory::where('session_id', $session->id)->first();

            if (!empty($agoraHistory) and !empty($agoraHistory->end_at)) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('update.this_live_has_been_ended'),
                    'status' => 'error'
                ];
                return redirect('/panel')->with(['toast' => $toastData]);
            }


            $canAccess = false;
            $streamRole = 'audience'; // host | audience
            $channelName = "session_$session->id";
            $accountName = "user {$user->id}";
            $userName = $user->full_name;
            $canAccessError = trans('update.you_cannot_enter_this_session');

            if ($user->id == $session->creator_id) {
                AgoraHistory::updateOrCreate([
                    'session_id' => $session->id,
                ], [
                    'start_at' => time()
                ]);

                $canAccess = true;
                $streamRole = 'host';
            } else {

                if (!empty($session->reserve_meeting_id)) {
                    $ReserveMeeting = ReserveMeeting::where('id', $session->reserve_meeting_id)
                        ->where('user_id', $user->id)
                        ->where('meeting_type', 'online')
                        ->where('status', \App\Models\ReserveMeeting::$open)
                        ->first();

                    if (!empty($ReserveMeeting)) {
                        $canAccess = true;
                    }
                } else {
                    $webinar = Webinar::find($session->webinar_id);

                    if ($webinar->checkUserHasBought($user)) {
                        $canAccess = true;
                    }
                }

                if (!$canAccess) {
                    $partnerTeachers = !empty($session->webinar->webinarPartnerTeacher) ? $session->webinar->webinarPartnerTeacher->pluck('teacher_id')->toArray() : [];

                    if (in_array($user->id, $partnerTeachers)) {
                        $canAccess = true;
                    }
                }

                if ($canAccess) {
                    $canAccess = (!empty($session->agora_settings) and $session->agora_settings->users_join);
                    if (!$canAccess) {
                        $canAccessError = trans('update.join_to_the_session_has_been_disabled_by_the_instructor');
                    }
                }
            }

            if ($canAccess) {
                $agoraController = new AgoraController();

                $isHost = ($streamRole === 'host');
                $appId = $agoraController->appId;
                $rtcToken = $agoraController->getRTCToken($channelName, $isHost);
                $rtmToken = $agoraController->getRTMToken($accountName);


                $data = [
                    'pageTitle' => trans('update.live_session'),
                    'session' => $session,
                    'isHost' => $isHost,
                    'appId' => $appId,
                    'accountName' => $accountName,
                    'userName' => $userName,
                    'channelName' => $channelName,
                    'rtcToken' => $rtcToken,
                    'rtmToken' => $rtmToken,
                    'streamRole' => $streamRole,
                    'notStarted' => (!$isHost and empty($agoraHistory)),
                    'streamStartAt' => (!$isHost and !empty($agoraHistory)) ? $agoraHistory->start_at : time(),
                    'authUserId' => $user->id,
                    'hostUserId' => $session->creator_id,
                    'sessionStreamType' => $session->getSessionStreamType()
                ];

                return view('web.default.course.agora.index', $data);
            } else {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => $canAccessError,
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
        }

        abort(404);
    }

    // private function getZoomAccessToken()
    // {
    //     $clientId = env('ZOOM_CLIENT_KEY');
    //     $clientSecret = env('ZOOM_CLIENT_SECRET');
    //     $accountId = env('ZOOM_ACCOUNT_ID');

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Basic ' . base64_encode("{$clientId}:{$clientSecret}"),
    //     ])->asForm()->post('https://zoom.us/oauth/token', [
    //         'grant_type' => 'account_credentials',
    //         'account_id' => $accountId,
    //     ]);

    //     if ($response->successful()) {
    //         Log::info('Access Token' . '' . $response->json()['access_token']);
    //         return $response->json()['access_token'];
    //     }

    //     throw new \Exception('Failed to retrieve Zoom access token');
    // }

    // public function createZoomMeeting($session, $webinar)
    // {
    //     $accessToken = $this->getZoomAccessToken();

    //     // Convert Unix timestamp to ISO8601 format
    //     $startTime = now()->utc()->toIso8601String();
    //     // $startTime = Carbon::createFromTimestamp($session->date)->utc()->toIso8601String();

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $accessToken,
    //         'Content-Type' => 'application/json',
    //     ])->post('https://api.zoom.us/v2/users/me/meetings', [
    //         'topic' => $webinar->slug,
    //         'type' => 2, // Scheduled meeting
    //         'start_time' => $startTime,
    //         'duration' => $session->duration,
    //         'settings' => [
    //             'join_before_host' => true,
    //             'waiting_room' => false,
    //             'mute_upon_entry' => true,
    //             'endMeetingOnHostLeave' => true, // Add this setting
    //         ],
    //     ]);

    //     if ($response->successful()) {
    //         // Log::info('meeting creation successful', ['data'=>$response->json()]);
    //         $meetingNumber = $response->json()['id'];
    //         $password = $response->json()['h323_password'];
    //         // Log::info('meeting creation ID', ['data'=>$meetingNumber]);
    //         $session->update([
    //             'zoom_meeting_id' => $meetingNumber,
    //             'zoom_password' => $password,
    //         ]);
    //         // session()->put('meetingNumber', $meetingNumber);
    //         // session()->put('password', $password);
    //         return response()->json([
    //             'code' => 200,
    //         ], 200);
    //     }

    //     return response()->json(['error' => 'Failed to create meeting', 'details' => $response->json()], 400);
    // }


    // public function viewZoom($id)
    // {

    //     if (!auth()->check()) {
    //         return redirect()->route('login')->with('error', 'You need to login to access the class.');
    //     }

    //     try {
    //         $session = Session::where('id', $id)
    //             ->where('session_api', 'zoom')
    //             ->where('status', Session::$Active)
    //             ->first();
    //         // $meetingNumber = session()->get('meetingNumber');
    //         // $password = session()->get('password');
    //         $meetingNumber = $session->zoom_meeting_id;
    //         $password = $session->zoom_password;
    //         $user = auth()->user();
    //         $role = null;
    //         if ($user->role_name == 'user') {
    //             $role = 0;
    //         } else if ($user->role_name == 'teacher') {
    //             $role = 1;
    //         };
    //         $data = [
    //             'meetingNumber' => $meetingNumber,
    //             'password' => $password,
    //             'username' => $user->full_name,
    //             'role' => $role,
    //             // Add other parameters here in the future
    //         ];
    //         // Log::info('meetingNumber in view', ['data'=>$meetingNumber]);
    //         // Log::info('password in view', ['data'=>$password]);
    //         return view('web.default.course.zoom', $data);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function generateSignature(Request $request)
    // {

    //     // Log::info('Signature Request:', ['data'=>$request]);
    //     $key = env('ZOOM_SDK_KEY');
    //     $secret = env('ZOOM_SDK_SECRET');
    //     $meeting_number = (int)$request->meetingNumber;
    //     $role = (int)$request->role;
    //     // Calculate issued at and expiration times
    //     $iat = time();        // Issued at (current timestamp)
    //     $exp = $iat + 3600;   // Expiration time (1 hour from now)


    //     // $decodedSecret = base64_decode($secret);

    //     $payload = [
    //         "appKey" => $key,
    //         'mn' => $meeting_number,
    //         'role' => $role,
    //         'iat' => $iat,
    //         'exp' => $exp,
    //         'tokenExp' => $exp
    //     ];
    //     $encode = JWT::encode($payload, $secret, 'HS256');

    //     // Log::info('encode',['data'=>$encode]);
    //     // Log::info('decode',['data'=>$decodedSecret]);
    //     // Log::info('Signature Payload:', $payload);
    //     return response()->json(['signature' => $encode])
    //         ->header('Cache-Control', 'no-store');
    // }

    public function endAgora($id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session) and !empty($user)) {
            $webinar = Webinar::query()->find($session->webinar_id);

            if ($session->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {
                $agoraHistory = AgoraHistory::where('session_id', $session->id)
                    ->whereNull('end_at')
                    ->first();

                if (!empty($agoraHistory)) {
                    $agoraHistory->update([
                        'end_at' => time()
                    ]);

                    return response()->json([
                        'code' => 200
                    ]);
                }
            }
        }

        return response()->json([
            'code' => 422
        ]);
    }

    public function toggleUsersJoinToAgora($id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)
            //->where('status', Session::$Active)
            ->first();

        if (!empty($session)) {
            $webinar = Webinar::query()->find($session->webinar_id);

            if ($session->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                $sessionSettings = json_decode($session->agora_settings);

                $userJoin = (!empty($sessionSettings) and !empty($sessionSettings->users_join) and $sessionSettings->users_join) ? false : true; // toggle users_join

                $sessionSettings->users_join = $userJoin;

                $session->update([
                    'agora_settings' => json_encode($sessionSettings)
                ]);

                return response()->json([
                    'code' => 200,
                    'heading' => trans('public.request_success'),
                    'text' => $userJoin ? trans('update.joining_users_to_the_session_is_enabled') : trans('update.joining_users_to_the_meeting_was_stopped'),
                    'icon' => $userJoin ? 'success' : 'error',
                ]);
            }
        }

        return response()->json([
            'code' => 422
        ]);
    }

    public function joinToJitsi($id)
    {
        $user = auth()->user();
        $session = Session::where('id', $id)
            ->where('session_api', 'jitsi')
            ->where('status', Session::$Active)
            ->first();

        if (!empty($session) and !empty($user)) {
            $canAccess = false;
            $webinar = Webinar::find($session->webinar_id);

            if (!empty($webinar)) {
                $role = 'participant';

                if ($user->id == $session->creator_id or $webinar->canAccess($user)) {
                    $canAccess = true;
                    $role = "moderator";
                } else if ($webinar->checkUserHasBought($user)) {
                    $canAccess = true;
                }

                if ($canAccess) {

                    $data = [
                        'pageTitle' => trans('update.jitsi_live_class'),
                        'session' => $session,
                        'role' => $role,
                    ];

                    return view('web.default.course.jitsi.join_live', $data);
                }
            }
        }

        abort(404);
    }
}
