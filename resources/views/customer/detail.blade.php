@extends ('layoutNavigate')
@section('title',__('txt.link.customer.details'))
@section('content')

<!-- @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert" id="errorAlert">
        {{ session('error') }}
    </div>
@endif -->
<div id="customerData" data-customer-id="{{ $customer->id }}"></div>

<form method="POST" action="{{ route('customer.update', ['id' => $customer->id]) }}">
    @csrf
    @method('post')
    <div class="input-group mb-1">
        <span class="input-group-text" id="basic-addon1">{{__('txt.customer.name')}}:</span>
        <input type="text" class="form-control capitalize" name="name" aria-describedby="basic-addon1" value="{{ $customer->name }}" maxlength="255">

        <span class="input-group-text" id="basic-addon1">{{__('txt.customer.address')}}:</span>
        <input type="text" class="form-control capitalize" name="address" aria-describedby="basic-addon1" value="{{ $customer->address }}" maxlength="255">

        <span class="input-group-text">{{__('txt.customer.remarks')}}:</span>
        <textarea class="form-control capitalize" id="remarks" name="remarks" maxlength="255">{{ $customer->remarks }}</textarea>

    </div>

    <div class="input-group mb-1">
        <span class="input-group-text" id="basic-addon1">{{__('txt.customer.ic_passport')}}:</span>
        <input type="text" class="form-control capitalize" name="ic_passport_num" aria-describedby="basic-addon1" value="{{ $customer->ic_passport_num }}" maxlength="30">

        <span class="input-group-text" id="basic-addon1">{{__('txt.customer.telephone')}}:</span>
        <input type="text" class="form-control capitalize" name="telephone_num" aria-describedby="basic-addon1" value="{{ $customer->telephone_num }}" maxlength="30">
    </div>

    <div class="d-grid ">
        <button id="saveButton" style="display: none;" class="btn btn-success" type="submit">Save</button>
    </div>
    
