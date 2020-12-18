<template>
<main class="main">
    <b-container>
        <b-card header="Productos con precio por mayor">
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
        :items="arrayProductos"
        :fields="fields"
        :tbody-tr-class="rowClass"
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

        <template v-slot:cell(actions)="row">
          <!--<b-button size="sm" @click="abrirModalCantidades(row.item)" class="mr-1">
            <i class="icon-pencil"></i>
          </b-button>-->
          <b-button size="sm" class="mr-1" @click="abrirModalInsertarPrecioPorMayor(row.item)">
            <i class="icon-eye"></i>
          </b-button>
          <b-button
            variant="info"
            size="sm"
            @click="abrirModalImagen(row.item)"
          >
            <i class="icon-picture"></i>
          </b-button>
        </template>
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>
      </b-table> 
        <div>
            <i class="fa fa-square-o text-danger bg-danger mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Menos de 5 unidades</span>
            <i class="fa fa-square-o mr-2" style="width: 15px; height: 15px;"></i><span>5 unidades o más</span>
        </div>     
        </b-card>
        <div class="modal fade bd-example-modal-lg show" tabindex="-1" id="modalImagen" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <spinner v-if="loadingImagen"></spinner>
                    <div class="modal-body d-flex flex-wrap justify-content-center align-items-center" id="dynamic-content" v-for="productoImagen in objetoProductoImagen" :key="productoImagen.key" v-else-if="initiatedImagen">
                        <img :src="productoImagen.imagen" style="width: 18rem;" class="img-fluid" alt=""/>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalInsertarPrecioPorMayor">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Insertar precio al por mayor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" @submit.prevent="insertarPrecioPorMayor" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Cantidad</label>
                                <input type="number" class="form-control" id="formGroupExampleInput" placeholder="Ingresa el precio al por mayor" v-model="objetoProdAlmacen.precioPorMayor">
                            </div>
                        </div>
                        <div class="modal-footer">
                            
                            <spinner v-if="loading"></spinner>
                            <template v-else>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </template>
                            
                        </div>      
                    </form>  
                </div>
            </div>
        </div>
      <!-- User Interface controls -->
        <loading :active.sync="show" 
            :can-cancel="false" 
            
            :is-full-page="true"></loading>
    </b-container>
</main>
  
</template>

<script>
// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    import XLSX from 'xlsx';
export default {
    mounted(){
        this.listarItem();
        this.$store.dispatch('cargarAlmacen')
    },
    data(){
        return{
            cargando: false,
            fields: [
                {
                key: "codigo",
                label: "Codigo",
                sortable: true,
                class: "text-center"
                },
                {
                key: "producto",
                label: "Producto",
                sortable: true,
                class: "text-center"
                },
                {
                key: "precio_por_mayor",
                label: "Precio por mayor",
                sortable: true,
                class: "text-center"
                },
                {
                key: "precio",
                label: "Precio por menor",
                sortable: true,
                class: "text-center"
                },
                { key: "actions", label: "acciones", class: "text-center" }
            ],
            loadingImagen: false,
            objetoProductoImagen: {},
            initiatedImagen: false,
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            loading: false,
            initiated: false,
            loadingProductos: false,
            initiatedProductos: false,
            loadingCantidades: false,
            initiatedCantidades: false,
            arrayAlmacen: [],
            arrayVariaciones: [],
            productoVariacion: '',
            objetoProdAlmacen: {
                id_producto: null,
                precioPorMayor: null
            },
            arrayProductos: [],
            idAlmacen: '',
            enAlmacen: false,
            modoEditable: false,
        }
    },
    components: {
            Loading
        },
    computed:{
        sortOptions() {
        // Create an options list from our fields
        return this.fields
            .filter(f => f.sortable)
            .map(f => {
            return { text: f.label, value: f.key };
            });
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen;
        },
        show(){
            return this.$store.getters.show;
        }
    },
    methods:{
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (!item.precio_por_mayor) return 'bg-danger'
        },
        async listarItem(){
            this.cargando = true
            axios.get('speed/obtenerProductosConPrecioPorMayor').then(response=>{
                this.arrayProductos = response.data;
                this.totalRows = this.arrayProductos.length
                this.cargando = false;      
            })
        },
        exportExcel: function () {
          var arrayPreciosPorMayor = []
          var arrayPreciosPorMayor = this.arrayProductos.filter(function (producto) {
              return producto.precio_por_mayor != null;
          });
          let data = XLSX.utils.json_to_sheet(arrayPreciosPorMayor)
          const workbook = XLSX.utils.book_new()
          const filename = 'precios_por_mayor'
          XLSX.utils.book_append_sheet(workbook, data, filename)
          XLSX.writeFile(workbook, `${filename}.xlsx`)
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        abrirModalImagen(producto){
            //var imagenProducto = {}
            this.loadingImagen = true
            $('#modalImagen').modal('show');
            axios.get(`speed/${producto.codigo}`).then(response=>{
                this.objetoProductoImagen = response.data
                this.loadingImagen = false
                this.initiatedImagen = true;
                //this.$emit('emitirImagen', imagenProducto);
            })
        },
        abrirModalInsertarPrecioPorMayor(producto){
            this.objetoProdAlmacen.id_producto = producto.codigo;
            $('#modalInsertarPrecioPorMayor').modal('show');
        },
        insertarPrecioPorMayor(){
            this.loading = true
            axios.post('speed/insertarPrecioPorMayor', this.objetoProdAlmacen).then(()=>{
                for(var i = 0; i < this.arrayProductos.length; i++){
                    if(this.arrayProductos[i].codigo == this.objetoProdAlmacen.id_producto){
                        this.arrayProductos[i].precio_por_mayor = this.objetoProdAlmacen.precioPorMayor; 
                    }
                }
                this.loading = false
                $('#modalInsertarPrecioPorMayor').modal('hide');
            })
        }
    }
}
</script>

<style>

.cursor-pointer{
    cursor: pointer;
}

</style>