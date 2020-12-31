<template>
    <main class="main">
        <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4>Cierre de caja</h4>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <b-row>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Ordenar"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            label-for="sortBySelect"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                <template v-slot:first>
                  <option value>-- ninguno --</option>
                </template>
              </b-form-select>
              <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                <option :value="false">Asc</option>
                <option :value="true">Desc</option>
              </b-form-select>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Buscar"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            label-for="filterInput"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                v-model="filter"
                type="search"
                id="filterInput"
                placeholder="Busca algo"
              ></b-form-input>
              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col sm="5" md="6" class="my-1">
          <b-form-group
            label="Por pagina"
            label-cols-sm="6"
            label-cols-md="4"
            label-cols-lg="3"
            label-align-sm="right"
            label-size="sm"
            label-for="perPageSelect"
            class="mb-0"
          >
            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                </b-form-group>
                </b-col>

                <b-col sm="7" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
                </b-col>
            </b-row>

            <!-- Tabla principal -->
            <b-table
                show-empty
                small
                stacked="md"
                :busy="cargando"
                :items="arrayCierres"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :filterIncludedFields="filterOn"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                @filtered="onFiltered"
                :emptyText="'No hay elementos para mostrar'"
                :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
            >
                <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>

                <template v-slot:cell(actions)="row">
                <b-button size="sm"  v-if="row.item.estado == 'abierto'" @click="cerrarCaja(row.item)" class="mr-1">
                    cerrar caja
                </b-button>
                <b-button size="sm" @click="abrirModalMetodosPago(row.item)" class="mr-1">
                    <i class="icon-eye"></i>
                </b-button>
                </template>
                <template v-slot:table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                </div>
                </template>
            </b-table>
        </div>
        <div class="modal fade" id="modalSocial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog cascading-modal modal-dialog-centered" role="document">
                <!--Content-->
                <div class="modal-content">
                <!--Header-->
                <div class="modal-header light-blue darken-3 white-text">
                    <h4 class="title"><i class="fa fa-users"></i>  Total ventas categorizadas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                </div>
                <!--Body-->
                <div class="modal-body mb-0 text-center">
                    <!--Pagos Efectivo-->
                    <a type="button" class="btn btn-primary" style="border-radius: 10px" @click="tipoPagoElegido = 'efectivo', abrirModalLista()"><i class="fa fa-money" style="font-size:36px"></i></a>
                    <!--Pagos Visa-->
                    <a type="button" class="btn btn-success" style="border-radius: 10px" @click="tipoPagoElegido = 'tarjeta', abrirModalLista()"><i class="fa fa-cc-visa" style="font-size:36px"></i></a>
                    <!--Pagos Cheque-->
                    <a type="button" class="btn btn-info" style="border-radius: 10px" @click="tipoPagoElegido = 'cheque', abrirModalLista()"><i class="fa fa-list-alt" style="font-size:36px"></i></a>
                    
                </div>

                </div>
                <!--/.Content-->

            </div>
        </div>
        <div class="modal fade" id="modalCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Ventas con {{tipoPagoElegido}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <!--Body-->
                <div class="modal-body">

                    <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">#</th>
                            <th class="text-center align-middle">Producto</th>
                            <th class="text-center align-middle">Cantidad</th>
                            <th class="text-center align-middle">Precio</th>
                            <th class="text-center align-middle">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(producto, index) in arrayProductosVenta" :key="producto.id">
                            <th scope="row" class="text-center align-middle">{{index+1}}</th>
                            <td class="text-center align-middle">{{producto.descripcion_producto}}</td>
                            <td class="text-center align-middle">{{producto.cantidad_producto}}</td>
                            <td class="text-center align-middle">S/ {{producto.precio_producto}}</td>
                            <td class="text-center align-middle">S/ {{producto.total_producto}}</td>
                        </tr>
                        <tr class="total">
                            <th scope="row"></th>
                            <td></td>
                            <td></td>
                            <td class="text-center align-middle">Total</td>
                            <td class="text-center align-middle">S/ {{totalItem}}</td>
                        </tr>
                    </tbody>
                    </table>

                </div>
                <!--Footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
        <loading :active.sync="show" 
            :can-cancel="false" 
            
            :is-full-page="true"></loading>
    </main>
</template>

