<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// FICHERO DE RUTAS

// Ruta hacia la landing-page
Route::get('/', function () {
    return view('index');
});

// Ruta hacia sección NOSOTROS
Route::get('/us', function () {
    return view('us');
})->name('us');

// Ruta de necesaria inclusión para autentificaciones. Modificada posteriormente
// para usar la verificación de mails por parámetro
Auth::routes(['verify' => true]);

// Índice de productos
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Comprobación de login para productos
Route::get('products/checklogin', [\App\Http\Controllers\ProductsController::class, 'checklogin'])->name('products.checklogin');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::post('products/addtocart', [\App\Http\Controllers\ProductsController::class, 'addtocart'])->name('products.addtocart');
});

// OBSOLETO: Añadir a carrito con middleware (autentificación)
// Route::post('products/addtocart', [\App\Http\Controllers\ProductsController::class, 'addtocart'])->middleware('auth')->name('products.addtocart');

// Eliminar del carrito con ID
Route::get('products/removefromcart/{id}', [\App\Http\Controllers\ProductsController::class, 'removefromcart'])->name('products.removefromcart');
// Todas las clases por defecto que incluse el controlador PRODUCTS
Route::resource('/products', ProductsController::class);
// Ruta hacia el formulario de pedidos
Route::resource('/orders', OrderController::class);
// Ruta hacia el listado de pedidos (controlador ORDER)
Route::post('/orders/list', [\App\Http\Controllers\OrderController::class, 'orderlist'])->name('orders.list');
// Ruta para realizar envío de pedidos
Route::post('/orders/{order}/sent', [\App\Http\Controllers\OrderController::class, 'ordersent'])->name('orders.sent');