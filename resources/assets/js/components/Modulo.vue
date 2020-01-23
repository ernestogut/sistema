<template>
    <main class="main">
        <ol class="breadcrumb">

                <li class="breadcrumb-item titulo-1">{{titulo}}</li>

        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">  
                        <h6>{{titulo}}</h6>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary boton" data-toggle="modal" @click="abrirModalRegistrar()">
                        Nuevo
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="modalRegistroItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 v-show="!modoEditable" class="modal-title" id="exampleModalLabel">Nuevo Almacén</h5>
                                        <h5 v-show="modoEditable" class="modal-title" id="exampleModalLabel">Editar Almacén</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formA" action="" @submit.prevent="modoEditable?editarItem(id):agregarItem()" enctype="multipart/form-data">
                                            <div class="form-group" v-for="(variable) of variables" :key="variable.key">
                                                <label :for="variable.for">{{variable.titulo}}</label>
                                                <input :type="variable.type" class="form-control" :id="variable.id" :name="variable.name" aria-describedby="emailHelp"
                                                v-model="variable.var" :placeholder="variable.placeholder">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary boton" data-dismiss="modal">Cerrar</button>
                                                <button v-show="!modoEditable" type="submit" class="btn btn-primary boton">Guardar</button>
                                                <button v-show="modoEditable" type="submit" class="btn btn-primary boton">Actualizar</button>
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
                            <th scope="col" v-for="cabecera of cabeceras" :key="cabecera.id">{{cabecera}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) of arrayItems" :key="item.key">
                            <th scope="row">{{index+1}}</th>
                            <td v-for="(value, key) in item" :key="value.key" v-if="key !== 'id' && key !== 'created_at' && key != 'updated_at'">{{value}}</td>
                            <td>
                                <span class="btn btn-primary btn-sm boton" @click="modalEditar(item)">editar</span>
                                <span class="btn btn-danger btn-sm boton" @click="eliminarItem(item, index)">x</span>
                            </td>
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
    props: ['variables', 'ruta', 'cabeceras', 'titulo'],
    mounted(){
        this.listarItem()
    },
    data(){
        return{
            codigo: '',
            descripcion: '',
            direccion: '',
            id: '',
            modoEditable: true,
            arrayItems:[],
        }
    },
    methods:{
        modalEditar(item){
            $('#modalRegistroItem').modal('show')
            axios.get(`${this.ruta}/${item.id}`).then((response)=>{
                /*for(var i = 0;  i < this.variables.length; i++){
                    this.variables[i].var = ''
                }*/
                this.codigo = response.data.codigo;
                this.descripcion = response.data.descripcion;
                this.direccion = response.data.direccion;
            })
            this.modoEditable = true;
            this.id = item.id
        },
        abrirModalRegistrar(){
            for(var i = 0;  i < this.variables.length; i++){
                this.variables[i].var = ''
            }
            this.modoEditable = false;
            $('#modalRegistroItem').modal('show')
        },
        miTabla(){
            $( function () {
                $('#myTable').DataTable();
            } );
        },
        listarItem(){
            var urlItem = this.ruta;
            axios.get(urlItem).then(response=>{
                this.arrayItems = response.data;
                this.miTabla();
            })
        },
        agregarItem(){
            if(this.variables[0].var.trim() === '' || this.variables[1].var.trim() === '' || this.variables[2].var.trim() === ''){
                alert('Debes completar todos los campos')
                return;
            }
            var me = this;
            let formDatos = new FormData();
            for(var i = 0; i < this.variables.length; i++){
                formDatos.append(this.variables[i].name,this.variables[i].var);
            }
            for(var j = 0;  j < this.variables.length; j++){
                this.variables[j].var = ''
            }
            axios.post(`${this.ruta}`, formDatos).then((response)=>{
                $( function () {
                    $('#myTable').DataTable().destroy();
                } );
                this.listarItem();
                
            })
            $('#modalRegistroItem').modal('toggle');
        },
        editarItem(item){
            var miLista = new Object();
            for(var i = 0; i < this.variables.length; i++){
                miLista[this.variables[i].name] = this.variables[i].var
            }
            axios.put(`${this.ruta}/${item}`, miLista).then((response) =>{
                $( function () {
                    $('#myTable').DataTable().destroy();
                } );
                this.listarItem();
            })
            $('#modalRegistroItem').modal('toggle');
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.codigo}?`)){
                axios.delete(`${this.ruta}/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)
                        
                    })
                    $( function () {
                        $('#myTable').DataTable().destroy();
                    } );
                    this.listarItem();
            }
        }
        
    }
}
</script>