<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WpSsoService;
use Illuminate\Support\Facades\Log;

class WpController extends Controller
{
    protected $wpSso;

    public function __construct(WpSsoService $wpSso)
    {
        $this->wpSso = $wpSso;
    }

    public function goToWordPress(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        try {
            // Call your service to create/login the user and get the auto-login URL
            $loginUrl = $this->wpSso->createOrLoginUser($user);

            // Return JSON for Vue to handle redirect
            return response()->json(['url' => $loginUrl]);

        } catch (\Exception $e) {
            Log::error('Failed to sync user with WordPress', [
                'email' => $user->email,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to create/login user in WordPress',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
