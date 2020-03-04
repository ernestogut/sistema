<template>
    <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap"  :id="idTabla" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle" v-for="cabecera of cabeceras" :key="cabecera.id">{{cabecera}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) of arrayItems" :key="item.key">
                        <th scope="row" class="text-center align-middle">{{index+1}}</th>
                        <td v-for="(value, key) in item" v-if="key !== 'id' && key !== 'created_at' && key != 'updated_at'" class="text-center align-middle">
                            <template v-if="key != 'imagen'">
                                {{value}}
                            </template>
                            <template v-else>
                                <img :src="(controlador!=1)?((value==null)?'images/0.jpg':`images/${value}`):((value==null)?'images/0.jpg':value)" alt="" width="60">
                            </template>
                        </td>
                        <td class="text-center align-middle">
                            <div v-if="!factura">
                                <span class="btn btn-primary btn-sm boton"  @click="(controlador!=5)?funcionConjunto(item):funcionBoton(item)"><i :class="icono"></i></span>
                                <span class="btn btn-primary btn-sm boton"  @click="funcionConjunto(item)" v-if="controlador == 5"><i class="icon-eye" ></i></span>
                                <span class="btn btn-danger btn-sm boton" v-if="controlador != 5" @click="eliminarItem(item, index)"><i class="icon-trash"></i></span>

                            </div>
                            <div v-else>
                                <span class="btn btn-primary btn-sm boton" @click="funcionConjunto(item)"><i class="icon-eye" ></i></span>
                                <span class="btn btn-danger btn-sm boton" v-if="controlador!=6"><i class="icon-trash"  ></i></span>
                                <span class="btn btn-danger btn-sm boton" v-if="controlador==6" @click="funcionConjuntoTrash(item)"><i :class="(item.estado==0)?'icon-heart':'icon-trash'"  ></i></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>
<script>
export default {
    created(){
        let datosLS = JSON.parse(localStorage.getItem('ventas'));
        if(datosLS === null){
            this.arrayVentas = []
        }else{
            this.arrayVentas = datosLS
        }
    },
    props:{
        funcionBoton: Function,
        funcionBotonTrash: Function,
        //modalEditar: Function,
        eliminarItem: Function,
        buscarClientes: Function,
        listarSeries: Function,
        arrayItems: Array,
        cabeceras: Array,
        listaVentasPadre: Array,
        icono: String,
        idTabla: String,
        controlador: Number,
        factura: Boolean
    },
    data(){
        return{
            arrayVentas: [],
            iconoHabilitarDeshabilitar: 0
        }
    },
    methods:{
        emitirEventoArray(ventas){
            this.$emit('emitirEvProductos', ventas)
        },
        emitirID_tipoComprobante(id){
            this.$emit('emitirEvId', id)
        },
        agregarProducto(item){
            var obj = {}
            var controlador = false
            
            for(const i in item){
                console.log(i)
                if(i == 'codigo'){
                    obj.codigo = item[i]
                }
                if(i == 'producto'){
                    obj.producto = item[i]
                }
                if(i == 'precio'){
                    obj.precio = item[i]
                }
                obj.cantidad = 1
                obj.descuento = 1
                obj.total = obj.cantidad*obj.precio
            }
            

            for(var j = 0; j < this.arrayVentas.length; j++){
                if(item.codigo == this.arrayVentas[j].codigo){
                    this.arrayVentas[j].cantidad = parseInt(this.arrayVentas[j].cantidad)
                    this.arrayVentas[j].cantidad += 1
                    this.arrayVentas[j].total = this.arrayVentas[j].cantidad*this.arrayVentas[j].precio
                    controlador = true
                    break;
                }else{
                    controlador = false
                }
            }
            if(controlador){
                this.emitirEventoArray(this.arrayVentas);
            }else{
                this.arrayVentas.push(obj)
                this.emitirEventoArray(this.arrayVentas);
            }
            localStorage.setItem('ventas', JSON.stringify(this.arrayVentas))
        },
        cerrarModalProductos(){
            $('#modalProducto').modal('hide');
        },
        funcionConjunto(item){
            // controlador = 0 -> editar, 1 -> agregar productos, 2 -> buscar productos, 3 -> listar series, 4 -> 
            if(this.controlador == 0){
                this.funcionBoton(item)
            }else if(this.controlador == 1){
                this.agregarProducto(item)
            }else if(this.controlador == 2){
                this.funcionBoton(item.codigo)
                $('#modalClientes').modal('hide');
            }else if(this.controlador == 3){
                this.funcionBoton(item.id)
            }else if(this.controlador == 5){
                this.emitirID_tipoComprobante(item.id)
                this.listarSeries(item.id)
                $('#modalSeries').modal('show')
            }else if(this.factura){
                this.funcionBoton(item)
            }
            /*else if(this.controlador == 4){
                this.funcionBoton(item)
            }*/
        },
        funcionConjuntoTrash(item){
            this.funcionBotonTrash(item)
        }
    },
    watch:{
        listaVentasPadre(){
            this.arrayVentas.length = this.listaVentasPadre.length
        }
    }
}
</script>

<style>

</style>