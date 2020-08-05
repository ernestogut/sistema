export default {
    state: {
        arrayUsuarioLogeado: [],
        arrayUsuarios: [],
        arrayProductos: [],
        arrayProductosTotales: [],
        arrayPedidos: [],
        arrayVentas: [],
        arrayAlmacen: [],
        arrayDocumentos: [],
        tablaVenta: [],
        arrayVariaciones: [],
        productoVariacion: '',
        show: false

    },
    mutations:{
        SET_USUARIO_LOGEADO (state, arrayUsuarioLogeado) {
            state.arrayUsuarioLogeado = arrayUsuarioLogeado;
        },
        SET_USUARIOS (state, arrayUsuarios) {
            state.arrayUsuarios = arrayUsuarios;
        },
        SET_PRODUCTOS (state, arrayProductos) {
            state.arrayProductos = arrayProductos;
        },
        SET_PEDIDOS (state, arrayPedidos) {
            state.arrayPedidos = arrayPedidos;
        },
        SET_VENTAS (state, arrayVentas){
            state.arrayVentas = arrayVentas;
        },
        SET_ALMACENES (state, arrayAlmacen){
            state.arrayAlmacen = arrayAlmacen;
        },
        SET_DOCUMENTOS (state, arrayDocumentos){
            state.arrayDocumentos = arrayDocumentos;
        },
        SET_TABLAVENTAS (state, tablaVenta){
            state.tablaVenta = tablaVenta;
        },
        SET_VARIACIONES (state, arrayVariaciones){
            state.arrayVariaciones = arrayVariaciones;
        },
        SET_PRODUCTOVARIACION (state, productoVariacion){
            state.productoVariacion = productoVariacion;
        },
        SET_SHOW (state, show){
            state.show = show;
        },
        SET_PRODUCTOSTOTALES (state, arrayProductosTotales){
            state.arrayProductosTotales = arrayProductosTotales;
        }
    },
    getters:{
        arrayUsuarioLogeado(state){
            return state.arrayUsuarioLogeado;
        },
        arrayUsuarios(state){
            return state.arrayUsuarios;
        },
        arrayProductos(state){
            return state.arrayProductos;
        },
        arrayPedidos(state){
            return state.arrayPedidos;
        },
        arrayAlmacen(state){
            return state.arrayAlmacen;
        },
        arrayDocumentos(state){
            return state.arrayDocumentos;
        },
        arrayVentas(state){
            return state.arrayVentas;
        },
        tablaVenta(state){
            return state.tablaVenta;
        },
        arrayVariaciones(state){
            return state.arrayVariaciones;
        },
        productoVariacion(state){
            return state.productoVariacion;
        },
        show(state){
            return state.show;
        },
        arrayProductosTotales(state){
            return state.arrayProductosTotales;
        }

    },
    actions:{
        cargarUsuarioLogeado({commit}){
            return axios.get('/user/logeado').then(response =>{
                    let arrayUsuarioLogeado = response.data
                    commit('SET_USUARIO_LOGEADO', arrayUsuarioLogeado)
                })
        },
        cargarAlmacen({commit}){
            return axios.get('/almacen/').then(response =>{
                    let arrayAlmacen = response.data
                    commit('SET_ALMACENES', arrayAlmacen)
                })
        },
        cargarDocumentos({commit}){
            return axios.get('/tipo_documento').then(response=>{
                let arrayDocumentos = response.data
                commit('SET_DOCUMENTOS', arrayDocumentos)
            })
        },
        cargarVentas({commit}){
            return axios.get('/reporte_venta').then((response)=>{
                let arrayVentas = response.data
                commit('SET_VENTAS', arrayVentas)
                
            })
        },
        cargarUsuarios({commit}){
            return axios.get('/user/obtenerUsuarios').then(response =>{
                    let arrayUsuarios = response.data
                    commit('SET_USUARIOS', arrayUsuarios)
                })
        },
        cargarProductos({commit}, id_almacen){
            return axios.get(`/speed/${id_almacen}/obtenerProductosPorAlmacen`).then(response =>{
                    let arrayProductos = response.data
                    commit('SET_PRODUCTOS', arrayProductos)
                })
        },
        cargarProductosTotales({commit}){
            return axios.get(`/speed`).then(response =>{
                    let arrayProductos = response.data
                    commit('SET_PRODUCTOS', arrayProductos)
                })
        },
        cargarPedidos({commit}){
            return axios.get('/orden_tienda').then(response =>{
                    let arrayPedidos = response.data
                    commit('SET_PEDIDOS', arrayPedidos)
                });
        },
        actualizarProductos({ commit }, arrayProductos) {
            commit("SET_PRODUCTOS", arrayProductos);
        },
        actualizarVentas({ commit }, arrayVentas) {
            commit("SET_VENTAS", arrayVentas);
        },
        actualizarTablaVentas({ commit }, tablaVenta) {
            commit("SET_TABLAVENTAS", tablaVenta);
        },
        actualizarVariaciones({ commit }, arrayVariaciones) {
            commit("SET_VARIACIONES", arrayVariaciones);
        },
        actualizarProductoVariacion({ commit }, productoVariacion) {
            commit("SET_PRODUCTOVARIACION", productoVariacion);
        },
        actualizarShow({ commit }, show) {
            commit("SET_SHOW", show);
        },
        actualizarUsuarioLogeado({ commit }, arrayUsuarioLogeado) {
            commit("SET_USUARIO_LOGEADO", arrayUsuarioLogeado);
        },
    }
}