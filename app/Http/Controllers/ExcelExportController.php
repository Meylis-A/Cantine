<?php

namespace App\Http\Controllers;

use App\Models\Choix;
use App\Models\History;
use App\Models\Personnelle;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelExportController extends Controller
{
    public function export(Request $request)
    {
        // Créez une instance de PhpSpreadsheet
        $spreadsheet = new Spreadsheet();

        $cadre = intval(trim($request->cadre));

        // Récupérez la feuille active (Feuille1 par défaut)
        $sheet = $spreadsheet->getActiveSheet();

        $data = $this->getData($request->dateA, $request->dateB, $cadre);

        // Insérez les en-têtes dans la première ligne
        $sheet->setCellValue('A1', 'Societe');
        $sheet->setCellValue('B1', 'Matricule');
        $sheet->setCellValue('C1', 'Noms');
        $sheet->setCellValue('D1', 'Prenoms');
        if ($cadre == 0) { // cantine 2 -> misy ny collation sy ny tariny
            $sheet->setCellValue('E1', 'Date');
            $sheet->setCellValue('F1', 'Dejeuner');
            $sheet->setCellValue('G1', 'Dinner');
            $sheet->setCellValue('H1', 'Collation');
            $sheet->setCellValue('I1', 'Yaourt Jour');
            $sheet->setCellValue('J1', 'Yaourt Nuit');
            $sheet->setCellValue('K1', 'Café');
            $sheet->setCellValue('L1', 'Thé');
        } elseif ($cadre == 1) {
            $c = 'E';
            foreach ($data['history'] as $elem) {
                $sheet->setCellValue($c . '1', $elem->date);
                $c++;
            }
        }


        // Itérez sur les données récupérées de la requête
        $row = 2; // Ligne où commencer l'insertion de données 
        $ca = 'E';
        if ($cadre == 0) {
            foreach ($data['history'] as $elem) {
                $sheet->setCellValue('A' . $row, $elem->societe);
                $sheet->setCellValue('B' . $row, $elem->matricule);
                $sheet->setCellValue('C' . $row, $elem->nom);
                $sheet->setCellValue('D' . $row, $elem->prenom);
                $sheet->setCellValue('E' . $row, $elem->date);
                $sheet->setCellValue('F' . $row, ($elem->dejeuner ? "1" : "0"));
                $sheet->setCellValue('G' . $row, ($elem->diner ? "1" : "0"));
                $sheet->setCellValue('H' . $row, ($elem->collation ? "1" : "0"));
                $sheet->setCellValue('I' . $row, ($elem->yaourtj ? "1" : "0"));
                $sheet->setCellValue('J' . $row, ($elem->yaourtn ? "1" : "0"));
                $sheet->setCellValue('K' . $row, ($elem->cafe ? "1" : "0"));
                $sheet->setCellValue('L' . $row, ($elem->the ? "1" : "0"));
                $row++;
            }
        } elseif ($cadre == 1) {
            foreach ($data['perso'] as $elem) {
                $d = 'E';
                $per = Personnelle::find($elem->matricule);
                $sheet->setCellValue('A' . $row, $per->societe);
                $sheet->setCellValue('B' . $row, $per->matricule);
                $sheet->setCellValue('C' . $row, $per->nom);
                $sheet->setCellValue('D' . $row, $per->prenom);
                foreach ($data['history'] as $elema) {
                    if ($elem->matricule == $elema->matricule)
                        $sheet->setCellValue($d . $row, ($elema->dejeuner ? "1" : "0"));
                    $d++;
                }
                $row++;
            }
        }
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
        ];
        // Appliquez une bordure à la plage de cellules du tableau
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    // Style de la bordure
                    'color' => ['rgb' => '000000'],
                    // Couleur de la bordure (noir)
                ],
            ],
        ];
        // Définissez les plages de cellules pour le tableau
        if ($cadre == 0) {
            $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
            $tableRange = 'A1:L' . ($row - 1);
            $sheet->getStyle($tableRange)->applyFromArray($tableStyle);
        } else {
            $tableRange = 'A1:D' . ($row - 1);
            $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
            $sheet->getStyle($tableRange)->applyFromArray($tableStyle);

        }



        // Créez un écrivain pour le format XLSX (Excel)
        $writer = new Xlsx($spreadsheet);

        // Définissez les en-têtes pour le téléchargement du fichier
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export.xlsx"');

        // Écrivez le contenu dans la sortie
        $writer->save('php://output');
    }

    function getData($dateA, $dateB, $cadre)
    {
        $history = null;
        $personelles = null;

        $history = History::query();
        $personelles = History::query();

        if ($dateA && $dateB) {
            $history->whereBetween('date', [$dateA, $dateB]);
            $personelles->whereBetween('date', [$dateA, $dateB])->distinct('matricule');
        } elseif ($dateA) {
            $history->where('date', '>=', $dateA);
            $personelles->whereDate('date', '>=', $dateA)->distinct('matricule');
        }
        if ($cadre != '2') {
            $history->where('cadre', $cadre)->orderBy('date', 'asc');
            $personelles->where('cadre', $cadre);
        }

        $history = $history->get();
        $personelles = $personelles->get();

        foreach ($history as $elem) {
            $perso = Personnelle::find($elem->matricule);
            $elem->nom = $perso->nom;
            $elem->prenom = $perso->prenom;
            $elem->matricule = $perso->matricule;
            $elem->societe = $perso->societe;
            $elem->cadre = $perso->cadre;
        }

        $data = [
            'history' => $history,
            'perso' => $personelles
        ];

        return $data;
    }

    public function processImport(Request $request)
    {
        $file = $request->file('file');

        // Validez le formulaire et le fichier envoyé
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // Utilisez PhpSpreadsheet pour lire les données du fichier XLSX
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        // Traitez et insérez les données dans la base de données
        $headerRow = null; // Stocke les noms de colonnes (matricule et dates)
        foreach ($data as $row) {
            if (!$headerRow) {
                // La première ligne contient les noms de colonnes
                $headerRow = $row;
            } else {
                // Les lignes suivantes contiennent les données
                $matricule = $row[0]; // La première colonne contient le matricule

                // Parcourez les colonnes de date
                for ($i = 1; $i < count($headerRow); $i++) {
                    $choix = new Choix;
                    $date = $headerRow[$i];
                    $valeur = $row[$i];

                    // Créez une nouvelle entrée dans la base de données
                    $choix->matricule = $matricule;
                    $choix->date = date('Y-m-d', strtotime($date));
                    $choix->dejeuner = $valeur;
                    $choix->save();
                }
            }
        }

        return redirect()->route('articles.index');
    }
}