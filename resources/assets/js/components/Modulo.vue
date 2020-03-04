<template>
    <main class="main">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">  
                        <h4>{{titulo}}</h4>
                        <!-- Abrir modal -->
                        <button type="button" class="btn btn-primary boton" data-toggle="modal" @click="abrirModalRegistrar()">
                        Nuevo
                        </button>
                        <!-- Modal -->
                    </div>
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
                                            <div class="form-group col-md-6" v-if="tituloModal == 'comprobante'">
                                                <label >Tipo de comprobante</label>
                                                <select class="form-control" v-model="comprobanteElegido">
                                                    <option disabled value="">Escoje...</option>
                                                    <option v-for="comprobante in arrayComprobantes" :key="comprobante.id" :value="comprobante.id">{{comprobante.nombre}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6" v-if="tituloModal == 'cliente' || tituloModal == 'tipo de comprobante' || tituloModal == 'proveedor'">
                                                <label >Tipo de documento</label>
                                                <select class="form-control" v-model="tipoDocumentoElegido">
                                                    <option value=""></option>
                                                    <option v-for="documento in arrayDocumentos" :key="documento.id" :value="documento.id">{{documento.tipo_doc}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6" v-for="(variable) of variables" :key="variable.key">
                                                <label :for="variable.for">{{variable.titulo}}</label>
                                                <input :type="variable.type" class="form-control" :id="variable.id" :name="variable.name" aria-describedby="emailHelp"
                                                v-model="variable.var" :placeholder="variable.placeholder">
                                            </div>
                                            <!--Si es producto, se añaden estos atributos-->
                                            <div class="d-flex flex-wrap justify-content-between" v-if="tituloModal == 'producto'">
                                                <div class="upload-btn-wrapper">
                                                    <button class="botonArchivo">Subir un archivo</button>
                                                    <input type="file" @change="obtenerImagenProducto">
                                                </div>
                                                <div class="imagenContenedor">
                                                    <img :src="imagenProducto" id="imagen" alt="">
                                                </div>
                                            </div>
                                            <!--<div class="form-group col-md-3" v-if="tituloModal == 'producto'">
                                                <label >Almacén</label>
                                                <select class="form-control" v-model="almacen_id">
                                                    <option disabled value="">Escoje un almacén</option>
                                                    <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                                </select>
                                            </div>-->
                                            
                                            <!--Fin atributos productos-->
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
            {{tabla}}
            <spinner v-if="loading"></spinner>
            <datatable :eliminarItem="eliminarItem" :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos" v-else-if="initiated" :controlador="controlador" :factura="factura" :idTabla="idTabla" :funcionBoton="(controlador!=3 || controlador!=6)?modalEditar:listarSeries" :listarSeries="listarSeries" @emitirEvId="recibirId"></datatable>        
    </main>
</template>
<script>

import datatables from 'datatables'

export default {
    //props: ['variables', 'ruta', 'cabeceras', 'titulo', 'tituloModal', 'factura', 'controlador', 'idTabla', 'listarSeries', 'tabla'],
    props:{
        variables: Array,
        ruta: String,
        cabeceras: Array,
        titulo: String,
        tituloModal: String,
        factura: Boolean,
        controlador: Number,
        idTabla: String,
        listarSeries: Function,
        //arrayItems: Array,
        tabla: Array
    },
    mounted(){
        if(this.tituloModal != 'tipo de combrobante'){
            this.listarItem()
        }
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
            arrayComprobantes: [],
            arrayDocumentos: [],
            almacen_id: '',
            iconos: 'icon-pencil',
            loading: false,
            initiated: false,
            comprobanteElegido: '',
            tipoDocumentoElegido: 1
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
        recibirId(id){
            this.id_comprobante = id
            this.$emit('emitirEvId', this.id_comprobante)
        },
        modalEditar(item){
            /*if(this.tituloModal == 'producto'){
                this.seleccionarAlmacen()
            }else */if(this.tituloModal == 'cliente' || this.tituloModal == 'tipo de comprobante' || this.tituloModal== 'proveedor'){
                this.listarDocumentos()
            }
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
                if(this.tituloModal == 'producto'){
                    this.almacen_id = response.data.almacen_id;
                }

            })
            this.modoEditable = true;
            this.id = item.id
            $('#modalRegistroItem').modal('show')
        },
        abrirModalRegistrar(){
            for(var i = 0;  i < this.variables.length; i++){
                this.variables[i].var = ''
            }
            
            //Si es agregar producto, agregar atributos especiales del producto
            if(this.tituloModal == 'producto'){
                this.imagenMiniatura = ''
                //this.seleccionarAlmacen()
            }else if(this.tituloModal == 'cliente' || this.tituloModal == 'tipo de comprobante' || this.tituloModal == 'proveedor'){
                this.listarDocumentos()
            }
            //
            this.modoEditable = false;
            $('#modalRegistroItem').modal('show')

        },
        miTabla(){
            $( function () {
                $('#myTable').DataTable({
                    searching: true
                });
            } );
        },
        listarItem(){
            this.loading = true
            var urlItem = this.ruta;
            axios.get(urlItem).then(response=>{
                this.arrayItems = response.data;
                this.loading = false;
                this.initiated = true;
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
        listarComprobantes(){
            var urlItem = '/tipo_comprobante';
            axios.get(urlItem).then(response=>{
                this.arrayComprobantes = response.data;
            })
        },
        listarDocumentos(){
            var urlItem = '/tipo_documento';
            axios.get(urlItem).then(response=>{
                this.arrayDocumentos = response.data;
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
            //Si es agregar producto, agregar atributos especiales del producto
            if(this.tituloModal == 'producto'){
                //formDatos.append('almacen_id', this.almacen_id)
                formDatos.append('imagen', this.imagen)
            }else if(this.tituloModal == 'cliente' || this.tituloModal == 'tipo de comprobante' || this.tituloModal == 'proveedor'){
                formDatos.append('id_tipo_doc', this.tipoDocumentoElegido)
            }else if(this.tituloModal == 'tipo de combrobante'){
                formDatos.append('id_tipo_comprobante', this.id_comprobante)
            }
            //
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
                //formDatos.append('almacen_id', this.almacen_id)
                formDatos.append('imagen', this.imagen) 
            }else if(this.tituloModal == 'cliente' || this.tituloModal == 'tipo de comprobante' || this.tituloModal == 'proveedor'){
                formDatos.append('id_tipo_doc', this.tipoDocumentoElegido)
            }else if(this.tituloModal == 'tipo de comprobante'){
                formDatos.append('id_tipo_comprobante', this.comprobanteElegido)
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
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}

.botonArchivo {
  border: 2px solid gray;
  color: gray;
  background-color: white;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
</style>