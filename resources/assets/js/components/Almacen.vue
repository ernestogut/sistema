<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo" :tituloModal="tituloModal" :factura="false" :controlador="0" :idTabla="'myTable'"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Almacenes',
            tituloModal: 'almacén',
            ruta: '/almacen',
            cabeceras: ['Acciones', '#', 'Codigo', 'Descripcion', 'Dirección'],
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
                    for: 'descripcion',
                    type: 'descripcion',
                    name: 'descripcion',
                    id: 'descripcion',
                    placeholder: 'Ingrese la descripción',
                    var: '',
                    titulo: 'Descripción'
                },
                {
                    for: 'direccion',
                    type: 'direccion',
                    name: 'direccion',
                    id: 'direccion',
                    placeholder: 'Ingrese la direccion',
                    var: '',
                    titulo: 'Dirección'
                }
            ],
        }
    },
}
</script>