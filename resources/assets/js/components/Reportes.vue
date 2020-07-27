<template>
  <div class="main">
    <b-card>
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
              <vue-excel-xlsx
                :data="arrayReporte"
                :columns="columns"
                :filename="'filename'"
                :sheetname="'sheetname'"
                class="btn btn-info btn-sm">
                Exportar XLSX
            </vue-excel-xlsx>
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
        :items="arrayReporte"
        :fields="fields"
        :current-page="currentPage"
        :per-page="perPage"
        :filter="filter"
        :filterIncludedFields="filterOn"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :sort-direction="sortDirection"
        @filtered="onFiltered"
        v-model="visibleRows"
        :emptyText="'No hay elementos para mostrar'"
        :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
      >
        <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>

        <template v-slot:cell(actions)="row">
          <b-button size="sm" @click="verFactura(row.item)" class="mr-1">
            <i class="icon-eye"></i>
          </b-button>
        </template>
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>
        <template slot="bottom-row">
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td class="text-center align-middle">Total</td>

          <!-- this is a computed prop that adds up all the expenses in the visible rows -->
          <td class="text-center align-middle">{{ ventasTotal.toFixed(2) }}</td>
        </template>
      </b-table>
      <div
        class="modal fade"
        tabindex="-1"
        role="dialog"
        id="modalInformacionFact"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
        ref="modalInformacionFact"
      >
        <div class="modal-dialog modal-xl">
          <div class="card-body">
            <div class="modal-content">
              <div class="modal-header">
                Detalle factura
                <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="moda-body">
                <div class="card">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center align-middle">#</th>
                          <th scope="col" class="text-center align-middle">Codigo</th>
                          <th scope="col" class="text-center align-middle">Producto</th>
                          <th scope="col" class="text-center align-middle">Almacen</th>
                          <th scope="col" class="text-center align-middle">Cantidad</th>
                          <th scope="col" class="text-center align-middle">Precio</th>
                          <th scope="col" class="text-center align-middle">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(detalle, index) in arrayFacturaDetalle" :key="detalle.id">
                          <th scope="row" class="text-center align-middle">{{index+1}}</th>
                          <td class="text-center align-middle">{{detalle.codigo_producto}}</td>
                          <td class="text-center align-middle">{{detalle.descripcion_producto}}</td>
                          <td class="text-center align-middle">{{detalle.almacen}}</td>
                          <td class="text-center align-middle">{{detalle.cantidad_producto}}</td>
                          <td class="text-center align-middle">S/ {{detalle.precio_producto}}</td>
                          <td class="text-center align-middle">S/ {{detalle.total_producto}}</td>
                        </tr>
                        <tr class="total">
                          <th scope="row"></th>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td class="text-center align-middle">Total</td>
                          <td class="text-center align-middle">S/ {{totalItem}}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-card>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import VueExcelXlsx from "vue-excel-xlsx";
import "vue2-datepicker/index.css";
import "vue2-datepicker/locale/es";
export default {
  components: {
    "date-picker": DatePicker
  },
  data() {
    return {
      fecha_inicio_filtro: new Date(),
      fecha_fin_filtro: new Date(),
      almacen_filtro: 0,
      filtrarPorRango: false,
      arrayReporte: [],
      arrayFacturaDetalle: [],
      cargando: false,
      columns : [
                    {
                        label: "Fecha",
                        field: "fecha",
                    },
                    {
                        label: "Folio",
                        field: "folio",
                    },
                    {
                        label: "Numero de documento",
                        field: "num_doc",
                    },
                    {
                        label: "Serie",
                        field: "serie",
                    },
                    {
                        label: "Total",
                        field: "total",
                    },
                    {
                        label: "Usuario",
                        field: "usuario",
                    },
                    {
                        label: 'Tipo de pago',
                        field: 'tipo_pago'
                    }
                ],
      fields: [
        {
          key: "num_doc",
          label: "Num doc",
          sortable: true,
          sortDirection: "desc",
          class: "text-center"
        },
        {
          key: "usuario",
          label: "Vendedor",
          sortable: true,
          class: "text-center",
          class: "text-center"
        },
        { key: "serie", label: "Serie", sortable: true, class: "text-center" },
        { key: "folio", label: "Folio", sortable: true, class: "text-center" },
        { key: "fecha", label: "Fecha", sortable: true, class: "text-center" },
        { key: "tipo_pago", label: "Tipo de pago", sortable: true, class: "text-center" },
        { key: "total", label: "Total", sortable: true, class: "text-center" },
        { key: "actions", label: "acciones", class: "text-center" }
      ],
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15],
      sortBy: "",
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      filterOn: [],
      infoModal: {
        id: "info-modal",
        title: "",
        content: ""
      },
      visibleRows: []
    };
  },
  computed: {
    totalItem: function() {
      let sum = 0;
      for (let i = 0; i < this.arrayFacturaDetalle.length; i++) {
        sum += parseFloat(this.arrayFacturaDetalle[i].total_producto);
      }
      return sum.toFixed(2);
    },
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key };
        });
    },

    ventasTotal() {
      return this.arrayReporte.reduce((accum, item) => {
        // Assuming expenses is the field you want to total up
        return accum + parseFloat(item.total);
      }, 0.0);
    },
    arrayAlmacenFijo() {
      return this.$store.getters.arrayAlmacen;
    }
  },
  methods: {
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
    verFactura(item) {
      axios.get(`d_fact/${item.num_doc}`).then(response => {
        this.arrayFacturaDetalle = response.data;
        $("#modalInformacionFact").modal("show");
      });
    },
    obtenerResultadosFiltro() {
      this.cargando = true;
      if (!this.filtrarPorRango) {
        axios
          .get(
            `reporte/${this.fecha_inicio_filtro}/${this.almacen_filtro}/ventasPorFecha`
          )
          .then(response => {
            this.arrayReporte = response.data;
            this.totalRows = this.arrayReporte.length;
            this.cargando = false;
          });
      } else {
        axios
          .get(
            `reporte/${this.fecha_inicio_filtro}/${this.fecha_fin_filtro}/${this.almacen_filtro}/ventasPorFechaRango`
          )
          .then(response => {
            this.arrayReporte = response.data;
            this.totalRows = this.arrayReporte.length;
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
};
</script>

<style>
</style>