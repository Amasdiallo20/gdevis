<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MaterialsExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $materials;
    protected $quoteNumber;
    protected $totalGeneral;

    public function __construct(array $materials, string $quoteNumber, float $totalGeneral)
    {
        $this->materials = $materials;
        $this->quoteNumber = $quoteNumber;
        $this->totalGeneral = $totalGeneral;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $data = [];
        
        foreach ($this->materials as $materiau) {
            $data[] = [
                $materiau['nom'],
                number_format($materiau['quantite'], 3, ',', ' '),
                number_format($materiau['prix_unitaire'], 0, ',', ' '),
                number_format($materiau['total_ligne'], 0, ',', ' '),
            ];
        }
        
        // Ajouter une ligne vide puis le total
        $data[] = [];
        $data[] = [
            'TOTAL GÉNÉRAL',
            '',
            '',
            number_format($this->totalGeneral, 0, ',', ' '),
        ];
        
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nom Matériau',
            'Quantité',
            'Prix Unitaire (GNF)',
            'Total par Ligne (GNF)',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Matériaux - ' . $this->quoteNumber;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->materials) + 3; // +3 pour l'en-tête, ligne vide et total
        
        return [
            // Style de l'en-tête
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '3B82F6'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            // Style des lignes de données
            'A2:D' . ($lastRow - 2) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Style de la ligne de total
            'A' . ($lastRow - 1) . ':D' . ($lastRow - 1) => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E5E7EB'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Alignement spécifique pour la ligne de total
            'A' . ($lastRow - 1) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'B' . ($lastRow - 1) . ':C' . ($lastRow - 1) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            'D' . ($lastRow - 1) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Alignement des colonnes de données
            'A2:A' . ($lastRow - 2) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],
            'B2:D' . ($lastRow - 2) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 25,
            'D' => 25,
        ];
    }
}
