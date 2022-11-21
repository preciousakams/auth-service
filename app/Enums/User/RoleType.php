<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;

/**
 * @method static static Landlord()
 * @method static static Agent()
 * @method static static SuperAdmin()
 */
final class RoleType extends Enum
{
    const Landlord =   'Landlord';
    const Agent =   'Agent';
    const SuperAdmin =   'Super Admin';
}
