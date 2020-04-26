<template>
  <main class="main">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Compras de la empresa</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalCompra()">Nueva compra</button>
                        </div>   
                    </div>
                </div>
            </div>
            <spinner v-if="loading"></spinner>
            <div class="card-body" v-else-if="initiated">
                <table  class="table table-hover table-bordered dt-responsive nowrap"  id="myTableCompras" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center align-middle" v-for="cabecera of cabecerasCompra" :key="cabecera.id">{{cabecera}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(compra) of arrayCompras" :key="compra.key">
                            <td class="text-center align-middle">
                                <div>
                                    <span class="btn btn-primary btn-sm boton" @click="detalleCompra(compra)"><i class="icon-eye"></i></span>
                                    <span class="btn btn-danger btn-sm boton" ><i class="icon-trash"></i></span>
                                </div>
                            </td>
                            <td class="text-center align-middle">{{compra.id}}</td>
                            <td class="text-center align-middle">{{compra.responsable}}</td>
                            <td class="text-center align-middle">{{compra.fecha}}</td>
                            <td class="text-center align-middle">{{compra.total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal fade bd-example-modal-lg1" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Nueva compra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="card-body">
                            <form class="kt-form kt-form-label-right" action="" @submit.prevent="insertarCabeceraCompra()">
                                <div class="kt-portlet__body" data-scroll="true" data-height="200" data-scrollbar-shown="true">
                                    <div class="row justify-content-start">
                                        <div class="col-2 col-form-label">Cod. Proveedor</div>
                                        <div class="input-group col-4">
                                            <span class="input-group-text buscador" @click="(objetoCompra.cod_proveedor.length > 0)?buscarProveedor(objetoCompra.cod_proveedor):abrirModalProveedores()"><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                            <input type="text" class="form-control" name="SdnCode" v-model="objetoCompra.cod_proveedor" placeholder="Buscar" style="width: 80%;">
                                        </div>
                                        <div class="col-2 col-form-label">Num.Doc</div>
                                        <div class="col-4"><input type="text" name="Id" class="form-control" disabled="disabled"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Tipo de documento</div>
                                            <div class="input-group col">
                                            <input type="text" name="RUC" class="form-control" :value="objetoCompra.ruc_proveedor" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-form-label">Dirección</div>
                                        <div class="col-4"><input type="text" name="Direccion" class="form-control" :value="objetoCompra.dir_proveedor" disabled="disabled"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Razón Social</div>
                                        <div class="col-4"><input type="text" name="SdnName" class="form-control" :value="objetoCompra.razon"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Responsable</div>
                                        <div class="col-4">
                                            <select required="required" class="form-control" v-model="objetoCompra.id_user">
                                                <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                            </select>
                                        </div>
                                        <div class="col-2 col-form-label">Fecha Emi</div>
                                        <div class="col-4"><input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoCompra.fecha"></div>
                                        <div class="col-2 col-form-label"></div>
                                        <div class="col-4"><button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalAgregarProducto()">Agregar productos</button></div>
                                    </div>
                                </div>
                                <div class="table-responsive scroll">   
                                    <table class="table table-bordered table-sm ">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center align-middle">#</th>
                                                <th scope="col" class="text-center align-middle">Codigo</th>
                                                <th scope="col" class="text-center align-middle">Descripcion</th>
                                                <th scope="col" class="text-center align-middle">Precio</th>
                                                <th scope="col" class="text-center align-middle" v-for="cabeceraAlm in arrayAlmacenFijo" :key="cabeceraAlm.id" >Cantidad {{cabeceraAlm.descripcion}}</th>
                                                <th scope="col" class="text-center align-middle">Descuento</th>
                                                <th scope="col" class="text-center align-middle">Total</th>
                                                <th scope="col" class="text-center align-middle">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(venta, index) in ventas" :key="venta.id">
                                                <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                <td class="text-center align-middle">{{venta.codigo}}</td>
                                                <td class="text-center align-middle">{{venta.producto}}</td>
                                                <td class="text-center align-middle" >   
                                                    <input class="form-control" type="number" id="monto1" v-model="venta.precio" @input="generarTotal(venta)">
                                                </td>
                                                <td class="text-center align-middle" v-for="cantidadAlm in venta.cantidades" :key="cantidadAlm.id"><input class="form-control" type="number" step="any"   v-model="cantidadAlm.cantidad" @input="generarTotal(venta)"></td>
                                                <td class="text-center align-middle"><input class="form-control" type="number" id="monto2" step="any" v-model="venta.descuento" @input="generarTotal(venta)"></td>
                                                <td class="text-center align-middle"><input class="form-control" v-model="venta.total" step="any" id="monto3" disabled></td>
                                                <td class="text-center align-middle">
                                                    <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(venta, index)"><i class="icon-trash"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Subtotal</div>
                                        <div class="col"><input type="text" class="form-control" readonly="" id="monto4" v-model="objetoCompra.sub_total"></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Desc.Global</div>
                                        <div class="col"><input type="text" name="DescGlobal" id="DescGlobal" class="form-control" v-model="objetoCompra.desc_global" ></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">IgvTotal</div>
                                        <div class="col"><input type="text" name="IgvTotal" id="IgvTotal" class="form-control" readonly="" v-model="objetoCompra.igv_total"></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Total</div>
                                        <div class="col"><input type="text" name="Totales" id="Totales" step="any" class="form-control" readonly="" v-model="objetoCompra.total"></div>
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
            <div class="modal fade" tabindex="-1" role="dialog" id="modalProducto" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaProductos" style="overflow-y: scroll;">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Tabla de productos
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="table-responsive">
                                <spinner v-if="loading"></spinner>
                                <datatable-productos @emitirEvProductos="recibirVenta" @emitirEvArrayAlm="recibirCantidadesAlmacen"  :abrirModalImagen="abrirModalImagen" :arrayAlmacenFijo="arrayAlmacenFijo" :ingreso="true" v-else-if="initiated"></datatable-productos>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="modalProveedores" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaProveedores">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Proveedores
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="table-responsive">
                                <spinner v-if="loading"></spinner>
                                <datatable :arrayItems="arrayProveedores" :cabeceras="cabecerasProveedor" :icono="iconos" v-else-if="initiated" :listaVentasPadre="ventas" :controlador="controlador" :funcionBoton="buscarProveedor" :factura="false" :idTabla="'myTableProveedores'"></datatable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="modalInformacionCompra" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalInformacionFact">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Detalle
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                                        <th scope="col" class="text-center align-middle" v-for="alm in arrayAlmacenFijo" :key="alm.id">Cantidad {{alm.descripcion}}</th>
                                                        <th scope="col" class="text-center align-middle">Precio</th>
                                                        <th scope="col" class="text-center align-middle">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr v-for="(detalle, index) in arrayComprasDetalle" :key="detalle.id">
                                                        <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                        <td class="text-center align-middle">{{detalle.codigo_producto}}</td>
                                                        <td class="text-center align-middle">{{detalle.descripcion_producto}}</td>
                                                        <td class="text-center align-middle" v-for="cantidad in detalle.cantidades" :key="cantidad.id">{{cantidad.cantidad}}</td>
                                                        <td class="text-center align-middle">S/ {{detalle.precio_producto}}</td>
                                                        <td class="text-center align-middle">S/ {{detalle.total_producto}}</td>
                                                    </tr>
                                                    <tr class="total">
                                                        <th scope="row"></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td v-for="alm in arrayAlmacenFijo" :key="alm.id"></td>
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
            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCantidades">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cantidades</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <spinner v-if="loading"></spinner>
                            <div class="card-body" v-else-if="initiated">
                                <ul class="list-group">
                                    <li class="list-group-item cursor-pointer" v-for="almacen in arrayAlmacen" :key="almacen.id">
                                        <span v-if="almacen.editable">{{almacen.id}}. Existen {{almacen.cantidad}} unidad(es) en el almacen de {{almacen.descripcion}}</span>
                                        <span v-else>
                                            {{almacen.id}}. No existen unidades en el almacen de {{almacen.descripcion}}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg show" id="modalImagen" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <spinner v-if="loadingImagen"></spinner>
                        <div class="modal-body d-flex flex-wrap justify-content-center align-items-center" id="dynamic-content" v-for="productoImagen in objetoProductoImagen" :key="productoImagen.key" v-else-if="initiatedImagen">
                            <img :src="productoImagen.imagen" style="width: 18rem; border-radius: 10px;" class="img-fluid" alt=""/>
                        </div>
                    </div>
                </div>
            </div> 
    </main>
</template>

<script>
export default {
    data(){
        return{
            initiated: false,
            loading: false,
            initiatedImagen: false,
            loadingImagen: false,
            arrayProveedores: [],
            arrayItems: [],
            arrayCompras: [],
            arrayComprasDetalle: [],
            ventas: [],
            arrayAlmacen: [],
            objetoComprobante: {},
            objetoProductoImagen: {},
            cabecerasProveedor: ['Acciones', '#', 'Codigo', 'Razón social', 'Dirección', 'Numero de documento'],
            iconos: 'icon-plus',
            cabecerasCompra: ['Acciones', 'Num doc', 'Responsable', 'Fecha', 'Total'],
            usuarioLogueado: {},
            comprobanteEscogido: 0,
            //datos de la compra
            objetoCompra: 
                {
                    cod_proveedor: '',
                    ruc_proveedor: '',
                    dir_proveedor: '',
                    razon: '',
                    id_user: null,
                    fecha: '',
                    sub_total: 0,
                    desc_global: 0,
                    igv_total: 0,
                    total: 0
                },
            controlador: 0, // 1 - productos, 2 - proveedores, 4 -> compras
        }
    },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        arrayUsuarios(){
            return this.$store.getters.arrayUsuarios;
        },
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen;
        },
        totalItem: function(){
            let sum = 0;
            for(let i = 0; i < this.arrayComprasDetalle.length; i++){
                sum += parseFloat(this.arrayComprasDetalle[i].total_producto);
            }
            return sum.toFixed(2);
        }
    },
    mounted(){
        this.listarCompras();
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
        $(this.$refs.tablaProductos).on("hidden.bs.modal", this.limpiarTablaProductos)
        $(this.$refs.tablaProveedores).on("hidden.bs.modal", this.limpiarTablaProveedores)
    },
    methods:{
        abrirModalImagen(producto){
            this.loadingImagen = true
            $('#modalImagen').modal('show');
            axios.get(`speed/${producto.codigo}`).then(response=>{
                this.objetoProductoImagen = response.data
                this.loadingImagen = false
                this.initiatedImagen = true;
            })
        },
        limpiarTabla(){
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            this.controlador = 4
            this.objetoCompra.cod_proveedor =  ''
            this.objetoCompra.ruc_proveedor =  ''
            this.objetoCompra.dir_proveedor =  ''
            this.objetoCompra.razon =  ''
            this.objetoCompra.fecha =  ''
        },
        limpiarTablaProductos(){
            $('#myTableProductos').DataTable().destroy();
            this.listarCompras()
        },
        limpiarTablaProveedores(){
            $('#myTableProveedores').DataTable().destroy();
            this.listarCompras();
        },
        generarTotal(item){
            var cantidadTotal = 0
            for(var i = 0; i < item.cantidades.length; i++){
                item.cantidades[i].cantidad = parseInt(item.cantidades[i].cantidad)
                cantidadTotal += item.cantidades[i].cantidad
            }
            item.total = item.precio * cantidadTotal
            var lista = []
            var suma = 0
            for(var i = 0; i < this.ventas.length; i++){
                lista.push(this.ventas[i].total)
            }
            suma = lista.reduce((a, b) => a + b, 0)
            this.objetoCompra.total = suma
            this.objetoCompra.igv_total = Math.round((this.objetoCompra.sub_total * 0.18)*100)/100
            this.objetoCompra.sub_total =  Math.round((this.objetoCompra.total / 1.18)*100)/100
        },
        detalleCompra(compra){
            axios.get(`detalle_compra/${compra.id}`).then((response)=>{
                this.arrayComprasDetalle = response.data;
                $('#modalInformacionCompra').modal('show');
            })
        },
        recibirVenta(venta){
            this.ventas = venta
        },
        recibirCantidadesAlmacen(almacen){
            this.arrayAlmacen = almacen
        },
        miTabla(){
            $( function () {
                $('#myTableCompras').DataTable();
            } );
        },
        async listarItem(){
            if(this.arrayProductos.length == 0){
                this.loading = true
                await this.$store.dispatch('cargarProductos').then(()=>{
                    this.loading = false;
                    this.initiated = true;
                    this.tablaProductos();
                });
            }else{
                this.tablaProductos();
            }
        },
        listarProveedores(){
            this.loading = true
            var urlItem = `/proveedor/listarProveedorModal`;
            axios.get(urlItem).then(response=>{
                this.arrayProveedores = response.data;
                this.loading = false;
                this.initiated = true;
                this.tablaProveedores();
            })
        },
        listarCompras(){
            var urlItem = '/cabecera_compra';
            this.loading = true
            axios.get(urlItem).then(response=>{
                this.arrayCompras = response.data;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        tablaProveedores(){
            $( function () {
                $('#myTableProveedores').DataTable({
                    searching: true
                });
            } );
        },
        tablaProductos(){
            $( function () {
                $('#myTableProductos').DataTable({
                    searching: true
                });
            } );
        },
        abrirModalCompra(){
            this.obtenerFecha()
            $('#modalCompra').modal('show');
        },
        abrirModalProveedores(){
            this.listarProveedores()
            this.controlador = 2
            $('#modalProveedores').modal('show');
        },
        abrirModalAgregarProducto(){
            this.listarItem();
            $('#modalProducto').modal('show');
            this.controlador = 1
        },
        eliminarProductoTabla(venta, index){
            this.ventas.splice(index,1)
            document.getElementById(`producto${venta.codigo}`).className = ''
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        },
        buscarProveedor(codigo){
            axios.get(`/proveedor/${codigo}/buscarProveedor`).then((response)=>{
                this.objetoCompra.cod_proveedor = response.data[0].codigo
                this.objetoCompra.ruc_proveedor = response.data[0].num_documento
                this.objetoCompra.dir_proveedor = response.data[0].direccion
                this.objetoCompra.razon = response.data[0].razon
                $('#modalProveedores').modal('hide');
            })
        },
        insertarCabeceraCompra(){
            var me = this;
            axios.post('/cabecera_compra', this.objetoCompra).then((response)=>{
                this.id_cabecera = response.data
                var productos = this.arrayProductos
                axios.post('/detalle_compra', {'ventas': this.ventas, 'id_cabecera': this.id_cabecera}).then((response)=>{
                    for(var k = 0; k < this.ventas.length; k++){
                        for(var l = 0; l < productos.length; l++){
                            if(this.ventas[k].codigo == productos[l].codigo){
                                for(var m = 0; m < this.ventas[k].cantidades.length; m++){
                                    productos[l].stock = productos[l].stock + parseInt(this.ventas[k].cantidades[m].cantidad);
                                }
                            }
                        }
                    }
                    this.$store.dispatch('actualizarProductos', productos)
                    $('#modalCompra').modal('hide');
                })
                Vue.swal(
                    'Compra concretada!',
                    'La compra se proceso correctamente!',
                    'success'
                );
                $('#myTableCompras').DataTable().destroy();
                this.listarCompras();
            })
            //Ingreso multinventario completado, terminar parte tecnica
            .catch(error=>{
                Vue.swal({
                        title: 'Compra no concretada!',
                        text: 'Hubo un error al procesar la compra!',
                        icon: 'error'
                    }   
                );
            })
        },
        obtenerFecha(){
            this.objetoCompra.fecha = this.$moment().format("YYYY-MM-DD")
        }
    },
    watch:{
        ventas:{
            handler: function(){
                var lista = []
                var suma = 0
                for(var i = 0; i < this.ventas.length; i++){
                    lista.push(this.ventas[i].total)
                    
                    //this.ventas[i].almacen = this.almacen_id;
                }
                suma = lista.reduce((a, b) => a + b, 0)
                this.objetoCompra.total = suma
                this.objetoCompra.igv_total = Math.round((this.objetoCompra.sub_total * 0.18)*100)/100
                this.objetoCompra.sub_total =  Math.round((this.objetoCompra.total / 1.18)*100)/100
            },
            deep: true
        },
    }
}
</script>

<style>


.buscador{
    cursor: pointer;
}

</style>