</form>
<p></p>
<div class="container text-center border border-primary p-2">
    <div class="row align-items-start">
        <div class="col">
            <h1 class="fs-5 text-body-emphasis align-items-center">
                {{__('txt.customer.details.vision_history')}}
            </h1>

            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">{{__('txt.customer.details.create_date')}}</th>
                        <th scope="col">{{__('txt.customer.details.left_eye_degree')}}</th>
                        <th scope="col">{{__('txt.customer.details.right_eye_degree')}}</th>
                        <th colspan="2">
                            <button type="button" class="btn btn-outline-success" id="addDegreeButton" style="min-width: 100px; font-weight: bold;">+</button>
                        </th>             
                    </tr>
                </thead>
                <tbody id="degreeRows">
                    @foreach($degrees as $key => $degree)
                        <tr class="{{$key == 0 ? 'table-warning' : '' }}">   
                            <td>{{$degree->created_at->format('d-m-Y')}}</td>    
                            <td class="left-eye-cell">{{$degree->left_eye_degree}}</td>
                            <td class="right-eye-cell">{{$degree->right_eye_degree}}</td>
                            <td>
                                <button class="editButton btn btn-warning" data-id="{{ $degree->id }}">
                                    ✏️
                                </button>
                                <button class="saveButton btn btn-success" style="display: none;fontWeight: bold;">
                                    Save
                                </button>
                            </td>
                            <td>
                                <button class="deleteButton btn btn-danger" data-id="{{ $degree->id }}" data-action="deleteDegree" style="fontWeight: bold;">
                                    X
                                </button>
                                <button class="cancelButton btn btn-danger" style="display: none;">
                                    Cancel
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col">
            <h1 class="fs-5 text-body-emphasis"> {{__('txt.customer.details.sales_history')}}</h1>
            
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">{{__('txt.customer.details.create_date')}}</th>
                        <th scope="col">{{__('txt.customer.details.sales_description')}}</th>
                        <th scope="col">{{__('txt.customer.details.sales_price')}}</th>
                        <th scope="col">{{__('txt.customer.details.sales_payment')}}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sales as $key => $sale)
                    <tr class="{{$key == 0 ? 'table-warning' : '' }}">   
                            <td>{{$sale->created_at->format('d-m-Y')}}</td>    
                            <td>{{$sale->description}}</td>
                            <td >{{$sale->price}}</td>
                            <td >
                                @if($sale->is_paid)
                                    Paid
                                @else
                                    Not Paid
                                @endif
                            </td>
                            <td>
                                <button class="deleteButton btn btn-danger" data-id="{{ $sale->id }}" data-action="deleteSale" style="fontWeight: bold;">
                                    X
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// #############################################################################################################################
//  _____       _     _ _        ______                _   _                 
// |  __ \     | |   | (_)      |  ____|              | | (_)                
// | |__) |   _| |__ | |_  ___  | |__ _   _ _ __   ___| |_ _  ___  _ __  ___ 
// |  ___/ | | | '_ \| | |/ __| |  __| | | | '_ \ / __| __| |/ _ \| '_ \/ __|
// | |   | |_| | |_) | | | (__  | |  | |_| | | | | (__| |_| | (_) | | | \__ \
// |_|    \__,_|_.__/|_|_|\___| |_|   \__,_|_| |_|\___|\__|_|\___/|_| |_|___/
    
    
    

    // function to format date into dd-mm-yyyy (eg. 15-02-2024)
    function formatDate(dateString) {
        // format: d-m-Y
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-indexed, so add 1
        const day = String(date.getDate()).padStart(2, '0');
        return `${day}-${month}-${year}`;
    }

    function toggleButtons(className, enable) {
        var buttons = document.getElementsByClassName(className);
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].disabled = !enable;
        }
    }

    // to fetch all customer degree and refresh the vision history table
    // function fetchAllDegrees(id){
    function fetchAllDegrees(){

        fetch('{{ route('customer.fetch_all_degree') }}', {
            method: 'POST',
            // body: JSON.stringify({ id: id }),
            body: JSON.stringify({ id: {{ $customer->id }} }),
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json' }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Construct the HTML content for the tbody with the updated data
            var tbodyContent = '';
            data.forEach(item => {
                for (var i = 0; i < item.length; i++) {
                    tbodyContent += `
                    <tr class="${i == 0 ? 'table-warning' : ''}">
                        <td>${formatDate(item[i].created_at)}</td>
                        <td class="left-eye-cell">${item[i].left_eye_degree ? item[i].left_eye_degree : ''}</td>
                        <td class="right-eye-cell">${item[i].right_eye_degree ? item[i].right_eye_degree : ''}</td>
                        <td>
                            <button class="editButton btn btn-warning" data-id="${item[i].id}">
                                ✏️
                            </button>
                            <button class="saveButton btn btn-success" style="display: none;fontWeight: bold;">
                                Save
                            </button>
                        </td>
                        <td>
                            <button class="deleteButton btn btn-danger" data-id="${item[i].id}" data-action="deleteDegree" style="fontWeight: bold;">
                                X
                            </button>
                            <button class="cancelButton btn btn-danger" style="display: none;">
                                Cancel
                            </button>
                        </td>
                    </tr>`;
                }
            });
            // Update the innerHTML of the tbody element
            document.getElementById('degreeRows').innerHTML = tbodyContent;
            addButton.disabled = false;

            // add event listener to delete and edit button
            addDeleteEventListenerToButton();
            addEditEventListenerToButton();
        })
        .catch(error => {
            console.error('Error fetching degrees:', error);
        });
    }

    // this function is called after javascript render a new tbody during the runtime
    function addDeleteEventListenerToButton(){
        document.querySelectorAll('.deleteButton').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const id = this.getAttribute('data-id');

                var to_be_deleted_data = {
                    customers_id: {{ $customer->id }},
                    id: id,
                };
                console.log('To be deleted data: ',to_be_deleted_data);

                if(action === 'deleteDegree'){
                    fetch('{{ route('customer.delete_degree') }}', {
                        method: 'POST',
                        body: JSON.stringify(to_be_deleted_data),
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json' }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                            return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        // fetchAllDegrees(data.id);
                        fetchAllDegrees();
                    })
                    .catch(error => {
                        console.error('Error performing deletion:', error);
                    });
                }
                else if (action === 'deleteSale') {
                    fetch('{{ route('sale.delete_sale') }}', {
                        method: 'POST',
                        body: JSON.stringify(to_be_deleted_data),
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Content-Type': 'application/json' }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                            return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        // fetchAllDegrees(data.id);
                        // fetchAllDegrees();
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error performing deletion:', error);
                    });
                }
                
            });
        });
    }

    function addEditEventListenerToButton(){
        document.querySelectorAll('.editButton').forEach(button => {
            button.addEventListener('click', function() {
                
                // get degree id
                const id = this.getAttribute('data-id');

                var to_be_editted_degree = {
                    customers_id: {{ $customer->id }},
                    id: id, // degree id
                };

                var row = button.closest('tr');
        
                // Get the left eye degree and right eye degree cells
                var leftEyeCell = row.querySelector('.left-eye-cell');
                var rightEyeCell = row.querySelector('.right-eye-cell');
                
                // Get the current values
                var leftEyeValue = leftEyeCell.textContent.trim();
                var rightEyeValue = rightEyeCell.textContent.trim();
                
                // Replace the cells with input fields
                leftEyeCell.innerHTML = '<input id="updatedLeftEyeValue" type="text" class="form-control capitalize" value="' + leftEyeValue + '" maxlength="255">';
                rightEyeCell.innerHTML = '<input id="updatedRightEyeValue" type="text" class="form-control capitalize" value="' + rightEyeValue + '" maxlength="255">';

                button.style.display = 'none';
                row.querySelector('.deleteButton').style.display = 'none';

                row.querySelector('.saveButton').style.display = 'inline-block';
                row.querySelector('.cancelButton').style.display = 'inline-block';

                // Disable all other edit & delete buttons
                toggleButtons('editButton', false);
                toggleButtons('deleteButton', false);

                capitalize();

                // when save edit button clicked
                row.querySelector('.saveButton').addEventListener('click', function(){
                    updateDegree(to_be_editted_degree);
                });

                // when cancel button clicked
                row.querySelector('.cancelButton').addEventListener('click', function(){
                    fetchAllDegrees();
                });
            });
        });
    }

    function updateDegree(to_be_editted_degree){
        var data = {
            id: to_be_editted_degree.id,
            customers_id: to_be_editted_degree.customers_id,
            left_eye_degree: document.getElementById('updatedLeftEyeValue').value,
            right_eye_degree: document.getElementById('updatedRightEyeValue').value,
        }

        // console.log('To be updated degree data',data );
        fetch('{{ route('customer.update_degree') }}', {
            method: 'POST',
            body: JSON.stringify(data), // Convert data to JSON
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
        })
        .then(response => {
            if (!response.ok) {
                console.log("Response not okay. Sent data: ",data);
                throw new Error('Network response was not ok: ',response.status);
            }
            return response.json();
        })
        .then(data =>{
            // console.log(JSON.stringify(data.id));
            fetchAllDegrees();
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error.message);
        });
    }

    function capitalize(){
        document.querySelectorAll('.capitalize').forEach(input => {
        // Add an event listener for the 'input' event
        input.addEventListener('input', function() {
            // Capitalize the input value
            this.value = this.value.toUpperCase();
        });
    });
    }

    // END
