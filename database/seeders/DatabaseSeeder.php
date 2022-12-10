<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Image;
use App\Models\User;
use App\Models\Order;
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
        // Crea otro usuario desde seeder
        User::factory()->create([
            'name' => 'antonio',
            'email' => 'antonio@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => '0'
        ]);
        Products::factory()->create([
            'name' => 'Camiseta 4',
            'description' => 'La camiseta es una pieza de ropa que
                            se caracteriza por sus mangas cortas, su cuello redondo y su parte frontal sin botones,
                            que es lo que lo distingue de una camisa o un polo, si bien su evolución la ha convertido
                            en una prenda de múltiples estilos y podemos encontrarlas de manga larga, cuello pico,
                            de cuerpo corto',
            'price' => 20.00,
            'stock' => 120
        ]);
        Products::factory()->create([
        'name' => 'Camiseta 3',
            'description' => 'La camiseta es una pieza de ropa que
                            se caracteriza por sus mangas cortas, su cuello redondo y su parte frontal sin botones,
                            que es lo que lo distingue de una camisa o un polo, si bien su evolución la ha convertido
                            en una prenda de múltiples estilos y podemos encontrarlas de manga larga, cuello pico,
                            de cuerpo corto',
            'price' => 15.00,
            'stock' => 120,
        ]);
        Products::factory()->create([
            'name' => 'Camiseta 2',
            'description' => 'La camiseta es una pieza de ropa que
                            se caracteriza por sus mangas cortas, su cuello redondo y su parte frontal sin botones,
                            que es lo que lo distingue de una camisa o un polo, si bien su evolución la ha convertido
                            en una prenda de múltiples estilos y podemos encontrarlas de manga larga, cuello pico,
                            de cuerpo corto',
            'price' => 12.00,
            'stock' => 80
        ]);
        Products::factory()->create([
            'name' => 'Camiseta 1',
            'description' => 'La camiseta es una pieza de ropa que
                            se caracteriza por sus mangas cortas, su cuello redondo y su parte frontal sin botones,
                            que es lo que lo distingue de una camisa o un polo, si bien su evolución la ha convertido
                            en una prenda de múltiples estilos y podemos encontrarlas de manga larga, cuello pico,
                            de cuerpo corto',
            'price' => 15.00,
            'stock' => 90
        ]);
        // Crea 27 productos con datos aleatorios
        Products::factory()->count(27)->create();        
        
        Image::factory()->create([
            'products_id' => 1,
            'image' => '01.jpg',
        ]);
        Image::factory()->create([
            'products_id' => 2,
            'image' => '02.jpg',
        ]);
        Image::factory()->create([
            'products_id' => 3,
            'image' => '03.jpg',
        ]);
        Image::factory()->create([
            'products_id' => 4,
            'image' => '04.jpg',
        ]);

        Order::factory()->count(8)->create();        
    }
}
