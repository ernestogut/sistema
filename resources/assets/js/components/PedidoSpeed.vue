<template>
  <main class="main">
     <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h4>Pedidos de la página web</h4>
                </div>
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
                :busy="loading"
                :items="arrayPedidos"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :filterIncludedFields="filterOn"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                :tbody-tr-class="rowClass"
                @filtered="onFiltered"
                :emptyText="'No hay elementos para mostrar'"
                :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
            >
                <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>
                <template v-slot:cell(nombre_completo)="data">
                  {{ data.item.nombre_facturacion }} {{ data.item.apellido_facturacion }}
                </template>
                <template v-slot:cell(actions)="row">
                <b-button size="sm" @click="verDetalle(row.item)">
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
            <div>
              <i class="fa fa-square-o text-danger bg-danger mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Sin pagar</span>
              <i class="fa fa-square-o text-warning bg-warning mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Contra entrega</span>
              <i class="fa fa-square-o text-success bg-success mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Pagado</span>
            </div>  
      </div>
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalDetalle">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Detalle pedido #{{numPedido}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Tipo de pago: {{ordenMetodoDePago}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Nombre: {{ordenNombre}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Direccion: {{ordenDireccion}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    DNI: {{ordenDocumento}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Teléfono: {{ordenTelefono}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Correo: {{ordenEmail}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Productos: {{ordenDetalle}}
                                                </p>
                                        </li>
                                        <li class="list-group-item list-group-item-action list-group-item-primary">
                                                <p style="color: black;">
                                                    Envio: {{ordenEnvio}}
                                                </p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
  </main>
</template>

<script>
export default {
    mounted(){
        this.cargarPedidos();
    },
    data(){
        return{
            cabeceras: ['Acciones', 'N° Orden', 'Cliente', 'Teléfono', 'Estado', 'Total pedido', 'Fecha'],
            ordenDetalle: '',
            ordenDireccion: '',
            ordenDocumento: '',
            ordenNombre: '',
            ordenTelefono: '',
            ordenEmail: '',
            ordenMetodoDePago: '',
            ordenEnvio: '',
            numPedido: '',
            loading: false,
            fields: [
                { key: "order_id", label: "N° Orden", sortable: true, sortDirection: "desc" ,class: "text-center"},
                { key: "nombre_completo", label: "Cliente", sortable: true, class: "text-center"},
                { key: "estado_compra", label: "Estado", sortable: true, class: "text-center"},
                { key: "total_orden", label: "Total pedido", sortable: true, class: "text-center"},
                { key: "fecha_compra", label: "Fecha", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones", class: "text-center"}
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: []
        }
    },
    computed:{
        arrayPedidos(){
            return this.$store.getters.arrayPedidos;
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
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.metodo_pago == 'bacs') return 'bg-danger'
            if (item.metodo_pago == 'cod') return 'bg-warning text-dark'
            if (item.metodo_pago == 'culqi' && item.fecha_pago) {return 'bg-success text-white'} else return 'bg-danger';
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        async cargarPedidos(){
            this.loading = true
            await this.$store.dispatch('cargarPedidos').then(()=>{
                this.totalRows  = this.arrayPedidos.length
                this.loading = false;
            })
        },
        verDetalle(pedido){
            if(pedido.metodo_pago == 'culqi' && pedido.fecha_pago){
                this.ordenMetodoDePago = 'Tarjeta - Pagado'
            }else if(pedido.metodo_pago == 'culqi' && !pedido.fecha_pago){
              this.ordenMetodoDePago = 'Tarjeta - Sin pagar'
            }else if(pedido.metodo_pago == 'cod'){
              this.ordenMetodoDePago = 'Pago contra entrega'
            }else if(pedido.metodo_pago == 'bacs'){
              this.ordenMetodoDePago = 'Transferencia'
            }
            this.ordenDetalle = pedido.productos_orden
            this.ordenNombre = `${pedido.nombre_facturacion} ${pedido.apellido_facturacion}`
            this.ordenDireccion = `${pedido.direccion_facturacion} - ${pedido.distrito_facturacion} - ${pedido.ciudad_facturacion}`
            this.ordenDocumento = pedido.documento_facturacion?pedido.documento_facturacion:'No brindó el Documento'
            this.ordenTelefono = pedido.telefono_facturacion
            this.ordenEmail = pedido.email_facturacion
            this.ordenEnvio = `Pagó S/ ${pedido.costo_envio} por ${pedido.envio}`
            this.numPedido = pedido.order_id
            $('#modalDetalle').modal('show');
        }
    }
}
</script>

<style>

</style>