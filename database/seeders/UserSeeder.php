<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::truncate();
        Role::truncate();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'support']);
        Role::create(['name' => 'seller']);
        Role::create(['name' => 'client']);

        $user = User::factory()->create([
            'email' => 'admin@test.com',
            'phone' => '1111',
            'role_id' => 1,
            'verified' => true
        ]);
        $user->assignRole('admin');

        $user = User::factory()->create([
            'email' => 'manager@test.com',
            'phone' => '2222',
            'role_id' => 2,
            'verified' => true
        ]);
        $user->assignRole('manager');

        $user = User::factory()->create([
            'email' => 'support@test.com',
            'phone' => '3333',
            'role_id' => 3,
            'verified' => true
        ]);
        $user->assignRole('support');

        $user = User::factory()->create([
            'email' => 'seller@test.com',
            'phone' => '4444',
            'role_id' => 4,
            'verified' => true
        ]);
        $user->assignRole('seller');

        $user = User::factory()->create([
            'email' => 'client@test.com',
            'phone' => '5555',
            'role_id' => 5,
            'verified' => true
        ]);
        $user->assignRole('client');

        User::factory()->count(10)->create([
            'created_at' => fake()->dateTimeBetween('-12 month')
        ])->each(function (User $user) {
            $user->assignRole('client');
        });
    }
}
