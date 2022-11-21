<?php

namespace App\Models;

use App\Enums\User\RoleType;
use App\Notifications\User\Auth\ResetPassword;
use App\Notifications\User\Auth\VerifyEmail;
use App\Notifications\User\Auth\NewPassword;
use App\Traits\UUID;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Browser;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use UUID;
    use LogsActivity;

    protected $guard_name = 'api_user';

    protected static $recordEvents = [];

    protected $appends = ['role', 'is_email_verified'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'city_of_origin', 'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'changed_password' => 'boolean'
    ];

    /**
    * Send the email verification notification.
    *
    * @return void
    */
    public function sendEmailVerificationNotification()
    {

        $this->notify(new VerifyEmail());
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token, request('callbackUrl')));
    }

    public function sendNewPasswordNotification($password)
    {
        $this->notify(new NewPassword($password));
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['user'=>$this];
    }

    public function getNameAttribute()
    {
        return ucwords($this->first_name . ' '. $this->last_name);
    }

    public function getRoleAttribute()
    {
        return $this->getRoleNames();
    }

    public function getIsEmailVerifiedAttribute()
    {
        return $this->hasVerifiedEmail();
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole(RoleType::SuperAdmin);
    }

    public function businessInformation()
    {
        return $this->hasOne(BusinessInformation::class)->withDefault([]);
    }

    public function otherInformation()
    {
        return $this->hasOne(OtherInformation::class)->withDefault([]);
    }

    public function izyPay()
    {
        return $this->hasOne(IzyPay::class);
    }

    public function getFullPlatformDetails(): array
    {
        $platform = [];

        if (Browser::isMobile()) {
            $deviceType = 'Mobile';

        } elseif (Browser::isTablet()) {
            $deviceType = 'Tablet';
        } else {
            $deviceType = 'Desktop';
        }

        $platform['device'] = $deviceType;
        $platform['platform_family'] = Browser::platformFamily() ;
        $platform['device_family'] = Browser::deviceFamily();
        $platform['device_model'] = Browser::deviceModel();
        $platform['browser'] = Browser::browserFamily();
        $platform['device_agent'] = Browser::userAgent();

        return $platform;
    }

    public function referralCandidates()
    {
        return $this->hasMany(__CLASS__,'referral_id');
    }

    public function referral()
    {
        return $this->belongsTo(__CLASS__, 'referral_id')
            ->withDefault();
    }
}
