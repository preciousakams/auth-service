<?php

namespace App\Exports\Administrator;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return User::whereNotNull('email_verified_at');
    }

    public function map($user): array
    {
        return [
            $user->first_name,
            $user->last_name,
            $user->liveizy_id,
            $user->email,
            $user->dob,
            $user->phone,
            $user->gender,
            $user->city_of_origin,
            $user->state_of_origin,
            $user->local_governement_area,
            $user->country,
            $user->marital_status,
            $user->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Liveizy Id',
            'Email',
            'Date Of Birth',
            'Phone Number',
            'Gender',
            'City of origin',
            'State of origin',
            'Local government area',
            'Country',
            'Marital Status',
            'Member since',
        ];
    }
}
