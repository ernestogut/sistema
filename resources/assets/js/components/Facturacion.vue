<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo</button>
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg1" id="modalVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ventas - Pedidos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form class="kt-form kt-form--label-right" name="oprd_form" id="oprd_form" method="post">
                        <div class="kt-portlet__body" data-scroll="true" data-height="200" data-scrollbar-shown="true">
                            <div class="row justify-content-start">

                                <div class="col-2 col-form-label">Cod.Cliente</div>
                                    <div class="input-group col">
                                    <div class="input-group-prepend btn_subbuscar" id="Btn_SubBuscar" idsubform="1">
                                    <span class="input-group-text" ><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                </div>
                                <input type="text" class="form-control" name="SdnCode" placeholder="Escriba ó Busque...">
                                </div>
                                <div class="col-2 col-form-label">Num.Doc</div>
                                <div class="col"><input type="text" name="Id" class="form-control" disabled="disabled"></div>

                                <div class="w-100"></div>
                                <div class="col-2 col-form-label">Razón Social</div>
                                <div class="col"><input type="text" name="SdnName" class="form-control"></div>

                                <div class="w-100"></div>

                                <div class="col-2 col-form-label">Vendedor</div>
                                <div class="col-3">
                                    <select required="required" class="form-control" name="EmpCode" id="EmpCode">
                                        <option value="1" selected="selected">Seleccione...</option>
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 col-form-label">Fecha Emi</div>
                                <div class="col"><input type="date" name="FechaReg" id="FechaReg" class="form-control" readonly="readonly"></div>

                                <div class="w-100"></div>
                                <div class="col-2 col-form-label">Tipo Venta</div>
                                <div class="col-3">
                                    <select name="PedTipo" required="required" class="form-control">
                                        <option value="A" selected="selected">Artículo</option>
                                        <option value="S">Servicio</option>
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 col-form-label">Folio</div>
                                <div class="col-2"><input type="text" name="Serie" class="form-control" placeholder="Serie"></div>
                                <div class="col-2"><input type="text" name="Folio" class="form-control" disabled="disabled" placeholder="Folio"></div>


                                <div class="w-100"></div>
                            </div>

                            <input type="button" name="addRow" id="addRow" value="Agrega1" style="display: none">

                            
                                <table id="D001" class="display pageResize D001" style="width:100%"></table>
                        </div>
                        <div class="row">
                            <div class="col-md-6 ml-auto">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg2">Agregar productos</button>
                            </div>
                        </div>
                        <table class="table table-bordered">
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
                                <tr v-for="(venta, index) in ventas">
                                    <th scope="row">{{index+1}}</th>
                                    <td>{{venta.codigo}}</td>
                                    <td>{{venta.descripcion}}</td>
                                    <td >
                                        <input type="number" v-model="venta.precio">
                                    </td>
                                    <td><input type="number" v-model="venta.cantidad"></td>
                                    <td><input type="number" v-model="venta.descuento"></td>
                                    <td>
                                        <select v-model="almacen_id">
                                            <option disabled value="">Escoje un almacén</option>
                                            <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                        </select>
                                    </td>
                                    <td ><input v-model="venta.precio*venta.cantidad" @change="probar" disabled></td>
                                    <td>
                                        <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(index)"><i class="icon-trash"></i></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">SubTotal</div>
                                <div class="col"><input type="text" name="SubTotal" id="SubTotal" class="form-control" readonly="">{{subTotal}}</div>

                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">Desc.Global</div>
                                <div class="col"><input type="text" name="DescGlobal" id="DescGlobal" class="form-control" ></div>

                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">IgvTotal</div>
                                <div class="col"><input type="text" name="IgvTotal" id="IgvTotal" class="form-control" readonly=""></div>

                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">Total</div>
                                <div class="col"><input type="text" name="Totales" id="Totales" class="form-control" readonly=""></div>
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
                <div class="modal fade bd-example-modal-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                                    <datatable :modalEditar="modalEditar" :eliminarItem="eliminarItem" :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos" @emitirEvProductos="recibirVenta"></datatable>
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
            arrayItems: [],
            cabeceras: ['#', 'Codigo', 'Marca', 'Modelo', 'Precio', 'Descripcion', 'Imagen', 'Almacen','Acciones'],
            iconos: 'icon-plus',
            ventas: [],
            descuento: 0,
            cantidad: 0,
            totalVenta: 0,
            arrayAlmacen: [],
            almacen_id: '',
            subTotal: ''
        }
    },
    mounted(){
        this.listarItem()
    },
    methods:{
        probar(){
            console.log('hola')
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
        seleccionarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.arrayAlmacen = response.data;
            })
        },
        eliminarProductoTabla(index){
            this.ventas.splice(index,1)
        }
        
    }
}
</script>

<style scoped>

li{
    list-style-type: none;
}
</style>
