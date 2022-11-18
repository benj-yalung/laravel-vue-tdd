<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Generate Admin user.
         */
        User::factory()->create(
            [
                'name'      => 'Admin',
                'role'      => 2,
                'email'     => 'admin@test.com',
                'password'  => bcrypt('qweqwe123')
            ]
        );

        /**
         * Generate Normal user.
         */
        User::factory()->create(
            [
                'name'      => 'User',
                'role'      => 1,
                'email'     => 'user@test.com',
                'password'  => bcrypt('qweqwe123')
            ]
        );
    }
}
