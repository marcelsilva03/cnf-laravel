<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplateStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_template_id',
        'user_id',
        'type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSent($query)
    {
        return $query->where('type', 'sent');
    }

    public function scopeOpened($query)
    {
        return $query->where('type', 'opened');
    }

    public function scopeClicked($query)
    {
        return $query->where('type', 'clicked');
    }
} 