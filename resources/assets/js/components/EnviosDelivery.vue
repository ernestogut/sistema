<template>
    <main class="main">
        
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4>Pedidos de Listos Ya!</h4>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" @click="abrirModalNuevoPedido()" v-if="usuarioLogeado.idrole != 4">Nuevo pedido</button>
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
                :items="arrayDiasDePedidos"
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
                <template #cell(voucher)="data">
                    <b-img center :src="`/uploads/vouchers_por_dia/${data.item.voucher}`" style="width: 100px" @click="showImageClick(`/uploads/vouchers_por_dia/${data.item.voucher}`)" :class="(data.item.voucher!='default.jpg'?'icono-upload':'')"></b-img>
                </template>
                <template v-slot:cell(actions)="row">
                    <div>
                        <template v-if="isMobile()">
                            <input
                                class="avatar-edit" 
                                type="file"
                                id="imageUpload"
                                @change="onFileSelected"
                                
                                accept="image/*" capture="camera"
                            
                            />
                            <label for="imageUpload" class="icono-upload btn btn-secondary btn-sm" @click="verPedidosDia(row.item, false)" style="margin-top: 8px">
                                <b-icon icon="camera"></b-icon>
                            </label>
                        </template>
                        
                        <input
                            class="avatar-edit" 
                            type="file"
                            id="imageUpload"
                            @change="onFileSelected"
                            
                            accept=".png, .jpg, .jpeg"
                        
                        />
                        <label for="imageUpload" class="icono-upload btn btn-secondary btn-sm" @click="verPedidosDia(row.item, false)" style="margin-top: 8px">
                            <b-icon icon="upload"></b-icon>
                        </label>
                        <b-button size="sm" @click="verPedidosDia(row.item, true)">
                            <i class="icon-eye"></i>
                        </b-button>
                    </div>
                    
                </template>
                <template #cell(fecha)="data">
                    {{new Date(data.item.fecha.replace(/-/g, '\/')).toLocaleDateString("es-ES", { year: 'numeric', month: 'long', day: 'numeric'})}}
                    
                </template>
                <template v-slot:table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                </div>
                </template>
            </b-table>
            <div>
                <i class="fa fa-square-o text-danger bg-danger mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Día sin completar</span>  
                <i class="fa fa-square-o text-success bg-success mr-2" style="width: 15px; height: 15px; border-radius: 5px"></i><span class="mr-2">Día completado</span>
            </div>  
            </div>
             
            <!---->
            <div class="modal fade" id="modalPedidosDetalle" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pedidos del {{diaSeleccionado.fecha}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <envio-delivery-detalle :dia="diaSeleccionado"></envio-delivery-detalle>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                    </div>
                </div>
            </div>
           <viewer v-model="showImage" :src="imageSrc" v-if="!modalEnvioControl">
            </viewer>
            
                            <loading :active.sync="show" 
            :can-cancel="false" 
            
            :is-full-page="true"></loading>
            <div class="modal fade bd-example-modal-lg1" id="modalEnvio" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalEnvio" style="overflow-y: scroll;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Nuevo pedido</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <form  action="" @submit.prevent="insertarPedido()">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Responsable</label>
                                        <select required="required" class="form-control" v-model="objetoEnvio.id_usuario">
                                            <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoEnvio.fecha">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Cliente</label>
                                        <input type="text" class="form-control" v-model="objetoEnvio.cliente">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Teléfono</label>
                                        <input type="number" class="form-control" v-model="objetoEnvio.telefono">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Dirección</label>
                                        <input type="text" class="form-control" v-model="objetoEnvio.direccion">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Referencia</label>
                                        <input type="text" class="form-control" v-model="objetoEnvio.referencia">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Distrito</label>
                                        <select v-model="distritoSeleccionado" class="form-control">
                                            <option disabled value="">Escoje un distrito</option>
                                            <option v-for="distrito in arrayDistritosDelivery" :key="distrito.id" :value="distrito">{{distrito.distrito}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Medio de recepción</label>
                                        <select v-model="objetoEnvio.medio_recepcion" class="form-control">
                                            <option disabled value="">Escoje un medio</option>
                                            <option value="facebook">Facebook</option>
                                            <option value="whatsapp">WhatsApp</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="web">Web</option>
                                            <option value="mercado_libre">Mercado Libre</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Método de pago</label>
                                        <select v-model="objetoEnvio.metodo_pago" class="form-control">
                                            <option disabled value="">Escoje un método de pago</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="transferencia">Transferencia</option>
                                            <option value="contra_entrega">Contra entrega</option>
                                        </select>
                                    </div>
                                    <!--<div class="form-group col-md-3">
                                        <label>Pedido</label>
                                        <input type="text" class="form-control" v-model="objetoEnvio.pedido">
                                    </div>-->
                                    <div class="form-group col-md-3">
                                        <label>Observación SCP</label>
                                        <input type="text" class="form-control" v-model="objetoEnvio.observacion_empresa">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Precio de los productos</label>
                                        <input type="number" step="any" class="form-control" id="precio_productos" v-model="objetoEnvio.precio_productos">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Costo de envío</label>
                                        <input type="number" step="any" class="form-control" id="costo_envio" v-model="objetoEnvio.envio_productos">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Monto pagado</label>
                                        <input type="number" step="any" class="form-control" id="monto_pagado" v-model="objetoEnvio.monto_pagado">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Por cobrar</label>
                                        <input type="number" step="any" class="form-control" id="por_cobrar" v-model="objetoEnvio.por_cobrar" disabled>
                                    </div>
                                </div>
                                <hr>
                                 <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Pedido</label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" v-model="objetoEnvio.pedido"></textarea>
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
// Import component
    import Loading from 'vue-loading-overlay';
    import moment from 'moment';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    import Viewer from 'vue-viewer';
export default {
    mounted(){
        //this.listarEnvios()
        this.objetoEnvio.id_usuario = this.usuarioLogeado.id
        this.listarEnvios()
        $(this.$refs.modalEnvio).on("hidden.bs.modal", this.limpiarModalEnvio)
        //this.listarUsuarios();
    },
    data(){
        return{
            loading: false,
            arrayEnvios: [],
            distritoSeleccionado: {},
            objetoEnvio:{
                id_usuario: null,
                fecha: null,
                hora: moment().format("HH:mm:ss"),
                cliente: null,
                telefono: null,
                direccion: null,
                referencia: null,
                distrito: null,
                pedido: null,
                observacion_empresa: null,
                metodo_pago: 'efectivo',
                medio_recepcion: 'facebook',
                precio_productos: 0.00,
                envio_productos: 0.00,
                total_pedido: 0.00,
                monto_pagado: 0.00,
                por_cobrar: 0.00,
                voucher: null,
                estado: 'pendiente'
            },
            arrayDistritosDelivery: [],
            fields: [
                { key: "id", label: "#", sortable: true, sortDirection: "desc" ,class: "text-center"},
                { key: "fecha", label: "Fecha", sortable: true, class: "text-center"},
                { key: "conteo", label: "Cantidad pedidos", sortable: true, class: "text-center"},
                { key: "conteo_cancelados", label: "Cantidad pedidos cancelados", sortable: true, class: "text-center"},
                { key: "total_cobrar", label: this.$store.getters.arrayUsuarioLogeado.idrole!=4?"Total a cobrar":"Total a pagar", sortable: true, class: "text-center"},
                { key: "total_monto_envios", label: "Total monto envios", sortable: true, class: "text-center"},
                { key: 'voucher', label: 'Voucher', class: "text-center"},
                { key: "actions", label: "Acciones", class: "text-center"}
            ],
            //totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            diaSeleccionado: {},
            voucherSeleccionado: null,
            showImage: false,
            imageSrc: ''
        }
    },
    components:{
        Loading,
        Viewer
    },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        arrayUsuarios(){
            return this.$store.getters.arrayUsuarios;
        },
        arrayDiasDePedidos(){
            return this.$store.getters.arrayDiasDePedidos;
        },
        totalRows(){
            return this.arrayDiasDePedidos.length;
        },
        sortOptions() {
      // Create an options list from our fields
        return this.fields
            .filter(f => f.sortable)
            .map(f => {
            return { text: f.label, value: f.key };
            });
        },
        show(){
            return this.$store.getters.show;
        },
        modalEnvioControl(){
            return this.$store.getters.modalEnvioControl;
        }
    },
    methods:{
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.pedidos_incompletos == '0') return 'bg-success'
            if (item.pedidos_incompletos != '0') return 'bg-danger'
        },
        showImageClick (imagen) {
            this.imageSrc = imagen
          this.showImage = true;
        },
        limpiarModalEnvio(){
            this.objetoEnvio.id_usuario = this.usuarioLogeado.id
            this.objetoEnvio.fecha =  null
            this.objetoEnvio.cliente = null
            this.objetoEnvio.telefono = null
            this.objetoEnvio.direccion = null
            this.objetoEnvio.referencia = null
            this.objetoEnvio.distrito = null
            this.distritoSeleccionado = {}
            this.objetoEnvio.pedido = null
            this.objetoEnvio.observacion =  null
            this.objetoEnvio.metodo_pago = 'efectivo'
            this.objetoEnvio.medio_recepcion = 'facebook',
            this.objetoEnvio.precio_productos = 0.00
            this.objetoEnvio.envio_productos = 0.00
            this.objetoEnvio.total_pedido = 0.00
            this.objetoEnvio.monto_pagado = 0.00
            this.objetoEnvio.por_cobrar = 0.00
            this.objetoEnvio.estado = 'pendiente'

            this.$store.dispatch('actualizarModalEnvioControl', false)
        },
        async listarEnvios(){
            //if(this.arrayProductos.length == 0){
                this.loading = true
                await this.$store.dispatch('cargarDiasDePedidos').then(()=>{
                    //this.totalRows = this.arrayDiasDePedidos.length
                    this.loading = false;
                });
            //}
        },
        
        isMobile(){
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return true
            }else{
                return false
            }
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        /*listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoEnvio.id_usuario = response.data.id
                this.objetoEnvio.id_almacen = response.data.id_almacen;
                this.listarEnvios()
            })
            var urlItem = '/user';
            axios.get(urlItem).then(response=>{
                this.arrayUsuarios = response.data;
            })
        },*/
        seleccionarDistrito(){
            let me= this;
            var url='/precio_delivery';
            axios.get(url).then(function (response){
                me.arrayDistritosDelivery = response.data;
            })
        },
        abrirModalNuevoPedido(){
                //this.listarUsuarios();
                this.seleccionarDistrito();
                this.obtenerFecha()
                //$("#monto").on('blur change input', function() {
               /*     $(this).val(function(i, input) {
                        input = input.replace(/\D/g, '');
                        return (input / 100).toFixed(2);
                    });
                }).trigger('blur');*/
                $('#modalEnvio').modal({
                    show: true,
                    focus: true
                })
                this.$store.dispatch('actualizarModalEnvioControl', true)
        },
        obtenerFecha(){
            this.objetoEnvio.fecha = this.$moment().format("YYYY-MM-DD")
        },
        insertarPedido(){
            if(confirm('¿Estás seguro de registrar este pedido?')){
                this.$store.dispatch('actualizarShow', true)
                var me = this;
                axios.post('/delivery', this.objetoEnvio).then((response)=>{
                    this.$store.dispatch('actualizarShow', false)
                    $('#modalEnvio').modal('hide');
                    Vue.swal({
                        title: `Pedido registrado correctamente!`,
                        text: `El pedido ha sido procesado con éxito`,
                        icon: 'success'
                    });
                    this.listarEnvios();
                })
                .catch(error=>{
                    this.$store.dispatch('actualizarShow', false)
                    Vue.swal({
                        title: `Pedido fallido`,
                        text: `El pedido no pudo procesarse`,
                        icon: 'error'
                    });
                })
            }else{
                return;
            }
                
        },
        verPedidosDia(dia, controladorModal){
            this.diaSeleccionado = dia;
            if(controladorModal){
                $('#modalPedidosDetalle').modal('show');
            }
        },
        //Subir voucher por dia
        onFileSelected(event) {
            this.objetoEnvio.voucher = event.target.files[0];
            this.onUploadVoucher();
        },
        onFotoSeleccionada(event) {
            this.objetoEnvio.voucher = event.target.files[0];
        },
        onUploadVoucher() {
        this.$store.dispatch('actualizarShow', true)
        var fd = new FormData();
        fd.append("voucher", this.objetoEnvio.voucher);
        fd.append("id_dia", this.diaSeleccionado.id);
        axios
            .post("/voucher_pedido_por_dia/subirVoucherPorDia", fd)
            .then(response => {
                this.$store.dispatch('actualizarShow', false)
            Vue.swal({
                title: "Felicidades",
                text: "Has subido correctamente el voucher!",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Gracias"
            }).then(result => {
                if (result.value) {
                    this.listarEnvios()
                }
            });
            });
        },
    },
    watch:{
        distritoSeleccionado(){
            this.objetoEnvio.distrito = this.distritoSeleccionado.id;
            if(this.distritoSeleccionado.costo_envio){
                this.objetoEnvio.envio_productos = this.distritoSeleccionado.costo_envio;
            }else{
                this.objetoEnvio.envio_productos = 0
            }
        },
        'objetoEnvio.envio_productos'(){
            this.objetoEnvio.por_cobrar = ((Number(this.objetoEnvio.precio_productos)+Number(this.objetoEnvio.envio_productos))-Number(this.objetoEnvio.monto_pagado)).toFixed(2);
        },
        'objetoEnvio.precio_productos'(){
            this.objetoEnvio.por_cobrar = ((Number(this.objetoEnvio.precio_productos)+Number(this.objetoEnvio.envio_productos))-Number(this.objetoEnvio.monto_pagado)).toFixed(2);
        },
        'objetoEnvio.monto_pagado'(){
            this.objetoEnvio.por_cobrar = ((Number(this.objetoEnvio.precio_productos)+Number(this.objetoEnvio.envio_productos))-Number(this.objetoEnvio.monto_pagado)).toFixed(2);
        },

    }
}
</script>

<style scoped>

.avatar-edit{
    display: none;
}
.icono-upload{
    cursor:pointer;
}

.clear { clear: both; }
</style>