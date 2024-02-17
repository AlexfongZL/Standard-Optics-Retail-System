@extends ('layoutNavigate')
@section('title',__('txt.link.customer.add_new'))
@section('content')
<div class="container mt-1">   
    <form method="post" action="{{ route('customer.store') }}">
    @csrf
    @method('post')
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Customer Name</span>
            <input type="text" class="form-control" placeholder="Example: Ali bin Abu" name="name" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">IC/Passport No.</span>
            <input type="text" class="form-control" placeholder="Example: 990101-13-1234" name="ic_passport_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tel. No.</span>
            <input type="text" class="form-control" placeholder="Example: 0123456789" name="telephone_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Home Address</span>
            <input type="text" class="form-control" name="address" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Left SPH</span>
            <input type="text" class="form-control" name="left_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Right SPH</span>
            <input type="text" class="form-control" name="right_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Remarks</span>
            <textarea class="form-control" name="remarks" maxlength="255"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
    </div>
@endsection