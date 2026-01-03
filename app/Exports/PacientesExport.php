<?php

namespace App\Exports;

use App\Models\Paciente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PacientesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Paciente::with(['servicio', 'cama'])->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Cédula',
            'Edad',
            'Estado',
            'Condición',
            'Servicio',
            'Cama',
            'Fecha de Creación'
        ];
    }

    public function map($paciente): array
    {
        $condicionMap = [
            'normal' => 'Normal',
            'diabetico' => 'Diabético',
            'hiposodico' => 'Hiposódico'
        ];

        return [
            $paciente->nombre,
            $paciente->apellido,
            $paciente->cedula,
            $paciente->edad,
            $paciente->estado,
            $condicionMap[$paciente->condicion] ?? $paciente->condicion,
            $paciente->servicio?->nombre ?? 'N/A',
            $paciente->cama?->numero ?? 'N/A',
            $paciente->created_at->format('d/m/Y H:i')
        ];
    }
}
