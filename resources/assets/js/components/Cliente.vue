<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="0" :idTabla="'myTable'"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Cliente',
            tituloModal: 'cliente',
            ruta: '/cliente',
            cabeceras: ['Acciones', '#', 'Codigo', 'Razón social', 'Dirección', 'Tipo de documento', 'Numero de documento'],
            variables: [
                {
                    for: 'codigo',
                    type: 'codigo',
                    name: 'codigo',
                    id: 'codigo',
                    placeholder: 'Ingrese el código',
                    var: '',
                    titulo: 'Código'
                },
                {
                    for: 'razon',
                    type: 'razon',
                    name: 'razon',
                    id: 'razon',
                    placeholder: 'Ingrese la razón social',
                    var: '',
                    titulo: 'Razón'
                },
                {
                    for: 'direccion',
                    type: 'direccion',
                    name: 'direccion',
                    id: 'direccion',
                    placeholder: 'Ingrese la direccion',
                    var: '',
                    titulo: 'Dirección'
                },
                {
                    for: 'num_documento',
                    type: 'num_documento',
                    name: 'num_documento',
                    id: 'num_documento',
                    placeholder: 'Ingrese el numero de documento',
                    var: '',
                    titulo: 'Numero de documento'
                }
            ],
        }
    },
}
</script>