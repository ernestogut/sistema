<template>
    <main class="main">
        <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4>Reportes de cierre de caja</h4>
                    </div>
                </div>
            </div>
        <div class="card-body">
            <b-card no-body class="mb-1">
        <b-card-header header-tag="header" class="p-1" role="tab">
          <b-button block v-b-toggle.accordion-1 variant="info">Filtros</b-button>
        </b-card-header>
        <b-collapse id="accordion-1" visible accordion="my-accordion" role="tabpanel">
          <b-card-body>
            <form @submit.prevent="obtenerResultadosFiltro()">
              <div class="form-group row">
                <label class="col-sm-1 col-form-label">Fecha {{filtrarPorRango?'inicio':''}}</label>
                <div class="col-sm-5 d-flex flex-row">
                  <date-picker
                    v-model="fecha_inicio_filtro"
                    value-type="format"
                    format="YYYY-MM-DD"
                    :default-value="new Date()"
                    :disabled-date="disabledBeforeTodayAndAfterAWeek"
                  ></date-picker>
                  <div class="form-check ml-3">
                    <input class="form-check-input" type="checkbox" v-model="filtrarPorRango" />
                    <label class="form-check-label">Rango de fechas</label>
                  </div>
                </div>

                <label class="col-sm-1 col-form-label">Almacén</label>
                <div class="col-sm-5">
                  <select v-model="almacen_filtro" class="form-control">
                    <option :value="0">Escoje un almacén</option>
                    <option
                      v-for="almacen in arrayAlmacenFijo"
                      :key="almacen.id"
                      :value="almacen.id"
                    >{{almacen.descripcion}}</option>
                  </select>
                </div>
                <template v-if="filtrarPorRango">
                  <label class="col-sm-1 col-form-label">Fecha fin</label>
                  <div class="col-sm-5">
                    <date-picker
                      v-model="fecha_fin_filtro"
                      value-type="format"
                      format="YYYY-MM-DD"
                      :default-value="new Date()"
                      :disabled-date="disabledBeforeTodayAndAfterAWeek"
                    ></date-picker>
                  </div>
                </template>
              </div>
              <button class="btn btn-success btn-sm" type="submit">Filtrar</button>
            </form>
          </b-card-body>
        </b-collapse>
      </b-card>

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
                    <h4 class="title"><i class="fa fa-users"></i>  Reportes</h4>
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
                    <b-button  variant="primary" @click="abrirModalMovimientos()"><i class="fa fa-bar-chart" style="font-size:36px"></i></b-button>
                    
                </div>

                </div>
                <!--/.Content-->

            </div>
        </div>
        <div class="modal fade" id="modalCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
        <div class="modal fade" id="modalMovimientos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Movimientos de caja</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <!--Body-->
                <div class="modal-body">
<b-card>
                <b-table striped hover :items="arrayMovimientos" :fields="fieldsA"></b-table>
            </b-card>

                </div>
                <!--Footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
        <!--<b-modal tabindex="-1" id="modal-xl" size="xl" title="Movimientos de caja">
            <b-card>
                <b-table striped hover :items="arrayMovimientos" :fields="fieldsA"></b-table>
            </b-card>
        </b-modal>-->
    </main>
</template>

<script>
    import DatePicker from "vue2-datepicker";
    import VueExcelXlsx from "vue-excel-xlsx";
    import "vue2-datepicker/index.css";
    import "vue2-datepicker/locale/es";
export default {
    data(){
        return{
            fieldsA: [
            {
                key: 'responsable',
                label: 'Responsable',
                sortable: true,
                class: "text-center"
            },
            {
                key: 'tienda',
                label: 'Tienda',
                sortable: false,
                class: "text-center"
            },
            {
                key: 'tipo_movimiento',
                label: 'Tipo de movimiento',
                sortable: true,
                class: "text-center"
            },
            {
                key: 'monto',
                label: 'Monto',
                sortable: true,
                class: "text-center"
            },
            {
                key: 'observacion',
                label: 'Observación',
                sortable: true,
                class: "text-center"
            },
            {
                key: 'fecha',
                label: 'Fecha',
                sortable: true,
                class: "text-center"
            }
            ],
            arrayCierres: [],
            arrayMovimientos: [],
            fecha_inicio_filtro: new Date(),
            fecha_fin_filtro: new Date(),
            almacen_filtro: 0,
            filtrarPorRango: false,
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
            "date-picker": DatePicker
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
        },
        arrayAlmacenFijo() {
            return this.$store.getters.arrayAlmacen;
        }
    },
    methods:{
        disabledBeforeTodayAndAfterAWeek(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            return date > today;
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
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
                    text: `No se han registrado ventas con ${this.tipoPagoElegido}`,
                    icon: 'error'
                })
            })
        },
        abrirModalMovimientos(){
            axios.get(`/movimiento_caja/${this.cajaSeleccionada.id_almacen}/${this.cajaSeleccionada.fecha}/reporteDeMovimientos`).then(response=>{
                $('#modalMovimientos').modal('show');
                this.arrayMovimientos = response.data
            })
        },
        obtenerResultadosFiltro() {
            this.cargando = true;
            if (!this.filtrarPorRango) {
                axios
                .get(
                    `reporte_cierre/${this.fecha_inicio_filtro}/${this.almacen_filtro}/obtenerCierres`
                )
                .then(response => {
                    this.arrayCierres = response.data;
                    this.totalRows = this.arrayCierres.length;
                    this.cargando = false;
                });
            } else {
                axios
                .get(
                    `reporte_cierre/${this.fecha_inicio_filtro}/${this.fecha_fin_filtro}/${this.almacen_filtro}/obtenerCierresPorRango`
                )
                .then(response => {
                    this.arrayCierres = response.data;
                    this.totalRows = this.arrayCierres.length;
                    this.cargando = false;
                });
            }
        }   
    },
    watch: {
        filtrarPorRango() {
        (this.fecha_inicio_filtro = new Date()),
            (this.fecha_fin_filtro = new Date()),
            (this.almacen_filtro = 0);
        }
    }

}
</script>

<style>