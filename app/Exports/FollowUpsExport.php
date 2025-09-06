<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FollowUpsExport implements FromCollection, WithHeadings
{
    protected $followUps;

    public function __construct($followUps)
    {
        $this->followUps = $followUps;
    }

    public function collection()
    {
        return $this->followUps->map(function ($fu) {
            return [
                'Título'               => $fu->title,
                'Fecha'                => $fu->date,
                'Comentarios'          => $fu->comments,
                'Cliente'              => optional($fu->customer)->name,
                'Empleado'             => optional($fu->employee)->fullName ?? optional($fu->employee?->user)->name,
                'Agencia'              => optional($fu->employee?->agency)->name,
                'Estado'               => optional($fu->status)->name,
                'Origen'               => optional($fu->origin)->name,
                'Última actualización' => $fu->updated_at ? $fu->updated_at->format('Y-m-d H:i:s') : null,
                'Días restantes'       => $fu->daysRemaining,
                'Último porcentaje'    => optional($fu->lastPercentage)->name,
                'Vehículo'             => optional($fu->vehicle)->model,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Título',
            'Fecha',
            'Comentarios',
            'Cliente',
            'Empleado',
            'Agencia',
            'Estado',
            'Origen',
            'Última actualización',
            'Días restantes',
            'Último porcentaje',
            'Vehículo',
        ];
    }
}
