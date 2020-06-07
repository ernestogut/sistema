<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="SpeedCuber Perú - Modulo de ventas">
  <meta name="author" content="ernestogutttt@gmail.com">
  <meta name="keyword" content="SpeedCuber Perú - Modulo de ventas">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="userId" content="{{  Auth::check() ? Auth::user()->id : ''}}">

  <title>Apertura Caja - SpeedCuber Peru</title>

  <link href="css/plantilla.css" rel="stylesheet">


</head>

<body class="app flex-row  back-inventario align-items-center">
  <div class="container " id="app" >
    <div>
        <div class="card" style="border-radius: 10px; background: #24273F; border-color: #24273F; color: white; -webkit-box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34); -moz-box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34); box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34);">
            <div class="card-header d-flex flex-row justify-content-between align-items-center" style="width:44%; border-radius: 10px; background: #1E2137; width: 100%; border-color: #1E2137;">
                        
                        <div>Apertura de caja</div>
                    
                    
                        <div>
                        <ul class="nav navbar-nav ml-auto">
                        {{-- <notification :notifications="notifications"></notification> --}}
                            <li class="nav-item dropdown" style="background: #1E2137;">
                            <div class="dropdown-menu dropdown-menu-right">
                                        <!--<div class="dropdown-header text-center">
                                            <strong>Cuenta</strong>
                                        </div>-->
                                <form method="POST" action="{{ route('logout') }}" >
                                    @csrf
                                    <button class="dropdown-item"><i class="fa fa-lock"></i>Cerrar sesión</button>
                                </form>
                            </div>
                            <a class="nav-link dropdown-toggle nav-link mr-4    " data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="d-md-down-none text-white">{{Auth::user()->nombre}} {{Auth::user()->apellido  }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <form method="post" action="/cierre_caja">
                {{csrf_field()}}
                    <div class="card-body text-white">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Monto inicial</label>
                                <input type="number" id="inputID" step="any" class="form-control" name="saldo_inicial" style="border-radius: 10px; border-color: #FD3954;">
                            </div>
                            <div class="form-group col-md-6" >
                                <label>Almacen</label>
                                <select class="form-control" name="id_almacen" style="border-radius: 10px; border-color: #FD3954;">
                                    @foreach(App\Http\Controllers\AlmacenController::indexLaravel() as $almacen)
                                        <option value='{{ $almacen->id }}'  >{{ $almacen->descripcion }}</option>
                                    @endforeach
                                    
                                </select>
                                @if($errors->any()) {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!} @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" style="width:44%; border-radius: 10px; background: #1E2137; width: 100%; border-color: #1E2137;">
                        <button type="submit" class="btn boton-oscuro" >Aperturar caja</button>
                        <button type="button" class="btn" style="border-radius: 10px; background: #56B092; border-color: #56B092; color: white;">Abrir gaveta</button>
                        @if(Auth::user()->idrole==1)
                            <a href="{{route('principal')}}" class="btn " style="border-radius: 10px; background: #56B092; border-color: #56B092; color: white;">Omitir apertura</a>
                        @endif
                    </div>
            </form>
        </div>
    </div>
  </div>

  <!-- Bootstrap and necessary plugins -->
  <!-- Bootstrap and necessary plugins -->
  <script src="js/app.js"></script>
  <script src="js/plantilla.js"></script>
  <script type="text/javascript">
        $("#inputID").on('blur change input', function() {
            $(this).val(function(i, input) {
                input = input.replace(/\D/g, '');
                return (input / 100).toFixed(2);
            });
        }).trigger('blur');
</script>

</body>
</html>
