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
            'send_to_users' => 'sometimes|boolean',
            'custom_emails' => [
                'required_if:send_to_users,false',
                'nullable',
                'string',
            ],
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
            'custom_emails.required_if' => 'Veuillez fournir des emails ou sélectionner "envoyer à tous les utilisateurs".',
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
        $this->merge([
            'send_to_users' => $this->has('send_to_users'),
        ]);
    }

    /**
     * Valider après la validation principale
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Vérifier qu'au moins une source d'emails est fournie
            if (!$this->send_to_users && 
                empty($this->custom_emails) && 
                !$this->hasFile('excel_file')) {
                $validator->errors()->add(
                    'recipients',
                    'Veuillez fournir au moins une source d\'emails (utilisateurs, liste personnalisée ou fichier Excel).'
                );
            }

            // Valider les emails personnalisés si fournis
            if (!empty($this->custom_emails)) {
                $emails = $this->parseCustomEmails($this->custom_emails);
                $invalidEmails = [];

                foreach ($emails as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $invalidEmails[] = $email;
                    }
                }

                if (!empty($invalidEmails)) {
                    $validator->errors()->add(
                        'custom_emails',
                        'Les emails suivants sont invalides : ' . implode(', ', $invalidEmails)
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
        return array_filter(array_map('trim', $emailList));
    }
}