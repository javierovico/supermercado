<?php

use App\Categoria;
use Illuminate\Database\Seeder;

class CategorizacionNuevaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $categoriasOriginales = Categoria::query()->where('categoria_id',null)->get();
        $cateGeneral = new Categoria();
        $cateGeneral->nombre = 'Generales';
        $cateGeneral->codigo = 'general';
        $cateGeneral->save();
        /** @var Categoria $categoria */
        foreach($categoriasOriginales as $categoria){
            $categoria->categoriaPadre()->associate($cateGeneral);
            $categoria->save();
        }
        $cateGeneral = new Categoria();
        $cateGeneral->nombre = 'Principales';
        $cateGeneral->codigo = 'principales';
        $cateGeneral->save();

    }
}
