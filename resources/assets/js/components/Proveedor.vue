<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Proveedores',
            tituloModal: 'proveedor',
            ruta: '/proveedor',
            cabeceras: ['#', 'Nombre', 'Num documento', 'Correo', 'Telef contacto', 'Acciones'],
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
                    for: 'num_documento',
                    type: 'num_documento',
                    name: 'num_documento',
                    id: 'num_documento',
                    placeholder: 'Ingrese el n° documento',
                    var: '',
                    titulo: 'Numero de documento'
                },
                {
                    for: 'correo',
                    type: 'correo',
                    name: 'correo',
                    id: 'correo',
                    placeholder: 'Ingrese el correo',
                    var: '',
                    titulo: 'Correo'
                },
                {
                    for: 'telefono_contacto',
                    type: 'telefono_contacto',
                    name: 'telefono_contacto',
                    id: 'telefono_contacto',
                    placeholder: 'Ingrese el teléfono de contacto',
                    var: '',
                    titulo: 'Teléfono de contacto'
                }
            ],
        }
    },
}
</script>