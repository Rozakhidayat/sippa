<?php

namespace App\Exports;

use App\Models\Submission;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


class SubmissionExport implements 
    FromCollection,
    WithHeadings, 
    WithMapping, 
    ShouldAutoSize, 
    WithStyles,
    WithCustomStartCell,
    WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected $data;
    protected $periode_date;
    protected $departements;
    

    public function __construct($data, $periode_date, $departements)
    {
        $this->data = $data;
        $this->periode_date = $periode_date;
        $this->departements = $departements;
    }
    
    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        return [
            'No. Tiket',
            'Nama Aplikasi',
            'Pemohon',
            'Unit Kerja',
            'Jenis Pengembangan',
            'Status',
            'Tanggal Pengajuan',
        ];
    }

    public function map($submission): array
    {
        return [
            $submission->no_ticket,
            $submission->application_name,
            $submission->user->name,
            $submission->departement->name,
            $submission->type_development,
            $submission->status_label,
            $submission->submission_date->format('d F Y'),
        ];
    }

    
    public function styles(Worksheet $sheet)
    {
        return [
            5 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1B6E1D']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->setCellValue('A1', 'LAPORAN PENGAJUAN SIPPA');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16)->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->mergeCells('A2:G2');
                $event->sheet->getDelegate()->setCellValue('A2', 'PT PUPUK KUJANG');
                $event->sheet->getDelegate()->getStyle('A2')->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle('A2')->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->mergeCells('A3:G3');
                $event->sheet->getDelegate()->setCellValue('A3', 'Periode: ' . $this->periode_date);
                $event->sheet->getDelegate()->getStyle('A3')->getAlignment()->setHorizontal('center');

                $event->sheet->getDelegate()->mergeCells('A4:G4');
                $event->sheet->getDelegate()->setCellValue('A4', 'Departemen: ' . $this->departements);
                $event->sheet->getDelegate()->getStyle('A4')->getAlignment()->setHorizontal('center');
                
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->getStyle('A5:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        },
        ];
    }

}