// #############################################################################################################################

// ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░▒▒▒▒░░░▒▒▒▒░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒░▒▒▒▒▒▒░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒▒▒▒▒▒▒░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒▒▒▒▒░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒▒▒░░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒░░░░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░▒░░░░░░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒░▒▒▒░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒▒▒░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒▒▒░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒▒▒░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▒▒▒░░░░░▓▓
// ▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░▒░░░░░░▓▓
// ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
// _______▒__________▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// ______▒_______________▒▒▒▒▒▒▒▒
// _____▒________________▒▒▒▒▒▒▒▒
// ____▒___________▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒
// ___▒
// __▒______▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
// _▒______▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓
// ▒▒▒▒___▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓
// ▒▒▒▒__▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓▒▓
// ▒▒▒__▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓
// ▒▒



// ## THESE CODE BELOW WILL BE LOADED WHEN REFRESH OR GO INTO THIS PAGE ##
// ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

// ##################################################################################################################################
//    _____          _                              _____       _        _ _            _                                _       _   
//   / ____|        | |                            |  __ \     | |      (_| |          | |                              (_)     | |  
//  | |    _   _ ___| |_ ___  _ __ ___   ___ _ __  | |  | | ___| |_ __ _ _| |___       | | __ ___   ____ _ ___  ___ _ __ _ _ __ | |_ 
//  | |   | | | / __| __/ _ \| '_ ` _ \ / _ | '__| | |  | |/ _ | __/ _` | | / __|  _   | |/ _` \ \ / / _` / __|/ __| '__| | '_ \| __|
//  | |___| |_| \__ | || (_) | | | | | |  __| |    | |__| |  __| || (_| | | \__ \ | |__| | (_| |\ V | (_| \__ | (__| |  | | |_) | |_ 
//   \_____\__,_|___/\__\___/|_| |_| |_|\___|_|    |_____/ \___|\__\__,_|_|_|___/  \____/ \__,_| \_/ \__,_|___/\___|_|  |_| .__/ \__|
//                                                                                                                        | |        
//                                                                                                                        |_|        
// ## LOAD THESE CODE BELOW WHEN REFRESH OR GO INTO THIS PAGE ##

