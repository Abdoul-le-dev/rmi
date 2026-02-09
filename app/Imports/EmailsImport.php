<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class EmailsImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private array $emails = [];
    private ?int $limit;
    
    // Colonnes possibles contenant des emails
    private array $emailColumns = [
        'email',
        'e-mail',
        'e_mail',
        'mail',
        'emails',
        'adresse_email',
        'adresse',
        'contact',
    ];

    public function __construct(?int $limit = null)
    {
        $this->limit = $limit;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($this->limit !== null && count($this->emails) >= $this->limit) {
                break;
            }

            // Chercher dans les colonnes connues
            foreach ($this->emailColumns as $column) {
                if (isset($row[$column])) {
                    $email = trim($row[$column]);
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $this->emails[] = strtolower($email);
                        break; // Passer à la ligne suivante une fois un email trouvé
                    }
                }
            }

            // Si aucune colonne connue, scanner toutes les cellules
            if (!$this->emailFoundInRow($row)) {
                foreach ($row as $cell) {
                    if ($cell !== null) {
                        $cell = trim($cell);
                        if (filter_var($cell, FILTER_VALIDATE_EMAIL)) {
                            $this->emails[] = strtolower($cell);
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * Vérifier si un email a été trouvé dans la ligne
     */
    private function emailFoundInRow(Collection $row): bool
    {
        foreach ($this->emailColumns as $column) {
            if (isset($row[$column])) {
                $email = trim($row[$column]);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getEmails(): array
    {
        return array_unique($this->emails);
    }

    public function getCount(): int
    {
        return count($this->getEmails());
    }
}