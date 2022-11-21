<?php

namespace App\Exports\Administrator;

use App\Models\Administrator;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdministratorsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Administrator::whereNotNull('email_verified_at');
    }

    public function map($administrator): array
    {
        return [
            $administrator->name,
            $administrator->email,
            $administrator->creator->name,
            $administrator->getRoleNames()[0] ?? 'No Role',
            $administrator->created_at

        ];
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Email',
            'Creator',
            'Role',
            'Member Since',
        ];
    }
}
