<template>
    <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap" id="myTable" style="width:100%">
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
                                <img :src="(value==null)?'images/0.jpg':`images/${value}`" alt="" width="60">
                            </template>
                        </td>
                        <td class="text-center align-middle">
                            <span class="btn btn-primary btn-sm boton"  data-dismiss="modal" @click="(controlador==0)?modalEditar(item):(controlador==1)?agregarProducto(item):buscarClientes(item.codigo)"><i :class="icono"></i></span>
                            <span class="btn btn-danger btn-sm boton" @click="eliminarItem(item, index)"><i class="icon-trash"></i></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>
<script>
export default {
    props:{
        modalEditar: Function,
        eliminarItem: Function,
        arrayItems: Array,
        cabeceras: Array,
        icono: String,
        listaVentasPadre: Array,
        controlador: Number,
        buscarClientes: Function
    },
    data(){
        return{
            arrayVentas: []
        }
    },
    methods:{
        emitirEventoArray(ventas){
            this.$emit('emitirEvProductos', ventas)
        },
        agregarProducto(item){
            console.log(item.codigo)
            var obj = {}
            var controlador = false
            for(const i in item){
                if(i == 'codigo'){
                    obj.codigo = item[i]
                }
                if(i == 'descripcion'){
                    obj.descripcion = item[i]
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
        },
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