// Get all input elements and the textarea element
    capitalize();
    const inputs = document.querySelectorAll('input[type="text"]');
    const textarea = document.getElementById('remarks');
    const saveButton = document.getElementById('saveButton');
    const addButton = document.getElementById('addDegreeButton');

    // Function to check if any input has changed
    function checkChanges() {
        // Show the save button if there are changes in any input or textarea
        const hasChanges = Array.from(inputs).some(input => input.value.trim() !== '') || textarea.value.trim() !== '';
        saveButton.style.display = hasChanges ? 'block' : 'none';
    }

    // Add event listeners to detect changes in all input elements
    inputs.forEach(input => input.addEventListener('input', checkChanges));
    // Add event listener to detect changes in the textarea
    textarea.addEventListener('input', checkChanges);

// END
// #############################################################################################################################

// ───────────▒▒▒▒▒▒▒▒
// ─────────▒▒▒──────▒▒▒
// ────────▒▒───▒▒▒▒──▒░▒
// ───────▒▒───▒▒──▒▒──▒░▒
// ──────▒▒░▒──────▒▒──▒░▒
// ───────▒▒░▒────▒▒──▒░▒
// ─────────▒▒▒▒▒▒▒───▒▒
// ─────────────────▒▒▒
// ─────▒▒▒▒────────▒▒
// ───▒▒▒░░▒▒▒─────▒▒──▓▓▓▓▓▓▓▓
// ──▒▒─────▒▒▒────▒▒▓▓▓▓▓░░░░░▓▓──▓▓▓▓
// ─▒───▒▒────▒▒─▓▓▒░░░░░░░░░█▓▒▓▓▓▓░░▓▓▓
// ▒▒──▒─▒▒───▓▒▒░░▒░░░░░████▓▓▒▒▓░░░░░░▓▓
// ░▒▒───▒──▓▓▓░▒░░░░░░█████▓▓▒▒▒▒▓▓▓▓▓░░▓▓
// ──▒▒▒▒──▓▓░░░░░░███████▓▓▓▒▒▒▒▒▓───▓▓░▓▓
// ──────▓▓░░░░░░███████▓▓▓▒▒▒▒▒▒▒▓───▓░░▓▓
// ─────▓▓░░░░░███████▓▓▓▒▒▒▒▒▒▒▒▒▓▓▓▓░░▓▓
// ────▓▓░░░░██████▓▓▓▓▒▒▒▒▒▒▒▒▒▒▒▓░░░░▓▓
// ────▓▓▓░████▓▓▓▓▓▒▒▒▒▒▒▒▒▒▒▒▒▒▓▓▓▓▓▓
// ─────▓▓▓▓▓▓▓▓▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▓▓
// ─────▓▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▓▓▓
// ──────▓▓▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▓▓▓
// ───────▓▓▓▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▓▓▓▓
// ─────────▓▓▓▒▒▒▒▒▒▒▒▒▒▒▒▓▓▓▓
// ───────────▓▓▓▓▓▓▒▒▒▒▒▓▓▓▓
// ───────────────▓▓▓▓▓▓▓▓


