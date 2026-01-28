<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Message;
use Exception;

class MailService
{
    /**
     * L'adresse email par défaut de l'expéditeur
     */
    private string $fromEmail;

    /**
     * Le nom par défaut de l'expéditeur
     */
    private string $fromName;

    /**
     * Constructeur du service
     */
    public function __construct()
    {
        $this->fromEmail = config('mail.from.address', 'noreply@example.com');
        $this->fromName = config('mail.from.name', 'Mon Application');
    }

    /**
     * Envoyer un email simple
     *
     * @param string|array $to Destinataire(s)
     * @param string $subject Sujet de l'email
     * @param string $message Corps du message (peut être HTML)
     * @param array $options Options supplémentaires (cc, bcc, attachments, etc.)
     * @return bool
     */
    public function send($to, string $subject, string $message, array $options = []): bool
    {
        try {
            Mail::send([], [], function (Message $mail) use ($to, $subject, $message, $options) {
                // Destinataire(s)
                $mail->to($to);

                // Expéditeur
                $fromEmail = $options['from_email'] ?? $this->fromEmail;
                $fromName = $options['from_name'] ?? $this->fromName;
                $mail->from($fromEmail, $fromName);

                // Sujet
                $mail->subject($subject);

                // Corps du message
                if (isset($options['is_html']) && $options['is_html']) {
                    $mail->html($message);
                } else {
                    // Détecter automatiquement si c'est du HTML
                    if ($this->isHtml($message)) {
                        $mail->html($message);
                    } else {
                        $mail->text($message);
                    }
                }

                // CC
                if (isset($options['cc'])) {
                    $mail->cc($options['cc']);
                }

                // BCC
                if (isset($options['bcc'])) {
                    $mail->bcc($options['bcc']);
                }

                // Reply-To
                if (isset($options['reply_to'])) {
                    $mail->replyTo($options['reply_to']);
                }

                // Pièces jointes
                if (isset($options['attachments']) && is_array($options['attachments'])) {
                    foreach ($options['attachments'] as $attachment) {
                        if (is_string($attachment)) {
                            $mail->attach($attachment);
                        } elseif (is_array($attachment)) {
                            $mail->attach(
                                $attachment['path'],
                                $attachment['options'] ?? []
                            );
                        }
                    }
                }

                // Priorité
                if (isset($options['priority'])) {
                    $mail->priority($options['priority']);
                }
            });

            Log::info('Email sent successfully', [
                'to' => is_array($to) ? implode(', ', $to) : $to,
                'subject' => $subject,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Email sending failed', [
                'to' => is_array($to) ? implode(', ', $to) : $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            if (config('app.debug')) {
                throw $e;
            }

            return false;
        }
    }

    /**
     * Envoyer un email avec une vue Blade
     *
     * @param string|array $to Destinataire(s)
     * @param string $subject Sujet de l'email
     * @param string $view Nom de la vue Blade
     * @param array $data Données à passer à la vue
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendView($to, string $subject, string $view, array $data = [], array $options = []): bool
    {
        try {
            Mail::send($view, $data, function (Message $mail) use ($to, $subject, $options) {
                $mail->to($to);
                $mail->subject($subject);

                $fromEmail = $options['from_email'] ?? $this->fromEmail;
                $fromName = $options['from_name'] ?? $this->fromName;
                $mail->from($fromEmail, $fromName);

                if (isset($options['cc'])) {
                    $mail->cc($options['cc']);
                }

                if (isset($options['bcc'])) {
                    $mail->bcc($options['bcc']);
                }

                if (isset($options['reply_to'])) {
                    $mail->replyTo($options['reply_to']);
                }

                if (isset($options['attachments']) && is_array($options['attachments'])) {
                    foreach ($options['attachments'] as $attachment) {
                        if (is_string($attachment)) {
                            $mail->attach($attachment);
                        } elseif (is_array($attachment)) {
                            $mail->attach(
                                $attachment['path'],
                                $attachment['options'] ?? []
                            );
                        }
                    }
                }
            });

            Log::info('Email with view sent successfully', [
                'to' => is_array($to) ? implode(', ', $to) : $to,
                'subject' => $subject,
                'view' => $view,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Email with view sending failed', [
                'to' => is_array($to) ? implode(', ', $to) : $to,
                'subject' => $subject,
                'view' => $view,
                'error' => $e->getMessage(),
            ]);

            if (config('app.debug')) {
                throw $e;
            }

            return false;
        }
    }

    /**
     * Envoyer un email de bienvenue
     *
     * @param string $to Email du destinataire
     * @param string $userName Nom de l'utilisateur
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendWelcomeEmail(string $to, string $userName, array $options = []): bool
    {
        $subject = $options['subject'] ?? 'Bienvenue sur ' . $this->fromName;
        
        $message = $options['message'] ?? "
            <h2>Bienvenue {$userName}!</h2>
            <p>Nous sommes ravis de vous compter parmi nous.</p>
            <p>Merci de vous être inscrit sur notre plateforme.</p>
        ";

        return $this->send($to, $subject, $message, array_merge($options, ['is_html' => true]));
    }

    /**
     * Envoyer un email de réinitialisation de mot de passe
     *
     * @param string $to Email du destinataire
     * @param string $resetUrl URL de réinitialisation
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendPasswordResetEmail(string $to, string $resetUrl, array $options = []): bool
    {
        $subject = $options['subject'] ?? 'Réinitialisation de votre mot de passe';
        
        $message = $options['message'] ?? "
            <h2>Réinitialisation de mot de passe</h2>
            <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
            <p>Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe:</p>
            <p><a href='{$resetUrl}' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Réinitialiser mon mot de passe</a></p>
            <p>Si vous n'avez pas demandé cette réinitialisation, ignorez cet email.</p>
            <p><small>Ce lien expirera dans 60 minutes.</small></p>
        ";

        return $this->send($to, $subject, $message, array_merge($options, ['is_html' => true]));
    }

    /**
     * Envoyer un email de vérification
     *
     * @param string $to Email du destinataire
     * @param string $verificationUrl URL de vérification
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendVerificationEmail(string $to, string $verificationUrl, array $options = []): bool
    {
        $subject = $options['subject'] ?? 'Vérifiez votre adresse email';
        
        $message = $options['message'] ?? "
            <h2>Vérification de votre adresse email</h2>
            <p>Merci de vous être inscrit!</p>
            <p>Pour activer votre compte, veuillez cliquer sur le lien ci-dessous:</p>
            <p><a href='{$verificationUrl}' style='background-color: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Vérifier mon email</a></p>
            <p>Si vous n'avez pas créé de compte, ignorez cet email.</p>
        ";

        return $this->send($to, $subject, $message, array_merge($options, ['is_html' => true]));
    }

    /**
     * Envoyer un email de notification
     *
     * @param string $to Email du destinataire
     * @param string $title Titre de la notification
     * @param string $content Contenu de la notification
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendNotification(string $to, string $title, string $content, array $options = []): bool
    {
        $subject = $options['subject'] ?? $title;
        
        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #333;'>{$title}</h2>
                <div style='background-color: #f5f5f5; padding: 20px; border-radius: 5px;'>
                    {$content}
                </div>
            </div>
        ";

        return $this->send($to, $subject, $message, array_merge($options, ['is_html' => true]));
    }

    /**
     * Envoyer un email avec un code de vérification
     *
     * @param string $to Email du destinataire
     * @param string $code Code de vérification
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendVerificationCode(string $to, string $code, array $options = []): bool
    {
        $subject = $options['subject'] ?? 'Votre code de vérification';
        
        $message = $options['message'] ?? "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; text-align: center;'>
                <h2>Code de vérification</h2>
                <p>Votre code de vérification est:</p>
                <div style='background-color: #f0f0f0; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                    <h1 style='color: #2196F3; letter-spacing: 5px; margin: 0;'>{$code}</h1>
                </div>
                <p><small>Ce code expirera dans 10 minutes.</small></p>
                <p><small>Ne partagez ce code avec personne.</small></p>
            </div>
        ";

        return $this->send($to, $subject, $message, array_merge($options, ['is_html' => true]));
    }

    /**
     * Envoyer un email en masse
     *
     * @param array $recipients Liste des destinataires
     * @param string $subject Sujet de l'email
     * @param string $message Corps du message
     * @param array $options Options supplémentaires
     * @return array Résultats de l'envoi
     */
    public function sendBulk(array $recipients, string $subject, string $message, array $options = []): array
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($recipients as $recipient) {
            try {
                $sent = $this->send($recipient, $subject, $message, $options);
                
                $results[] = [
                    'recipient' => $recipient,
                    'success' => $sent,
                ];

                if ($sent) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            } catch (Exception $e) {
                $results[] = [
                    'recipient' => $recipient,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
                $failureCount++;
            }
        }

        return [
            'total' => count($recipients),
            'success' => $successCount,
            'failed' => $failureCount,
            'results' => $results,
        ];
    }

    /**
     * Envoyer un email avec un template HTML personnalisé
     *
     * @param string $to Email du destinataire
     * @param string $subject Sujet de l'email
     * @param string $title Titre du template
     * @param string $content Contenu principal
     * @param array $options Options supplémentaires
     * @return bool
     */
    public function sendTemplate(string $to, string $subject, string $title, string $content, array $options = []): bool
    {
        $buttonText = $options['button_text'] ?? null;
        $buttonUrl = $options['button_url'] ?? null;
        $footerText = $options['footer_text'] ?? 'Merci de faire partie de notre communauté.';

        $buttonHtml = '';
        if ($buttonText && $buttonUrl) {
            $buttonHtml = "
                <p style='text-align: center; margin: 30px 0;'>
                    <a href='{$buttonUrl}' style='background-color: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>{$buttonText}</a>
                </p>
            ";
        }

        $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            </head>
            <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;'>
                <table width='100%' cellpadding='0' cellspacing='0' style='background-color: #f4f4f4; padding: 20px;'>
                    <tr>
                        <td align='center'>
                            <table width='600' cellpadding='0' cellspacing='0' style='background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
                                <tr>
                                    <td style='background-color: #2196F3; padding: 30px; text-align: center;'>
                                        <h1 style='color: white; margin: 0; font-size: 28px;'>{$title}</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='padding: 40px 30px;'>
                                        {$content}
                                        {$buttonHtml}
                                    </td>
                                </tr>
                                <tr>
                                    <td style='background-color: #f9f9f9; padding: 20px 30px; text-align: center; border-top: 1px solid #e0e0e0;'>
                                        <p style='color: #666; margin: 0; font-size: 14px;'>{$footerText}</p>
                                        <p style='color: #999; margin: 10px 0 0 0; font-size: 12px;'>© " . date('Y') . " {$this->fromName}. Tous droits réservés.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>
        ";

        return $this->send($to, $subject, $message, array_merge($options, ['is_html' => true]));
    }

    /**
     * Vérifier si une chaîne contient du HTML
     *
     * @param string $string
     * @return bool
     */
    private function isHtml(string $string): bool
    {
        return preg_match('/<[^<]+>/', $string) !== 0;
    }

    /**
     * Valider une adresse email
     *
     * @param string $email
     * @return bool
     */
    public function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Obtenir l'adresse email de l'expéditeur par défaut
     *
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * Obtenir le nom de l'expéditeur par défaut
     *
     * @return string
     */
    public function getFromName(): string
    {
        return $this->fromName;
    }

    /**
     * Définir l'adresse email de l'expéditeur par défaut
     *
     * @param string $email
     * @return self
     */
    public function setFromEmail(string $email): self
    {
        $this->fromEmail = $email;
        return $this;
    }

    /**
     * Définir le nom de l'expéditeur par défaut
     *
     * @param string $name
     * @return self
     */
    public function setFromName(string $name): self
    {
        $this->fromName = $name;
        return $this;
    }
}