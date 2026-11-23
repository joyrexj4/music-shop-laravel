<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use Notifiable;
    protected $table = 'client';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'Surname',
        'Name',
        'Patronymic',
        'Phone',
        'email',
        'login',
        'password',
        'IsBlocked',
    ];
    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'IsBlocked' => 'boolean',
    ];
    public function scopeActive($query)
    {
        return $query->where('IsBlocked', false);
    }
    public function scopeBlocked($query)
    {
        return $query->where('IsBlocked', true);
    }
    public function orders()
    {
        return $this->hasMany(ClientOrder::class, 'Client_Id', 'Id');
    }
}
?>