<script>
// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    mounted(){
        this.obtenerCierres()
        //this.obtenerCierres();
    },
    data(){
        return{
            initiated: false,
            loading: false,
            arrayCierres: [],
            almacen: '',
            tipoPagoElegido: 'efectivo', // 1 -> Efectivo, 2 -> VISA, 3 -> Cheque,
            cajaSeleccionada: null,
            arrayProductosVenta: [],
            totalRows: 1,
            currentPage: 1,
            show: false,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            cargando: false,
            fields: [
                { key: "id", label: "Num doc", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "responsable", label: "Responsable", sortable: true, class: "text-center"},
                { key: "fecha", label: "Fecha", sortable: true, class: "text-center"},
                { key: "saldo_inicial", label: "Saldo inicial", sortable: true, class: "text-center"},
                { key: "ventas_diarias", label: "Ventas diarias", sortable: true, class: "text-center"},
                { key: "ingresos", label: "Ingresos", sortable: true, class: "text-center"},
                { key: "egresos", label: "Egresos", sortable: true, class: "text-center"},
                { key: "saldo_final", label: "Saldo final", sortable: true, class: "text-center"},
                { key: "estado", label: "Estado", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones", class: "text-center"}
            ]
        }
    },
    components: {
            Loading
        },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        totalItem: function(){
            let sum = 0;
            for(let i = 0; i < this.arrayProductosVenta.length; i++){
                sum += parseFloat(this.arrayProductosVenta[i].total_producto);
            }
            return sum;
        },
        sortOptions() {
      // Create an options list from our fields
        return this.fields
            .filter(f => f.sortable)
            .map(f => {
            return { text: f.label, value: f.key };
            });
        }
    },
    methods:{
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        obtenerCierres(){
            this.cargando = true
            axios.get(`cierre_caja/${this.usuarioLogeado.id_almacen}`).then((response)=>{
                this.arrayCierres  = response.data
                this.totalRows = this.arrayCierres.length
                this.cargando = false;
            })
        },
        cerrarCaja(item){
            let formDatos = new FormData();
            formDatos.append('estado', 'cerrado')
            formDatos.append('id_almacen', 0);
            formDatos.append("_method", "put");
            Vue.swal({
                title: 'Estas seguro de cerrar caja?',
                text: "No podrás volver a activarla!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cerrar caja!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {
                    this.show = true
                    axios.get(`cierre_caja/${item.id}/consultarCajaSeleccionada`).then(respuesta=>{
                        if(this.usuarioLogeado.id == respuesta.data.id_usuario){
                            axios.post(`cierre_caja/${item.id}/cerrarCaja`, formDatos).then(async (response)=>{
                                this.show = false
                                Vue.swal({
                                    title: 'Cierre de caja exitoso!',
                                    text: 'El cierre de caja ha sido procesado con éxito!',
                                    icon: 'success'
                                })
                                await this.$store.dispatch('cargarUsuarioLogeado').then(()=>{
                                     this.obtenerCierres()
                                })
                            }).catch(error=>{
                                this.show = false
                                Vue.swal({
                                    title: 'Cierre de caja fallido!',
                                    text: 'No ha podido procesarse el cierre de caja!',
                                    icon: 'error'
                                });
                            })
                        }else{
                            Vue.swal({
                                title: 'Cierre de caja fallido!',
                                text: 'No eres el usuario responsable de esta caja!',
                                icon: 'error'
                            });
                        }
                    })
                    
                }
            })
        },
        abrirModalMetodosPago(caja){
            $('#modalSocial').modal('show');
            this.cajaSeleccionada = caja
        },
        abrirModalLista(){
            axios.get(`/c_fact/${this.tipoPagoElegido}/${this.cajaSeleccionada.fecha}/${this.cajaSeleccionada.id_almacen}/mostrarVentasTipoPago`).then(response=>{
                this.arrayProductosVenta = response.data
                $('#modalCart').modal('show');
            }).catch(error=>{
                Vue.swal({
                    title: `No hay ventas con ${this.tipoPagoElegido}!`,
                    text: `Por el momento no se han registrado ventas con ${this.tipoPagoElegido}`,
                    icon: 'error'
                })
            })
        }
                    
    }

}
</script>

<style>


.badge{
    cursor: pointer;
}

</style>