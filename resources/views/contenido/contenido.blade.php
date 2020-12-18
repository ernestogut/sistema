    @extends('principal')
    @section('contenido')
    @if(Auth::check())
        @if(Auth::user()->idrole == 1)
            <template v-if="menu==1">
                <dashboard></dashboard>
            </template>
            <!--<template >
                <categoria></categoria>
            </template>-->
            <template v-if="menu==3">
                <ingreso-almacen></ingreso-almacen>
            </template>
            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>
            <template v-if="menu==5">
                <facturacion></facturacion>
            </template>
            <template v-if="menu==19">
                <pedido-speed></pedido-speed>
            </template>
            <template v-if="menu==6">
                <cliente></cliente>
            </template>
            <template v-if="menu==7">
                <user></user>
            </template>
            <template v-if="menu==8">
                <role></role>
            </template>
            <template v-if="menu==11">
                <dato></dato>
            </template>
            <template v-if="menu==12">
                <almacen></almacen>
            </template>
            <template v-if="menu==13">
                <producto-speed></producto-speed>
            </template>
            <template v-if="menu==14">
                <traspaso-almacen></traspaso-almacen>
            </template>

            <template v-if="menu==16">
                <tipo-comprobante></tipo-comprobante>
            </template>
            <template v-if="menu==17">
                <cierre-caja></cierre-caja>
            </template>
            <template v-if="menu==18">
                <movimiento-caja></movimiento-caja>
            </template>
            <template v-if="menu==20">
                <compra></compra>
            </template>
            <template v-if="menu==22">
                <reporte></reporte>
            </template>
            <template v-if="menu==23">
                <reporte-ranking-producto></reporte-ranking-producto>
            </template>
            <template v-if="menu==24">
                <reporte-ranking-categoria></reporte-ranking-categoria>
            </template>
            <template v-if="menu==25">
                <reporte-cierre></reporte-cierre>
            </template>
            <template v-if="menu==26">
                <por-mayor></por-mayor>
            </template>
            <template v-if="menu==27">
                <pedidos-por-mayor></pedidos-por-mayor>
            </template>
            <template v-if="menu==28">
                <reporte-movimiento></reporte-movimiento>
            </template>
            <template v-if="menu==29">
                <reporte-envio></reporte-envio>
            </template>
            <template v-if="menu==32">
                <salida-almacen></salida-almacen>
            </template>
        @elseif(Auth::user()->idrole == 2)
            <template v-if="menu==5">
                <facturacion></facturacion>
            </template>
            <template v-if="menu==14">
                <traspaso-almacen></traspaso-almacen>
            </template>
            <template v-if="menu==19">
                <pedido-speed></pedido-speed>
            </template>
            <template v-if="menu==6">
                <cliente></cliente>
            </template>
            <template v-if="menu==17">
                <cierre-caja></cierre-caja>
            </template>
            <template v-if="menu==18">
                <movimiento-caja></movimiento-caja>
            </template>
            <template v-if="menu==31">
                <envio-delivery></envio-delivery>
            </template>
            
        @elseif(Auth::user()->idrole == 3)
            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>
        @elseif(Auth::user()->idrole == 4)
            <template v-if="menu==30">
                <precios-envio></precios-envio>
            </template>
            <template v-if="menu==31">
                <envio-delivery></envio-delivery>
            </template>
        @endIf
    @endIf

@endsection
