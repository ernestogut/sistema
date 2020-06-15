<template>
  <b-card>
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
            <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Busca algo"></b-form-input>
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
      :items="items"
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
        <div v-if="!factura">
          <b-button
            variant="primary"
            size="sm"
            @click="(controlador!=5)?funcionConjunto(row.item):funcionBoton(row.item)"
          >
            <i class="icon-pencil"></i>
          </b-button>
          <b-button
            variant="primary"
            size="sm"
            @click="funcionConjunto(row.item)"
            v-if="controlador == 5"
          >
            <i class="icon-eye"></i>
          </b-button>
          <b-button
            variant="danger"
            size="sm"
            v-if="controlador != 5"
            @click="eliminarItem(row.item, row.index)"
          >
            <i class="icon-trash"></i>
          </b-button>
        </div>
        <div v-else>
          <b-button variant="primary" size="sm" @click="funcionConjunto(row.item)">
            <i class="icon-eye"></i>
          </b-button>
          <b-button variant="danger" size="sm" v-if="controlador!=6" @click="eliminarItem(row.item, row.index)">
            <i class="icon-trash"></i>
          </b-button>
          <b-button
            variant="danger"
            size="sm"
            v-if="controlador==6"
            @click="funcionConjuntoTrash(row.item)"
          >
            <i :class="(row.item.estado==0)?'icon-heart':'icon-trash'"></i>
          </b-button>
        </div>
      </template>
      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Cargando...</strong>
        </div>
      </template>
    </b-table>

    <!-- User Interface controls -->
  </b-card>
</template>

<script>
export default {
  props: {
    cargando: Boolean,
    fields: Array,
    items: Array,
    controlador: Number,
    factura: Boolean,
    funcionBoton: Function,
    funcionBotonTrash: Function,
    eliminarItem: Function,
    listarSeries: Function
  },
  mounted(){
    this.totalRows = this.items.length;
  },
  data() {
    return {
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [5, 10, 15],
      sortBy: "",
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      filterOn: []
    };
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key };
        });
    }
  },
  methods: {
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    emitirID_tipoComprobante(id) {
      this.$emit("emitirEvId", id);
    },
    cerrarModalProductos() {
      $("#modalProducto").modal("hide");
    },
    funcionConjunto(item) {
      // controlador = 0 -> editar, 1 -> agregar productos, 2 -> buscar productos, 3 -> listar series, 4 ->
      if (this.controlador == 0) {
        this.funcionBoton(item);
      } else if (this.controlador == 1) {
        this.agregarProducto(item);
      } else if (this.controlador == 2) {
        this.funcionBoton(item.codigo);
      } else if (this.controlador == 3) {
        this.funcionBoton(item.id);
      } else if (this.controlador == 5) {
        this.emitirID_tipoComprobante(item.id);
        this.listarSeries(item.id);
        $("#modalSeries").modal("show");
      } else if (this.factura) {
        this.funcionBoton(item);
      }
      /*else if(this.controlador == 4){
                this.funcionBoton(item)
            }*/
    },
    funcionConjuntoTrash(item) {
      this.funcionBotonTrash(item);
    }
  }
};
</script>