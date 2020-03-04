<template>
<main class="main">
    <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap"  id="idTablaProductos" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center align-middle" >#</th>
                        <th scope="col" class="text-center align-middle" >Codigo</th>
                        <th scope="col" class="text-center align-middle" >Producto</th>
                        <th scope="col" class="text-center align-middle" >Precio</th>
                        <th scope="col" class="text-center align-middle" >Cantidad</th>
                        <!--<th scope="col" class="text-center align-middle" >Imagen</th>-->
                        <th scope="col" class="text-center align-middle" >Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(producto, index) of arrayProductos" :key="producto.key">
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
                        <td class="text-center align-middle">
                            <div>
                                <span class="btn btn-primary btn-sm boton"  ><i class="icon-pencil"></i></span>
                                <span class="btn btn-danger btn-sm boton"><i class="icon-trash"></i></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
</main>
  
</template>

<script>
export default {
    mounted(){
        this.listarItem();
    },
    data(){
        return{
            arrayProductos: []
        }
    },
    methods:{
        listarItem(){
            var urlItem = 'speed';
            axios.get(urlItem).then(response=>{
                this.arrayProductos = response.data;
                this.tablaProductos();
            })
        },
        tablaProductos(){
            $( function () {
                $('#idTablaProductos').DataTable({
                    searching: true
                });
            } );
        },
    }
}
</script>

<style>

</style>