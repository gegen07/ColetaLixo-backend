<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class User extends Model
  implements JWTSubject, AuthenticatableContract, AuthorizableContract,
    CanResetPasswordContract

{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'address', 'telephone', 'password'
    ];

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function stationSells() {
        return $this->belongsToMany(StationSell::class, 'station_id', 'id');
    }

    public function companyBuys() {
        return $this->belongsToMany(CompanyBuy::class, 'company_id', 'id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPassword($token));
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                    abort(401, 'This action is unauthorized.');
        }

        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized.');
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }
}

class CustomPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        // $route = app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('passwords.reset', $this->token);

        return (new MailMessage)
            ->from('gegenbarcelos@gmail.com')
            ->line('We are sending this email because we recieved a forgot password request.')
            ->line('This is your token ' . $this->token)
            ->line('Put this token in the form to confirm that you wnat to reset your password')
            ->line('If you did not request a password reset, no further action is required. Please contact us if you did not submit this request.');
    }
}
