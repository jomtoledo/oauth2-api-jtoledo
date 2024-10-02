<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\DB;

class PassportSeeder extends Seeder
{
    public function run()
    {
        // Create a personal access client
        $personalAccessClient = Client::create([
            'user_id' => null,
            'name' => 'Personal Access Client',
            'secret' => 'personal-access-secret',
            'redirect' => 'http://localhost',
            'password_client' => false, 
            'personal_access_client' => true,
            'revoked' => false,
        ]);

        // Create a password grant client
        Client::create([
            'user_id' => null,
            'name' => 'Password Grant Client',
            'secret' => 'password-grant-secret',
            'redirect' => 'http://localhost',
            'password_client' => true, 
            'personal_access_client' => false,
            'revoked' => false,
        ]);

        
        if ($personalAccessClient) {
            // Insert into the oauth_personal_access_clients table
            DB::table('oauth_personal_access_clients')->insert([
                'client_id' => $personalAccessClient->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
