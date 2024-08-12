@extends ('layoutNavigate')
@section('title',__('txt.link.customer.add_new'))
@section('content')
<div class="container mt-1">   
    <form method="post">
    @csrf
    @method('post')
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Customer Name</span>
            <input autocomplete="off" type="text" id="customer_name" class="form-control capitalize" placeholder="Example: Ali bin Abu" name="name" aria-describedby="basic-addon1" maxlength="255">
        </div>
        <div class="input-group" id="warning-message" style="color: red;"></div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">IC/Passport No.</span>
            <input autocomplete="off" type="text" id="ic_passport_num" class="form-control capitalize" placeholder="Example: 990101-13-1234" name="ic_passport_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tel. No.</span>
            <input autocomplete="off" type="text" id="telephone_num" class="form-control capitalize" placeholder="Example: 0123456789" name="telephone_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Home Address</span>
            <input type="text" id="address" class="form-control capitalize" name="address" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Left SPH</span>
            <input type="text" id="left_eye_degree" class="form-control capitalize" name="left_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Right SPH</span>
            <input type="text" id="right_eye_degree" class="form-control capitalize" name="right_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Remarks</span>
            <textarea id="remarks" class="form-control capitalize" name="remarks" maxlength="255"></textarea>
        </div>

        <button id="submitButton" type="submit" class="btn btn-primary">Submit</button>

    </form>
    </div>

<script>
// #############################################################################################################################
//  _____       _     _ _        ______                _   _                 
// |  __ \     | |   | (_)      |  ____|              | | (_)                
// | |__) |   _| |__ | |_  ___  | |__ _   _ _ __   ___| |_ _  ___  _ __  ___ 
// |  ___/ | | | '_ \| | |/ __| |  __| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
// | |   | |_| | |_) | | | (__  | |  | |_| | | | | (__| |_| | (_) | | | \__ \
// |_|    \__,_|_.__/|_|_|\___| |_|   \__,_|_| |_|\___|\__|_|\___/|_| |_|___/

    const textareas = document.querySelectorAll('.capitalize');

    // Add event listener for input event to each textarea
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            // Capitalize the text in the textarea
            this.value = this.value.toUpperCase();
        });
    });

    // submit the information
    $('#submitButton').click(function(event) {
            // Prevent the default form submission
            event.preventDefault();
            const customerNameValue = document.getElementById('customer_name').value.trim();

            if (customerNameValue === ''){
                event.preventDefault();
                document.getElementById('customer_name').classList.add('input-error');                
                document.getElementById('warning-message').textContent = '*Please Insert Customer Name';
                
            }else{
                var new_customer_data = {
                    name: customer_name.value,
                    ic_passport_num: ic_passport_num.value,
                    telephone_num: telephone_num.value,
                    address: address.value,
                    left_eye_degree: left_eye_degree.value,
                    right_eye_degree: right_eye_degree.value,
                    remarks: remarks.value,
                };
                console.log('new_customer_data: ', new_customer_data);

                fetch('{{ route('customer.store') }}', {
                    method: 'POST',
                    body: JSON.stringify(new_customer_data), // Convert data to JSON
                    headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ',response.status);
                    }
                    return response.json();
                })
                .then(data =>{
                    console.log(data);
                    window.location.href = '/customer/list';// go to customer list once successfully saved
                    // if (data.error) {
                    //     // Show the error message to the user
                    //     alert('An error occurred: ' + data.error);
                    // } else {
                    //     // Process the successful response
                    //     console.log(data);
                    //     // Redirect to another page if needed
                    //     // window.location.href = '/sale/list';
                    // }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error.message);
                });
            }
            
        });

</script>
@endsection