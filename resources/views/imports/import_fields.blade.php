<x-layout>
  <x-slot:title>
    Importar Usuarios
  </x-slot>
  
  <form action="{{ route('import_process') }}" method="POST">
    @csrf
    <input type="hidden" name="csv_data_file_id" value="{{ $csv_data_file->id }}" />

    <table class="table">
      @if (isset($headings))
        <thead>
          <tr>
            @foreach ($headings[0][0] as $csv_header_field)
              {{-- @dd($headings)--}}
              <th><span>{{ $csv_header_field }}</span></th>
            @endforeach
          </tr>
        </thead>
      @endif
      <tbody>
        @foreach($csv_data as $row)
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
                  <option value="{{ (\Request::has('header')) ? $db_field : $loop->index }}" @if ($key===$db_field) selected @endif>
                    {{ $db_field }}
                  </option>
                @endforeach
              </select>
            </td>
          @endforeach
        </tr>
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary">
      Importar datos
    </button>
  </form>
</x-layout>