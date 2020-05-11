<?php

namespace App\Http\Controllers;

use App\Categoria;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request){
        $request->validate([
            'cantidad' => 'integer',
            'page'=>'integer',
            'categoria_id' => 'integer',        //si pertenece a cierto padre unicamenbte (0=> solo raiz, null: cualquiera)
            'palabra_clave' => 'string|max:40',
            'producto_match' => 'integer',     //para que aparte de mostrar todx lo que ya tenemos, muestre si esta o no en una categoria especifica
            'opcionProducto' => 'integer',     //1-> todos 2->sincate, 3->con cat
            'producto_id' => 'integer',         //si pertenece solo a un tipo de producto
        ]);
        $categoriasQuery = Categoria::query();
        //VER SI SOLO SE QUIERE DE CIERTO PADRE LA CATEGORIA (el nulo = solo los principales)

        if(null !== ($categoriaPadre = $request->get('categoria_id'))){
            $categoriasQuery = $categoriasQuery->where('categoria_id',$categoriaPadre==0?null:$categoriaPadre);
        }
        //IMPRIMIR LA CANTIDAD DE PRODUCTOS QUE SE TIENE
        $categoriasQuery = $categoriasQuery->withCount(['productos as cant_productos']);
        //FILTRO POR CANTIDAD DE PRODUCTOS
        switch($opcionProducto = $request->get('opcionProducto',1)){
            case 2:
            case 3:
//                $categoriasQuery = $categoriasQuery->where('cant_categorias',$opcioncategoria==2?'=':'>',0);
                $categoriasQuery = $categoriasQuery->has('productos',$opcionProducto==2?'=':'>',0);
                break;
        }
        //si pertenece a cierto producto
        if($productoId = $request->get('producto_match')){
            $categoriasQuery = $categoriasQuery->withCount(['productos','productos as seleccionado' => function(Builder $query2) use ($productoId) {
                $query2->where('categoria_producto.producto_id','=',$productoId);
            }]);
        }
        //solo de cierta producto
        if($productoId = $request->get('producto_id')){
            $categoriasQuery = $categoriasQuery->whereHas('productos',function (Builder $q) use ($productoId) {
                $q->where('id','=',$productoId);
            });
        }
        //solo de cierta palabra clave
        if($palabraClave = $request->get('palabra_clave')){
            $categoriasQuery->where(function (Builder $query) use ($palabraClave) {
                $query->where('nombre','like',"% {$palabraClave}%")
                    ->orWhere('nombre','like',"{$palabraClave}%");
//                    ->orWhere('nombre','like',"% {$palabraClave}");
            });
        }
        $productos = $categoriasQuery->paginate($request->input('cantidad',10),['*'],'page',$request->input('page',1));
//        return DB::getQueryLog();
        return $productos;

//        $categoria_id = $request->input('categoria_id');
//        return Categoria::porPadreId($categoria_id);
    }

    /**
     * Retorna un array con todas las categorias que existen
     * @param null $id
     * @return Builder[]|Collection
     */
    public function listaOrdenada($id = null){
        $catRoot = Categoria::query()->where('categoria_id',$id)->get();
        foreach($catRoot as $cat){
            $cat->subCategorias = $this->listaOrdenada($cat->id);
        }
        return $catRoot;
    }

    public function updateProductosList(Request $request){
        $user = Auth::user();
        if($user && $user->hasAnyRole('admin')){
            $post = $request->all();
            $lista = json_decode($post['lista']);
            $categoria = $post['categoria'];
            Categoria::updateProducto($categoria,$lista);
            return ['success' => true];
        }else{
            return response(['success'=>false,'message'=>'tenes que iniciar sesion como admin'],401);
        }
    }


    public function vista(){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Categoria $categoria
     * @return Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Categoria $categoria
     * @return Response
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Categoria $categoria
     * @return Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Categoria $categoria
     * @return Response
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
