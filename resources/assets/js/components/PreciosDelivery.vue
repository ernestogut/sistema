<template>
    <div class="main">
        <b-card>
            <template v-slot:header>
            <div class="d-flex justify-content-between align-items-center p-0">
                <p>Precios de Delivery</p>
                <!--<b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                    Nuevo almacén
                </b-button>-->
            </div>
            </template>
            <vue-datatable :cargando="cargando" :fields="fields" :items="arrayItems" :controlador="0" :factura="false" :funcionBoton="abrirModalModificar" :eliminarItem="eliminarItem"></vue-datatable>
            <b-modal size="lg" hide-footer v-model="estadoModal" id="modalAlmacen" title="Modificar precio" @hidden="resetModal">
            <form
                id="formA"
                action
                @submit.prevent="modificarPrecio(id)"
                enctype="multipart/form-data"
                >
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="distrito">Distrito</label>
                        <input
                        id="distrito"
                        type="text"
                        class="form-control"
                        v-model="objetoAlmacen.distrito"
                        placeholder="Ingrese el distrito"
                        disabled
                        />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="precio">Precio delivery</label>
                        <input
                        type="number"
                        class="form-control"
                        v-model="objetoAlmacen.costo_envio"
                        placeholder="Ingrese el precio del delivery"
                        />
                    </div>
                    </div>
                    <b-button size="sm" @click="estadoModal = false">Cerrar</b-button>
                    <b-button size="sm"  v-show="!modoEditable" type="submit" variant="primary">Guardar</b-button>
                    <b-button size="sm"  v-show="modoEditable" type="submit" variant=primary>Actualizar</b-button>
                    </form>
                </b-modal> 
        </b-card>
    </div>
    <!--<modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="0" :idTabla="'myTable'"></modulo>-->
</template>
<script>

export default {
    mounted(){
        this.obtenerItems();
    },
    data(){
        return{
            cargando: false,
            arrayItems:[],
            fields: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "distrito", label: "Distrito", sortable: true, class: "text-center"},
                { key: "costo_envio", label: "Precio del delivery", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones", class: "text-center" }
            ],
            objetoAlmacen:{
                distrito: null,
                costo_envio: null
            },
            modoEditable: false,
            estadoModal: false,
            id: ''
            
        }
    },
    methods:{
        obtenerItems(){
            this.cargando = true
            axios.get('/precio_delivery').then(response=>{
                this.arrayItems = response.data
                this.cargando = false
            })
        },
        resetModal(){
            this.objetoAlmacen.distrito = null,
            this.objetoAlmacen.costo_envio = null
        },
        abrirModalRegistro(){
            this.modoEditable = false
            this.$bvModal.show('modalAlmacen')
        },
        abrirModalModificar(item){
            this.id = item.id
            this.objetoAlmacen.distrito = item.distrito
            this.modoEditable = true
            this.$bvModal.show('modalAlmacen')
        },
        agregarAlmacen(){
            axios.post('/almacen', this.objetoAlmacen).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalAlmacen')
            })
        },
        modificarPrecio(id){
            axios.put(`/precio_delivery/${id}`, this.objetoAlmacen).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalAlmacen')
            })
        },
        eliminarItem(item, index){
            console.log('item');
        }
    }
}
</script>