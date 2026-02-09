<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajuster selon vos besoins d'autorisation
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'send_to_users' => 'nullable|boolean',
            'custom_emails' => 'nullable|string',
            'excel_file' => [
                'nullable',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120', // 5MB max
            ],
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'send_to_users.boolean' => 'Le champ "envoyer aux utilisateurs" doit être vrai ou faux.',
            'excel_file.mimes' => 'Le fichier doit être au format Excel (.xlsx, .xls) ou CSV.',
            'excel_file.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
            'subject.required' => 'Le sujet est obligatoire.',
            'subject.max' => 'Le sujet ne doit pas dépasser 255 caractères.',
            'content.required' => 'Le contenu du mail est obligatoire.',
        ];
    }

    /**
     * Préparer les données pour la validation
     */
    protected function prepareForValidation(): void
    {
        // Convertir la checkbox en boolean
        $this->merge([
            'send_to_users' => $this->has('send_to_users') && $this->send_to_users === 'on' ? true : (bool) $this->send_to_users,
        ]);
    }

    /**
     * Valider après la validation principale
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $sendToUsers = $this->send_to_users;
            $hasCustomEmails = !empty($this->custom_emails) && trim($this->custom_emails) !== '';
            $hasExcelFile = $this->hasFile('excel_file');

            // Vérifier qu'au moins une source d'emails est fournie
            if (!$sendToUsers && !$hasCustomEmails && !$hasExcelFile) {
                $validator->errors()->add(
                    'recipients',
                    'Veuillez fournir au moins une source d\'emails : cocher "Envoyer à tous les utilisateurs", saisir des emails ou importer un fichier Excel.'
                );
            }

            // Valider les emails personnalisés si fournis
            if ($hasCustomEmails) {
                $emails = $this->parseCustomEmails($this->custom_emails);
                
                if (empty($emails)) {
                    $validator->errors()->add(
                        'custom_emails',
                        'Aucun email valide trouvé dans la liste fournie.'
                    );
                } else {
                    $invalidEmails = [];

                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $invalidEmails[] = $email;
                        }
                    }

                    if (!empty($invalidEmails)) {
                        // Limiter l'affichage à 5 emails invalides
                        $displayInvalid = array_slice($invalidEmails, 0, 5);
                        $remaining = count($invalidEmails) - count($displayInvalid);
                        
                        $errorMessage = 'Les emails suivants sont invalides : ' . implode(', ', $displayInvalid);
                        if ($remaining > 0) {
                            $errorMessage .= " (et $remaining autre(s))";
                        }
                        
                        $validator->errors()->add('custom_emails', $errorMessage);
                    }
                }
            }

            // Valider le fichier Excel si fourni
            if ($hasExcelFile) {
                $file = $this->file('excel_file');
                $extension = $file->getClientOriginalExtension();
                
                // Vérifier que l'extension est bien supportée
                if (!in_array(strtolower($extension), ['xlsx', 'xls', 'csv'])) {
                    $validator->errors()->add(
                        'excel_file',
                        'Le fichier doit avoir une extension .xlsx, .xls ou .csv'
                    );
                }

                // Vérifier la taille (5MB = 5120KB)
                if ($file->getSize() > 5120 * 1024) {
                    $validator->errors()->add(
                        'excel_file',
                        'Le fichier ne doit pas dépasser 5 Mo.'
                    );
                }
            }
        });
    }

    /**
     * Parser les emails personnalisés
     */
    private function parseCustomEmails(string $emails): array
    {
        // Séparer par virgule, point-virgule ou retour à la ligne
        $emailList = preg_split('/[,;\n\r]+/', $emails);
        
        // Nettoyer et filtrer les emails vides
        return array_filter(array_map('trim', $emailList), function($email) {
            return !empty($email);
        });
    }

    /**
     * Obtenir les emails validés et nettoyés
     */
    public function getValidatedEmails(): array
    {
        if (!empty($this->custom_emails)) {
            $emails = $this->parseCustomEmails($this->custom_emails);
            return array_filter($emails, function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });
        }

        return [];
    }

    /**
     * Vérifier si au moins une source d'emails est fournie
     */
    public function hasEmailSource(): bool
    {
        return $this->send_to_users 
            || !empty($this->custom_emails) 
            || $this->hasFile('excel_file');
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'send_to_users' => 'Envoyer aux utilisateurs',
            'custom_emails' => 'Emails personnalisés',
            'excel_file' => 'Fichier Excel',
            'subject' => 'Sujet',
            'content' => 'Contenu',
        ];
    }
}