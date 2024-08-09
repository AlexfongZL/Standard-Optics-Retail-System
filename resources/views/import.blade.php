@extends ('layoutNavigate')

@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 mt-5">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Import Customer Excel Data into Database</h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ url('/database/import_customer') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="input-group">
                                <input type="file" name="import_file" class="form-control" />
                                <button type="submit" class="btn btn-primary">Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection