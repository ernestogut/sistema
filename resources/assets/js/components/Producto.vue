<template>
    <modulo :variables="variables" :ruta="ruta" :cabeceras="cabeceras" :titulo="titulo"></modulo>
</template>
<script>

export default {
    data(){
        return{
            titulo: 'Productos',
            ruta: '/producto',
            cabeceras: ['#', 'Codigo', 'Marca', 'Modelo', 'Precio', 'Descripcion', 'Acciones'],
            variables: [
                {
                    for: 'codigo',
                    type: 'codigo',
                    name: 'codigo',
                    id: 'codigo',
                    placeholder: 'Ingrese el codigo',
                    var: '',
                    titulo: 'Código'
                },
                {
                    for: 'marca',
                    type: 'marca',
                    name: 'marca',
                    id: 'marca',
                    placeholder: 'Ingrese la marca',
                    var: '',
                    titulo: 'Marca'
                },
                {
                    for: 'modelo',
                    type: 'modelo',
                    name: 'modelo',
                    id: 'modelo',
                    placeholder: 'Ingrese el modelo',
                    var: '',
                    titulo: 'Modelo'
                },
                {
                    for: 'precio',
                    type: 'precio',
                    name: 'precio',
                    id: 'precio',
                    placeholder: 'Ingrese el precio',
                    var: '',
                    titulo: 'Precio'
                },
                {
                    for: 'descripcion',
                    type: 'descripcion',
                    name: 'descripcion',
                    id: 'descripcion',
                    placeholder: 'Ingrese la descripcion',
                    var: '',
                    titulo: 'Descripción'
                }
            ],
        }
    },
}
</script>