<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LiveClassRecording extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_class_id',
        'room_name',
        'file_name',
        'file_path',
        'file_size',
        'duration_seconds',
        'status',
        'started_at',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relations
     */
    public function liveClass()
    {
        return $this->belongsTo(LiveClass::class);
    }

    /**
     * Accessors
     */
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDurationHumanAttribute(): string
    {
        if (!$this->duration_seconds) {
            return 'N/A';
        }

        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%dh %02dm %02ds', $hours, $minutes, $seconds);
        }

        return sprintf('%02dm %02ds', $minutes, $seconds);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('live-classes.recordings.download', $this->id);
    }

    public function getStreamUrlAttribute(): string
    {
        return route('live-classes.recordings.stream', $this->id);
    }

    /**
     * Méthodes métier
     */
    public function markAsCompleted(int $fileSize = null, int $duration = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'file_size' => $fileSize,
            'duration_seconds' => $duration,
        ]);
    }

    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    public function fileExists(): bool
    {
        return file_exists($this->file_path);
    }

    public function delete()
    {
        // Supprimer le fichier physique
        if ($this->fileExists()) {
            unlink($this->file_path);
        }

        return parent::delete();
    }
}