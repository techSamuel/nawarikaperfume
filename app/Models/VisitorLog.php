<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_id',
        'user_type',
        'user_name',
        'user_email',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'timezone',
        'country',
        'city',
        'url',
        'referer',
        'duration_seconds',
        'visited_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedDurationAttribute()
    {
        $seconds = $this->duration_seconds ?? 0;
        if ($seconds < 60) {
            return $seconds . 's';
        }
        $minutes = floor($seconds / 60);
        $remSeconds = $seconds % 60;
        if ($minutes < 60) {
            return $minutes . 'm ' . $remSeconds . 's';
        }
        $hours = floor($minutes / 60);
        $remMinutes = $minutes % 60;
        return $hours . 'h ' . $remMinutes . 'm';
    }

    public function getDeviceIconAttribute()
    {
        return match(strtolower($this->device_type ?? '')) {
            'mobile' => '📱',
            'tablet' => '📱',
            'desktop' => '🖥️',
            default => '💻',
        };
    }
}
