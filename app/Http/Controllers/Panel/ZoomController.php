<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZoomController extends Controller
{

    public function revokeAccessToken()
    {
        session()->forget('access_token');
    }

    // Endpoint to get ZAK token, uses OAuth flow
    public function getZakToken(Request $request)
    {
        $userId = $request->userId;

        Log::info('UserId' . '' . $userId);

        $accessToken = $this->getZoomAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get("https://api.zoom.us/v2/users/{$userId}/token", [
            'type' => 'zak',
        ]);

        if ($response->successful()) {
            Log::info('ZAK Token' . '' .$response->json()['token']);
            return response()->json(['zak' => $response->json()['token']]);
        }

        return response()->json(['error' => 'Failed to retrieve ZAK token', 'details' => $response->json()], 400);
    }


    private function getZoomAccessToken()
    {
        $clientId = env('ZOOM_CLIENT_KEY');
        $clientSecret = env('ZOOM_CLIENT_SECRET');
        $accountId = env('ZOOM_ACCOUNT_ID');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode("{$clientId}:{$clientSecret}"),
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => $accountId,
        ]);

        if ($response->successful()) {
            Log::info('Access Token' . '' . $response->json()['access_token']);
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to retrieve Zoom access token');
    }

    public function createMeeting(Request $request)
    {
        $accessToken = $this->getZoomAccessToken();

        // Get the current time in UTC
        $startTime = now()->utc()->toIso8601String(); // This gives the current time in RFC3339 format (e.g. 2024-11-21T14:00:00Z)

        // Set the duration to 10 minutes
        $duration = 60;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->post('https://api.zoom.us/v2/users/me/meetings', [
            'topic' => $request->topic,
            'type' => 2, // Scheduled meeting
            'start_time' => $startTime,
            'duration' => $duration,
            'settings' => [
                'join_before_host' => true,
                'waiting_room' => false,
                'mute_upon_entry' => true,
                'endMeetingOnHostLeave' => true, // Add this setting
            ],
        ]);

        if ($response->successful()) {
            Log::info('meeting creation successful', ['data'=>$response->json()]);
            $meetingNumber = $response->json()['id'];
            $password = $response->json()['h323_password'];
            Log::info('meeting creation ID', ['data'=>$meetingNumber]);
            session()->put('meetingNumber', $meetingNumber);
            session()->put('password', $password);
            // return response()->json($response->json());
            return redirect('/zoom');
        }

        return response()->json(['error' => 'Failed to create meeting', 'details' => $response->json()], 400);
    }


    public function viewZoom(Request $request)
    {
        try {
            $meetingNumber = session()->get('meetingNumber');
            $user = auth()->user();
            $password = session()->get('password');
            $data = [
                'meetingNumber' => $meetingNumber,
                'password' => $password,
                'username' => $user->full_name,
                // Add other parameters here in the future
            ];
            Log::info('meetingNumber in view', ['data'=>$meetingNumber]);
            Log::info('password in view', ['data'=>$password]);
            return view('web.default.course.zoom', $data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function generateSignature(Request $request) {

        $key = env('ZOOM_SDK_KEY');
        $secret = env('ZOOM_SDK_SECRET');
        $meeting_number = $request->meetingNumber;
        $role = $request->role;
        // Calculate issued at and expiration times
        $iat = time();        // Issued at (current timestamp)
        $exp = $iat + 3600;   // Expiration time (1 hour from now)


        $decodedSecret = base64_decode($secret);

        $payload = [
            "appKey" => $key,
            'mn' => $meeting_number,
            'role' => $role,
            'iat' => $iat,
            'exp' => $exp,
            'tokenExp' => $exp
        ];
        $encode = JWT::encode($payload, $secret, 'HS256');

        Log::info('encode',['data'=>$encode]);
        Log::info('decode',['data'=>$decodedSecret]);
        Log::info('Signature Payload:', $payload);
        return response()->json(['signature' => $encode]);
    }

    public function validateMeetingHost(Request $request)
    {
        $accessToken = $this->getZoomAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.zoom.us/v2/users/me');

        if ($response->successful()) {
            Log::info('User fetched', ['data'=>$response->json()]);

            return response()->json($response->json());
        }

        throw new \Exception('Host user is not authorized for Web SDK access');
    }
}
