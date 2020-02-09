<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Cliente',
            tituloModal: 'cliente',
            ruta: '/cliente',
            cabeceras: ['#', 'Codigo', 'Razón social', 'Dirección', 'RUC', 'Acciones'],
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
                    for: 'ruc',
                    type: 'ruc',
                    name: 'ruc',
                    id: 'ruc',
                    placeholder: 'Ingrese el RUC',
                    var: '',
                    titulo: 'RUC'
                }
            ],
        }
    },
}
</script>