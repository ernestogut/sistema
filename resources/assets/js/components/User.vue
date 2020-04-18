<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="0" :idTabla="'myTable'"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Mantenimiento de usuario',
            tituloModal: 'usuario',
            ruta: '/user',
            cabeceras: ['Acciones', '#', 'Usuario', 'Nombre', 'Apellidos', 'Rol'],
            variables: [
                {
                    for: 'nombre',
                    type: 'nombre',
                    name: 'nombre',
                    id: 'nombre',
                    placeholder: 'Ingrese el nombre',
                    var: '',
                    titulo: 'Nombre'
                },
                {
                    for: 'apellido',
                    type: 'apellido',
                    name: 'apellido',
                    id: 'apellido',
                    placeholder: 'Ingrese los apellidos',
                    var: '',
                    titulo: 'Apellidos'
                },
                {
                    for: 'usuario',
                    type: 'usuario',
                    name: 'usuario',
                    id: 'usuario',
                    placeholder: 'Ingrese el usuario',
                    var: '',
                    titulo: 'Usuario (login)'
                },
                {
                    for: 'password',
                    type: 'password',
                    name: 'password',
                    id: 'password',
                    placeholder: 'Ingrese la contraseña',
                    var: '',
                    titulo: 'Contraseña (login)'
                }
            ],
        }
    },
}
</script>