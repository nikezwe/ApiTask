<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
        // Crée 10 utilisateurs
        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                // Pour chaque utilisateur, on crée entre 2 et 5 tâches
                Task::factory()
                    ->count(rand(2, 5))
                    ->create([
                        'user_id' => $user->id,
                    ]);
            });
    }
    }
}
