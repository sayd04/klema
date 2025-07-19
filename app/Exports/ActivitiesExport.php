<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ActivitiesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $activities;
    protected $startDate;
    protected $endDate;

    public function __construct($activities, $startDate = null, $endDate = null)
    {
        $this->activities = $activities;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Type',
            'Date',
            'Created At',
            'Updated At'
        ];
    }

    public function map($activity): array
    {
        return [
            $activity->id,
            $activity->title,
            $activity->description,
            ucfirst($activity->type),
            Carbon::parse($activity->date)->format('Y-m-d'),
            Carbon::parse($activity->created_at)->format('Y-m-d H:i:s'),
            Carbon::parse($activity->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E8']
                ]
            ]
        ];
    }
} 