<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Role;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin', 'description' => 'Full access']);
        $editor = Role::create(['name' => 'editor', 'description' => 'Manage catalog']);
        $listener = Role::create(['name' => 'listener', 'description' => 'Listen and search']);

        User::create([
            'role_id' => $admin->id,
            'name' => 'Soola Admin',
            'email' => 'admin@spotify.test',
            'password' => Hash::make('Admin@123'),
        ]);
        User::create([
            'role_id' => $editor->id,
            'name' => 'Music Editor',
            'email' => 'editor@spotify.test',
            'password' => Hash::make('Editor@123'),
        ]);
        User::create([
            'role_id' => $listener->id,
            'name' => 'Lina Listener',
            'email' => 'user@spotify.test',
            'password' => Hash::make('User@123'),
        ]);

        $weeknd = Artist::create(['name' => 'The Weeknd', 'country' => 'Canada']);
        $billie = Artist::create(['name' => 'Billie Eilish', 'country' => 'USA']);
        $jadal = Artist::create(['name' => 'Jadal', 'country' => 'Jordan']);

        $afterHours = Album::create(['artist_id' => $weeknd->id, 'title' => 'After Hours', 'release_year' => 2020]);
        $hmhas = Album::create(['artist_id' => $billie->id, 'title' => 'Hit Me Hard and Soft', 'release_year' => 2024]);
        $malyeesh = Album::create(['artist_id' => $jadal->id, 'title' => 'Malyeesh', 'release_year' => 2016]);

        Track::create(['album_id' => $afterHours->id, 'title' => 'Blinding Lights', 'duration_seconds' => 200, 'genre' => 'Synth-pop']);
        Track::create(['album_id' => $hmhas->id, 'title' => 'BIRDS OF A FEATHER', 'duration_seconds' => 210, 'genre' => 'Pop']);
        Track::create(['album_id' => $malyeesh->id, 'title' => 'Salma', 'duration_seconds' => 230, 'genre' => 'Rock']);
    }
}
