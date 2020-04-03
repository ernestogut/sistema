/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');


import moment from 'moment'
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueSweetalert2);
Object.defineProperty(Vue.prototype, '$moment', {value: moment})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('spinner', require('vue-simple-spinner'));


Vue.component('categoria', require('./components/Categoria.vue').default);
Vue.component('articulo', require('./components/Articulo.vue').default);
Vue.component('cliente', require('./components/Cliente.vue').default);
Vue.component('proveedor', require('./components/Proveedor.vue').default);
Vue.component('role', require('./components/Role.vue').default);
Vue.component('dato', require('./components/Dato.vue').default);
Vue.component('user', require('./components/User.vue').default);
Vue.component('ingreso', require('./components/Ingreso.vue').default);
Vue.component('venta', require('./components/Venta.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('consultaingreso', require('./components/ConsultaIngreso.vue').default);
Vue.component('consultaventa', require('./components/ConsultaVenta.vue').default);
Vue.component('notification', require('./components/Notification.vue').default);
Vue.component('almacen', require('./components/Almacen.vue').default);
Vue.component('modulo', require('./components/Modulo.vue').default);
Vue.component('producto', require('./components/Producto.vue').default);
Vue.component('componente-prueba', require('./components/ComponentePrueba.vue').default);
Vue.component('facturacion', require('./components/Facturacion.vue').default);
Vue.component('datatable', require('./components/DataTable.vue').default);
Vue.component('comprobante', require('./components/Comprobante.vue').default);
Vue.component('tipo-comprobante', require('./components/TipoComprobante.vue').default);
Vue.component('ingreso-almacen', require('./components/IngresoAlmacen.vue').default);
Vue.component('producto-speed', require('./components/ProductoSpeed.vue').default);
Vue.component('traspaso-almacen', require('./components/TraspasoAlmacen.vue').default);
Vue.component('cierre-caja', require('./components/CierreCaja.vue').default);
Vue.component('apertura-caja', require('./components/AperturaCaja.vue').default);
Vue.component('movimiento-caja', require('./components/MovimientoCaja.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



var app = new Vue({
    el: '#app',
    data :{
        menu : 0,
        arrayProductos: [],
        notifications: []
    },
    created(){
        // let me = this;
        // axios.post('notification/get').then(function(response){
        //     //console.log(response.data);
        //     me.notifications = response.data;

        // }).catch(function(error){
        //     console.log(error);
        // })

        // var userId = $('meta[name="userId').attr('content');
        // Echo.private('App.User.' + userId).notification((notification) => {
        //     me.notifications.unshift(notification);
        // });
    }
});

