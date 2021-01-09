<template>
  <div class="main">
   
        <b-card >
      <template v-slot:header>
          <div class="d-flex justify-content-between align-items-center p-0">
              <p>Tipo de pagos para delivery</p>
              <b-button size="sm" variant="primary" @click="abrirModalRegistrar()">
                  Nuevo tipo de pago
              </b-button>
          </div>
        
        </template>
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
        :items="arrayTiPoDePagos"
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
          <b-button size="sm" @click="modalEditar(row.item)" class="mr-1">
            <i class="icon-pencil"></i>
          </b-button>
          <b-button size="sm" >
            <i class="icon-trash" @click="eliminarTipoDePago(row.item, row.index)"></i>
          </b-button>
        </template>
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>
      </b-table>      
      <b-modal size="lg" hide-footer v-model="estadoModal" id="modal-1" :title="modoEditable?'Modificar usuario':'Nuevo usuario'" @hidden="resetModal">
       <form
          id="formA"
          action
          @submit.prevent="modoEditable?editarTipoDePago(id):agregarTipoDePago()"
          enctype="multipart/form-data"
        >
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="usuario">Nombre</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="objetoTipoDePago.nombre"
                  placeholder="Ingrese el nombre del tipo de pago"
                />
              </div>
              <div class="form-group col-md-6">
                <label for="nombre">Descripción</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="objetoTipoDePago.descripcion"
                  placeholder="Ingrese la descripción del tipo de pago"
                />
              </div>
              <div class="form-group col-md-6">
                <label for="apellido">Metodo</label>
                <select class="form-control" v-model="objetoTipoDePago.metodo">
                    <option value="">Elige un método para el tipo de pago</option>
                  <option value="efectivo">Efectivo</option>
                  <option value="transferencia">Transferencia</option>
                </select>
              </div>
            </div>
            <b-button size="sm" @click="estadoModal = false">Cerrar</b-button>
            <b-button size="sm"  v-show="!modoEditable" type="submit" variant="primary">Guardar</b-button>
            <b-button size="sm"  v-show="modoEditable" type="submit" variant=primary>Actualizar</b-button>
            </form>
          </b-modal> 
        </b-card>
      <!-- User Interface controls -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      cargando: false,
      fields: [
        { key: "index", label: "#", sortable: true, sortDirection: "desc" , class: "text-center"},
        {
          key: "nombre",
          label: "Nombre",
          sortable: true, 
          class: "text-center"
        },
        {
          key: "descripcion",
          label: "Descripción",
          sortable: true, 
          class: "text-center"
        },
        {
          key: "metodo",
          label: "Método",
          sortable: true, 
          class: "text-center"
        },
        { key: "actions", label: "acciones" , class: "text-center"}
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
      objetoTipoDePago: {
        nombre: null,
        descripcion: null,
        metodo: null
      },
      arrayRoles: [],
      arrayTiPoDePagos: [],
      modoEditable: false,
      id: "",
      estadoModal: false
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
  mounted(){
      this.obtenerTipoDePagos();
  },
  methods: {
    resetModal() {
        this.objetoTipoDePago.nombre = null;
        this.objetoTipoDePago.descripcion = null;
        this.objetoTipoDePago.metodo = null;
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    obtenerTipoDePagos() {
      this.cargando = true;
      axios.get("/tipo_pago").then(response => {
        this.arrayTiPoDePagos = response.data;
        this.totalRows = this.arrayTiPoDePagos.length;
        this.cargando = false;
      });
    },
    abrirModalRegistrar(){
            this.objetoTipoDePago.nombre = null
            this.objetoTipoDePago.descripcion = null
            this.objetoTipoDePago.metodo = 'efectivo'
            this.modoEditable = false;
            this.estadoModal = true;
        },  
    modalEditar(item) {
      axios.get(`/tipo_pago/${item.id}`).then(response => {
        this.objetoTipoDePago.nombre = response.data.nombre;
        this.objetoTipoDePago.descripcion = response.data.descripcion;
        this.objetoTipoDePago.metodo = response.data.metodo;
      });
      this.modoEditable = true;
      this.id = item.id;
      this.estadoModal = true
    },
    editarTipoDePago(tipoDePago) {
      axios.put(`/tipo_pago/${tipoDePago}`, this.objetoTipoDePago).then(response => {
        this.obtenerTipoDePagos();
        this.estadoModal = false;
      });
    },
    agregarTipoDePago(){
            axios.post('/tipo_pago', this.objetoTipoDePago).then((response)=>{
                this.obtenerTipoDePagos();
                this.estadoModal = false;
            })
        },
    eliminarTipoDePago(tipoDePago, index){
        Vue.swal({
                title: 'Eliminar tipo de pago',
                text: `Está seguro de eliminar el tipo de pago ${tipoDePago.nombre}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.value) {
                    axios.delete(`/tipo_pago/${tipoDePago.id}`)
                    .then(()=>{
                        this.obtenerTipoDePagos();
                    })
                }
            })
        }
  }
};
</script>