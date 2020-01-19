<template>
    <main class="main">
        <ol class="breadcrumb">

                <li class="breadcrumb-item titulo-1">Almacenes</li>

        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">  
                        <h6>Almacenes</h6>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary boton" data-toggle="modal" data-target="#modalRegistroAlmacen">
                        Nuevo
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="modalRegistroAlmacen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Nuevo almacén</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" @submit.prevent="agregarAlmacen()" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="codigo">Código</label>
                                                <input type="codigo" class="form-control" id="codigo" aria-describedby="emailHelp" placeholder="Código" v-model="codigo">
                                            </div>
                                            <div class="form-group">
                                                <label for="descripcion">Descripción</label>
                                                <input type="descripcion" class="form-control" id="descripcion" placeholder="Descripción" v-model="descripcion">
                                            </div>
                                            <div class="form-group">
                                                <label for="direccion">Dirección</label>
                                                <input type="direccion" class="form-control" id="direccion" placeholder="Dirección" v-model="direccion">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary boton" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary boton">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="myTable">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Descripcion</th>
                            <th scope="col">Direccion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="almacen of arrayAlmacen" :key="almacen.key">
                            <th scope="row">1</th>
                            <td>{{almacen.codigo}}</td>
                            <td>{{almacen.descripcion}}</td>
                            <td>{{almacen.direccion}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </main>
</template>
<script>

import datatables from 'datatables'

export default {
    mounted(){
        this.listarAlmacen()
    },
    data(){
        return{
            /*
            buscar: '',
            criterio: 'nombre',
            arrayAlmacen: []
            */
            arrayAlmacen:[],
            codigo: '',
            descripcion: '',
            direccion: ''
        }
    },
    methods:{
        miTabla(){
            $( function () {
                $('#myTable').DataTable();
            } );
        },
        listarAlmacen(){
            var urlAlmacen = 'almacen';
            axios.get(urlAlmacen).then(response=>{
                this.arrayAlmacen = response.data;
                this.miTabla();
            })
        },
        agregarAlmacen(){
            if(this.codigo.trim() === '' || this.descripcion.trim() === '' || this.direccion.trim() === ''){
                alert('Debes completar todos los campos')
                return;
            }
            var me = this;
            let formDatos = new FormData();
            formDatos.append('codigo',this.codigo);
            formDatos.append('descripcion',this.descripcion);
            formDatos.append('direccion',this.direccion);
            let obj = this;
            this.codigo = ""
            this.descripcion = ""
            this.direccion = ""
            axios.post('/almacen', formDatos).then((response)=>{
                $( function () {
                    $('#myTable').DataTable().destroy();
                } );
                this.listarAlmacen();
                
            })
            $('#modalRegistroAlmacen').modal('toggle');
        },
        
        
    },
    
}
</script>