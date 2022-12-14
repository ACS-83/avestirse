<?php
// NAMESPACE
namespace App\Http\Controllers;
// IMPORTACIÓN DE CLASES

// Modelo IMAGE
use App\Models\Image;
// Modelo PRODUCTS
use App\Models\Products;
// Instancia de solicitud HTTP
use Illuminate\Http\Request;
// Importación AUTH
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// Control de sesiones
use Illuminate\Support\Facades\Session;

// Controlador de productos que hereda de CONTROLLER
class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     // Modificación de esta línea para activar verificaciones de email
    //     $this->middleware(['auth', 'verified']);
    // }
    // Método para realizar un listado de productos
    public function index()
    {
        /* Almacenaje en variable de sentencia eloquent que extrae
        todos los productos ordenados por columna UPDATED_AT de forma
        descendente */
        if(Auth::check() && !Auth::user()->email_verified_at) {
            return redirect()->route('verification.notice');
        }
        $products = Products::orderBy('updated_at', 'DESC')->get();
        /* Reubicación de usuario a vista de productos con acoplamiento de
        variable */
        return view('products.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Método para creación de productos
    public function create()
    {
        // Si el usuario no está autentificado...
            if(!Auth::check()) {
                // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
                return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
            }
            // Si el usuario está autentificado pero no posee rol de ADMINISTRADOR...
            if(Auth::check() && !Auth::user()->role == 1) {
                // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
                return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
            }
           
            // Caso de que nada de lo anterior se cumpla, se reubica al usuario a la vista de 
            // creación de productos
            return view('products.create');
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Si no hay usuario logueado...
        if(!Auth::check()) {
            // ... Vuelve al índice de productos con mensaje de error para mostrar
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Si hay usuario logueado pero no tiene el ROLE 1 (administrador)
        if(Auth::check() && !Auth::user()->role == 1) {
            // ... Vuelve al índice de productos con mensaje de error para mostrar
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Se validan los campos. Todos son necesarios (excepto el de imágenes). Aquellos
        // campos que contengan 'MAX:100' solo permitirán cadenas con un máximo de 100 caracteres 
        $data = $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
        ]);
        /* Si supera la validación, usa el método CREATE
        para almacenar los datos en BDD */
        $new_prod = Products::create($data);
        
        // Si en la petición que llega por REQUEST hay un campo IMAGES...
        if ($request->has('images')) {
            // Se recorre cada fichero recibido
            foreach($request->file('images') as $image) {
                // Se prepara un nombre de fichero con la extensión
                $imgName = 'image-'.time().rand(1,1000).'.'.$image->extension();
                /* Se almacena el fichero a tratar con el nombre de la variable anterior
                en la carpeta PUBLIC */
                $image->move(public_path('products_images'),$imgName);
                // Se almacenan los datos de la imagen en la BDD (id y nombre de imagen)
                // para reutilizar desde la vista
                Image::create([
                    'products_id' => $new_prod->id,
                    'image' => $imgName
                ]);
            }
        }
        // Redirige al índice de productos con mensaje de aviso
        return to_route('products.index')->with('success', 'Producto añadido');
    }

    // Método de extracción de imágenes desde BDD
    public function images($id){
        // Búsqueda de imagen los productos por ID
        $product = Products::find($id);
        // Si lo anterior no devuelve nada... Se aborta
        if(!$product) abort(404);
        // Caso contrario... Se sacan las imágenes del producto...
        $images = $product->images;
        // ... Y hacemos que el método lo devuelva para usar
        return $images;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    // Método para comprobar login de usuario para uso en vistas
    public function checklogin(){
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // Se le redirige al login
            return redirect()->route('login');
        }
    }
    
    // Método de detalles de producto con parámetro de ID
    public function show(Request $request, $id)
    {
        // Búsqueda del primer producto que coincida con el ID
        $product = Products::where('id', $id)->firstOrFail();
        /* Llamada al método de extracción de nombre de imágen desde BDD con
        la ID como parámetro */
        $images = self::images($id);
        // Reubicación hacia vista detalle de producto con los datos adjuntos
        return view('products.show')->with('product', $product)->with('images', $images);
    }

    // Método para añadir producto al carrito con recepción de datos por parámetro por REQUEST
    public function addtocart(Request $request) {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... se le reubica hacia el registro
            return redirect()->route('register');
        }
        // Si el usuario está logueado pero no tiene el mail verificado...
        if(Auth::check() && !Auth::user()->email_verified_at) {
            // ... se le reubica hacia el aviso de verificaciones
            return redirect()->route('verification.notice');
        }

        // Se almacenan los datos que haya del SESSION en variable (los productos de carrito)
        $cart = Session::get('productsCart');
        // Almacenaje de ID de producto a excepción del TOKEN
        $id = (int)$request->except('_token')['product_id'];

        // Si al comprobar que $cart no está a NULL y hay una cantidad inferior a 1...
        if(isset($cart) && $request->except('_token')['quantity'] < 1) {
            // ... Se elimina el producto del carrito (usado para cuando añades 0
            // desde el apartado AÑADIR)
            $this->removefromcart($id);
            // Reubicación del usuario al formulario de pedido
            return redirect()->route('orders.index');
        }

        // Si lo anterior no se cumple pero hay variable CART con cosas...
        if(isset($cart)){
            // Guardamos el ID
            $product_id = $request->product_id;
            // Si dicha ID de producto existe en el carrito...
            if(array_key_exists($product_id, $cart)) {
                // Almacenamos los detalles como el ID
                $itemDetail = $cart[$product_id];
                // Establecemos nueva cantidad
                $newQuantity = ($itemDetail['quantity'] = $request->quantity);
                // Eliminamos el producto que hay ahora mismo en SESSION
                unset($cart[$product_id]);
                // Guardamos el producto con los nuevos valores establecidos en SESSION
                $newItemDetail = ['product_id'=>$product_id, 'quantity' => $newQuantity];
                // Preparamos el nuevo producto para actualizar el carrito
                $cart[$product_id] = $newItemDetail;
                // Actualizamos el carrito
                Session::put('productsCart', $cart);
            // Si lo anterior no se da...
            } else {
                // Eliminamos el producto del carrito
                unset($cart[$product_id]);
                // Reconfiguramos el producto (obviando el token)
                $cart[$request->product_id] = $request->except('_token');
                // Actualizamos carrito
                Session::put('productsCart', $cart);
            }
        // Si ninguno de los dos casos se da...
        }else {
            // Preparamos el producto con los datos actuales
            $cart[$request->product_id] = $request->except('_token');
            // Actualizamos carrito
            Session::put('productsCart', $cart);
        }
        // Reubicamos al usuario al formulario de pedidos
        return redirect()->route('orders.index');
    }
    // Método para eliminar producto de un carrito (pensado sobre todo para el botón
    // de eliminar producto desde formulario) con pasada de ID por parámetro
    public function removefromcart($id) {
        // Obtenemos el producto del carrito mediante su ID
        $product = Session::get('productsCart');
        // Vaciamos el contenido
        unset($product[$id]);
        // Insertamos "el nuevo carrito"
        Session::put('productsCart', $product);
        // Reubicación de usuario hacia formulario         
        return redirect()->route('orders.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */

    // Método para editar producto con ID de producto pasado por parámetro
    public function edit(Products $product)
    {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Si el usuario está autentificado pero no posee rol de ADMINISTRADOR...
        if(Auth::check() && !Auth::user()->role == 1) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Si lo anterior no se cumple, se reubica al usuario hacia la página de edición de producto
        // con variable de producto adjunta
        $productImages = DB::table('images')->where('products_id', $product->id)->get();
        //return view('products.edit')->with('product', $product)->with('productImages', $productImages);
        return view('products.edit', compact('product', 'productImages'));
    }   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */

     // Método de actualización de producto con REQUEST y variable de producto pasadas por parámetro
    public function update(Request $request, Products $product)
    {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Si el usuario está autentificado pero no posee rol de ADMINISTRADOR...
        if(Auth::check() && !Auth::user()->role == 1) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Se validan los datos que llegan desde el formulario por REQUEST. Todos los campos son requeridos
        // (excepto el de imágenes). Aquellos que contengan MAX:100 impiden que las cadenas no superen los 100
        // caracteres
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);
        // Se actualiza en base de datos cada uno de los campos de producto
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock
        ]);
        // Se reubica al usuario hacia el índice de productos con mensaje de aviso
        return to_route('products.index')->with('success', 'Producto actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */

    // Método para borrar producto con datos de producto recibido por parámetro
    public function destroy(Products $product)
    {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        // Si el usuario está autentificado pero no posee rol de ADMINISTRADOR...
        if(Auth::check() && !Auth::user()->role == 1) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita privilegios de administrador');
        }
        $id = $product['id'];
        // Se aplica método DELETE al producto que exista en BDD...
        Products::find($id)->delete();
        // ... Y se reubica a usuario a la página de productos con mensaje de aviso
        return to_route('products.index')->with('success', 'Producto eliminado');
    }
    
}
