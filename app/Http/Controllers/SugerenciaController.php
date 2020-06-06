<?php

namespace App\Http\Controllers;

use App\Sugerencia;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class SugerenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request){
        $request->validate([
            'cantidad' => 'integer|max:40',
            'page'=>'integer',
        ]);
        $this->autorizar('financiero');
        return Sugerencia::query()->orderBy('updated_at')->paginate($request->get('cantidad'),['*'],'page',$request->get('page'));
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
     * @param Request $request
     * @return string[]
     */
    public function store(Request $request){
        $req = $request->validate([
            'nombre' => 'required|max:150',
            'email' => 'required|email|max:40',
            'asunto' => 'required|max:50',
            'mensaje' => 'required|max:1000'
        ]);
        $sug = new Sugerencia($req);
        $sug->save();
        return ['success' => 'true', 'message' => 'Tu sugerencia/queja fue guardada'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function show(Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sugerencia $sugerencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sugerencia  $sugerencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sugerencia $sugerencia)
    {
        //
    }
}
