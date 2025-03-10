<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    use HasFactory;

    protected $table = 'student_notifications'; // Explicitly define table name if needed

    protected $fillable = [
        'student_id',
        'note',
        'state',
        'date',
    ];

    protected $casts = [
        'date' => 'date', // Ensure 'date' is cast as a date object
        'state' => 'string',
    ];

    /**
     * Get the student that owns the notification.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Scope a query to only include unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('state', 'unread');
    }

    /**
     * Mark the notification as read.
     */
    public function markAsRead()
    {
        $this->update(['state' => 'read']);
    }
}
