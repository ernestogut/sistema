<template>
<main class="main">
    <b-container>
        <b-card header="Productos de la web">
            <b-row>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Ordenar"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            label-for="sortBySelect"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                <template v-slot:first>
                  <option value>-- ninguno --</option>
                </template>
              </b-form-select>
              <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                <option :value="false">Asc</option>
                <option :value="true">Desc</option>
              </b-form-select>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Buscar"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            label-for="filterInput"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                v-model="filter"
                type="search"
                id="filterInput"
                placeholder="Busca algo"
              ></b-form-input>
              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col sm="5" md="6" class="my-1">
          <b-form-group
            label="Por pagina"
            label-cols-sm="6"
            label-cols-md="4"
            label-cols-lg="3"
            label-align-sm="right"
            label-size="sm"
            label-for="perPageSelect"
            class="mb-0"
          >
            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
          </b-form-group>
        </b-col>

        <b-col sm="7" md="6" class="my-1">
          <b-pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
            align="fill"
            size="sm"
            class="my-0"
          ></b-pagination>
        </b-col>
      </b-row>

      <!-- Tabla principal -->
      <b-table
        show-empty
        small
        stacked="md"
        :busy="cargando"
        :items="arrayProductos"
        :fields="fields"
        :tbody-tr-class="rowClass"
        :current-page="currentPage"
        :per-page="perPage"
        :filter="filter"
        :filterIncludedFields="filterOn"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :sort-direction="sortDirection"
        @filtered="onFiltered"
        :emptyText="'No hay elementos para mostrar'"
        :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
      >
        <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>

        <template v-slot:cell(actions)="row">
          <b-button size="sm" @click="abrirModalCantidades(row.item)" class="mr-1">
            <i class="icon-pencil"></i>
          </b-button>
        </template>
        <template v-slot:table-busy>
          <div class="text-center text-danger my-2">
            <b-spinner class="align-middle"></b-spinner>
            <strong>Cargando...</strong>
          </div>
        </template>
      </b-table>      
        </b-card>
      <!-- User Interface controls -->
      <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCantidades">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Cantidades</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <spinner v-if="loadingCantidades"></spinner>
                        <div class="card-body" v-else-if="initiatedCantidades">
                            <ul class="list-group">
                                <li class="list-group-item cursor-pointer" v-for="almacen in arrayAlmacen" :key="almacen.id" @click="abrirModalModificarCantidad(almacen)">
                        
                                    <span v-if="almacen.editable">{{almacen.id}}. Existen {{almacen.cantidad}} unidad(es) en el almacen de {{almacen.descripcion}}</span>
                                    <span v-else>
                                        {{almacen.id}}. No existen unidades en el almacen de {{almacen.descripcion}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modalModificarCantidad">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{(modoEditable)?'Modificar cantidad':'Insertar Cantidad'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" @submit.prevent="(modoEditable)?modificarCantidad(idAlmacen):insertarCantidad()" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label >Cantidad</label>
                                <input type="number" class="form-control" id="formGroupExampleInput" placeholder="Ingresa la cantidad" v-model="objetoProdAlmacen.cantidad">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <spinner v-if="loading"></spinner>
                            <button type="submit" class="btn btn-primary" v-if="!modoEditable && !loading">Guardar</button>
                            <button type="submit" class="btn btn-primary" v-else-if="modoEditable && !loading">Actualizar</button>
                        </div>      
                    </form>  
                </div>
            </div>
        </div>
    </b-container>
</main>
  
</template>

<script>
export default {
    mounted(){
        this.listarItem();
        this.$store.dispatch('cargarAlmacen')
    },
    data(){
        return{
            cargando: false,
            fields: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc", class: "text-center" },
                {
                key: "codigo",
                label: "Codigo",
                sortable: true,
                class: "text-center"
                },
                {
                key: "producto",
                label: "Producto",
                sortable: true,
                class: "text-center"
                },
                {
                key: "precio",
                label: "Precio",
                sortable: true,
                class: "text-center"
                },
                {
                key: "stock",
                label: "Cantidad",
                sortable: true,
                class: "text-center"
                },
                { key: "actions", label: "acciones", class: "text-center" }
            ],
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            loading: false,
            initiated: false,
            loadingProductos: false,
            initiatedProductos: false,
            loadingCantidades: false,
            initiatedCantidades: false,
            arrayAlmacen: [],
            objetoProdAlmacen: {
                id_producto: null,
                id_almacen: null,
                cantidad: null,
            },
            idAlmacen: '',
            enAlmacen: false,
            modoEditable: false,
        }
    },
    computed:{
        sortOptions() {
        // Create an options list from our fields
        return this.fields
            .filter(f => f.sortable)
            .map(f => {
            return { text: f.label, value: f.key };
            });
        },
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen;
        }
    },
    methods:{
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.stock < 5) return 'bg-danger'
        },
        async listarItem(){
            this.cargando = true
            await this.$store.dispatch('cargarProductos').then(()=>{
                this.totalRows = this.arrayProductos.length
                this.cargando = false;
            });
        },
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        abrirModalCantidades(producto){
            this.loadingCantidades = true
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
                me.loadingCantidades = false
                me.initiatedCantidades = true
            })
            this.objetoProdAlmacen.id_producto = producto.codigo;
        },
        abrirModalModificarCantidad(almacen){
            if(almacen.editable){
                this.modoEditable = true;
            }else{
                this.modoEditable = false;
            }
            this.idAlmacen = almacen.id_inventario;
            this.objetoProdAlmacen.cantidad = null;
            $('#modalModificarCantidad').modal('show');
            this.objetoProdAlmacen.id_almacen = almacen.id
        },
        insertarCantidad(){
            this.loading = true
            let formDatos = new FormData()
            formDatos.append('id_producto', this.objetoProdAlmacen.id_producto)
            formDatos.append('id_almacen', this.objetoProdAlmacen.id_almacen)
            formDatos.append('cantidad', this.objetoProdAlmacen.cantidad)
            axios.post('/inventario', formDatos).then((response)=>{
                var productos = this.arrayProductos
                this.loading = false;
                this.initiated = true;
                for(var j = 0; j < this.arrayAlmacen.length; j++){
                    if(this.arrayAlmacen[j].id == this.objetoProdAlmacen.id_almacen){
                        var almacen = this.arrayAlmacen[j].descripcion
                        this.arrayAlmacen[j].cantidad = this.objetoProdAlmacen.cantidad
                        this.arrayAlmacen[j].editable = true;
                    }
                }
                for(var i = 0; i < productos.length; i++){
                    if(productos[i].codigo == this.objetoProdAlmacen.id_producto){
                        var producto = productos[i].producto
                        productos[i].stock = response.data
                    }
                }
                this.$store.dispatch('actualizarProductos', productos)
                Vue.swal({
                    title: `Añadiste ${this.objetoProdAlmacen.cantidad} unidades`,
                    text: `Añadiste ${this.objetoProdAlmacen.cantidad} unidades de ${producto} al almacen de ${almacen}`,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    }).then((result) => {
                    if (result.value) {
                        $('#modalModificarCantidad').modal('hide');
                    }
                })
                //alert('quedo')
            }).catch((error)=>{
                Vue.swal({
                    icon: 'error',
                    title: 'No se pudieron añadir las unidades',
                    text: 'Intentalo nuevamente más tarde',
                })
            })
        },
        modificarCantidad(idAlmacen){
            let formDatos = new FormData()
            this.loading = true
            formDatos.append('id_producto', this.objetoProdAlmacen.id_producto)
            formDatos.append('id_almacen', this.objetoProdAlmacen.id_almacen)
            formDatos.append('cantidad', this.objetoProdAlmacen.cantidad)
            formDatos.append("_method", "put");
            axios.post(`/inventario/${idAlmacen}`, formDatos).then((response)=>{
                var productos = this.arrayProductos
                this.loading = false;
                this.initiated = true;
                for(var j = 0; j < this.arrayAlmacen.length; j++){
                    if(this.arrayAlmacen[j].id == this.objetoProdAlmacen.id_almacen){
                        var almacen = this.arrayAlmacen[j].descripcion
                        this.arrayAlmacen[j].cantidad = this.objetoProdAlmacen.cantidad
                    }
                }
                for(var i = 0; i < productos.length; i++){
                    if(productos[i].codigo == this.objetoProdAlmacen.id_producto){
                        var producto = productos[i].producto
                        productos[i].stock = response.data
                    }
                }
                this.$store.dispatch('actualizarProductos', productos)
                Vue.swal({
                    title: `Modificación de stock exitoso!`,
                    text: `Modificaste a ${this.objetoProdAlmacen.cantidad} unidad(es) el stock de ${producto} en el almacen de ${almacen}`,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    }).then((result) => {
                    if (result.value) {
                        $('#modalModificarCantidad').modal('hide');
                    }
                })
                //alert('quedo')
            }).catch((error)=>{
                Vue.swal({
                    icon: 'error',
                    title: 'No se pudieron añadir las unidades',
                    text: 'Intentalo nuevamente más tarde',
                })
            })
        }
    }
}
</script>

<style>

.cursor-pointer{
    cursor: pointer;
}

</style>