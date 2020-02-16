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
                                <datatable :arrayItems="arraySeries" :cabeceras="cabecerasSerie" :controlador="6"  :factura="true" :idTabla="'myTableSeries'"></datatable>
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
                                    <div class="form-group">
                                        <label for="serie">Serie</label>
                                        <input type="text" class="form-control" id="serie" placeholder="Example input" v-model="objetoSerie.serie">
                                    </div>
                                    <div class="form-group">
                                        <label for="numero_inicial">Numero inicial</label>
                                        <input type="text" class="form-control" id="numero_inicial" placeholder="Another input" v-model="objetoSerie.numeroInicial">
                                    </div>
                                    <div class="form-group">
                                        <label for="numero_final">Numero final</label>
                                        <input type="text" class="form-control" id="numero_final" placeholder="Another input" v-model="objetoSerie.numeroFinal">
                                    </div>
                                    <div class="form-group">
                                        <label for="año">Año</label>
                                        <input type="text" class="form-control" id="año" placeholder="Another input" v-model="objetoSerie.año">
                                    </div>
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <input type="text" class="form-control" id="estado" placeholder="Another input" v-model="objetoSerie.estado">
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
            cabeceras: ['#', 'Nombre', 'Descripcion', 'Acciones'],
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
            cabecerasSerie: ['#', 'Serie',  'Numero inicial', 'Numero final', 'Año', 'Estado', 'Acciones'],
            arraySeries: [],
            objetoSerie: 
            
            {
                id_comprobante: null,
                serie: '',
                numeroInicial: 1,
                numeroFinal: null,
                año: '',
                estado: 1
            },
            
        }
    },
    methods:{
        recibirId(id){
            this.objetoSerie.id_comprobante = id
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
            let formDatos = new FormData();
            formDatos.append('id_tipo_comprobante', this.objetoSerie.id_comprobante)
            formDatos.append('serie', this.objetoSerie.serie)
            formDatos.append('numero_inicial', this.objetoSerie.numeroInicial)
            formDatos.append('numero_final', this.objetoSerie.numeroFinal)
            formDatos.append('anio', this.objetoSerie.año)
            formDatos.append('estado', this.objetoSerie.estado)
            axios.post(`${this.rutaSerie}`, formDatos).then((response)=>{
                this.cerrarModalAserie();
                $( function () {
                    $('#myTableSeries').DataTable().destroy();
                } );
                this.listarSeries(this.objetoSerie.id_comprobante);
            })
        }
    }
}
</script>

<style scoped>
.tamaño{
    width: 100%;
    height: 100%;
}

</style>