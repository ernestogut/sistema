<template>

    <div class="tamaño">
        <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="5" :listarSeries="listarSeries" :idTabla="'myTable'" @emitirEvId="recibirId"></modulo>


        <div class="modal fade" tabindex="-1" role="dialog" id="modalSeries" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaSeries">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Series
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <div class="d-flex flex-nowrap justify-content-end">
                                    <button type="button" class="btn btn-primary boton" data-toggle="modal" @click="nuevaSerieModal()">
                                        Nuevo
                                    </button>
                                </div>
                                <datatable :arrayItems="arraySeries" :cabeceras="cabecerasSerie" :controlador="6"  :factura="true" :idTabla="'myTableSeries'" :funcionBotonTrash="condicionSerie"></datatable>
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
                                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
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
                                            <select v-model="objetoSerie.estado" class="form-control">
                                                <!--<option disabled value="">Escoje un almacén</option>-->
                                                <option value="1">Habilitado</option>
                                                <option value="0">Desabilitado</option>
                                            </select>
                                            <!--<input type="text" class="form-control" id="estado" placeholder="Another input" v-model="objetoSerie.estado">-->
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
    </div>
    
</template>
<script>

export default {
    mounted(){
        $(this.$refs.tablaSeries).on("hidden.bs.modal", this.limpiarTablaSeries) 
    },
    data(){
        return{
            titulo: 'Tipo de combrobantes',
            tituloModal: 'tipo de comprobante',
            ruta: '/tipo_comprobante',
            cabeceras: ['Acciones', '#', 'Nombre', 'Documento', 'Descripcion'],
            variables: [
                {
                    for: 'nombre',
                    type: 'nombre',
                    name: 'nombre',
                    id: 'nombre',
                    placeholder: 'Ingrese el nombre',
                    var: '',
                    titulo: 'Nombre'
                },
                {
                    for: 'descripcion',
                    type: 'descripcion',
                    name: 'descripcion',
                    id: 'descripcion',
                    placeholder: 'Ingrese la descripción',
                    var: '',
                    titulo: 'Descripción'
                }
                
            ],
            tituloSerie: 'Tipo de combrobantes',
            tituloModalSerie: 'tipo de comprobante',
            rutaSerie: '/serie_comprobante',
            cabecerasSerie: ['Acciones', '#', 'Serie',  'Numero inicial', 'Numero actual', 'Numero final', 'Año', 'Estado'],
            arraySeries: [],
            objetoSerie: 
            
            {
                id_tipo_comprobante: null,
                serie: '',
                numero_inicial: 1,
                numero_actual: null,
                numero_final: null,
                anio: '',
                estado: 1
            },
            //controladorTrash: 1 // 1 -> Deshabilitar, 2 -> Habilitar
        }

    },
    methods:{
        recibirId(id){
            this.objetoSerie.id_tipo_comprobante = id
        },
        limpiarTablaSeries(){
            $('#myTableSeries').DataTable().destroy();
        },
        miTabla(){
            $( function () {
                $('#myTableSeries').DataTable({
                    searching: true
                });
            } );
        },
        listarSeries(id){
            axios.get(`/serie_comprobante/${id}`).then((response)=>{
                this.arraySeries = response.data
                this.miTabla();
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
                $( function () {
                    $('#myTableSeries').DataTable().destroy();
                } );
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
                    $( function () {
                        $('#myTableSeries').DataTable().destroy();
                    } );
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
                    $( function () {
                        $('#myTableSeries').DataTable().destroy();
                    } );
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