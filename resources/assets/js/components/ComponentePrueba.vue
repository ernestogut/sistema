<template>
    <main class="main">  
        <div class="container">
            <h2>Extra Large Modal</h2>
            <!-- Button to Open the Modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                Open modal
            </button>

            <!-- The Modal -->
            <div class="modal fade" id="myModal">
                <div class="modal-dialog modal-xl">
                <div class="modal-content">
                
                    <!-- Modal Header -->
                    <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="productos d-flex flex-wrap justify-content-around">
                            <div class="card d-flex justify-content-center align-items-center" style="width: 14rem;" v-for="producto in arrayProductos" :key="producto.key">
                                <img :src="producto.imagen" class="card-img-top" id="imagen" alt="..." >
                                <div class="card-body">
                                    <h5 class="card-text">{{producto.codigo}} - {{producto.producto}}</h5>
                                    <p>S/ {{producto.precio}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal footer -->
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            arrayProductos: [],
        }
    },
    mounted(){
        this.listarItem();
    },
    methods:{
        listarItem(){
            var urlItem = 'speed';
            axios.get(urlItem).then(response=>{
                this.arrayProductos = response.data;
            })
        },
    }




}
</script>

<style scoped>


#imagen{
    border-radius: 10px;
    border: 1px;
    /*margin-left: 5px;*/
    max-width: 90%;
}

</style>

