<template>

    <div class="main">
        <b-card>
            <template v-slot:header>
            <div class="d-flex justify-content-between align-items-center p-0">
                <p>Tipos de comprobante</p>
                <b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                    Nuevo tipo de comprobante
                </b-button>
            </div>
            </template>
        <vue-datatable :listarSeries="listarSeries" :items="arrayItems" :fields="cabecerasComprobantes" :controlador="5" @emitirEvId="recibirId" :factura="false" :funcionBoton="abrirModalModificar" :cargando="cargando">

        </vue-datatable>
        <b-modal size="lg" hide-footer v-model="estadoModal" id="modalTipoComprobante" :title="modoEditable?'Modificar tipo de comprobante':'Nuevo tipo de comprobante'" @hidden="resetModal">
            <form
                id="formA"
                action
                @submit.prevent="modoEditable?modificarTipoComprobante(id):agregarTipoComprobante()"
                enctype="multipart/form-data"
                >
                    <div class="form-row">
                    <div class="form-group col-md-6">
                            <label >Tipo de documento</label>
                            <select class="form-control" v-model="objetoTipoComprobante.id_tipo_doc">
                                <option v-for="documento in arrayDocumentos" :key="documento.id" :value="documento.id">{{documento.tipo_doc}}</option>
                            </select>
                        </div>
                    <div class="form-group col-md-6">
                        <label for="descripcion">Nombre</label>
                        <input
                        type="text"
                        class="form-control"
                        v-model="objetoTipoComprobante.nombre"
                        placeholder="Ingrese la descripcion del almacén"
                        />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="descripcion">Descripción</label>
                        <input
                        type="text"
                        class="form-control"
                        v-model="objetoTipoComprobante.descripcion"
                        placeholder="Ingrese la direccion del almacen"
                        />
                    </div>
                    </div>
                    <b-button size="sm" @click="estadoModal = false">Cerrar</b-button>
                    <b-button size="sm"  v-show="!modoEditable" type="submit" variant="primary">Guardar</b-button>
                    <b-button size="sm"  v-show="modoEditable" type="submit" variant=primary>Actualizar</b-button>
                    </form>
                </b-modal> 
        <div class="modal fade" tabindex="-1" role="dialog" id="modalSeries" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaSeries">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content" >
                        <div class="modal-header">
                            Series
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex flex-nowrap justify-content-end">
                                    <button type="button" class="btn btn-primary boton" data-toggle="modal" @click="nuevaSerieModal()">
                                        Nueva serie
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" >
                                <div class="table-responsive">
                                    <vue-datatable :items="arraySeries" :fields="cabecerasSerie" :controlador="6"  :factura="true" :idTabla="'myTableSeries'" :funcionBotonTrash="condicionSerie" :cargando="cargandoSeries"></vue-datatable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="agregarSeries" aria-labelledby="myLargeModalLabel2" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nueva serie</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" @submit.prevent="agregarSerie()" enctype="multipart/form-data">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="serie">Serie</label>
                                            <input type="text" class="form-control" id="serie" placeholder="Ingresa la serie" v-model="objetoSerie.serie">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="numero_inicial">Numero inicial</label>
                                            <input type="text" class="form-control" id="numero_inicial" placeholder="Establece un número inicial" v-model="objetoSerie.numero_inicial">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="numero_actual">Numero actual</label>
                                            <input type="text" class="form-control" id="numero_actual" placeholder="" v-model="objetoSerie.numero_actual" disabled>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="numero_final">Numero final</label>
                                            <input type="text" class="form-control" id="numero_final" placeholder="Establece un número final" v-model="objetoSerie.numero_final">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="año">Año</label>
                                            <input type="text" class="form-control" id="año" placeholder="Ingresa el año" v-model="objetoSerie.anio">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado">Estado</label>
                                            <select v-model="objetoSerie.id_almacen" class="form-control">
                                                <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="estado">Estado</label>
                                            <select v-model="objetoSerie.estado" class="form-control">
                                                <option :value="1">Habilitado</option>
                                                <option :value="0">Desabilitado</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" @click="cerrarModalAserie()">
                                            Cerrar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            Guardar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-card>
    </div>
    
</template>
<script>