// #############################################################################################################################
//  __      ___     _               _    _ _     _                   _            _                                _       _   
//  \ \    / (_)   (_)             | |  | (_)   | |                 ( )          | |                              (_)     | |  
//   \ \  / / _ ___ _  ___  _ __   | |__| |_ ___| |_ ___  _ __ _   _|/ ___       | | __ ___   ____ _ ___  ___ _ __ _ _ __ | |_ 
//    \ \/ / | / __| |/ _ \| '_ \  |  __  | / __| __/ _ \| '__| | | | / __|  _   | |/ _` \ \ / / _` / __|/ __| '__| | '_ \| __|
//     \  /  | \__ | | (_) | | | | | |  | | \__ | || (_) | |  | |_| | \__ \ | |__| | (_| |\ V | (_| \__ | (__| |  | | |_) | |_ 
//      \/   |_|___|_|\___/|_| |_| |_|  |_|_|___/\__\___/|_|   \__, | |___/  \____/ \__,_| \_/ \__,_|___/\___|_|  |_| .__/ \__|
//                                                              __/ |                                               | |        
//                                                             |___/                                                |_|        
//

    // to assign all editButton & deleteButton an event listener
    addEditEventListenerToButton();
    addDeleteEventListenerToButton();

    // Add event listener to the addDegreeButton
    document.getElementById('addDegreeButton').addEventListener('click', function() {
        const customerId = document.getElementById('customerData').dataset.customerId;
        // Hide the "addDegreeButton" button
        // var addButton = document.getElementById('addDegreeButton');
        // addButton.style.display = 'none';
        addButton.disabled = true; // Add disabled attribute to make the button unclickable

        // Create three input fields
        var dateInput = document.createElement('input');
        dateInput.type = 'text';
        dateInput.className = 'form-control text-center';
        dateInput.value = formatDate(new Date()); // Set value to today's date
        dateInput.disabled = true; // Disable the input

        var leftEyeInput = document.createElement('input');
        leftEyeInput.type = 'text';
        leftEyeInput.className = 'form-control';

        var rightEyeInput = document.createElement('input');
        rightEyeInput.type = 'text';
        rightEyeInput.className = 'form-control';

        // Create a "Save" button
        var saveButton = document.createElement('button');
        saveButton.type = 'button';
        saveButton.className = 'btn btn-outline-success';
        saveButton.textContent = '✓';
        saveButton.style.fontWeight = 'bold';
        saveButton.disabled = true;

        // Add event listeners to the input fields
        leftEyeInput.addEventListener('input', toggleSaveButton);
        rightEyeInput.addEventListener('input', toggleSaveButton);

        // Function to toggle the save button based on input values
        function toggleSaveButton() {
            var leftEyeDegree = leftEyeInput.value.trim();
            var rightEyeDegree = rightEyeInput.value.trim();

            // Enable the save button if at least one input field has a value, otherwise disable it
            saveButton.disabled = (leftEyeDegree === '' && rightEyeDegree === '');
        }

        // Add event listener to the "Save" button. Once user click "Save",
        // send the data to route.
        saveButton.addEventListener('click', function() {
            var leftEyeDegree = leftEyeInput.value.toUpperCase();
            var rightEyeDegree = rightEyeInput.value.toUpperCase();

            var customer_new_degree = {
                id: {{ $customer->id }},
                leftEyeDegree: leftEyeDegree,
                rightEyeDegree: rightEyeDegree
            };

            fetch('{{ route('customer.add_new_degree') }}', {
                method: 'POST',
                body: JSON.stringify(customer_new_degree), // Convert data to JSON
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
            })
            .then(response => {
                if (!response.ok) {
                    console.log("Sent data: ",customer_new_degree);
                    throw new Error('Network response was not okkkk: ',response.status);
                }
                return response.json();
            })
            .then(data =>{
                // console.log(JSON.stringify(data.id));
                fetchAllDegrees();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error.message);
            });
        });

        // Create a "Cancel" button
        var cancelButton = document.createElement('button');
        cancelButton.type = 'button';
        cancelButton.className = 'btn btn-outline-danger';
        cancelButton.textContent = 'X';
        cancelButton.style.fontWeight = 'bold';

        // Add event listener to the "Cancel" button
        cancelButton.addEventListener('click', function() {
            // Remove the new row
            newRow.remove();
            // Show the "addDegreeButton" button again
            // addButton.style.display = 'block';
            addButton.disabled = false; // Enable the button again
        });

        // Create a new row
        var newRow = document.createElement('tr');
        
        // Create and append table data elements to the row
        var dateCell = document.createElement('td');
        dateCell.className = 'text-center'; // Added text-center class
        dateCell.appendChild(dateInput);
        newRow.appendChild(dateCell);

        var leftEyeCell = document.createElement('td');
        leftEyeInput.addEventListener('input', function() {
            // Convert the input value to uppercase
            this.value = this.value.toUpperCase();
        });
        leftEyeCell.appendChild(leftEyeInput);
        newRow.appendChild(leftEyeCell);

        var rightEyeCell = document.createElement('td');
        rightEyeInput.addEventListener('input', function() {
            // Convert the input value to uppercase
            this.value = this.value.toUpperCase();
        });
        rightEyeCell.appendChild(rightEyeInput);
        newRow.appendChild(rightEyeCell);

        // Create a cell for the "Save" button
        var saveButtonCell = document.createElement('td');
        saveButtonCell.appendChild(saveButton);
        newRow.appendChild(saveButtonCell);

        // Create a cell for the "Cancel" button
        var cancelButtonCell = document.createElement('td');
        cancelButtonCell.appendChild(cancelButton);
        newRow.appendChild(cancelButtonCell);

        // Get the first row in the tbody
        var firstRow = document.getElementById('degreeRows').rows[0];

        // Insert the new row before the first row
        document.getElementById('degreeRows').insertBefore(newRow, firstRow);
        
    });

    // END
