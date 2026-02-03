<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\LiveClassRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class LiveClassRecordingController extends Controller
{
     /**
     * Webhook appelé par Jibri après upload S3
     */
    public function recordingCompleted(Request $request)
    {
        try {
            // Validation des données reçues
            $validated = $request->validate([
                'room_name' => 'required|string',
                'file_path' => 'required|string',
                'timestamp' => 'required|string',
                's3_bucket' => 'required|string',
                's3_region' => 'nullable|string',
            ]);

            Log::info('Recording completed webhook received', $validated);

            // Extraire le nom de fichier depuis le path S3
            $fileName = basename($validated['file_path']);

            // Trouver la LiveClass correspondante
            $liveClass = LiveClass::where('room_name', $validated['room_name'])
                ->first();

            if (!$liveClass) {
                Log::warning('LiveClass not found for room', ['room_name' => $validated['room_name']]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'LiveClass not found for this room',
                ], 404);
            }

            // Récupérer la taille du fichier depuis S3
            $s3Path = $validated['file_path'];
            $fileSize = null;
            
            try {
                $fileSize = Storage::disk('s3')->size($s3Path);
            } catch (\Exception $e) {
                Log::warning('Could not get file size from S3', [
                    'path' => $s3Path,
                    'error' => $e->getMessage()
                ]);
            }

            // Créer ou mettre à jour l'enregistrement
            $recording = LiveClassRecording::updateOrCreate(
                [
                    'live_class_id' => $liveClass->id,
                    'room_name' => $validated['room_name'],
                ],
                [
                    'file_name' => $fileName,
                    'file_path' => $s3Path,
                    'file_size' => $fileSize,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'metadata' => [
                        's3_bucket' => $validated['s3_bucket'],
                        's3_region' => $validated['s3_region'] ?? null,
                        'timestamp' => $validated['timestamp'],
                        'uploaded_at' => now()->toDateTimeString(),
                    ],
                ]
            );

            Log::info('Recording saved successfully', [
                'recording_id' => $recording->id,
                'live_class_id' => $liveClass->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recording saved successfully',
                'data' => [
                    'recording_id' => $recording->id,
                    'live_class_id' => $liveClass->id,
                    'file_path' => $recording->file_path,
                ],
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in recording webhook', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error processing recording webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function list(Request $request)
    {

        try {

            $recordings = LiveClassRecording::where('status', 'completed')
                ->with('liveClass') // Charger la relation si elle existe
                ->orderBy('completed_at', 'desc')
                ->get()
                ->map(function ($recording) {
                    
                    return [
                        'id' => $recording->id,
                        'liveclass' => $recording->liveClass,
                        'url' =>\App\Helpers\S3Helper::getTemporaryUrl($recording->file_path, 60),
                        'room_name' => $recording->room_name,
                        'file_name' => $recording->file_name,
                        'file_path' => $recording->file_path,
                        'file_size' => $recording->file_size,
                        'duration_seconds' => $recording->duration_seconds,
                        'status' => $recording->status,
                        'created_at' => $recording->created_at->toISOString(),
                        'completed_at' => $recording->completed_at ? $recording->completed_at->toISOString() : null,
                        
                    ];
                });

            return response()->json($recordings);
        } catch (\Exception $e) {
            Log::error('Error fetching recordings: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }
   
}
