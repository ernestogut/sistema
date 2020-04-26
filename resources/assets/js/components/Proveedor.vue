<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="0" :idTabla="'myTable'"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Proveedores',
            tituloModal: 'proveedor',
            ruta: '/proveedor',
            cabeceras: ['Acciones', '#', 'Código', 'Tipo de documento', 'Num documento', 'Razon',   'Correo', 'Telef contacto'],
            variables: [
                {
                    for: 'codigo',
                    type: 'codigo',
                    name: 'codigo',
                    id: 'codigo',
                    placeholder: 'Ingrese el codigo',
                    var: '',
                    titulo: 'Codigo'
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
                    for: 'razon',
                    type: 'razon',
                    name: 'razon',
                    id: 'razon',
                    placeholder: 'Ingresa la razon social',
                    var: '',
                    titulo: 'Razon'
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
                },
                {
                    for: 'direccion',
                    type: 'direccion',
                    name: 'direccion',
                    id: 'direccion',
                    placeholder: 'Ingrese la direccion',
                    var: '',
                    titulo: 'Direccion'
                }
            ],
        }
    },
}
</script>