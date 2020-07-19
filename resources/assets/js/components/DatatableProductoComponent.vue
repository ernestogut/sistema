<template>
    <b-card>
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
            <b-form-input v-model="filter" type="search" id="filterInput" placeholder="Busca algo"></b-form-input>
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
      :items="arrayProductos"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :filterIncludedFields="filterOn"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      :tbody-tr-class="rowClass"
      @filtered="onFiltered"
      :emptyText="'No hay elementos para mostrar'"
      :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
    >
      <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>

      <template v-slot:cell(actions)="row">
        <div >
          <b-button
            variant="info"
            size="sm"
            @click="agregarProducto(row.item)"
          >
            <i class="icon-plus"></i>
          </b-button>
          <b-button
            variant="info"
            size="sm"
            @click="abrirModalCantidades(row.item)"
          >
            <i class="icon-eye"></i>
          </b-button>
          <b-button
            variant="info"
            size="sm"
            @click="abrirModalImagen(row.item)"
          >
            <i class="icon-picture"></i>
          </b-button>
        </div>
      </template>
      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Cargando...</strong>
        </div>
      </template>
    </b-table>

    <!-- User Interface controls -->
  </b-card>
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
        this.totalRows = this.arrayProductos.length
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
            fields: [
                { key: "codigo", label: "Codigo", sortable: true,class: "text-center"},
                { key: "producto", label: "Producto", sortable: true, class: "text-center"},
                { key: "precio", label: "Precio", sortable: true, class: "text-center"},
                { key: "stock", label: "Cantidad", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones" , class: "text-center"}
            ],
            enAlmacen: false,
            arrayAlmacen: [],
            ventas: [],
            color: 'btn-primary',
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            seleccionado: []
        }
    },
    computed:{
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
        sortOptions() {
        // Create an options list from our fields
            return this.fields
                .filter(f => f.sortable)
                .map(f => {
                return { text: f.label, value: f.key };
                });
        }
    },
    methods:{
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
        rowClass(item, type) {
            if (!item || type !== 'row') return
            if (item.stock < 5) return 'bg-danger'
        },
        agregarProducto(item){
            //document.getElementById(`producto${item.codigo}`).className = 'selected'
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
                if(i == 'codigo_padre'){
                    obj.codigo_padre = item[i]
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

</style>