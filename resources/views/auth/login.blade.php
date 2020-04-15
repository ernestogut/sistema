@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-group mb-0">
        <div class="card p-4" style="border-radius: 10px; background: #24273F; border-color: #24273F; color: white; -webkit-box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34); -moz-box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34); box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34);">
        <form method="POST" class="form-horizontal was-validated" action="{{route('login')}}">
             {{ csrf_field() }}
          <div class="card-body">
            <h1>Bienvenido</h1>
            <p class="text-muted">Login de usuario</p>
            <div class="form-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
              
            <input type="text" name="usuario" value="{{old('usuario')}}" id="usuario" class="form-control" style="border-radius: 10px; border-color: #FD3954;" placeholder="Usuario">
              {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
            </div>
            <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
              
              <input type="password" style="border-radius: 10px; border-color: #FD3954; " name="password" id="password" class="form-control" placeholder="Password">
              {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
            </div>
            <div class="row">
              <div class="col-6">
                <button type="submit" class="btn px-4 text-white" style="border-radius: 10px; background: #FD3954;">Acceder</button>
              </div>
            </div>
          </div>
        </form>
        </div>
        <div class="card text-white py-5 d-md-down-none" style="width:44%; border-radius: 10px; background: #1E2137; border-color: #1E2137; -webkit-box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34); -moz-box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34); box-shadow: 10px 10px 9px 6px rgba(0,0,0,0.34);">
          <div class="card-body text-center">
            <img src="{{ asset('img/logo-scp.png') }}" alt="asdasd" style="width: 40%;">
            @foreach($dato as $d)
            <div>
              <h2>{{$d->nombre}}</h2>
              <br>{{$d->direccion}} - {{$d->ciudad}}, {{$d->pais}}<br>Telefono: {{$d->telefono}}<br>e-mail: {{$d->email}}

            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
