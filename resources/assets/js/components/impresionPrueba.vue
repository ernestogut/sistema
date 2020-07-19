<template>
    <div class="main">
        <button @click="imprimir()" class="btn btn-success">
            Imprimir
        </button>
        <button @click="abrirCaja()" class="btn btn-success">
            Abrir caja
        </button>
    </div>
    
</template>

<script>

//import Impresora from '../../../../public/js/Impresora'
export default {
    data(){
        
        return{
            impresoras: []
        }
    },
    methods:{
        imprimir(){
            const RUTA_API = "http://localhost:8000";
            
            let impresora = new Impresora(RUTA_API);
                        
            impresora.setFontSize(1, 1);
            impresora.setEmphasize(0);
            impresora.setAlign("center");
            impresora.write("ROJAS RIOS VANESSA KATHERINE\n");
            impresora.write("AV. ARENALES 1624 Tienda 34\n");
            impresora.write("LIMA - LIMA - LINCE\n");
            impresora.write("RUC: 10457782417\n");
            impresora.write("Telefono: 01-7505980\n");
            impresora.write("Fecha/Hora: 2019-08-01 13:21:22\n");
            impresora.write("--------------------------------\n");
            impresora.write("Cubo de rubik\n");
            impresora.setAlign("right");
            impresora.write("S/ 25.00\n");
            impresora.write("--------------------------------\n");
            impresora.write("TOTAL: S/ 25.00\n");
            impresora.write("--------------------------------\n");
            impresora.setAlign("center");
            impresora.write("***Gracias por su compra***");
            impresora.feed(5);
            impresora.cut();
            impresora.cutPartial(); // Pongo este y también cut porque en ocasiones no funciona con cut, solo con cutPartial
            impresora.cash();
            impresora.end()
                .then(valor => {
                    console.log(valor)
                })
            /*impresora.setFontSize(1, 1);
            impresora.setAlign("right");
            impresora.write("Ernesto\n");
            impresora.setFont("A");
            impresora.write("Gutierrez\n");
            impresora.cut();
            impresora.cutPartial(); // Pongo este y también cut porque en ocasiones no funciona con cut, solo con cutPartial
            //Impresora.setImpresora("GAVETA")
            impresora.cash();
            //impresora.imprimirEnImpresora("GAVETA")
            impresora.end()
                .then(valor => {
                    console.log(valor)
                });*/
        },
        abrirCaja(){
            const RUTA_API = "http://localhost:8000";
            
            let impresora = new Impresora(RUTA_API);
            impresora.cash();
            impresora.end()
                .then(valor => {
                    console.log(valor)
                })
            /*impresora.setFontSize(1, 1);
            impresora.setAlign("right");
            impresora.write("Ernesto\n");
            impresora.setFont("A");
            impresora.write("Gutierrez\n");
            impresora.cut();
            impresora.cutPartial(); // Pongo este y también cut porque en ocasiones no funciona con cut, solo con cutPartial
            //Impresora.setImpresora("GAVETA")
            impresora.cash();
            //impresora.imprimirEnImpresora("GAVETA")
            impresora.end()
                .then(valor => {
                    console.log(valor)
                });*/
        }
    }
}
</script>

<style>

</style>