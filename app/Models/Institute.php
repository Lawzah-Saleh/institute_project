<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;
    protected $table = 'institutes';

    protected $fillable = [
        'institute_name',
        'email',
        'phone',
        'address',
        'institute_description',
        'about_us',
        'about_image',
    ];
}
