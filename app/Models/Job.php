<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Job extends Model
{
    protected $table = 'job_title';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $fillable = [
        'Name',
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class, 'Job_Id', 'Id');
    }
}
?>