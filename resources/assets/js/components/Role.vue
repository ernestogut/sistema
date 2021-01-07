<template>
    <div class="main">
        <b-card>
            <template v-slot:header>
            <div class="d-flex justify-content-between align-items-center p-0">
                <p>Roles</p>
                <b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                    Nuevo rol
                </b-button>
            </div>
            </template>
            <vue-datatable :cargando="cargando" :fields="fields" :items="arrayItems" :controlador="0" :factura="false" :funcionBoton="abrirModalModificar" :eliminarItem="eliminarItem"></vue-datatable>
            <b-modal size="lg" hide-footer v-model="estadoModal" id="modalRol" :title="modoEditable?'Modificar almacén':'Nuevo almacén'" @hidden="resetModal">
            <form
                id="formA"
                action
                @submit.prevent="modoEditable?modificarRol(id):agregarRol()"
                enctype="multipart/form-data"
                >
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoRol.nombre"
                            placeholder="Ingrese el nombre del rol"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="usuario">Descripción</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoRol.descripcion"
                            placeholder="Ingrese la descripción del rol"
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
                { key: "index", label: "#", sortable: true, sortDirection: "desc" , class: "text-center"},
                {
                key: "nombre",
                label: "Nombre",
                sortable: true,
                class: "text-center"
                },
                {
                key: "descripcion",
                label: "Descripción",
                sortable: true, 
                class: "text-center"
                },
                { key: "actions", label: "acciones" , class: "text-center"}
            ],
            objetoRol: {
                nombre: null,
                descripcion: null
            },
            modoEditable: false,
            estadoModal: false,
            id: ''
            
        }
    },
    methods:{
        obtenerItems(){
            this.cargando = true
            axios.get('/rol').then(response=>{
                this.arrayItems = response.data
                this.cargando = false
            })
        },
        resetModal(){
            this.objetoRol.descripcion = null,
            this.objetoRol.nombre = null
        },
        abrirModalRegistro(){
            this.modoEditable = false
            this.$bvModal.show('modalRol')
        },
        abrirModalModificar(item){
            this.id = item.id
            axios.get(`/rol/${item.id}`).then(response => {
                this.objetoRol.nombre = response.data.nombre;
                this.objetoRol.descripcion = response.data.descripcion;
                this.modoEditable = true;
                this.$bvModal.show('modalRol')
            });
        },
        agregarRol(){
            axios.post('/rol', this.objetoRol).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalRol')
            })
        },
        modificarRol(id){
            axios.put(`/rol/${id}`, this.objetoRol).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalRol')
            })
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el rol ${item.nombre}?`)){
                axios.delete(`/rol/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)          
                    })
            }
        }
    }
}
</script>