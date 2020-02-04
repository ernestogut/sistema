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
                            <span class="btn btn-primary btn-sm boton" @click="modalEditar(item)"><i :class="icono"></i></span>
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
        icono: String
    }
}
</script>

<style>

</style>