@extends('layouts.app')

@section('title', 'Importar Usuarios')

@section('content')
  <div class="row">
    <div class="col-md-12 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Importar CSV</div>

        <div class="panel-body">
          <form action="{{ route('import_parse') }}" method="POST" class="mb-4" enctype="multipart/form-data">
            @csrf

            <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
              <label for="csv_file" class="col-md-10 control-label">Archivo CSV para importar</label>

              <div class="col-md-12">
                <input id="csv_file" type="file" class="form-control" name="csv_file" required>

                @if ($errors->has('csv_file'))
                  <span class="help-block">
                    <strong>{{ $errors->first('csv_file') }}</strong>
                  </span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-10 col-md-offset-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="header" checked> El archivo contiene encabezados?
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-10 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Analizar CSV
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection