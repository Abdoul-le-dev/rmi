<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class LiveClassRecordingController extends Controller
{
    /**
     * Webhook appelé par Jibri quand un enregistrement est terminé
     */
    public function recordingCompleted(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string',
            'file_path' => 'required|string',
            'timestamp' => 'nullable|string',
        ]);

        // Trouver le live class
        $liveClass = LiveClass::where('room_name', $validated['room_name'])->first();

        if (!$liveClass) {
            return response()->json(['error' => 'Live class not found'], 404);
        }

        // Vérifier que le fichier existe
        if (!file_exists($validated['file_path'])) {
            return response()->json(['error' => 'Recording file not found'], 404);
        }

        // Créer ou mettre à jour l'enregistrement
        $recording = LiveClassRecording::updateOrCreate(
            [
                'live_class_id' => $liveClass->id,
                'room_name' => $validated['room_name'],
            ],
            [
                'file_name' => basename($validated['file_path']),
                'file_path' => $validated['file_path'],
                'file_size' => filesize($validated['file_path']),
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        // Calculer la durée de la vidéo (nécessite ffprobe)
        $duration = $this->getVideoDuration($validated['file_path']);
        if ($duration) {
            $recording->update(['duration_seconds' => $duration]);
        }

        // Marquer le live class comme n'étant plus enregistré
        $liveClass->update(['is_being_recorded' => false]);

        return response()->json([
            'success' => true,
            'recording_id' => $recording->id,
        ]);
    }

    /**
     * Liste des enregistrements d'un live class
     */
    public function index(LiveClass $liveClass)
    {
        $this->authorize('view', $liveClass);

        $recordings = $liveClass->recordings()
            ->orderBy('created_at', 'desc')
            ->get();
            $authUser=Auth::user();

        return view('liveclass.records', compact('liveClass', 'recordings','authUser'));
    }

    /**
     * Télécharger un enregistrement
     */
    public function download(LiveClassRecording $recording)
    {
        $this->authorize('view', $recording->liveClass);

        if (!$recording->fileExists()) {
            abort(404, 'Fichier d\'enregistrement introuvable');
        }

        return Response::download(
            $recording->file_path,
            $recording->file_name,
            [
                'Content-Type' => 'video/mp4',
            ]
        );
    }

    /**
     * Streamer un enregistrement
     */
    public function stream(LiveClassRecording $recording)
    {
        $this->authorize('view', $recording->liveClass);

        if (!$recording->fileExists()) {
            abort(404, 'Fichier d\'enregistrement introuvable');
        }

        $path = $recording->file_path;
        $stream = fopen($path, 'rb');
        $size = filesize($path);
        $length = $size;
        $start = 0;
        $end = $size - 1;

        header('Content-Type: video/mp4');
        header('Accept-Ranges: bytes');

        if (isset($_SERVER['HTTP_RANGE'])) {
            $c_start = $start;
            $c_end = $end;

            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                exit;
            }

            if ($range == '-') {
                $c_start = $size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $c_start = $range[0];
                $c_end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }

            $c_end = ($c_end > $end) ? $end : $c_end;

            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                exit;
            }

            $start = $c_start;
            $end = $c_end;
            $length = $end - $start + 1;
            fseek($stream, $start);
            header('HTTP/1.1 206 Partial Content');
        }

        header("Content-Range: bytes $start-$end/$size");
        header("Content-Length: $length");

        $buffer = 1024 * 8;
        while (!feof($stream) && ($p = ftell($stream)) <= $end) {
            if ($p + $buffer > $end) {
                $buffer = $end - $p + 1;
            }
            echo fread($stream, $buffer);
            flush();
        }

        fclose($stream);
        exit;
    }

    /**
     * Supprimer un enregistrement
     */
    public function destroy(LiveClassRecording $recording)
    {
        $this->authorize('update', $recording->liveClass);

        $recording->delete();

        return back()->with('success', 'Enregistrement supprimé avec succès');
    }

    /**
     * Calculer la durée d'une vidéo avec ffprobe
     */
    private function getVideoDuration(string $filePath): ?int
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $ffprobe = '/usr/bin/ffprobe'; // Chemin vers ffprobe

        if (!file_exists($ffprobe)) {
            return null;
        }

        $command = sprintf(
            '%s -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s',
            escapeshellcmd($ffprobe),
            escapeshellarg($filePath)
        );

        $duration = shell_exec($command);

        return $duration ? (int) round((float) $duration) : null;
    }
}