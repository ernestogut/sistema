<template>
    <main class="main">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4>Movimiento de caja</h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalNuevoMovimiento()">Nuevo movimiento de caja</button>
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
                :items="arrayMovimientos"
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

                <template v-slot:cell(actions)="">
                <b-button size="sm">
                    <i class="icon-pencil"></i>
                </b-button>
                <b-button size="sm">
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
            <!---->
            <div class="modal fade bd-example-modal-lg1" id="modalMovimiento" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalMovimiento" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo movimiento de caja</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form  action="" @submit.prevent="insertarMovimiento()">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Tienda</label>
                                    <select v-model="objetoMovimiento.id_almacen" class="form-control">
                                        <option disabled value="">Escoje un almacén</option>
                                        <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Responsable</label>
                                    <select required="required" class="form-control" v-model="objetoMovimiento.id_usuario">
                                        <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tipo de movimiento</label>
                                    <select required="required" class="form-control" v-model="objetoMovimiento.tipo_movimiento">
                                        <option value="ingreso">Ingreso</option>
                                        <option value="egreso">Egreso</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Fecha de emisión</label>
                                    <input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoMovimiento.fecha">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Observación</label>
                                    <input type="text" class="form-control" v-model="objetoMovimiento.observacion">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Monto</label>
                                    <input type="number" step="any" class="form-control" id="monto" v-model="objetoMovimiento.monto">
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                    </div>  
                </div>
            </div>
            </div>
    </main>
</template>

<script>
export default {
    mounted(){
        //this.listarMovimientosCaja()
        this.objetoMovimiento.id_usuario = this.usuarioLogeado.id
        this.objetoMovimiento.id_almacen = this.usuarioLogeado.id_almacen;
        this.listarMovimientosCaja()
        $(this.$refs.modalMovimiento).on("hidden.bs.modal", this.limpiarModalMovimiento)
        //this.listarUsuarios();
    },
    data(){
        return{
            loading: false,
            arrayMovimientos: [],
            objetoMovimiento:{
                id_usuario: null,
                id_almacen: null,
                fecha: null,
                observacion: null,
                monto: 0.00,
                tipo_movimiento: null,
            },
            arrayAlmacen: [],
            fields: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc" ,class: "text-center"},
                { key: "responsable", label: "Responsable", sortable: true, class: "text-center"},
                { key: "tienda", label: "Tienda", sortable: true, class: "text-center"},
                { key: "tipo_movimiento", label: "Tipo de movimiento", sortable: true, class: "text-center"},
                { key: "monto", label: "Monto", sortable: true, class: "text-center"},
                { key: "observacion", label: "Observación", sortable: true, class: "text-center"},
                { key: "fecha", label: "Fecha", sortable: true, class: "text-center"},
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
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        arrayUsuarios(){
            return this.$store.getters.arrayUsuarios;
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
        limpiarModalMovimiento(){
            this.objetoMovimiento.id_usuario = this.usuarioLogeado.id
            this.objetoMovimiento.id_almacen = this.usuarioLogeado.id_almacen;
            this.objetoMovimiento.fecha =  null
            this.objetoMovimiento.observacion =  null
            this.objetoMovimiento.monto = 0.00
            this.objetoMovimiento.tipo_movimiento =  null
        },
        listarMovimientosCaja(){
            this.loading = true
            axios.get(`/movimiento_caja/${this.objetoMovimiento.id_almacen}`).then(response=>{
                this.arrayMovimientos = response.data;
                this.totalRows = this.arrayMovimientos.length
                this.loading = false;
            })
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        /*listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoMovimiento.id_usuario = response.data.id
                this.objetoMovimiento.id_almacen = response.data.id_almacen;
                this.listarMovimientosCaja()
            })
            var urlItem = '/user';
            axios.get(urlItem).then(response=>{
                this.arrayUsuarios = response.data;
            })
        },*/
        seleccionarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.arrayAlmacen = response.data;
            })
        },
        abrirModalNuevoMovimiento(){
                //this.listarUsuarios();
                this.seleccionarAlmacen();
                this.obtenerFecha()
                //$("#monto").on('blur change input', function() {
               /*     $(this).val(function(i, input) {
                        input = input.replace(/\D/g, '');
                        return (input / 100).toFixed(2);
                    });
                }).trigger('blur');*/
                $('#modalMovimiento').modal('show');
        },
        obtenerFecha(){
            this.objetoMovimiento.fecha = this.$moment().format("YYYY-MM-DD")
        },
        insertarMovimiento(){
                var me = this;
                axios.post('/movimiento_caja', this.objetoMovimiento).then((response)=>{
                    $('#modalMovimiento').modal('hide');
                    Vue.swal({
                        title: `${this.objetoMovimiento.tipo_movimiento} exitoso!`,
                        text: `El ${this.objetoMovimiento.tipo_movimiento} ha sido procesado con éxito`,
                        icon: 'success'
                    });
                    this.listarMovimientosCaja();
                })
                .catch(error=>{
                    Vue.swal({
                        title: `${this.objetoMovimiento.tipo_movimiento} fallido`,
                        text: `El ${this.objetoMovimiento.tipo_movimiento} no pudo procesarse`,
                        icon: 'error'
                    });
                })
        },
    }
}
</script>

<style>

</style>