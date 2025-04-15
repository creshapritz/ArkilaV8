<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AccountActivitySeeder;
use Database\Seeders\StaffAdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // User::factory(10)->create();

        $this->call([
           
            CarSeeder::class, 
            AdminUserSeeder::class,
            PartnerAdminSeeder::class,
            StaffAdminSeeder::class,
            SettingSeeder::class,
           
        ]);

    
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

      

        
    }

    
}
