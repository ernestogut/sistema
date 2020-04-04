export default {
    state: {
        arrayUsuarioLogeado: [],
        arrayUsuarios: [],
        arrayProductos: []

    },
    mutations:{
        SET_USUARIO_LOGEADO (state, arrayUsuarioLogeado) {
            state.arrayUsuarioLogeado = arrayUsuarioLogeado
        },
        SET_USUARIOS (state, arrayUsuarios) {
            state.arrayUsuarios = arrayUsuarios
        },
        SET_PRODUCTOS (state, arrayProductos) {
            state.arrayProductos = arrayProductos
        },
    },
    getters:{
        welcome(state){
            return state.welcomeMessage;
        },
        arrayUsuarioLogeado(state){
            return state.arrayUsuarioLogeado;
        },
        arrayUsuarios(state){
            return state.arrayUsuarios;
        },
        arrayProductos(state){
            return state.arrayProductos;
        },
        arrayUsuarioLogeado(state){
            return state.arrayUsuarioLogeado;
        }
    },
    actions:{
        cargarUsuarioLogeado({commit}){
            return axios.get('/user/logeado').then(response =>{
                    let arrayUsuarioLogeado = response.data
                    commit('SET_USUARIO_LOGEADO', arrayUsuarioLogeado)
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
        actualizarProductos({ commit }, arrayProductos) {
            commit("SET_PRODUCTOS", arrayProductos);
        },
        actualizarUsuarioLogeado({ commit }, arrayUsuarioLogeado) {
            commit("SET_USUARIO_LOGEADO", arrayUsuarioLogeado);
        },
    }
}