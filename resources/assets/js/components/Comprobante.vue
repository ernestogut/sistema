<template>
    <div class="tamaño">
        <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="true" :controlador="3" :listarSeries="listarSeries" :idTabla="'myTable'">

        </modulo>
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
                                <datatable :arrayItems="arraySeries" :cabeceras="cabecerasSerie" :controlador="4"  :factura="true" :idTabla="'myTableSeries'"></datatable>
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
            titulo: 'Comprobantes',
            tituloModal: 'comprobante',
            ruta: '/comprobante',
            cabeceras: ['#', 'Tipo de comprobante', 'Serie', 'Número', 'Año', 'Acciones'],
            cabecerasSerie: ['#', 'Serie', 'Numero', 'Año', 'Acciones'],
            arraySeries: [],
            variables: [
                {
                    for: 'serie',
                    type: 'serie',
                    name: 'serie',
                    id: 'serie',
                    placeholder: 'Ingrese la serie',
                    var: '',
                    titulo: 'Serie'
                },
                {
                    for: 'numero',
                    type: 'numero',
                    name: 'numero',
                    id: 'numero',
                    placeholder: 'Ingrese el número',
                    var: '',
                    titulo: 'Número'
                },
                {
                    for: 'año',
                    type: 'año',
                    name: 'año',
                    id: 'año',
                    placeholder: 'Ingrese el año',
                    var: '',
                    titulo: 'Año'
                }
            ],
        }
    },
    methods:{
        limpiarTablaSeries(){
            $('#myTableSeries').DataTable().destroy();
        },
        listarSeries(id){
            axios.get(`/comprobante/${id}`).then((response)=>{
                this.arraySeries = response.data
                this.miTabla();
            })
            $('#modalSeries').modal('show')
        },
        miTabla(){
            $( function () {
                $('#myTableSeries').DataTable({
                    searching: true
                });
            } );
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
