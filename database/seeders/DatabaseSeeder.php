<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\GroupUserType;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(100)
            ->create([
                'password' => Hash::make(config('faker.password')),
            ]);

        $groups = Group::factory(10)
            ->create();

        $groups->each(function (Group $group) use ($users): void {
            // Add 1 owner to each group
            $group->users()
                ->attach($users->random(1), [
                    'type' => GroupUserType::TYPE_OWNER,
                ]);
            // Add some members to each group
            $group->users()
                ->attach($users->random(10), [
                    'type' => GroupUserType::TYPE_MEMBER,
                ]);
        });
    }
}
