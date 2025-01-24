<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'applicationid';

    protected $fillable = [
        'vacancyid',
        'applicantid',
        'status',
        'created_at',
        'updated_at'
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class, 'vacancyid', 'vacancyid');
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicantid', 'id');
    }
}
