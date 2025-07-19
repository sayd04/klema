<?php

namespace App\Exports;

use App\Models\WeatherForecast;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class WeatherExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $weatherData;
    protected $startDate;
    protected $endDate;

    public function __construct($weatherData, $startDate = null, $endDate = null)
    {
        $this->weatherData = $weatherData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->weatherData;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Location',
            'Date',
            'Temperature (°C)',
            'Humidity (%)',
            'Weather Type',
            'Weather Icon',
            'Advice',
            'Created At'
        ];
    }

    public function map($weather): array
    {
        return [
            $weather->id,
            $weather->location->name ?? 'N/A',
            Carbon::parse($weather->date)->format('Y-m-d'),
            $weather->temp,
            $weather->humidity,
            $weather->weather_type,
            $weather->weather_icon,
            $weather->advice,
            Carbon::parse($weather->created_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ]
            ]
        ];
    }
} 