<template>
<main class="main">
    <b-container>
        <b-card header="Pedidos al por mayor">
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
        :items="arrayPedidosPorMayor"
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
        <template v-slot:cell(pedido)="data">
          #{{data.item.id}} {{  data.item.name }} {{ data.item.apellido }}
        </template>
        <template v-slot:cell(actions)="row">
          <!--<b-button size="sm" @click="abrirModalCantidades(row.item)" class="mr-1">
            <i class="icon-pencil"></i>
          </b-button>-->
          <b-button size="sm" class="mr-1" @click="obtenerDetallesPedido(row.item)">
            <i class="icon-eye"></i>
          </b-button>
          <!--<b-button
            variant="info"
            size="sm"
            @click="abrirModalImagen(row.item)"
          >
            <i class="icon-picture"></i>
          </b-button>-->
        </template>
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>
      </b-table> 
        <div>
            <i class="fa fa-square-o text-danger bg-danger mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Cancelado</span>
            <i class="fa fa-square-o mr-2" style="width: 15px; height: 15px;"></i><span>Pendiente</span>
            <i class="fa fa-square-o text-warning bg-warning mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Procesando</span>
            <i class="fa fa-square-o text-success bg-success mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Completado</span>
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
        <div class="modal fade" id="modalDetallesFacturacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalle pedido #{{pedidoSeleccionado.id}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-md-6">
                      <strong>Detalles de facturación</strong><br>
                      {{pedidoSeleccionado.nombre_envio}}<br>
                      {{pedidoSeleccionado.direccion_envio}}<br>
                      {{pedidoSeleccionado.distrito_envio}}<br>
                      {{pedidoSeleccionado.provincia_envio}}<br>
                      {{pedidoSeleccionado.departamento_envio}}<br>
                    </div>
                    <div class="col-md-6">
                      <strong>Correo eletrónico</strong><br>
                      {{pedidoSeleccionado.correo_envio}}<br>

                      <strong>Teléfono</strong><br>
                      {{pedidoSeleccionado.telefono_envio}}<br>
                      <strong>Estado</strong><br>
                      <select required="required" class="form-control" v-model="objetoEstadoPedido">
                          <option v-for="estado in estadoPedidos" :value="estado" :key="estado.opcion">{{estado.opcion.charAt(0).toUpperCase() + estado.opcion.slice(1)}}</option>
                      </select> 
                    </div>
                  </div>
                  <div class="container">
                        <div class="row">
                          <div class="table-responsive">
                          <table class="table table-image table-responsive">
                            <thead>
                              <tr>
                                <th scope="col" class="text-center">Imagen</th>
                                <th scope="col" class="text-center">Producto</th>
                                <th scope="col" class="text-center">Precio</th>
                                <th scope="col" class="text-center">Cantidad</th>
                                <th scope="col" class="text-center">Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-for="detalle in arrayDetallePedido" :key="detalle.id">
                                <td class="w-25">
                                  <img :src="!detalle.es_variacion?detalle.images[0].src:detalle.image.src" class="img-fluid img-thumbnail" alt="Sheep">
                                </td>
                                <td class="text-center">{{detalle.name}}</td>
                                <td class="text-center">S/ {{detalle.price}}</td>
                                <td class="text-center">{{detalle.cantidad}}</td>
                                <td class="text-center">S/ {{detalle.price * detalle.cantidad}}</td>
                              </tr>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>Total</strong></td>
                                <td>S/ {{pedidoSeleccionado.total_carrito}}</td>
                              </tr>
                            </tbody>
                          </table>   
                          </div>
                        </div>
                      </div>

                </div>
              </div>
              <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="cambiarEstadoPedido()">Guardar</button>
              </div>
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
                key: "pedido",
                label: "Pedido",
                sortable: true,
                class: "text-center"
                },
                {
                key: "created_at",
                label: "Fecha",
                sortable: true,
                class: "text-center"
                },
                {
                key: "estado",
                label: "Estado",
                sortable: true,
                class: "text-center"
                },
                {
                key: "total_carrito",
                label: "Total",
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
            arrayPedidosPorMayor: [],
            idAlmacen: '',
            enAlmacen: false,
            modoEditable: false,
            pedidoSeleccionado: '',
            arrayDetallePedido: [],
            estadoPedidos: [
              {
                opcion: 'cancelado',
                observacion: 'Su pedido ha sido cancelado, para más información comunicarse con 958992917'
              },
              {
                opcion: 'enviado',
                observacion: 'Su pedido ha sido enviado'
              },
              {
                opcion: 'pagado',
                observacion: 'El pedido está pagado. El plazo de entrega es de 7 días hábiles'
              },
              {
                opcion: 'procesando',
                observacion: 'Ha realizado su pedido con éxito. El plazo para pagar el pedido es de 15 días'
              },
              {
                opcion: 'completado',
                observacion: 'Su pedido ha sido entregado'
              }
            ],
            objetoEstadoPedido: {
            }
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
            if (item.estado == 'completado') return 'bg-success'
            if (item.estado == 'procesando') return 'bg-warning text-dark'
            if (item.estado == 'cancelado') return 'bg-danger'
        },
        async listarItem(){
            this.cargando = true
            axios.get('speed/obtenerPedidosPorMayor').then(response=>{
                this.arrayPedidosPorMayor = response.data;
                this.totalRows = this.arrayPedidosPorMayor.length
                this.cargando = false;      
            })
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
        obtenerDetallesPedido(item){
            this.pedidoSeleccionado = item
            this.objetoEstadoPedido.opcion = this.pedidoSeleccionado.estado;
            axios.get(`speed/${this.pedidoSeleccionado.id}/obtenerDetallePedidoPorMayor`).then(response=>{
              $('#modalDetallesFacturacion').modal('show');
              this.arrayDetallePedido = response.data;
            })
        },
        cambiarEstadoPedido(){
          axios.put(`speed/${this.pedidoSeleccionado.id}/cambiarEstadoPedido`, this.objetoEstadoPedido).then(response=>{
            for(var i = 0; i < this.arrayPedidosPorMayor.length; i++){
              if(this.arrayPedidosPorMayor[i].id == this.pedidoSeleccionado.id){
                this.arrayPedidosPorMayor[i].estado = this.objetoEstadoPedido.opcion;
              }
            }
            Vue.swal({
                title: 'Estado cambiado',
                text: `El estado del pedido #${this.pedidoSeleccionado.id} cambió a ${this.objetoEstadoPedido.opcion}!`,
                icon: 'success'
            }).then((result) => {
                if (result.value) {
                    $('#modalDetallesFacturacion').modal('hide');
                }
            })
            
          })
        }
    }
}
</script>

<style lang="scss" scoped>
.container {
  padding: 2rem 0rem;
}

h4 {
  margin: 2rem 0rem 1rem;
}

.table-image {
  td, th {
    vertical-align: middle;
  }
}
.cursor-pointer{
    cursor: pointer;
}

</style>