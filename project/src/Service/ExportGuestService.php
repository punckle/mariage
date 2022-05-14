<?php

namespace App\Service;

use App\Entity\GuestPlusOne;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportGuestService
{
    public function export(array $guests): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Prénom');
        $sheet->setCellValue('C1', 'Groupe rattaché');
        $sheet->setCellValue('D1', 'Nb personnes prévues groupe');
        $sheet->setCellValue('E1', 'Nb personnes ok');
        $sheet->setCellValue('F1', 'Cérémonie');
        $sheet->setCellValue('G1', 'Apéro');
        $sheet->setCellValue('H1', 'Dîner');
        $sheet->setCellValue('I1', 'Enfant');
        $sheet->setCellValue('J1', 'Commentaire');
        $sheet->setCellValue('K1', 'Message groupe');

        $i = 2;
        /** @var GuestPlusOne $guest */
        foreach ($guests as $guest) {
            $sheet->setCellValue('A' . $i, $guest->getLastName());
            $sheet->setCellValue('B' . $i, $guest->getFirstName());
            $sheet->setCellValue('C' . $i, $guest->getGuest());
            $sheet->setCellValue('D' . $i, $guest->getGuest()->getInitialNbPeople());
            $sheet->setCellValue('E' . $i, $guest->getGuest()->getFinalNbPeople());
            $sheet->setCellValue('F' . $i, $this->getBoolean($guest->getCeremony()));
            $sheet->setCellValue('G' . $i, $this->getBoolean($guest->getApero()));
            $sheet->setCellValue('H' . $i, $this->getBoolean($guest->getDinner()));
            $sheet->setCellValue('I' . $i, $this->getBoolean($guest->getKid()));
            $sheet->setCellValue('J' . $i, $guest->getComment());
            $sheet->setCellValue('K' . $i, $guest->getGuest()->getMessage());

            $i++;
        }

        $sheet->setTitle('Export invités');

        return $spreadsheet;
    }

    private function getBoolean(bool $boolean): ?string
    {
        if ($boolean === true) {
            return 'X';
        } else {
            return null;
        }
    }
}