// #############################################################################################################################

// ██████████████████████████
// ▌════════════════════════▐
// ▌══▄▄▓█████▓▄═════▄▄▓█▓▄═▐ 
// ▌═▄▓▀▀▀██████▓▄═▄▓█████▓▌▐
// ▌═══════▄▓███████████▓▀▀▓▐ 
// ▌═══▄▓█████████▓████▓▄═══▐
// ▌═▄▓████▓███▓█████████▓▄═▐ 
// ▌▐▓██▓▓▀▀▓▓███████▓▓▀▓█▓▄▐
// ▌▓▀▀════▄▓██▓██████▓▄═▀▓█▐
// ▌══════▓██▓▀═██═▀▓██▓▄══▀▐
// ▌═════▄███▀═▐█▌═══▀▓█▓▌══▐ 
// ▌════▐▓██▓══██▌═════▓▓█══▐
// ▌════▐▓█▓══▐██═══════▀▓▌═▐
// ▌═════▓█▀══██▌════════▀══▐
// ▌══════▀═══██▌═══════════▐ 
// ▌═════════▐██▌═══════════▐
// ▌═════════▐██════════════▐
// ▌═════════███════════════▐
// ▌═════════███════════════▐ 
// ▌════════▐██▌════════════▐
// ▌▓▓▓▓▓▓▓▓▐██▌▓▓▓▓▓▓▓▓▓▓▓▓▐
// ▌▓▓▓▓▓▓▓▓▐██▌▓▓▓▓▓▓▓▓▓▓▓▓▐
// ▌▓▓▓▓▓▄▄██████▄▄▄▓▓▓▓▓▓▓▓▐ 
// ██████████████████████████
</script>
@endsection