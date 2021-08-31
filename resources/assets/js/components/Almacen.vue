<template>
    <div class="main">
        <b-card>
            <template v-slot:header>
            <div class="d-flex justify-content-between align-items-center p-0">
                <p>Almacenes</p>
                <b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                    Nuevo almacén
                </b-button>
            </div>
            </template>
            <vue-datatable :cargando="cargando" :fields="fields" :items="arrayItems" :controlador="0" :factura="false" :funcionBoton="abrirModalModificar" :eliminarItem="eliminarItem" :isFromAlmacen="true" :generarInventario="generarInventario"></vue-datatable>
            <b-modal size="lg" hide-footer v-model="estadoModal" id="modalAlmacen" :title="modoEditable?'Modificar almacén':'Nuevo almacén'" @hidden="resetModal">
            <form
                id="formA"
                action
                @submit.prevent="modoEditable?modificarAlmacen(id):agregarAlmacen()"
                enctype="multipart/form-data"
                >
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="codigo">Codigo</label>
                        <input
                        type="text"
                        class="form-control"
                        v-model="objetoAlmacen.codigo"
                        placeholder="Ingrese el codigo del almacén"
                        />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="descripcion">Descripción</label>
                        <input
                        type="text"
                        class="form-control"
                        v-model="objetoAlmacen.descripcion"
                        placeholder="Ingrese la descripcion del almacén"
                        />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="direccion">Dirección</label>
                        <input
                        type="text"
                        class="form-control"
                        v-model="objetoAlmacen.direccion"
                        placeholder="Ingrese la direccion del almacen"
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
import XLSX from 'xlsx';
import moment from 'moment'
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
                { key: "codigo", label: "Codigo", sortable: true, class: "text-center"},
                { key: "descripcion", label: "Descripcion", sortable: true, class: "text-center"},
                { key: "direccion", label: "Direccion", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones", class: "text-center" }
            ],
            objetoAlmacen:{
                codigo: null,
                descripcion: null,
                direccion: null
            },
            modoEditable: false,
            estadoModal: false,
            id: '',
            arrayReporte: [],
            show: false
            
        }
    },
    methods:{
        obtenerItems(){
            this.cargando = true
            axios.get('/almacen').then(response=>{
                this.arrayItems = response.data
                this.cargando = false
            })
        },
        resetModal(){
            this.objetoAlmacen.codigo = null,
            this.objetoAlmacen.descripcion = null,
            this.objetoAlmacen.direccion = null
        },
        abrirModalRegistro(){
            this.modoEditable = false
            this.$bvModal.show('modalAlmacen')
        },
        abrirModalModificar(item){
            this.id = item.id
            this.modoEditable = true
            this.$bvModal.show('modalAlmacen')
        },
        agregarAlmacen(){
            axios.post('/almacen', this.objetoAlmacen).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalAlmacen')
            })
        },
        modificarAlmacen(id){
            axios.put(`/almacen/${id}`, this.objetoAlmacen).then(response=>{
                this.obtenerItems();
                this.$bvModal.hide('modalAlmacen')
            })
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.codigo}?`)){
                axios.delete(`/almacen/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)          
                    })
            }
        },
        generarInventario(id, almacen_name){
            this.show = true
            axios.post(`almacen/generarInventario`, {
                almacen_id: id
            }).then(res=>{
                this.arrayReporte = res.data
                this.exportExcel(almacen_name);
                this.show = false;
                Vue.swal(
                    'Inventario generado!',
                    'El inventario se ha generado con éxito!',
                    'success'
                );
            }).catch(err=>{
                Vue.swal(
                    'Oops, hubo un error!',
                    'Hubo un error al generar el inventario, intenta más tarde!',
                    'error'
                );
            })
        },
        exportExcel: function (almacen_name) {
            let data = XLSX.utils.json_to_sheet(this.arrayReporte)
            const workbook = XLSX.utils.book_new()
            const filename = `stock-${almacen_name}-${moment().format('YYYY-MM-DD')}`
            XLSX.utils.book_append_sheet(workbook, data, filename)
            XLSX.writeFile(workbook, `${filename}.xlsx`)
            },
    }
}
</script>