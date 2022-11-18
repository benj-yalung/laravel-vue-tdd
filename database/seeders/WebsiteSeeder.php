<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\User;

class WebsiteSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Generate Websites.
         */
        $admin = User::where('role', '2')->first();

        for ($i=0; $i < 10; $i++) { 
            Website::factory()->create([
                'author_id' => $admin->id
            ]);
        }
    }
}
