<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Producto;
use App\TipoRespuesta;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller{


    public function index(Request $request){
        $request->validate([
            'cantidad' => 'integer',
            'page'=>'integer',
            'categoria_id' => 'integer',
            'palabra_clave' => 'string|max:40',
            'categoria_match' => 'integer',     //para que aparte de mostrar todx lo que ya tenemos, muestre si esta o no en una categoria especifica
            'opcionCategoria' => 'integer',     //1-> todos 2->sincate, 3->con cat
            'recursivo' => 'boolean'
        ]);
        $perpage = $request->input('cantidad',10);
        $page = $request->input('page',1);
        $palabraClave = $request->get('palabra_clave');
        $productosQuery = Producto::query();
        //IMPRIMIR LA CANTIDAD DE CATEGORIAS QUE SE TIENE
        $productosQuery = $productosQuery->withCount(['categorias as cant_categorias']);
        //FILTRO POR CANTIDAD DE CATEGORIAS
        switch($opcionCategoria = $request->get('opcionCategoria',1)){
            case 2:
            case 3:
//                $productosQuery = $productosQuery->where('cant_categorias',$opcionCategoria==2?'=':'>',0);
                $productosQuery = $productosQuery->has('categorias',$opcionCategoria==2?'=':'>',0);
                break;
        }
        //si pertenece a cierta categoria
        if($categoriaId = $request->get('categoria_match')){
            $productosQuery = $productosQuery->withCount(['categorias','categorias as seleccionado' => function(Builder $query2) use ($categoriaId) {
                $query2->where('categoria_producto.categoria_id','=',$categoriaId);
            }]);
        }
        //solo de cierta categoria
        if($categoriaId = $request->get('categoria_id')){
            $productosQuery = $productosQuery->where(function(Builder $we) use ($request, $categoriaId) {
                $we->whereHas('categorias',function (Builder $q) use (/*$palabraClave,*/ $categoriaId){$this->recur($q,$categoriaId/*,$palabraClave*/);});
                if($request->get('recursivo',false)) {
                    $we->orwhereHas('categorias.categoriaPadre', function (Builder $q) use (/*$palabraClave,*/ $categoriaId) {
                        $this->recur($q, $categoriaId/*,$palabraClave*/);
                    });
                    $we->orwhereHas('categorias.categoriaPadre.categoriaPadre', function (Builder $q) use (/*$palabraClave,*/ $categoriaId) {
                        $this->recur($q, $categoriaId/*,$palabraClave*/);
                    });
                    $we->orwhereHas('categorias.categoriaPadre.categoriaPadre.categoriaPadre', function (Builder $q) use (/*$palabraClave,*/ $categoriaId) {
                        $this->recur($q, $categoriaId/*,$palabraClave*/);
                    });
                }
            });
        }
        //solo de cierta palabra clave
        if($palabraClave){
            $productosQuery->where(function (Builder $query) use ($palabraClave) {
                $query->where('codigo','=',$palabraClave);
                $query->orWhere('nombre','like',"% {$palabraClave}%")
                    ->orWhere('nombre','like',"{$palabraClave}%");
//                    ->orWhere('nombre','like',"% {$palabraClave}");
            });
        }
        DB::enableQueryLog(); // Enable query log
        $productos = $productosQuery->paginate($perpage,['*'],'page',$page);
//        return DB::getQueryLog();
        return $productos;
    }

    public function listaRecursiva(Request $request){
        $productosQuery = Producto::query();
        $palabraClave = $request->get('palabra_clave');
        if($categoriaId = $request->get('categoria_id')){
            $productosQuery = $productosQuery->whereHas('categorias',function (Builder $q) use ($palabraClave, $categoriaId){$this->recur($q,$categoriaId,$palabraClave);});
            $productosQuery = $productosQuery->orwhereHas('categorias.categoriaPadre',function (Builder $q) use ($palabraClave, $categoriaId){$this->recur($q,$categoriaId,$palabraClave);});
            $productosQuery = $productosQuery->orwhereHas('categorias.categoriaPadre.categoriaPadre',function (Builder $q) use ($palabraClave, $categoriaId){$this->recur($q,$categoriaId,$palabraClave);});
//            //otra sub
//            $otro = Producto::query()->whereHas('categorias.categoriaPadre',function (Builder $q) use ($categoriaId){
//                $q->where('id','=',1);
//            });
//            $productosQuery = $productosQuery->union($otro);
//            //otra sub
//            $otro = Producto::query()->whereHas('categorias.categoriaPadre.categoriaPadre',function (Builder $q) use ($categoriaId){
//                $q->where('id','=',1);
//            });
//            $productosQuery = $productosQuery->union($otro);
//            //otra sub
//            $otro = Producto::query()->whereHas('categorias.categoriaPadre.categoriaPadre.categoriaPadre',function (Builder $q) use ($categoriaId){
//                $q->where('id','=',1);
//            });
//            $productosQuery = $productosQuery->union($otro);
        }
        $productos = $productosQuery->paginate($request->input('cantidad',10),['*'],'page',$request->input('page',1));
//        return DB::getQueryLog();
        return $productos;
    }

    private function recur(Builder &$q,$categoriaId/*,$palabraClave*/){
        $q->where('id','=',$categoriaId);
//        if($palabraClave){
//            $q->where(function (Builder $query) use ($palabraClave) {
//                $query->where('codigo','=',$palabraClave);
//                $query->orWhere('nombre','like',"% {$palabraClave}%")
//                    ->orWhere('nombre','like',"{$palabraClave}%");
//            });
//        }
        return $q;
    }


    public function getCategorias($id, Request $resquest){
        $producto =  Producto::find($id);
        $categorias = $producto->categorias;
        return $categorias;
    }

    public function updateCategorias($id, Request $resquest){
        $validado = $resquest->validate([
            'categorias' => 'array|min:1',
            'categorias.*.categoriaId' => 'required|integer',
            'categorias.*.valor' => 'required|boolean'
        ]);
        $this->autorizar('admin');
        $producto = Producto::find($id);
        $listaCategorias = $validado['categorias'];
        $variacion = 0;
        foreach ($listaCategorias as $categoria){
            try {
                if ($categoria['valor']) {
                    $producto->categorias()->attach($categoria['categoriaId']);
                    $variacion++;
                } else {
                    $producto->categorias()->detach($categoria['categoriaId']);
                    $variacion--;
                }
            }catch (\Exception $e){

            }
        }
        return response(['success'=>true,'data'=>['variacion'=>$variacion]],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('producto.editar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store(Request $request){
        return [
            'op'=>'store',
            'data' => $request->all()
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Producto $producto)
    {
        return view('producto.visor',[
            'producto' => $producto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Producto $producto)
    {
        return view('producto.editar',[
            'producto' => $producto
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Producto $producto
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto){
        $user = Auth::user();
        if($user && $user->hasAnyRole('admin')){
            $producto->update($request->all());
            return response(['success'=>true],200);
        }else{
            return response(['success'=>false,'message'=>'tenes que iniciar sesion como admin'],401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto){
        $user = Auth::user();
        if($user && $user->hasAnyRole('admin')){
            try {
                $producto->delete();
                return response(['success'=>true],200);
            } catch (\Exception $e) {
                return response(['success'=>false,'message'=>$e->getMessage()],401);
            }
        }else{
            return response(['success'=>false,'message'=>'tenes que iniciar sesion como admin'],401);
        }
    }

    public function thumbnail(Request $request,$id){
        $validatedData = $request->validate([
            'thumbnail' => 'required|mimes:jpeg|dimensions:min_width=100,min_height=100,max_width=500,max_height=500',
        ]);
        /** @var Producto $producto */
        $producto = Producto::find($id);
        $producto->setThumbnail($validatedData['thumbnail']->get());
        return response(['success'=>true,'message'=>'Se alzo el archivo'],200);
    }
}
