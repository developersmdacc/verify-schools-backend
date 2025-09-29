<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WpSsoService
{
    public function createOrLoginUser($user)
    {
        Log::info('🔑 Starting WordPress sync for user', ['email' => $user->email]);

        $wpUrl  = env('WP_SITE_URL');
        $secret = env('WP_SSO_SECRET');

        $wpUser = env('WP_ADMIN_USER'); // admin username
        $wpPass = env('WP_ADMIN_PASS'); // application password

        // Step 1: Check if user exists
        $check = Http::withBasicAuth($wpUser, $wpPass)
            ->get($wpUrl . '/wp-json/wp/v2/users', [
                'search' => $user->email,
                'per_page' => 100,
            ]);

        if ($check->failed()) {
            Log::error('❌ Failed to connect to WordPress', ['response' => $check->body()]);
            throw new \Exception("Failed to connect to WordPress");
        }

        $foundUser = collect($check->json())->firstWhere('email', $user->email);

        // Step 2: If not found, create
        if (!$foundUser) {
            Log::info('👤 Creating new WordPress user', ['email' => $user->email]);

            $create = Http::withBasicAuth($wpUser, $wpPass)
                ->post($wpUrl . '/wp-json/wp/v2/users', [
                    'username' => $user->name ?? Str::before($user->email, '@'),
                    'email'    => $user->email,
                    'password' => Str::random(16), // random password, SSO will handle login
                    'roles'    => ['subscriber'],
                ]);

            if ($create->failed()) {
                Log::error('❌ Failed to create user', ['response' => $create->body()]);
                throw new \Exception("Failed to create user in WordPress: " . $create->body());
            }
        }

        // Step 3: Build signed auto-login URL
        $signature = hash_hmac('sha256', $user->email, $secret);

        $loginUrl = $wpUrl . "/?auto-login=1&email=" . urlencode($user->email) . "&signature=" . $signature;

        Log::info('✅ Generated WordPress login URL', ['url' => $loginUrl]);

        return $loginUrl;
    }
}
