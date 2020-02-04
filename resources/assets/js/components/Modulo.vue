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
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 v-show="!modoEditable" class="modal-title" id="exampleModalLabel">Nuevo {{tituloModal}}</h5>
                                        <h5 v-show="modoEditable" class="modal-title" id="exampleModalLabel">Editar {{tituloModal}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formA" action="" @submit.prevent="modoEditable?editarItem(id):agregarItem()" enctype="multipart/form-data">
                                            <div class="form-row">
                                                <div class="form-group col-md-6" v-for="(variable) of variables" :key="variable.key">
                                                    <label :for="variable.for">{{variable.titulo}}</label>
                                                    <input :type="variable.type" class="form-control" :id="variable.id" :name="variable.name" aria-describedby="emailHelp"
                                                    v-model="variable.var" :placeholder="variable.placeholder">
                                                </div>
                                                <div class="d-flex flex-wrap justify-content-between" v-if="tituloModal == 'producto'">
                                                    <div>
                                                        <label for="exampleFormControlFile1"></label>
                                                        <input type="file" class="form-control-file" @change="obtenerImagenProducto" id="exampleFormControlFile1">
                                                    </div>
                                                    <div class="imagenContenedor">
                                                        <img :src="imagenProducto" id="imagen" alt="">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3" v-if="tituloModal == 'producto'">
                                                    <label >Almacén</label>
                                                    <select class="form-control" v-model="almacen_id">
                                                        <option disabled value="">Escoje un almacén</option>
                                                        <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary boton" data-dismiss="modal">Cerrar</button>
                                                    <button v-show="!modoEditable" type="submit" class="btn btn-primary boton">Guardar</button>
                                                    <button v-show="modoEditable" type="submit" class="btn btn-primary boton">Actualizar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <datatable :modalEditar="modalEditar" :eliminarItem="eliminarItem" :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos"></datatable>
            </div>         
    </main>
</template>
<script>

import datatables from 'datatables'

export default {
    props: ['variables', 'ruta', 'cabeceras', 'titulo', 'tituloModal'],
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
            imagen: '',
            imagenMiniatura: '',
            arrayAlmacen: [],
            almacen_id: '',
            iconos: 'icon-pencil'
        }
    },
    computed:{
        imagenProducto(){
            return this.imagenMiniatura;
        },
        
    },
    methods:{
        obtenerImagenProducto(e){
            let archivo = e.target.files[0];
            this.imagen = archivo;
            this.cargarImagenProducto(archivo);
        },
        cargarImagenProducto(archivo){
            let reader = new FileReader()
            reader.onload = (e) =>{
                this.imagenMiniatura = e.target.result;
            }
            reader.readAsDataURL(archivo)
        },
        modalEditar(item){
            $('#modalRegistroItem').modal('show')
            axios.get(`${this.ruta}/${item.id}`).then((response)=>{
                var arrayValoresRecientes = Object.values(response.data)
                var arrayValores = [];
                for (var j = 0; j < arrayValoresRecientes.length; j++) {
                    if(arrayValoresRecientes.indexOf(arrayValoresRecientes[j]) != 0 && arrayValoresRecientes.indexOf(arrayValoresRecientes[j]) != arrayValoresRecientes.length-1 && arrayValoresRecientes.indexOf(arrayValoresRecientes[j]) != arrayValoresRecientes.length-2){
                        arrayValores.push(arrayValoresRecientes[j])
                    }                      
                }
                for(var i = 0;  i < this.variables.length; i++){
                    for(var j = 0; j < arrayValores.length; j++){
                        if(this.variables.indexOf(this.variables[i]) == arrayValores.indexOf(arrayValores[j])){
                            this.variables[i].var = arrayValores[j];
                            break;
                        }
                    }
                }
            })
            this.modoEditable = true;
            this.id = item.id
        },
        abrirModalRegistrar(){
            for(var i = 0;  i < this.variables.length; i++){
                this.variables[i].var = ''
            }
            this.seleccionarAlmacen()
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
        seleccionarAlmacen(){
                let me= this;
                var url='/almacen';
                axios.get(url).then(function (response){
                    me.arrayAlmacen = response.data;
                })
            },
        agregarItem(){
            /*if(this.variables[0].var.trim() === '' || this.variables[1].var.trim() === '' || this.variables[2].var.trim() === ''){
                alert('Debes completar todos los campos')
                return;
            }*/
            
            var me = this;
            let formDatos = new FormData();
            for(var i = 0; i < this.variables.length; i++){
                formDatos.append(this.variables[i].name,this.variables[i].var);
            }
            if(this.tituloModal == 'producto'){
                formDatos.append('almacen_id', this.almacen_id)
                formDatos.append('imagen', this.imagen)
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
            /*var miLista = new Object();
            for(var i = 0; i < this.variables.length; i++){
                miLista[this.variables[i].name] = this.variables[i].var  
            }*/
            let formDatos = new FormData();
            for(var i = 0; i < this.variables.length; i++){
                formDatos.append(this.variables[i].name,this.variables[i].var);
            }
            if(this.tituloModal == 'producto'){
                formDatos.append('almacen_id', this.almacen_id)
                formDatos.append('imagen', this.imagen) 
            }
            for(var j = 0;  j < this.variables.length; j++){
                this.variables[j].var = ''
            }
            formDatos.append("_method", "put");
            var mi = $('#myTable').DataTable()
            axios.post(`${this.ruta}/${item}`, formDatos).then((response) =>{
                mi.destroy()
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

<style scoped>


.imagenContenedor{
   width: 100px;
   height: 100px; 
}
#imagen{
    max-width: 100%;
}
</style>