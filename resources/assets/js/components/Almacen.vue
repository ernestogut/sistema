<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Almacenes',
            ruta: '/almacen',
            cabeceras: ['#', 'Codigo', 'Descripcion', 'Dirección', 'Acciones'],
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