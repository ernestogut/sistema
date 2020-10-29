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
              <b-button class="btn btn-info btn-sm" @click="exportExcel">
                Exportar XLSX
              </b-button>
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
          <b-button size="sm"  class="mr-1">
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
    </b-card>
  </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import XLSX from 'xlsx';
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
                        label: "Codigo",
                        field: "codigo_producto",
                    },
                    {
                        label: "Producto",
                        field: "descripcion_producto",
                    },
                    {
                        label: "Cantidad",
                        field: "cantidad",
                    }
                ],
      fields: [
        { key: "index", label: "Puesto", sortable: true, class: "text-center" },
        { key: "codigo_producto", label: "Código", sortable: true, class: "text-center" },
        { key: "descripcion_producto", label: "Producto", sortable: true, class: "text-center" },
        { key: "cantidad", label: "Cantidad", sortable: true, class: "text-center" },
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
      return this.visibleRows.reduce((accum, item) => {
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
    exportExcel: function () {
      let data = XLSX.utils.json_to_sheet(this.arrayReporte)
      const workbook = XLSX.utils.book_new()
      const filename = 'holi'
      XLSX.utils.book_append_sheet(workbook, data, filename)
      XLSX.writeFile(workbook, `${filename}.xlsx`)
      this.arrayReporte.splice(this.arrayReporte.length-4, 4);
    },
    obtenerResultadosFiltro() {
      this.cargando = true;
      if (!this.filtrarPorRango) {
        axios
          .get(
            `d_fact/${this.fecha_inicio_filtro}/${this.almacen_filtro}/rankingProductos`
          )
          .then(response => {
            this.arrayReporte = response.data;
            this.totalRows = this.arrayReporte.length;
            this.cargando = false;
          });
      } else {
        axios
          .get(
            `d_fact/${this.fecha_inicio_filtro}/${this.fecha_fin_filtro}/${this.almacen_filtro}/rankingProductosPorRango`
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