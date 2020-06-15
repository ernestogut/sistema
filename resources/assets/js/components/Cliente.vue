<template>
    <div class="main">
        <b-card>
            <template v-slot:header>
            <div class="d-flex justify-content-between align-items-center p-0">
                <p>Clientes</p>
                <b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                    Nuevo cliente
                </b-button>
            </div>
            </template>
            <vue-datatable :cargando="cargando" :fields="fields" :items="arrayItems" :controlador="0" :factura="false" :funcionBoton="abrirModalModificar" :eliminarItem="eliminarItem"></vue-datatable>
            <b-modal size="lg" hide-footer v-model="estadoModal" id="modalCliente" :title="modoEditable?'Modificar cliente':'Nuevo cliente'" @hidden="resetModal">
            <form
                id="formA"
                action
                @submit.prevent="modoEditable?modificarCliente(id):agregarCliente()"
                enctype="multipart/form-data"
                >
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="codigo">Codigo</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoCliente.codigo"
                            placeholder="Ingrese el codigo del cliente"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="razon">Razón social</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoCliente.razon"
                            placeholder="Ingresa la razón social"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="direccion">Dirección</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoCliente.direccion"
                            placeholder="Ingrese la direccion del cliente"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label >Tipo de documento</label>
                            <select class="form-control" v-model="objetoCliente.id_tipo_doc">
                                <option v-for="documento in arrayDocumentos" :key="documento.id" :value="documento.id">{{documento.tipo_doc}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tipo_documento">Número de documento</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoCliente.num_documento"
                            placeholder="Ingrese el número de documento del cliente"
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
        this.listarDocumentos();
    },
    data(){
        return{
            cargando: false,
            arrayItems:[],
            arrayDocumentos: [],
            fields: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc" , class: "text-center"},
                { key: "codigo", label: "Codigo", sortable: true,class: "text-center", class: "text-center"},
                { key: "razon", label: "Razon social", sortable: true, class: "text-center"},
                { key: "direccion", label: "Direccion", sortable: true, class: "text-center"},
                { key: "tipo_documento", label: "Tipo documento", sortable: true, class: "text-center"},
                { key: "num_documento", label: "N° documento", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones", class: "text-center" }
            ],
            objetoCliente:{
                id_tipo_doc: null,
                codigo: null,
                razon: null,
                direccion: null,
                num_documento: null
            },
            modoEditable: false,
            estadoModal: false,
            id: ''
            
        }
    },
    methods:{
        obtenerItems(){
            this.cargando = true
            axios.get('/cliente').then(response=>{
                this.arrayItems = response.data
                this.cargando = false
            })
        },
        resetModal(){
            this.objetoCliente.id_tipo_doc = null
            this.objetoCliente.codigo = null
            this.objetoCliente.razon = null
            this.objetoCliente.direccion = null
            this.objetoCliente.num_documento = null 
        },
        abrirModalRegistro(){
            this.modoEditable = false
            this.$bvModal.show('modalCliente')
        },
        abrirModalModificar(item){
            for(var i in this.arrayItems){
                if(this.arrayItems[i].id == item.id){
                    this.objetoCliente.codigo = item.codigo
                    this.objetoCliente.razon = item.razon
                    this.objetoCliente.direccion = item.direccion
                    this.objetoCliente.num_documento = item.num_documento 
                }
            }
            this.id = item.id
            this.modoEditable = true
            this.$bvModal.show('modalCliente')
        },
        agregarCliente(){
            axios.post('/cliente', this.objetoCliente).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalCliente')
            })
        },
        modificarCliente(id){
            axios.put(`/cliente/${id}`, this.objetoCliente).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalCliente')
            })
        },
        listarDocumentos(){
            var urlItem = '/tipo_documento';
            axios.get(urlItem).then(response=>{
                this.arrayDocumentos = response.data;
            })
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.codigo}?`)){
                axios.delete(`/cliente/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)          
                    })
            }
        }
    }
}
</script>