export default {
    mounted(){
        this.listarDocumentos();
        this.obtenerItems();
        $(this.$refs.tablaSeries).on("hidden.bs.modal", this.limpiarTablaSeries) 
    },
    data(){
        return{
            arrayItems: [],
            rutaSerie: '/serie_comprobante',
            cabecerasComprobantes: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc" , class: "text-center"},
                { key: "nombre", label: "Nombre", sortable: true,class: "text-center"},
                { key: "tipo_doc", label: "Tipo de documento", sortable: true, class: "text-center"},
                { key: "descripcion", label: "Descrición", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones" , class: "text-center"}
            ],
            cabecerasSerie: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc" , class: "text-center"},
                { key: "serie", label: "Serie", sortable: true,class: "text-center", class: "text-center"},
                { key: "almacen", label: "Almacén", sortable: true, class: "text-center"},
                { key: "numero_inicial", label: "Número inicial", sortable: true, class: "text-center"},
                { key: "numero_actual", label: "Número actual", sortable: true, class: "text-center"},
                { key: "numero_final", label: "Número final", sortable: true, class: "text-center"},
                { key: "anio", label: "Año", sortable: true, class: "text-center"},
                { key: "estado", label: "Estado", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones" , class: "text-center"}
            ],
            arraySeries: [],
            objetoSerie: 
            
            {
                id_tipo_comprobante: null,
                id_almacen: 1,
                serie: '',
                numero_inicial: 1,
                numero_actual: null,
                numero_final: null,
                anio: '',
                estado: 1,
                arrayDocumentos: [],
                modoEditable: false
            },
            objetoTipoComprobante: {
                id_tipo_doc: null,
                nombre: null,
                descripcion: null
            },
            modoEditable: false,
            estadoModal: false,
            arrayDocumentos: [],
            cargando: false,
            cargandoSeries: false
            //controladorTrash: 1 // 1 -> Deshabilitar, 2 -> Habilitar
        }

    },
    computed:{
        arrayAlmacen(){
            return this.$store.getters.arrayAlmacen;
        }
    },
    methods:{
        obtenerItems(){
            this.cargando = true
            axios.get('/tipo_comprobante').then(response=>{
                this.arrayItems = response.data
                this.cargando = false
            })
        },
        listarDocumentos(){
            var urlItem = '/tipo_documento';
            axios.get(urlItem).then(response=>{
                this.arrayDocumentos = response.data;
            })
        },
        agregarTipoComprobante(){
            axios.post('/tipo_comprobante', this.objetoTipoComprobante).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalTipoComprobante')
            })
        },
        modificarTipoComprobante(id){
            axios.put(`/tipo_comprobante/${id}`, this.objetoTipoComprobante).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalTipoComprobante')
            })
        },
        resetModal(){
            this.objetoTipoComprobante.id_tipo_doc = null
            this.objetoTipoComprobante.nombre = null
            this.objetoTipoComprobante.descripcion = null
        },
        abrirModalRegistro(){
            this.modoEditable = false
            this.$bvModal.show('modalTipoComprobante')
        },
        abrirModalModificar(item){
            for(var i in this.arrayItems){
                if(this.arrayItems[i].id == item.id){
                    this.objetoTipoComprobante.descripcion = item.descripcion
                    this.objetoTipoComprobante.nombre = item.nombre
                    this.objetoTipoComprobante.id_tipo_doc = item.tipo_doc
                }
            }
            this.id = item.id
            this.modoEditable = true
            this.$bvModal.show('modalTipoComprobante')
        },
        recibirId(id){
            this.objetoSerie.id_tipo_comprobante = id
        },
        listarSeries(id){
            this.cargandoSeries = true
            axios.get(`/serie_comprobante/${id}`).then((response)=>{
                this.arraySeries = response.data
                this.cargandoSeries = false;
            })
        },
        nuevaSerieModal(){
            $('#agregarSeries').modal('show')
        },
        cerrarModalAserie(){
            $('#agregarSeries').modal('hide')
        },
        agregarSerie(){
            axios.post(`${this.rutaSerie}`, this.objetoSerie).then((response)=>{
                this.cerrarModalAserie();
                this.listarSeries(this.objetoSerie.id_tipo_comprobante);
            })
        },
        condicionSerie(item){
            let formDatos = new FormData();
            if(item.estado == 0){
                formDatos.append('estado', 1)
                formDatos.append("_method", "put");
                axios.post(`serie_comprobante/${item.id}`, formDatos).then((response)=>{
                    Vue.swal({
                        title: 'Serie Habilitada!',
                        text: 'La serie ha sido habilitada con éxito!',
                        icon: 'success'
                    });
                    this.listarSeries(this.objetoSerie.id_tipo_comprobante);
                    }).catch(error=>{
                        Vue.swal({
                            title: 'Error al habilitar!',
                            text: 'La serie no ha podido ser habilitada!',
                            icon: 'error'
                        });
                    })
            }else if(item.estado == 1){
                formDatos.append('estado', 0)
                formDatos.append("_method", "put");
                axios.post(`serie_comprobante/${item.id}`, formDatos).then((response)=>{
                    Vue.swal({
                        title: 'Serie deshabilitada!',
                        text: 'La serie ha sido deshabilitada con éxito!',
                        icon: 'success'
                    });
                    this.listarSeries(this.objetoSerie.id_tipo_comprobante);
                }).catch(error=>{
                    Vue.swal({
                        title: 'Error al deshabilitar!',
                        text: 'La serie no ha podido ser deshabilitada!',
                        icon: 'error'
                    });
                })
            }
            
            
        },
    },
    watch:{
        objetoSerie:{
            handler: function(){
                    this.objetoSerie.numero_actual = this.objetoSerie.numero_inicial-1
                },
            deep: true
        },
    }
}
</script>

<style scoped>
.tamaño{
    width: 100%;
    height: 100%;
}

</style>