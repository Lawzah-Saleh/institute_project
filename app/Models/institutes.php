<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institutes extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'logo_url', 'about_us', 'about_image', 'institute_servicies', 'address', 'phone', 'email'];
}
