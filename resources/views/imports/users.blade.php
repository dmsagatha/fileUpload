<x-layout>
  <x-slot:title>
    Importar Usuarios
  </x-slot>
  
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">CSV Import</div>

        <div class="panel-body">
          <form class="form-horizontal" method="POST" action="{{ route('import_parse') }}"
            enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
              <label for="csv_file" class="col-md-4 control-label">CSV file to import</label>

              <div class="col-md-6">
                <input id="csv_file" type="file" class="form-control" name="csv_file" required>

                @if ($errors->has('csv_file'))
                <span class="help-block">
                  <strong>{{ $errors->first('csv_file') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="header" checked> File contains header row?
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Parse CSV
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <form method="POST" action="{{ route('import_process') }}" enctype="multipart/form-data">
    @csrf

    <div class="card shadow">
      <div class="card-header">
        <h4>Importación CSV</h4>
      </div>
      <div class="card-body">
        <div class="form-group">
          <input type="file" name="csv_file" class="form-control">
          {!! $errors->first("csv_file", '<small class="text-danger">:message</small>') !!}
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-success" name="submit">Importar Datos </button>
      </div>
    </div>
  </form>

  {{-- <form class="form-horizontal" method="POST" action="{{ route('import_process') }}">
    {{ csrf_field() }}

    <table class="table">
      @foreach ($csv_data as $row)
      <tr>
        @foreach ($row as $key => $value)
        <td>{{ $value }}</td>
        @endforeach
      </tr>
      @endforeach
      <tr>
        @foreach ($csv_data[0] as $key => $value)
        <td>
          <select name="fields[{{ $key }}]">
            @foreach (config('app.db_fields') as $db_field)
            <option value="{{ $loop->index }}">{{ $db_field }}</option>
            @endforeach
          </select>
        </td>
        @endforeach
      </tr>
    </table>

    <button type="submit" class="btn btn-primary">
      Import Data
    </button>
  </form> --}}
</x-layout>