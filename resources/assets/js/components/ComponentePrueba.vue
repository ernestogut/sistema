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
                        <div class="productos d-flex flex-wrap justify-content-between">
                            <div class="card" style="width: 11rem;" v-for="producto in arrayProductos" :key="producto.key">
                                <img :src="`images/${producto.imagen}`" class="card-img-top" alt="..." width="60">
                                <div class="card-body">
                                    <p class="card-text">{{producto.marca}} - {{producto.modelo}} -- {{producto.precio}}</p>
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
            var urlItem = 'producto';
            axios.get(urlItem).then(response=>{
                this.arrayProductos = response.data;
            })
        },
    }




}
</script>

