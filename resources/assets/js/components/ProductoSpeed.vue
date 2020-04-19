<template>
<main class="main">
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-row justify-content-between align-items-center">  
                <h4>Productos de la web</h4>
            </div>
        </div>
        <spinner v-if="loading"></spinner>
        <div class="card-body" v-else-if="initiated">
        
            <table  class="table table-hover table-bordered dt-responsive nowrap"  id="idTablaProductos" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle" >Acciones</th>
                        <th scope="col" class="text-center align-middle" >#</th>
                        <th scope="col" class="text-center align-middle" >Codigo</th>
                        <th scope="col" class="text-center align-middle" >Producto</th>
                        <th scope="col" class="text-center align-middle" >Precio</th>
                        <th scope="col" class="text-center align-middle" >Cantidad</th>
                        <!--<th scope="col" class="text-center align-middle" >Imagen</th>-->
                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(producto, index) of arrayProductos" :key="producto.key" :class="(producto.stock < 5)?'table-danger':''">
                        <td class="text-center align-middle">
                            <div>
                                <span class="btn btn-primary btn-sm boton" @click="abrirModalCantidades(producto)" ><i class="icon-pencil"></i></span>
                                <span class="btn btn-danger btn-sm boton"><i class="icon-trash"></i></span>
                            </div>
                        </td>
                        <th scope="row" class="text-center align-middle">{{index+1}}</th>
                        <td class="text-center align-middle">
                            {{producto.codigo}}
                        </td >
                        <td class="text-center align-middle">
                            {{producto.producto}}
                        </td>
                        <td class="text-center align-middle">
                            {{producto.precio}}
                        </td>
                        <td class="text-center align-middle">
                            {{producto.stock}}
                        </td>
                        <!--<td class="text-center align-middle">
                            <img :src="(producto.imagen==null)?'images/0.jpg':producto.imagen" alt="" width="60">
                        </td>-->
                        
                    </tr>
                </tbody>
            </table>
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
                                <li class="list-group-item cursor-pointer" v-for="almacen in arrayAlmacen" :key="almacen.id" @click="abrirModalModificarCantidad(almacen)">
                        
                                    <span v-if="almacen.editable">{{almacen.id}}. Existen {{almacen.cantidad}} unidad(es) en el almacen de {{almacen.descripcion}}</span>
                                    <span v-else>
                                        {{almacen.id}}. No existen unidades en el almacen de {{almacen.descripcion}}
                                    </span>
                                    
                                        
                                    <!--<div>
                                        <span class="badge badge-primary badge-pill" id="eliminar" @click="eliminarTiempo(item, index)" v-show="!modoWcaRecibido || item.tiempoReal < 3000" >x</span>
                                    </div>-->
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalModificarCantidad">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{(modoEditable)?'Modificar cantidad':'Insertar Cantidad'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" @submit.prevent="(modoEditable)?modificarCantidad(idAlmacen):insertarCantidad()" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Cantidad</label>
                                <input type="number" class="form-control" id="formGroupExampleInput" placeholder="Ingresa la cantidad" v-model="objetoProdAlmacen.cantidad">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <spinner v-if="loading"></spinner>
                            <button type="submit" class="btn btn-primary" v-if="!modoEditable && !loading">Guardar</button>
                            <button type="submit" class="btn btn-primary" v-else-if="modoEditable && !loading">Actualizar</button>
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
        this.listarItem();
        this.$store.dispatch('cargarAlmacen')
    },
    data(){
        return{
            loading: false,
            initiated: false,
            arrayAlmacen: [],
            objetoProdAlmacen: {
                id_producto: null,
                id_almacen: null,
                cantidad: null,
            },
            idAlmacen: '',
            enAlmacen: false,
            modoEditable: false,
        }
    },
    computed:{
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen;
        }
    },
    methods:{
        async listarItem(){
            this.loading = true
            await this.$store.dispatch('cargarProductos').then(()=>{
                this.loading = false;
                this.initiated = true;
                this.tablaProductos();
            });
        },
        tablaProductos(){
            $( function () {
                $('#idTablaProductos').DataTable({
                    searching: true
                });
            } );
        },
        abrirModalCantidades(producto){
            //this.loading = true
            $('#modalCantidades').modal('show');
            let me= this;
            var contador = 0
            axios.get(`almacen/${producto.codigo}/cantidadesAlmacen`).then(function (response){
                if(response.data.length < 1){
                    me.enAlmacen = false;
                    me.arrayAlmacen = me.arrayAlmacenFijo
                }else{
                    if(response.data.length == me.arrayAlmacenFijo.length){
                        me.enAlmacen = true;
                        me.arrayAlmacen = response.data;
                    }else{
                        for(var i = 0; i < me.arrayAlmacenFijo.length; i++){                           
                            contador = 0                           
                            for(var j = 0; j < response.data.length; j++){
                                if(response.data[j].id != me.arrayAlmacenFijo[i].id){
                                    contador += 1
                                    if(contador == response.data.length){
                                        me.arrayAlmacen = response.data;
                                        me.arrayAlmacen.push(me.arrayAlmacenFijo[i])
                                        me.arrayAlmacen[i].editable = false;
                                    }
                                }
                            }
                        }
                    }
                }
            })
            this.objetoProdAlmacen.id_producto = producto.codigo;
        },
        abrirModalModificarCantidad(almacen){
            if(almacen.editable){
                this.modoEditable = true;
            }else{
                this.modoEditable = false;
            }
            this.idAlmacen = almacen.id_inventario;
            this.objetoProdAlmacen.cantidad = null;
            $('#modalModificarCantidad').modal('show');
            this.objetoProdAlmacen.id_almacen = almacen.id
        },
        insertarCantidad(){
            this.loading = true
            let formDatos = new FormData()
            formDatos.append('id_producto', this.objetoProdAlmacen.id_producto)
            formDatos.append('id_almacen', this.objetoProdAlmacen.id_almacen)
            formDatos.append('cantidad', this.objetoProdAlmacen.cantidad)
            axios.post('/inventario', formDatos).then((response)=>{
                this.loading = false;
                this.initiated = true;
                $('#modalModificarCantidad').modal('hide');
                //alert('quedo')
            }).catch((error)=>{
                alert('error')
            })
        },
        modificarCantidad(idAlmacen){
            let formDatos = new FormData()
            this.loading = true
            formDatos.append('id_producto', this.objetoProdAlmacen.id_producto)
            formDatos.append('id_almacen', this.objetoProdAlmacen.id_almacen)
            formDatos.append('cantidad', this.objetoProdAlmacen.cantidad)
            formDatos.append("_method", "put");
            axios.post(`/inventario/${idAlmacen}`, formDatos).then((response)=>{
                this.loading = false;
                this.initiated = true;
                $('#modalModificarCantidad').modal('hide');
                //alert('quedo')
            }).catch((error)=>{
                alert('error')
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