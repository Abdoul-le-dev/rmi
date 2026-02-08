<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmailsImport;
use Exception;

class ExcelEmailExtractor
{
    /**
     * Extraire les emails d'un fichier Excel/CSV
     *
     * @param UploadedFile $file
     * @return array
     * @throws Exception
     */
    public function extractEmails(UploadedFile $file): array
    {
        try {
            $import = new EmailsImport();
            Excel::import($import, $file);
            
            $emails = $import->getEmails();
            
            return $this->validateEmails($emails);
            
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la lecture du fichier : ' . $e->getMessage());
        }
    }

    /**
     * Valider et nettoyer une liste d'emails
     *
     * @param array $emails
     * @return array
     */
    public function validateEmails(array $emails): array
    {
        $validEmails = [];

        foreach ($emails as $email) {
            $email = trim(strtolower($email));
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = $email;
            }
        }

        return array_unique($validEmails);
    }

    /**
     * Obtenir un aperçu des emails du fichier (premières lignes)
     *
     * @param UploadedFile $file
     * @param int $limit
     * @return array
     */
    public function previewEmails(UploadedFile $file, int $limit = 10): array
    {
        try {
            $import = new EmailsImport($limit);
            Excel::import($import, $file);
            
            return $import->getEmails();
            
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la prévisualisation : ' . $e->getMessage());
        }
    }

    /**
     * Compter le nombre d'emails dans le fichier
     *
     * @param UploadedFile $file
     * @return int
     */
    public function countEmails(UploadedFile $file): int
    {
        try {
            $emails = $this->extractEmails($file);
            return count($emails);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Vérifier si le fichier contient des emails valides
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function hasValidEmails(UploadedFile $file): bool
    {
        try {
            $emails = $this->extractEmails($file);
            return count($emails) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
}