@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="#" class="btn btn-primary" onclick="productoCRUD(true,'C')">Crear nuevo</a>
            </div>
            @foreach($productos as $producto)
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="{{asset('img/producto.jpg')}}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">{{$producto->nombre}}</h5>
                            <p class="card-text">Precio: {{$producto->precio}} Gs.</p>
                            <a href="{{route('prefix.producto.edit',$producto->id)}}" class="btn btn-primary">Editar</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('modal')

    <!-- Modal -->
    <div class="modal fade" id="modalAgregarColumna" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Nombre</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Nombre Columna" aria-label="Recipient's username" aria-describedby="basic-addon2" id="columna-nueva-nombre-input">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">Obligatorio?</span>
                        </div>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" aria-label="Checkbox for following text input" id="columna-nueva-obligatorio-check">
                            </div>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">Por teclado?</span>
                        </div>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" aria-label="Checkbox for following text input" id="columna-nueva-teclado-check">
                            </div>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text">No repetido?</span>
                        </div>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="checkbox" aria-label="Checkbox for following text input" id="columna-nueva-no-repetido-check">
                            </div>
                        </div>
                    </div>
                    <label>Tipo de dato esperado</label>
                    <div class="input-group mb-3">
                        <select class="custom-select" id="columna-nueva-tipo-select" name="columna-nueva-tipo-select">
                            <option value="0">Seleccione...</option>
                        </select>
                        <div class="input-group-append">
                            <label class="input-group-text" for="columna-nueva-tipo-select">Tipo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    <script>
        function productoCRUD(modal,operacion) {
            if(modal){
                switch (operacion) {
                    case 'C':

                        break;
                }
            }
        }
    </script>
@endsection
