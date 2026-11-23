<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $table = 'employee';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Surname',
        'Name',
        'Patronymic',
        'email',
        'login',
        'password',
        'Job_Id',
        'IsBlocked',
    ];

    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'Job_Id' => 'integer',
    ];

    public function job()
    {
        return $this->belongsTo(JobTitle::class, 'Job_Id', 'Id');
    }
    public function getFullNameAttribute()
    {
        return "{$this->Surname} {$this->Name} {$this->Patronymic}";
    }
    
    public function getShortNameAttribute()
    {
        return "{$this->Surname} {$this->Name}";
    }
    public function scopeActive($query)
    {
        return $query->where('IsBlocked', false);
    }
    public function scopeBlocked($query)
    {
        return $query->where('IsBlocked', true);
    }
    public function isBlocked()
    {
        return $this->IsBlocked;
    }
}
?>