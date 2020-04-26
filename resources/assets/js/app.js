/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');


import moment from 'moment'
import VCharts from 'v-charts'
Vue.use(VCharts)
import VueSweetalert2 from 'vue-sweetalert2';
import Vuex from 'vuex';
import StoreData from './store';


Vue.use(Vuex);
Vue.use(VueSweetalert2);

Object.defineProperty(Vue.prototype, '$moment', {value: moment})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('spinner', require('vue-simple-spinner'));
Vue.component('cliente', require('./components/Cliente.vue').default);
Vue.component('proveedor', require('./components/Proveedor.vue').default);
Vue.component('role', require('./components/Role.vue').default);
Vue.component('dato', require('./components/Dato.vue').default);
Vue.component('user', require('./components/User.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);
Vue.component('dashboard', require('./components/Escritorio.vue').default);
Vue.component('almacen', require('./components/Almacen.vue').default);
Vue.component('modulo', require('./components/Modulo.vue').default);
Vue.component('compra', require('./components/Compras.vue').default);
Vue.component('producto', require('./components/Producto.vue').default);
Vue.component('facturacion', require('./components/Facturacion.vue').default);
Vue.component('datatable', require('./components/DataTable.vue').default);
Vue.component('datatable-productos', require('./components/DataTableProductos.vue').default);
Vue.component('comprobante', require('./components/Comprobante.vue').default);
Vue.component('tipo-comprobante', require('./components/TipoComprobante.vue').default);
Vue.component('ingreso-almacen', require('./components/IngresoAlmacen.vue').default);
Vue.component('producto-speed', require('./components/ProductoSpeed.vue').default);
Vue.component('pedido-speed', require('./components/PedidoSpeed.vue').default);
Vue.component('traspaso-almacen', require('./components/TraspasoAlmacen.vue').default);
Vue.component('cierre-caja', require('./components/CierreCaja.vue').default);
Vue.component('movimiento-caja', require('./components/MovimientoCaja.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const store = new Vuex.Store(StoreData);

var app = new Vue({
    el: '#app',
    store,
    mounted(){
        this.cargarUsuarioLog();
        //this.$store.dispatch('cargarProductos')
    },
    data :{
        menu : null,
        arrayProductos: [],
        notifications: []
    },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        }
    },
    methods:{
        async cargarUsuarioLog(){
            await this.$store.dispatch('cargarUsuarioLogeado')
            await this.$store.dispatch('cargarAlmacen')
            await this.$store.dispatch('cargarUsuarios').then(()=>{
                if(this.usuarioLogeado.idrole == 1){
                    this.menu = 1
                }else if(this.usuarioLogeado.idrole ==2){
                    this.menu = 5
                }
                //this.$store.dispatch('cargarProductos')
            })

        },
    },
});

