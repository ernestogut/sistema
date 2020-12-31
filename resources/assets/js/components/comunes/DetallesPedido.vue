<template>
    <div class="d-flex justify-content-center">
    <div class="card card-1">
        <div class="card-header bg-white">
            <div class="media flex-sm-row flex-column-reverse justify-content-between ">
                <div class="col my-auto">
                    <h4 class="mb-0">Orden de <span class="change-color"> {{pedido.cliente}}</span></h4>
                </div>
                <div class="col-auto text-center my-auto pl-0 pt-sm-4"> <img class="img-fluid my-auto align-items-center mb-0 pt-3" src="/img/solo-logo-scp.png" width="85" height="85"><br>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-between mb-3">
                <div class="col-auto">
                    <h6 class="color-1 mb-0 change-color">Pedido</h6>
                </div>
                <div class="col-auto "> <small>Número de pedido: #{{pedido.id}}</small> </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card card-2">
                        <div class="card-body">
                            <div class="media">
                                <!--<div class="sq align-self-center "> <img class="img-fluid my-auto align-self-center mr-2 mr-md-4 pl-0 p-0 m-0" src="https://i.imgur.com/fUWWpRS.jpg" width="135" height="135" /> </div>-->
                                <div class="media-body my-auto text-right">
                                    <div class="row my-auto flex-column flex-md-row">
                                        {{pedido.pedido}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <p class="mb-1 text-dark"><b>Detalle de la orden</b></p>
                        </div>
                        <div class="flex-sm-col text-right col">
                            <p class="mb-1"><b>Precio productos</b></p>
                        </div>
                        <div class="flex-sm-col col-auto">
                            <p class="mb-1">S/ {{pedido.precio_productos}}</p>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="flex-sm-col text-right col">
                            <p class="mb-1"> <b>Envio</b></p>
                        </div>
                        <div class="flex-sm-col col-auto">
                            <p class="mb-1">S/ {{pedido.envio_productos}}</p>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="flex-sm-col text-right col">
                            <p class="mb-1"><b>Monto pagado</b></p>
                        </div>
                        <div class="flex-sm-col col-auto">
                            <p class="mb-1">S/ {{pedido.monto_pagado}}</p>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="flex-sm-col text-right col">
                            <p class="mb-1"><b>Por cobrar</b></p>
                        </div>
                        <div class="flex-sm-col col-auto">
                            <p class="mb-1">S/ {{pedido.por_cobrar}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row invoice ">
                <div class="col">
                    <p class="mb-1">Teléfono: <strong>{{pedido.telefono}}</strong></p>
                    <p class="mb-1">Distrito: <strong>{{pedido.distrito}}</strong></p>
                    <p class="mb-1">Dirección: <strong>{{pedido.direccion}}</strong></p>
                    <p class="mb-1">Referencia: <strong>{{pedido.referencia}}</strong></p><hr>
                    <p class="mb-1">Observación: <strong>{{pedido.observacion}}</strong></p>
                    <p class="mb-1">Medio de recepción: <strong>{{pedido.medio_recepcion}}</strong></p>
                    <p class="mb-1">Método de pago: <strong>{{pedido.metodo_pago}}</strong></p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="jumbotron-fluid">
                <div class="d-flex flex-row justify-content-between ">
                    <div class="col-sm-auto col-auto my-auto"><img class="img-fluid my-auto align-self-center " src="/img/solo-logo-scp.png" width="135" height="135"></div>
                    <div class="col-auto my-auto ">
                        <h2 class="mb-0 font-weight-bold">TOTAL PAGADO</h2>
                    </div>
                    <div class="col-auto my-auto ml-auto">
                        <h1 class="display-4 ">S/ {{pedido.monto_pagado}}</h1>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-center mb-3 mt-3 mt-md-0">
                    <div class="col-auto border-line"> <small class="text-white">SPEED</small></div>
                    <div class="col-auto border-line"> <small class="text-white">CUBER </small></div>
                    <div class="col-auto "><small class="text-white">PERÚ </small> </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        pedido: Object
    }
}
</script>

<style scoped>
body {
    min-height: 100vh;
    background-size: cover;
    font-family: 'Lato', sans-serif;
    color: rgba(116, 116, 116, 0.667);
    background: linear-gradient(140deg, #fff, 50%, #BA68C8)
}

.container-fluid {
    margin-top: 200px
}

p {
    font-size: 14px;
    margin-bottom: 7px
}

.small {
    letter-spacing: 0.5px !important
}

.card-1 {
    box-shadow: 2px 2px 10px 0px rgb(190, 108, 170)
}

hr {
    background-color: rgba(248, 248, 248, 0.667)
}

.bold {
    font-weight: 500
}

.change-color {
    color: #AB47BC !important
}

.card-2 {
    box-shadow: 1px 1px 3px 0px rgb(112, 115, 139)
}

.fa-circle.active {
    font-size: 8px;
    color: #AB47BC
}

.fa-circle {
    font-size: 8px;
    color: #aaa
}

.rounded {
    border-radius: 2.25rem !important
}

.progress-bar {
    background-color: #AB47BC !important
}

.progress {
    height: 5px !important;
    margin-bottom: 0
}

.invoice {
    position: relative;
    top: -70px
}

.Glasses {
    position: relative;
    top: -12px !important
}

.card-footer {
    background-color: #1E2137;
    color: #fff
}

h2 {
    color: rgb(180, 13, 209);
    letter-spacing: 2px !important
}

.display-4 {
    font-weight: 500 !important
}

@media (max-width: 479px) {
    .invoice {
        position: relative;
        top: 7px
    }

    .border-line {
        border-right: 0px solid rgb(226, 206, 226) !important
    }
}

@media (max-width: 700px) {
    h2 {
        color: rgb(78, 0, 92);
        font-size: 17px
    }

    .display-3 {
        font-size: 28px;
        font-weight: 500 !important
    }
}

.card-footer small {
    letter-spacing: 7px !important;
    font-size: 12px
}

.border-line {
    border-right: 1px solid rgb(226, 206, 226)
}
</style>