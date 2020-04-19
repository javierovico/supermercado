@php
    if(!isset($producto)){
        $producto = new \App\Producto();
    }
@endphp
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <label>Detalles</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3">Nombre</span>
                </div>
                <input value="{{$producto->nombre}}" type="text" class="form-control" id="producto-nombre" aria-describedby="basic-addon3">
            </div>
            <div class="input-group mb-3">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="{{asset('img/producto.jpg')}}" alt="Card image cap">
                </div>
            </div>

            <div class="input-group col-md-12 p-2">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" accept="image/jpeg" id="inputGroupFile02" onchange="archivoSeleccionado(this)"/>
                    <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="input-group-append">
                    <button onclick="enviarFichero()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>

    function archivoSeleccionado(e){
        let fileName = $(e).val();
        fileName = fileName.replace(/^.*[\\\/]/, '');
        $(e).next('.custom-file-label').html(fileName);

    }

    function enviarFichero() {
        const attachment = $('#inputGroupFile02').prop('files')[0];
        const formData = new FormData();
        if(attachment!=null){
            formData.append('userfile', attachment, $('.custom-file-label').html().trim());
        }
        formData.append('productoNombre',$('#producto-nombre').val());
        enviarDatos(formData,'{{route('prefix.producto.'.(isset($producto->id)?'update':'store'),$producto->id)}}',function (data) {
            console.log(data);
        },true);
    }


    function enviarDatos(dato,direccion,listener, fichero = false) {
        {{--if(dato instanceof FormData){--}}
        {{--    dato.append('_token','{{csrf_token()}}')--}}
        {{--}else{--}}
        {{--    dato._token = '{{csrf_token()}}';--}}
        {{--}--}}
        const ajaxConfig ={
            url: direccion,
            data: dato,
            type: '{{!isset($producto->id)?'POST':'PUT'}}',
            cache: false,
            dataType: 'json',
            error: function(jqXHR, textStatus, errorThrown) {
                alert('error');
                console.log(jqXHR,textStatus,errorThrown);
            },
            success: function(data, textStatus, jqXHR) {
                if(data.success){
                    listener(data.dato);
                }else{
                    alert('Error: '+JSON.stringify(data.error));
                }
            }
        };
        if(fichero){
            ajaxConfig.processData = false;
            ajaxConfig.contentType = false;
        }
        $.ajax(ajaxConfig);
    }
</script>
