<?php
// NAMESPACE
namespace App\Http\Controllers;
// IMPORTACIÓN DE CLASES

// Instancia de solicitud HTTP
use Illuminate\Http\Request;
// Modelo ORDER
use App\Models\Order;
// Modelo PRODUCTS
use App\Models\Products;
// Importación AUTH
use Illuminate\Support\Facades\Auth;
// Controlador de pedidos que hereda de CONTROLLER
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     // Método para mostrar índice de pedidos
    public function index() {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita estar registrado');
        }
        // Si el usuario está autentificado pero no posee rol de USUARIO...
        if(Auth::check() && !Auth::user()->role == 0) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita estar registrado');
        }

        // Si lo anterior no ocurre...
        // ...Almacenamos en variable los datos que llegan a través de REQUEST
        $data = request()->all();
        /* Llamamos al modelo de PRODUCTS para almacenar en variable todos los productos ordenados
        por la columna UPDATED_AT de forma descendente (el más reciente primero) */
        $products = Products::orderBy('updated_at', 'DESC')->get();
        // Reubicamos la usuario al índice de pedidos adjuntando variables para tratar desde la vista
        return view('orders.index')->with('data', $data)->with('products', $products);
    }
    
    // Método "para indicar al usuario el envío de pedidos" con parámetro ID
    public function ordersent($id) {
        // Conversión de cadena a entero del ID
        $id = (int)$id;
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita ser administrador');
        }
        // Si el usuario está autentificado pero no posee rol de ADMINISTRADOR...
        if(Auth::check() && !Auth::user()->role == 1) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita ser administrador');
        }
        // Solicitud de actualización de datos del pedido que debe quedar como ENVIADO
        Order::where('id', $id)->update(['sent' => 1]);
        // Almacenado de pedidos ordenados por columna UPDATED AT
        $orders = Order::orderBy('updated_at', 'DESC')->get();
        // Si la variable ORDERS está definida y no es null...
        if(isset($orders)) {
            // Recorremos dicha variable quedándonos con los datos que nos proporciona VALUE
            foreach ($orders as $key => $value) {
                // JUGANDO A PARTIR DE AQUÍ CON VARIABLE DECODE
                $decode = $value['productsOrdered'];
                // Conversión de la anterior cadena a ARRAY, separando mediante "],["
                $decode = explode("],[", $decode);
                // Recorrido del actual array
                foreach ($decode as $key) {
                    // Eliminación de caracteres '["'
                    $decode = str_replace('["', '', $decode);
                    // Eliminación de caracteres '"]'
                    $decode = str_replace('"]', '', $decode);
                }
                // Elimino el contenido del actual índice del array inicial que estaba recorriendo...
                unset($value['productsOrdered']);
                // ... e inserto los nuevos valores en él.
                $value['productsOrdered'] = $decode;
            }
        }
        
        // Preparación de mensaje de aviso al usuario
        $message['success'] = "¡Pedido enviado!";
        // Devolución de vista de pedidos con variables de pedido y mensaje de aviso para tratar desde la vista
        return view('orders.list')->with(compact('orders', 'message'));
    }

    // Método para listar pedidos
    public function orderlist()
    {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita estar registrado');
        }
        
        // Si el usuario está autentificado Y posee rol de ADMINISTRADOR...
        if(Auth::check() && Auth::user()->role == 1) {
            // Se prepara variable con listado de pedidos ordenados por columna de última actualización
            $orders = Order::orderBy('updated_at', 'DESC')->get();
        }
        // ¿Lo anterior tampoco se cumple?
        // Si el usuario está autentificado Y posee rol de USUARIO...
        if(Auth::check() && Auth::user()->role == 0) {
            // Se prepara variable para identificar el mail del actual usuario con los pedidos...
            $user = Auth::user()->email;
            /* ... Y se recoge en la variable de pedidos aquellas coincidencias que
            contengan el mail del usuario en cuestión. */
            $orders = Order::all()->where('mailuser', '=', $user);
        }
        // Si la variable ORDERS está definida y no es null...
        if(isset($orders)) {
            // Recorremos dicha variable quedándonos con los datos que nos proporciona VALUE
            foreach ($orders as $key => $value) {
                // JUGANDO A PARTIR DE AQUÍ CON VARIABLE DECODE
                $decode = $value['productsOrdered'];
                // Conversión de la anterior cadena a ARRAY, separando mediante "],["
                $decode = explode("],[", $decode);
                // Recorrido del actual array
                foreach ($decode as $key) {
                    // Eliminación de caracteres '["'
                    $decode = str_replace('["', '', $decode);
                    // Eliminación de caracteres '"]'
                    $decode = str_replace('"]', '', $decode);
                }
                // Elimino el contenido del actual índice del array inicial que estaba recorriendo...
                unset($value['productsOrdered']);
                // ... e inserto los nuevos valores en él.
                $value['productsOrdered'] = $decode;
            }
        }
        // Devolución de vista de pedidos con variable de pedido adjunta
        return view('orders.list')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /* Método para inserción de datos de producto en BDD*/
    public function store(Request $request)
    {
        // Si el usuario no está autentificado...
        if(!Auth::check()) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita estar registrado');
        }
        // Si el usuario está autentificado pero no posee rol de USUARIO...
        if(Auth::check() && !Auth::user()->role == 0) {
            // ... Se le lleva de vuelta al índice de productos con mensaje de aviso
            return to_route('products.index')->with('error', 'Necesita estar registrado');
        }
        // PREPARACIÓN DE DATOS

        /* Almacenamos en orderDa/orderDataTotalPrice el precio total del pedido excluyendo
        el TOKEN */
        $orderData['orderDataTotalPrice'] = $request->except('_token')['orderDataTotalPrice'];
        // Preparación de variable de correo de usuario que solicita el pedido
        $mail = Auth::user()->email;
        // Almacenamiento en variable DATA del correo de usuario
        $data['mailuser'] = $mail;
        
        // VALIDACIÓN DE DATOS QUE LLEGAN POR REQUEST

        /* Se requieren todos los campos. Aquellos campos que contengan 'MAX:INTEGER'
        impiden que las cadenas que reciben no superen la cifra establecida en INTEGER.
        Los campos que contengan 'DIGITS:INTEGER' requieren de una cifra numérica con una
        longitud establecida por INTEGER (no puede ser ni mayor ni menor) */
        $data += $request->validate([
            'name' => 'required|max:50',
            'surname' => 'required|max:50',
            'address' => 'required|max:80',
            'country' => 'required',
            'zip' => 'required|digits:5',
            'paymentMethod' => 'required',
            'fullCardName' => 'required',
            'cardNumber' => 'required|digits:16',
            'cardExpiration' => 'required|max:5',
            'cvv' => 'required|digits:3'
        ]);
        // Si supera la validación anterior...

        // PREPARACIÓN Y UNIFICACIÓN DE DATOS EN UNA SOLA VARIABLE PARA ALMACENAR EN BDD
        
        /* Se incluye en ORDERDATA todo lo que venga por el índice de DATAPRODUCT del REQUEST
        a excepción del TOKEN */
        $orderData['productsOrdered'] = $request->except('_token')['dataProduct'];
        
        // La variable a lanzar almacena el precio total
        $data['orderTotalPrice'] = $orderData['orderDataTotalPrice'];
        // Se convierte a JSON los productos preparados (los convierte a cadena)
        $data['productsOrdered'] = json_encode($orderData['productsOrdered']);
        // Reemplazo de las comas por "],[" en el pedido
        $data['productsOrdered'] = str_replace('","', "],[", $data['productsOrdered']);
        /* ^ Estos tres últimos pasos ^ se hacen para poder aplicar un JSON_DECODE durante
        la fase de extracción de datos desde la BDD*/

        // Se añade el valor 0 a la columna de envíos
        $data['sent'] = 0;
        // Todos los datos ya están preparados. Ahora, a almacenar en la BDD mediante CREATE
        Order::create($data);

        /* Se eliminan los datos almacenados hasta el momento a través del SESSION de Laravel
        (los del carrito en concreto) */
        session()->forget('productsCart');

        // Actualización de stock de productos pedidos

        // Preparación de variable para actualizar stock de productos
        $updateStock = $request->except('_token')['dataProduct'];

        // Se recorre la variable...
        foreach ($updateStock as $key => $value) {
            // Se separa cada valor mediante método EXPLODE para convertir en ARRAY a través de ", "
            $values = explode(", ", $value);

            // De ahí se sacan 4 índices de array que corresponden a:
            
            // ID de producto
            $productId = (int)$values[0];
            // Cantidad de producto
            $productQuantity = (int)$values[2];
            // Se saca el stock actual del producto en cuestión...
            $actualStockInDB = Products::find($productId)->stock;
            // ... Y se aplica una resta con el stock que ha pedido el cliente
            $newStock = $actualStockInDB - $productQuantity;
            // Se realiza una actualización del stock de producto mediante sentencia eloquent de Laravel
            Products::where('id', $productId)->update(['stock' => $newStock]);
        }
        // Se reubica al usuario a la página de productos y se le avisa mediante mensaje
        return to_route('products.index')->with('success', '¡Gracias! Pronto te enviaremos tu pedido');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    // Método para ver los detalles del pedido con parámetro de pedido (ORDER)
    public function show(Order $order)
    {
        /* Reubicación de usuario hacia la vista de detalles de pedido adjuntando
        variable de pedido para manejar */
        return view('orders.details')->with('order', $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Models\Order  $order
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Order $order)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
