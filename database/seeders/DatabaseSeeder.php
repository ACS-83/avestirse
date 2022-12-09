<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Products;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* ADMIN lleva rol 1 y USER 0 */

        // Crea admin desde seeder
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'role' => '1'
        ]);
        
        // Crea usuario desde seeder
        User::factory()->create([
            'name' => 'Gabriel',
            'email' => 'gabriel@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => '0'
        ]);
        // Crea 27 productos con datos aleatorios
        Products::factory()->count(27)->create();        
    }
}
