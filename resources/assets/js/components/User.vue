<template>
    <main class="main">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between align-items-center">  
                        <h4>Mantenimiento de usuario</h4>
                            <!-- Abrir modal -->
                        <button type="button" class="btn btn-primary boton" data-toggle="modal" @click="abrirModalRegistrar()">
                         Nuevo
                        </button>
                            <!-- Modal -->
                    </div>
                    <div class="modal fade" id="modalRegistroItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 v-show="!modoEditable" class="modal-title" id="exampleModalLabel">Nuevo usuario</h5>
                                    <h5 v-show="modoEditable" class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formA" action="" @submit.prevent="modoEditable?editarUsuario(id):agregarUsuario()" enctype="multipart/form-data">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="usuario">Usuario (login)</label>
                                                <input type="text" class="form-control"
                                                v-model="objetoUsuario.usuario" placeholder="Ingrese el nickname del usuario">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="nombre">Nombre</label>
                                                <input type="text" class="form-control"
                                                v-model="objetoUsuario.nombre" placeholder="Ingrese el nombre del usuario">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="apellido">Apellido</label>
                                                <input type="text" class="form-control"
                                                v-model="objetoUsuario.apellido" placeholder="Ingrese el apellido del usuario">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="contrasena">Contraseña (login)</label>
                                                <input type="password" class="form-control"
                                                v-model="objetoUsuario.password" placeholder="Ingrese la contraseña del usuario">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label >Rol de usuario</label>
                                                <select class="form-control" v-model="objetoUsuario.idrole">
                                                    <option v-for="rol in arrayRoles" :key="rol.id" :value="rol.id">{{rol.nombre}}</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary boton" data-dismiss="modal">Cerrar</button>
                                                <button v-show="!modoEditable" type="submit" class="btn btn-primary boton">Guardar</button>
                                                <button v-show="modoEditable" type="submit" class="btn btn-primary boton">Actualizar</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <spinner v-if="loading"></spinner>
                <div class="card-body" v-else-if="initiated">
                    <table  class="table table-hover table-bordered dt-responsive nowrap"  id="myTableUsuarios" style="width:100%">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center align-middle" v-for="cabecera of cabeceras" :key="cabecera.id">{{cabecera}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(usuario) of arrayUsuarios" :key="usuario.key">
                                <td class="text-center align-middle">
                                    <div>
                                        <span class="btn btn-primary btn-sm boton" @click="modalEditar(usuario)"><i class="icon-pencil" ></i></span>
                                        <span class="btn btn-danger btn-sm boton" @click="eliminarUsuario(usuario)"><i class="icon-trash"></i></span>
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{usuario.id}}</td>
                                <td class="text-center align-middle">{{usuario.usuario}}</td>
                                <td class="text-center align-middle">{{usuario.nombre}}</td>
                                <td class="text-center align-middle">{{usuario.apellido}}</td>
                                <td class="text-center align-middle">{{usuario.rol}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>        
        </main>
</template>

<script>

export default {
    data(){
        return{
            loading: false,
            initiated: false,
            cabeceras: ['Acciones', '#', 'Usuario', 'Nombre', 'Apellidos', 'Rol'],
            objetoUsuario: {
                usuario: null,
                nombre: null,
                apellido: null,
                condicion: null,
                idrole: 1,
                password: null,
            },
            arrayRoles: [],
            arrayUsuarios: [],
            modoEditable: true,
            id: ''
        }
    },
    mounted(){
        this.listarRoles();
        this.listarUsuarios()
    },
    methods:{
        miTabla(){
            $( function () {
                $('#myTableUsuarios').DataTable({
                    searching: true
                });
            } );
        },
        abrirModalRegistrar(){
            this.objetoUsuario.usuario = null
            this.objetoUsuario.nombre = null
            this.objetoUsuario.apellido = null
            this.objetoUsuario.idrole = 1
            this.modoEditable = false;
            $('#modalRegistroItem').modal('show')
        },  
        listarRoles(){
            var urlItem = '/role';
            axios.get(urlItem).then(response=>{
                this.arrayRoles = response.data;
            })
        },
        modalEditar(item){
            axios.get(`/user/${item.id}`).then((response)=>{
                this.objetoUsuario.usuario = response.data[0].usuario;
                this.objetoUsuario.nombre = response.data[0].nombre
                this.objetoUsuario.apellido = response.data[0].apellido
                this.objetoUsuario.idrole = response.data[0].idrole
            })
            this.modoEditable = true;
            this.id = item.id
            $('#modalRegistroItem').modal('show')
        },
        listarUsuarios(){
            this.loading = true
            var urlItem = '/user';
            axios.get(urlItem).then(response=>{
                this.arrayUsuarios = response.data;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        agregarUsuario(){
            axios.post('/user', this.objetoUsuario).then((response)=>{
                $( function () {
                    $('#myTableUsuarios').DataTable().destroy();
                } );
                this.listarUsuarios();
            })
            $('#modalRegistroItem').modal('toggle');
        },
        editarUsuario(usuario){
            var mi = $('#myTableUsuarios').DataTable()
            axios.put(`/user/${usuario}`, this.objetoUsuario).then((response) =>{
                mi.destroy()
                this.listarUsuarios();
            })
            $('#modalRegistroItem').modal('toggle');
        },
        eliminarUsuario(usuario, index){
            if(confirm(`Está seguro de eliminar el usuario ${usuario.usuario}?`)){
                axios.delete(`/user/${usuario.id}`)
                    .then(()=>{
                        this.arrayUsuarios.splice(index,1)
                    })
                    $( function () {
                        $('#myTableUsuarios').DataTable().destroy();
                    } );
                    this.listarUsuarios();
            }
        }
        
    }
}
</script>