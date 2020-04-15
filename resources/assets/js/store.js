export default {
    state: {
        arrayUsuarioLogeado: [],
        arrayUsuarios: [],
        arrayProductos: [],
        arrayPedidos: [],
        arrayVentas: [],
        arrayAlmacen: []

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
        arrayVentas(state){
            return state.arrayVentas;
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
            return axios.get('/almacen').then(response =>{
                    let arrayAlmacen = response.data
                    commit('SET_ALMACENES', arrayAlmacen)
                })
        },
        cargarVentas({commit}){
            return axios.get('/reporte_venta').then((response)=>{
                let arrayVentas = response.data
                commit('SET_VENTAS', arrayVentas)
                
            })
        },
        cargarUsuarios({commit}){
            return axios.get('/user').then(response =>{
                    let arrayUsuarios = response.data
                    commit('SET_USUARIOS', arrayUsuarios)
                })
        },
        cargarProductos({commit}){
            return axios.get('/speed').then(response =>{
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
        actualizarUsuarioLogeado({ commit }, arrayUsuarioLogeado) {
            commit("SET_USUARIO_LOGEADO", arrayUsuarioLogeado);
        },
    }
}