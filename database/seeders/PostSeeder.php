<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'Primera publicación',
            'content' => 'Contenido de la primera publicación',
        ]);

        Post::create([
            'title' => 'Segunda publicación',
            'content' => 'Contenido de la segunda publicación',
        ]);
    }
}
