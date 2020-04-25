<template>
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <img class="activator" :src="urlBase+((thought.thumbnail)?('productos/'+thought.thumbnail):'img/producto.jpg')">
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4 truncate">{{thought.nombre}}</span>
                    <p><a href="#">Precio: {{thought.precio.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}} Gs.</a></p>
                </div>
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Detalles<i class="material-icons right">close</i></span>
                    <p>
                        {{thought.nombre}}
                    </p>
                </div>
                <div class="card-action">
                    <a href="#">Agregar al carrito</a>
                    <a href="#">Lista de deseos</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    // <img class="activator" :src="urlBase+((thought.thumbnail)?('productos/'+thought.thumbnail):'img/producto.jpg')">
    export default {
        props: ['thought'],
        data() {
            return {
                editMode: false,
                urlBase: urlBase +'/'
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            onClickDelete() {
                axios.delete(`/thoughts/${this.thought.id}`).then(() => {
                    this.$emit('delete');
                });
            },
            onClickEdit() {
                this.editMode = true;
            },
            onClickUpdate() {
                const params = {
                    nombre: this.thought.nombre
                };
                axios.put(`/thoughts/${this.thought.id}`, params).then((response) => {
                    this.editMode = false;
                    const thought = response.data;
                    this.$emit('update', thought);
                });
            }
        }
    }
</script>
