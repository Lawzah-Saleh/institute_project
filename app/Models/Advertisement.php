<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Advertisement extends Model
{
    protected $fillable = [
        'title', 'content', 'image', 'publish_date', 'end_date', 'state'
    ];

    // تحويل القيم النصية إلى كائنات Carbon تلقائيًا
    protected $dates = ['publish_date', 'end_date'];

    public function getPublishDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value);
    }
}
