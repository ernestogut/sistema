<template>
    <div class="card-body">
        <table  class="table table-hover table-bordered dt-responsive nowrap"  id="myTableProductos" style="width:100%">
            <thead>
                <tr>
                    <th scope="col" class="text-center align-middle" v-for="cabecera of cabeceras" :key="cabecera.id">{{cabecera}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(producto) of arrayProductos" :key="producto.key" :id="`producto${producto.codigo}`">
                    <td class="text-center align-middle">
                        <div>
                            <span :class="['btn', 'btn-primary', 'btn-sm', 'boton']" id="botonAgregar" @click="agregarProducto(producto)"><i class="icon-plus" ></i></span>
                            <span class="btn btn-success btn-sm boton" @click="abrirModalCantidades(producto)"><i class="icon-eye"></i></span>
                            <span class="btn btn-success btn-sm boton" @click="abrirModalImagen(producto)"><i class="icon-picture"></i></span>
                        </div>
                    </td>
                    <td class="text-center align-middle">{{producto.codigo}}</td>
                    <td class="text-center align-middle">{{producto.producto}}</td>
                    <td class="text-center align-middle">{{producto.precio}}</td>
                    <td class="text-center align-middle">{{producto.stock}}</td>
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
            this.ventas = []
        }else{
            this.ventas = datosLS
        }
    },
    mounted(){
        window.onbeforeunload = function (e) {
            var e = e || window.event;

            // For IE and Firefox
            if (e) {
                this.ventas = []
                localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            }

            // For Safari
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        };
    },
    props:{
        arrayAlmacenFijo: Array,
        abrirModalImagen: Function,
        ingreso: Boolean
    },
    data(){
        return{
            cabeceras: ['Acciones', 'Codigo', 'Producto', 'Precio', 'Cantidad'],
            enAlmacen: false,
            arrayAlmacen: [],
            ventas: [],
            color: 'btn-primary'
        }
    },
    computed:{
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
    },
    methods:{
        agregarProducto(item){
            document.getElementById(`producto${item.codigo}`).className = 'selected'
            /*var estilos = document.querySelector(`.codigo${item.codigo}`).style
            estilos.background = 'black'
            estilos.borderColor = 'black'
            estilos.color = 'white'*/
            //this.estiloCeldaSeleccionada = 'table-success'
            var obj = {}
            var controlador = false
            
            for(const i in item){
                if(i == 'codigo'){
                    obj.codigo = item[i]
                }
                if(i == 'producto'){
                    obj.producto = item[i]
                }
                if(i == 'precio'){
                    obj.precio = item[i]
                }
                if(this.ingreso){
                    var objetoCantidad = {}
                    var arrayCantidad = []
                    var cantidadTotal = 0
                    for(var j = 0; j < this.arrayAlmacenFijo.length; j++){
                        objetoCantidad.id_almacen = this.arrayAlmacenFijo[j].id
                        objetoCantidad.cantidad = 0
                        objetoCantidad.nombre = this.arrayAlmacenFijo[j].descripcion
                        arrayCantidad.push(objetoCantidad)
                        objetoCantidad = {}
                    }
                    obj.cantidades = arrayCantidad;
                    for(var k = 0; k < obj.cantidades.length; k++){
                        cantidadTotal += obj.cantidades[k].cantidad  
                    }
                    obj.descuento = 1
                    obj.total = cantidadTotal*obj.precio
                }else{
                    obj.almacen = 1;
                    obj.cantidad = 1
                    obj.descuento = 1
                    obj.total = obj.cantidad*obj.precio
                }
            }
            
            for(var j = 0; j < this.ventas.length; j++){
                if(item.codigo == this.ventas[j].codigo){
                    if(!this.ingreso){
                        this.ventas[j].cantidad = parseInt(this.ventas[j].cantidad)
                        this.ventas[j].cantidad += 1
                        this.ventas[j].total = this.ventas[j].cantidad*this.ventas[j].precio
                        controlador = true
                        break;
                    }
                }else{
                    controlador = false
                }
            }
            if(controlador){
                this.$emit('emitirEvProductos', this.ventas)
            }else{
                this.ventas.push(obj)
                this.$emit('emitirEvProductos', this.ventas)
            }
            localStorage.setItem('ventas', JSON.stringify(this.ventas))
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
                me.$emit('emitirEvArrayAlm', me.arrayAlmacen);
            })
        },
    },
}
</script>

<style>

.btn{
    cursor: pointer;
}


</style>