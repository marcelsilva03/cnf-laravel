<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'action_text',
        'action_url',
        'variables',
        'is_active',
        'is_html',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'is_html' => 'boolean',
    ];

    public function attachments()
    {
        return $this->hasMany(EmailAttachment::class);
    }

    public function statistics()
    {
        return $this->hasMany(EmailTemplateStatistic::class);
    }

    public function parseContent(array $data): string
    {
        if (empty($this->content)) {
            return 'Content is not set.';
        }

        $content = $this->content;
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            } elseif (!is_string($value)) {
                $value = (string) $value;
            }
            $content = str_replace("{{$key}}", $value, $content);
        }

        return $content;
    }

    public function parseSubject(array $data): string
    {
        if (empty($this->subject)) {
            return 'Subject is not set.';
        }

        $subject = $this->subject;
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            } elseif (!is_string($value)) {
                $value = (string) $value;
            }
            $subject = str_replace("{{$key}}", $value, $subject);
        }

        return $subject;
    }

    public function parseActionUrl(array $data): ?string
    {
        if (!$this->action_url) {
            return null;
        }

        $url = $this->action_url;
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            } elseif (!is_string($value)) {
                $value = (string) $value;
            }
            $url = str_replace("{{$key}}", $value, $url);
        }

        return $url;
    }

    public function preview(array $data = []): array
    {
        return [
            'subject' => $this->parseSubject($data),
            'content' => $this->parseContent($data),
            'action_text' => $this->action_text,
            'action_url' => $this->parseActionUrl($data),
            'is_html' => $this->is_html,
            'attachments' => $this->attachments,
        ];
    }

    public function trackEvent(string $type, ?User $user = null, array $data = []): void
    {
        $this->statistics()->create([
            'user_id' => $user?->id,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function getStatsAttribute(): array
    {
        return [
            'sent' => $this->statistics()->sent()->count(),
            'opened' => $this->statistics()->opened()->count(),
            'clicked' => $this->statistics()->clicked()->count(),
            'open_rate' => $this->statistics()->sent()->count() > 0
                ? round(($this->statistics()->opened()->count() / $this->statistics()->sent()->count()) * 100, 2)
                : 0,
            'click_rate' => $this->statistics()->sent()->count() > 0
                ? round(($this->statistics()->clicked()->count() / $this->statistics()->sent()->count()) * 100, 2)
                : 0,
        ];
    }
} 