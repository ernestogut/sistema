<div class="sidebar">
        <nav class="sidebar-nav">
            <ul class="nav" >
                <li @click="menu=1" class="nav-item">
                    <a class="nav-link active" href="#" style="background: #24273F;"><i class="icon-speedometer"></i> Escritorio</a>
                </li>
                <li class="nav-title">
                    Mantenimiento
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=5" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-basket-loaded"></i> Factura</a>
                        </li>
                        <li  @click="menu=19" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-basket-loaded"></i> Pedidos web</a>
                        </li>
                    </ul>
                </li>
                <li  class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Financiero</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=17" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Cierre de caja</a>
                        </li>
                        <li  @click="menu=18" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Movimiento de caja</a>
                        </li>
                    </ul>
                </li>
                <li  class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Inventario</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=12" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Almacenes</a>
                        </li>
                        <li  @click="menu=3" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-wallet"></i> Ingresos</a>
                        </li>
                        <li  @click="menu=14" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Traspaso almacén</a>
                        </li>
                        <li  @click="menu=13" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Productos</a>
                        </li>
                    </ul>
                </li>
                <li  class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Por Mayor</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=26" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Productos</a>
                        </li>
                        <li  @click="menu=27" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Pedidos</a>
                        </li>
                    </ul>
                </li>
                <li  class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Reportes</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=22" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Por fecha</a>
                        </li>
                        <li  @click="menu=25" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-basket-loaded"></i> Cierres de caja</a>
                        </li>
                        <li  @click="menu=23" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Ranking productos</a>
                        </li>
                        <li  @click="menu=24" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Ranking categorias</a>
                        </li>
                        <li  @click="menu=28" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Movimientos usuarios</a>
                        </li>
                        <li  @click="menu=29" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-bag"></i> Envios</a>
                        </li>
                    </ul>
                </li>
                <li  class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Socios de negocio</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=6" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Clientes</a>
                        </li>
                        <li  @click="menu=4" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedor</a>
                        </li>
                    </ul>
                </li>
                <li  class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Gestión</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=16" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-wallet"></i> Tipo Comprobantes</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Compras</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=4" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedores</a>
                        </li>
                        <li  @click="menu=20" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-notebook"></i> Compras</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=7" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
                        </li>
                        <li  @click="menu=8" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
                        </li>
                    </ul>
                </li>
                <!--<li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                    <ul class="nav-dropdown-items">
                        <li  @click="menu=9" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ingresos</a>
                        </li>
                        <li   @click="menu=10" class="nav-item">
                            <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ventas</a>
                        </li>
                    </ul>
                </li>-->
                <li @click="menu=11" class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-speedometer"></i> Mis Datos</a>
                </li>
            </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>
