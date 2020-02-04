<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg1">Nuevo</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg2">Agregar productos</button>
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form>
                        <div class="card ">
                            <li class="breadcrumb-item ml-3 mt-3">Factura</li>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <label >Serie</label>
                                        <input type="text" class="form-control" placeholder="First name">
                                    </div>
                                    <div class="col">
                                        <label >Número (8 números)</label>
                                        <input type="text" class="form-control" placeholder="Last name">
                                    </div>
                                    <div class="col">
                                        <label >Fecha doc</label>
                                        <input type="text" class="form-control" placeholder="Last name">
                                    </div>
                                    <div class="col">
                                        <label for="inputState">Moneda</label>
                                        <select id="inputState" class="form-control">
                                            <option selected>Escoge...</option>
                                            <option>Soles</option>
                                            <option>Dolares</option>
                                            <option>Euros</option>
                                        </select> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card ">
                            <li class="breadcrumb-item ml-3 mt-3">Cliente</li>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col">
                                        <label for="inputState">Tipo de documento</label>
                                        <select id="inputState" class="form-control">
                                            <option selected>Escoge...</option>
                                            <option>RUC</option>
                                        </select> 
                                    </div>
                                    <div class="col">
                                        <label >N° de RUC</label>
                                        <input type="text" class="form-control" placeholder="Numero de RUC">
                                    </div>
                                    <div class="col">
                                        <label >Razón Social</label>
                                        <input type="text" class="form-control" placeholder="Razón social aquí">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label >Dirección</label>
                                    <input type="text" class="form-control" placeholder="Escribe la dirección completa">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            <!--<div class="card">
                <div class="card-header">
                    <p>Tipo de combrobante</p>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-around align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                            <label class="form-check-label" for="inlineRadio1">Factura</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">Boleta</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
                            <label class="form-check-label" for="inlineRadio3">Nota de crédito</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3">
                            <label class="form-check-label" for="inlineRadio3">Nota de débito</label>
                        </div>
                    </div>
                </div>
            </div>-->
                <div class="modal fade bd-example-modal-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="card-body">
                            <div class="modal-content" >
                                <div class="table-responsive">
                                    <datatable :modalEditar="modalEditar" :eliminarItem="eliminarItem" :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos"></datatable>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
    </main>
</template>
<script>

export default {
    data(){
        return{
            arrayItems: [],
            cabeceras: ['#', 'Codigo', 'Marca', 'Modelo', 'Precio', 'Descripcion', 'Imagen', 'Almacen','Acciones'],
            iconos: 'icon-plus'
        }
    },
    mounted(){
        this.listarItem()
    },
    methods:{
        miTabla(){
            $( function () {
                $('#myTable').DataTable();
            } );
        },
        listarItem(){
            var urlItem = '/producto';
            axios.get(urlItem).then(response=>{
                this.arrayItems = response.data;
                this.miTabla();
            })
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.codigo}?`)){
                axios.delete(`/producto/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)
                        
                    })
                    $( function () {
                        $('#myTable').DataTable().destroy();
                    } );
                    this.listarItem();
            }
        },
        modalEditar(){
            return 
        }
    }
}
</script>


<style scoped>

li{
    list-style-type: none;
}

</style>