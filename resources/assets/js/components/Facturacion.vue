<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Factura</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo</button>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg1" id="modalVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ventas - Pedidos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form class="kt-form kt-form--label-right" action="" @submit.prevent="insertarCabecera()">
                        <div class="kt-portlet__body" data-scroll="true" data-height="200" data-scrollbar-shown="true">
                            <div class="row justify-content-start">

                                <div class="col-2 col-form-label">Cod.Cliente</div>
                                    <div class="input-group col">
                                    <div class="btn input-group-prepend btn_subbuscar" id="Btn_SubBuscar" idsubform="1">
                                    <span class="input-group-text" ><i class="fa fa-search" aria-hidden="true" @click="(buscarCodigo.length > 0)?buscarCliente(buscarCodigo):abrirModalClientes()"></i ></span>
                                </div>
                                <input type="text" class="form-control" name="SdnCode" v-model="buscarCodigo" placeholder="Escriba ó Busque...">
                                </div>
                                <div class="col-2 col-form-label">Num.Doc</div>
                                <div class="col"><input type="text" name="Id" class="form-control" disabled="disabled"></div>
                                <div class="w-100"></div>

                                <div class="col-2 col-form-label">RUC</div>
                                    <div class="input-group col">
                                    <input type="text" name="RUC" class="form-control" :value="ruc" disabled="disabled">
                                </div>
                                <div class="col-2 col-form-label">Dirección</div>
                                <div class="col"><input type="text" name="Direccion" class="form-control" :value="direccion" disabled="disabled"></div>
                                <div class="w-100"></div>

                                <div class="col-2 col-form-label">Razón Social</div>
                                <div class="col"><input type="text" name="SdnName" class="form-control" :value="razon"></div>

                                <div class="w-100"></div>
                          

                                <div class="col-2 col-form-label">Vendedor</div>
                                <div class="col-3">
                                    <select required="required" class="form-control" v-model="vendedor">
                                        <option selected="selected">Seleccione...</option>
                                        <option selected="selected">1</option>
                                        <option selected="selected">2</option>
                                    </select>
                                    {{vendedor}}
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 col-form-label">Fecha Emi</div>
                                <div class="col"><input type="date" name="FechaReg" id="FechaReg" class="form-control" v-model="fecha"></div>

                                <div class="w-100"></div>
                                <div class="col-2 col-form-label">Tipo Venta</div>
                                <div class="col-3">
                                    <select name="PedTipo" required="required" class="form-control" v-model="tipo_venta">
                                        <option value="A" selected="selected">Artículo</option>
                                        <option value="S">Servicio</option>
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 col-form-label">Folio</div>
                                <div class="col-2"><input type="text" name="Serie" class="form-control" placeholder="Serie" v-model="serie"></div>
                                <div class="col-2"><input type="text" name="Folio" class="form-control"  placeholder="Folio" v-model="folio"></div>


                                <div class="w-100"></div>
                            </div>

                            <input type="button" name="addRow" id="addRow" value="Agrega1" style="display: none">

                            
                                <table id="D001" class="display pageResize D001" style="width:100%"></table>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ml-auto">
                                <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalAgregarProducto()">Agregar productos</button>
                            </div>
                        </div>
                        <div class="table-responsive scroll">   
                            <table class="table table-bordered table-sm ">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Descuento</th>
                                        <th scope="col">Almacen</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(venta, index) in ventas" :key="venta.id">
                                        <th scope="row">{{index+1}}</th>
                                        <td>{{venta.codigo}}</td>
                                        <td>{{venta.descripcion}}</td>
                                        <td >
                                            <input type="number" v-model="venta.precio" @input="generarTotal(venta)">
                                        </td>
                                        <td><input type="number" v-model="venta.cantidad" @input="generarTotal(venta)"></td>
                                        <td><input type="number" v-model="venta.descuento" @input="generarTotal(venta)"></td>
                                        <td>
                                            <select v-model="almacen_id">
                                                <option disabled value="">Escoje un almacén</option>
                                                <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                            </select>
                                        </td>
                                        <td ><input v-model="venta.total" disabled></td>
                                        <td>
                                            <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(index)"><i class="icon-trash"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">Subtotal</div>
                                <div class="col"><input type="text" class="form-control" readonly="" v-model="subTotal"></div>

                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">Desc.Global</div>
                                <div class="col"><input type="text" name="DescGlobal" id="DescGlobal" class="form-control" v-model="desc_global" ></div>

                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">IgvTotal</div>
                                <div class="col"><input type="text" name="IgvTotal" id="IgvTotal" class="form-control" readonly="" v-model="igv"></div>

                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">Total</div>
                                <div class="col"><input type="text" name="Totales" id="Totales" class="form-control" readonly="" v-model="total"></div>
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
                <div class="modal fade" tabindex="-1" role="dialog" id="modalProducto" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                                    <datatable :eliminarItem="eliminarItem" :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos" @emitirEvProductos="recibirVenta" :listaVentasPadre="ventas" :controlador="controlador"></datatable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="modalClientes" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="card-body">
                            <div class="modal-content" >
                                <div class="modal-header">
                                    Clientes
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <datatable :eliminarItem="eliminarItem" :arrayItems="arrayClientes" :cabeceras="cabecerasCliente" :icono="iconos" @emitirEvProductos="recibirVenta" :listaVentasPadre="ventas" :controlador="controlador" :buscarClientes="buscarCliente"></datatable>
                                </div>
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
            arrayClientes: [],
            arrayItems: [],
            cabeceras: ['#', 'Codigo', 'Marca', 'Modelo', 'Precio', 'Descripcion', 'Imagen', 'Almacen','Acciones'],
            cabecerasCliente: ['#', 'Codigo', 'Razón social', 'Dirección', 'RUC', 'Acciones'],
            iconos: 'icon-plus',
            ventas: [],
            arrayAlmacen: [],
            almacen_id: '',
            total: 0,
            subTotal: 0,
            igv: 0,
            buscarCodigo: '',
            razon: '',
            ruc: '',
            direccion: '',
            controlador: 0, // 1 - productos, 2 - clientes
            vendedor: '',
            serie: 'F001',
            folio: '',
            fecha: '',
            tipo_venta: '',
            desc_global: 0,
            id_cabecera: 0


        }
    },
    mounted(){
        this.listarItem()
        this.listarClientes()
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
    },
    methods:{
        limpiarTabla(){
            this.ventas = []
        },
        generarTotal(item){
            item.total = item.precio * item.cantidad
            var lista = []
            var suma = 0
            for(var i = 0; i < this.ventas.length; i++){
                lista.push(this.ventas[i].total)
            }
            suma = lista.reduce((a, b) => a + b, 0)
            this.subTotal = suma
            this.igv = Math.round((this.subTotal * 0.18)*100)/100
            this.total =  Math.round((this.subTotal + this.igv)*100)/100 
           
        },
        recibirVenta(venta){
            this.ventas = venta
        },
        miTabla(){
            $( function () {
                $('#myTable').DataTable();
            } );
        },
        listarItem(){
            var urlItem = '/producto';
            axios.get(urlItem).then(response=>{
                this.arrayItems = response.data;
                this.miTabla();
            })
        },
        listarClientes(){
            var urlItem = '/cliente';
            axios.get(urlItem).then(response=>{
                this.arrayClientes = response.data;
                this.miTabla();
            })
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.codigo}?`)){
                axios.delete(`/producto/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)
                        
                    })
                    $( function () {
                        $('#myTable').DataTable().destroy();
                    } );
                    this.listarItem();
            }
        },
        abrirModalVenta(){
            this.seleccionarAlmacen();
            $('#modalVenta').modal('show');
        },
        abrirModalClientes(){
            this.controlador = 2
            $('#modalClientes').modal('show');
        },
        abrirModalAgregarProducto(){
            this.controlador = 1
            $('#modalProducto').modal('show');
        },
        seleccionarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.arrayAlmacen = response.data;
            })
        },
        eliminarProductoTabla(index){
            this.ventas.splice(index,1)
        },
        buscarCliente(codigo){
            axios.get(`/cliente/${codigo}`).then((response)=>{
                console.log(response.data[0])
                this.buscarCodigo = response.data[0].codigo
                this.razon = response.data[0].razon
                this.direccion = response.data[0].direccion
                this.ruc = response.data[0].ruc
            })
        },
        insertarCabecera(){
            var me = this;
            let formDatos = new FormData();
            formDatos.append('cod_cliente', this.buscarCodigo);
            formDatos.append('ruc_cliente', this.ruc);
            formDatos.append('dir_cliente', this.direccion);
            formDatos.append('razon', this.razon);
            formDatos.append('id_users', Number(this.vendedor));
            formDatos.append('fecha', this.fecha);
            formDatos.append('tipo_venta', this.tipo_venta);
            formDatos.append('serie', this.serie);
            formDatos.append('folio', this.folio);
            formDatos.append('sub_total', this.subTotal);
            formDatos.append('desc_global', this.desc_global);
            formDatos.append('igv_total', this.igv);
            formDatos.append('total', this.total);

            axios.post('/c_fact', formDatos).then((response)=>{
                this.id_cabecera = response.data
            })
            let formArray = new FormData();
            formArray.append('respuesta', this.ventas);
            axios.post('/d_fact', formArray).then((response)=>{
                //console.log(response.data)
            })
        }
        
    },
    watch:{
        ventas:{
            handler: function(){
                var lista = []
                var suma = 0
                for(var i = 0; i < this.ventas.length; i++){
                    lista.push(this.ventas[i].total)
                }
                suma = lista.reduce((a, b) => a + b, 0)
                this.subTotal = suma
                this.igv = Math.round((this.subTotal * 0.18)*100)/100
                this.total =  Math.round((this.subTotal + this.igv)*100)/100 
                },
            deep: true
        }
    }
}
</script>

<style scoped>

li{
    list-style-type: none;
}
.scroll{
    height: 300px;
    overflow-y: scroll
}
</style>
