<template>
    <div class="main">
        <b-card>
            <template v-slot:header>
            <div class="d-flex justify-content-between align-items-center p-0">
                <p>Proveedores</p>
                <b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                    Nuevo proveedor
                </b-button>
            </div>
            </template>
            <vue-datatable :cargando="cargando" :fields="fields" :items="arrayItems" :controlador="0" :factura="false" :funcionBoton="abrirModalModificar" :eliminarItem="eliminarItem"></vue-datatable>
            <b-modal size="lg" hide-footer v-model="estadoModal" id="modalProveedor" :title="modoEditable?'Modificar proveedor':'Nuevo proveedor'" @hidden="resetModal">
            <form
                id="formA"
                action
                @submit.prevent="modoEditable?modificarProveedor(id):agregarProveedor()"
                enctype="multipart/form-data"
                >
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="codigo">Codigo</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoProveedor.codigo"
                            placeholder="Ingrese el codigo del proveedor"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="razon">Razón social</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoProveedor.razon"
                            placeholder="Ingresa la razón social"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="razon">Correo electrónico</label>
                            <input
                            type="email"
                            class="form-control"
                            v-model="objetoProveedor.correo"
                            placeholder="Ingresa el correo electrónico"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="razon">Número de teléfono</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoProveedor.telefono_contacto"
                            placeholder="Ingresa el número de teléfono"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="direccion">Dirección</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoProveedor.direccion"
                            placeholder="Ingrese la direccion del proveedor"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label >Tipo de documento</label>
                            <select class="form-control" v-model="objetoProveedor.id_tipo_doc">
                                <option v-for="documento in arrayDocumentos" :key="documento.id" :value="documento.id">{{documento.tipo_doc}}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tipo_documento">Número de documento</label>
                            <input
                            type="text"
                            class="form-control"
                            v-model="objetoProveedor.num_documento"
                            placeholder="Ingrese el número de documento del proveedor"
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
                { key: "codigo", label: "Codigo", sortable: true,class: "text-center"},
                { key: "razon", label: "Razón social", sortable: true, class: "text-center"},
                { key: "", label: "Razón social", sortable: true, class: "text-center"},
                { key: "num_documento", label: "N° documento", sortable: true, class: "text-center"},
                { key: "correo", label: "Correo", sortable: true, class: "text-center"},
                { key: "telefono_contacto", label: "Telefono contacto", sortable: true, class: "text-center"},
                { key: "direccion", label: "Dirección", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones" , class: "text-center"}
            ],
            objetoProveedor:{
                id_tipo_doc: null,
                codigo: null,
                razon: null,
                direccion: null,
                num_documento: null,
                correo: null,
                telefono_contacto: null
            },
            modoEditable: false,
            estadoModal: false,
            id: ''
            
        }
    },
    methods:{
        obtenerItems(){
            this.cargando = true
            axios.get('/proveedor').then(response=>{
                this.arrayItems = response.data
                this.cargando = false
            })
        },
        resetModal(){
            this.objetoProveedor.id_tipo_doc = null
            this.objetoProveedor.codigo = null
            this.objetoProveedor.razon = null
            this.objetoProveedor.direccion = null
            this.objetoProveedor.num_documento = null 
            this.objetoProveedor.correo = null,
            this.objetoProveedor.telefono_contacto = null
        },
        abrirModalRegistro(){
            this.modoEditable = false
            this.$bvModal.show('modalProveedor')
        },
        abrirModalModificar(item){
            for(var i in this.arrayItems){
                if(this.arrayItems[i].id == item.id){
                    this.objetoProveedor.codigo = item.codigo
                    this.objetoProveedor.razon = item.razon
                    this.objetoProveedor.direccion = item.direccion
                    this.objetoProveedor.num_documento = item.num_documento 
                    this.objetoProveedor.correo = item.correo
                    this.objetoProveedor.telefono_contacto = item.telefono_contacto
                }
            }
            this.id = item.id
            this.modoEditable = true
            this.$bvModal.show('modalProveedor')
        },
        agregarProveedor(){
            axios.post('/proveedor', this.objetoProveedor).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalProveedor')
            })
        },
        modificarProveedor(id){
            axios.put(`/proveedor/${id}`, this.objetoProveedor).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalProveedor')
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
                axios.delete(`/proveedor/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)          
                    })
            }
        }
    }
}
</script>





<!--<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="0" :idTabla="'myTable'"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Proveedores',
            tituloModal: 'proveedor',
            ruta: '/proveedor',
            cabeceras: ['Acciones', '#', 'Código', 'Tipo de documento', 'Num documento', 'Razon',   'Correo', 'Telef contacto'],
            variables: [
                {
                    for: 'codigo',
                    type: 'codigo',
                    name: 'codigo',
                    id: 'codigo',
                    placeholder: 'Ingrese el codigo',
                    var: '',
                    titulo: 'Codigo'
                },
                {
                    for: 'num_documento',
                    type: 'num_documento',
                    name: 'num_documento',
                    id: 'num_documento',
                    placeholder: 'Ingrese el n° documento',
                    var: '',
                    titulo: 'Numero de documento'
                },
                {
                    for: 'razon',
                    type: 'razon',
                    name: 'razon',
                    id: 'razon',
                    placeholder: 'Ingresa la razon social',
                    var: '',
                    titulo: 'Razon'
                },
                {
                    for: 'correo',
                    type: 'correo',
                    name: 'correo',
                    id: 'correo',
                    placeholder: 'Ingrese el correo',
                    var: '',
                    titulo: 'Correo'
                },
                {
                    for: 'telefono_contacto',
                    type: 'telefono_contacto',
                    name: 'telefono_contacto',
                    id: 'telefono_contacto',
                    placeholder: 'Ingrese el teléfono de contacto',
                    var: '',
                    titulo: 'Teléfono de contacto'
                },
                {
                    for: 'direccion',
                    type: 'direccion',
                    name: 'direccion',
                    id: 'direccion',
                    placeholder: 'Ingrese la direccion',
                    var: '',
                    titulo: 'Direccion'
                }
            ],
        }
    },
}
</script>-->