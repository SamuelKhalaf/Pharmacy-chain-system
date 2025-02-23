<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Admin
 *
 * @property string $name
 * @property string $phone_number
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property int|null $branch_id
 * @property string|null $remember_token
 * @property Carbon|null $email_verified_at
 */
class Admin extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'role_id',
        'branch_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getRoleNameAttribute()
    {
        return Role::where('id',$this->role_id)->pluck('name')->first();
    }

    public function getBranchNameAttribute()
    {
        return Branch::where('id',$this->branch_id)->pluck('name')->first() ?? 'No Branch';
    }
}
