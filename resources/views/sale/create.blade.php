@extends ('layoutNavigate')
@section('title',__('txt.link.sale.add_new'))
@section('content')
<!-- <div class="container mt-1"> -->
<div class="container mt-2">
<div class="row align-items-start">
    
<div class="col">
    <h1 class="fs-5 text-body-emphasis align-items-center" style="text-decoration: underline;">
        Customer Details
    </h1>

    <div class="row">
        <div class="col">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked="true" onclick="toggleCustomerDetails()">
                <label class="form-check-label h6" for="flexSwitchCheckChecked">Insert Customer Details</label>
            </div>
        </div>

        <div class="col">
            <a href="#" class="text-danger" id="clearCustomerInputs">Clear</a>
        </div>
    </div>
    
    <form method="post" id="customerForm">
    @csrf
    @method('post')
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1">Customer Name</span>
            <input autocomplete="off" type="text" id="customer_name" class="customer-details form-control capitalize" name="name" aria-describedby="basic-addon1" maxlength="255">            
        </div>
        <div class="input-group" id="warning-message" style="color: red;"></div>
        
        <div id="search-results" class="position-absolute w-50 border border-secondary bg-white" style="z-index: 1000; display: none; left: 18.55%;";></div>

        <div class="input-group mt-3 mb-3">
            <span class="input-group-text" id="basic-addon1">IC/Passport No.</span>
            <input type="text" id="ic_passport_num" class="customer-details form-control capitalize" name="ic_passport_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tel. No.</span>
            <input type="text" id="telephone_num" class="customer-details form-control capitalize" name="telephone_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Home Address</span>
            <input type="text" id="address" class="customer-details form-control capitalize" name="address" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Left SPH</span>
            <button id="left_eye_degree_update" class="btn btn-outline-success" type="button">Update</button>
            <button id="left_eye_degree_confirm" class="btn btn-outline-success hide" type="button" style="fontWeight: bold;">✓</button>
            <button id="left_eye_degree_cancel" class="btn btn-outline-danger hide" type="button" style="fontWeight: bold;">X</button>
            <input type="text" id="left_eye_degree" class="customer-details form-control capitalize" name="left_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Right SPH</span>
            <button id="right_eye_degree_update" class="btn btn-outline-success" type="button">Update</button>
            <button id="right_eye_degree_confirm" class="btn btn-outline-success hide" type="button" style="fontWeight: bold;">✓</button>
            <button id="right_eye_degree_cancel" class="btn btn-outline-danger hide" type="button" style="fontWeight: bold;">X</button>
            <input type="text" id="right_eye_degree" class="customer-details form-control capitalize" name="right_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Remarks</span>
            <textarea id="remarks" class="customer-details form-control capitalize" name="remarks" maxlength="255"></textarea>
        </div>
    </form>
</div>

<div class="col">
    <h1 class="fs-5 text-body-emphasis align-items-center mt-3 mb-3" style="text-decoration: underline;">
        Sales Details
    </h1>

<form>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Sale's Date</span>
            <input type="text" class="form-control" id="sale_date" name="sale_date" autocomplete="off">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Description</span>
            <textarea class="form-control capitalize" id="sale_description" maxlength="255"></textarea>
        </div>

        <div class="input-group" id="warning-message-sale-description" style="color: red;"></div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Price</span>
            <span class="input-group-text">RM</span>
            <!-- <input type="text" class="form-control" name="sale_price" aria-describedby="basic-addon1" maxlength="30"> -->
            <!-- <input class="form-control" id="sale_price" type="number" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)"> -->
            <input class="form-control" id="sale_price" type="number" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)" value="1.00" onkeydown="preventDelete(event)">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Payment Status</span>
            <!-- <input type="text" class="form-control" name="payment_status" aria-describedby="basic-addon1" maxlength="30"> -->
            <select class="form-select" id="paymentStatus" aria-label="Default select example" onchange="toggleInputs()">
                <option value="1" selected>Fully Paid</option>
                <option value="2">Pay by Deposit</option>
            </select>
        </div>

        <div class="input-group mb-3">
            <span class="deposit input-group-text" id="basic-addon1">Deposit</span>
            <span class="deposit input-group-text">RM</span>
            <input class="deposit form-control" id="sale_deposit" type="number" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)" value="1.00" onkeydown="preventDelete(event)">
        </div>

        <div class="input-group mb-1">
            <span class="remaining input-group-text" id="basic-addon1">Remaining</span>
            <span class="remaining input-group-text">RM</span>
            <input class="remaining form-control" id="sale_remaining" type="number" disabled>
        </div>
</form>
    </div>

</div>
    <div class="row align-items-start mt-3">
            <button id="submitButton" class="btn btn-success" type="submit">Submit</button>
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

    var customerId = null;
    var original_left_eye_degree = '';
    var original_right_eye_degree = '';
    const textareas = document.querySelectorAll('.capitalize');

    // Add event listener for input event to each textarea
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            // Capitalize the text in the textarea
            this.value = this.value.toUpperCase();
        });
    });

    // Define the mapping between suggestion properties and input IDs
    const propertyToInputMap = {
            'name': 'customer_name',
            'ic_passport_num': 'ic_passport_num',
            'telephone_num': 'telephone_num',
            'address': 'address',
            // 'latest_degree': {
            //     'left_eye_degree': 'left_eye_degree',
            //     'right_eye_degree': 'right_eye_degree',
            // },
            // 'left_eye_degree': 'left_eye_degree',
            // 'right_eye_degree': 'right_eye_degree',
            'remarks': 'remarks'
        };

    // function to check if the switch is toggle
    function toggleCustomerDetails() {
        // Get the checkbox element
        var checkbox = document.getElementById('flexSwitchCheckChecked');

        var customerDetailsElements = document.getElementsByClassName('customer-details');

        // Check if the checkbox is checked
        if (checkbox.checked) {
            // If checked, enable all elements with the "customer-details" class
            for (var i = 0; i < customerDetailsElements.length; i++) {
                customerDetailsElements[i].disabled = false;
            }
            $('#customerForm').show();
        } else {
            // If not checked, disable all elements with the "customer-details" class
            for (var i = 0; i < customerDetailsElements.length; i++) {
                customerDetailsElements[i].disabled = true;
                customerDetailsElements[i].value = '';
            }
            $('#customerForm').hide();
        }
    }

    // function to disable all customer details input
    function disableCustomerDetailsInput() {
        // document.getElementById('flexSwitchCheckChecked').disabled = true;

        var customerDetailsElements = document.getElementsByClassName('customer-details');

        for (var i = 0; i < customerDetailsElements.length; i++) {
            customerDetailsElements[i].disabled = true;
        }
    }

    // Function to auto fetch suggestions --> WHEN USER ENTER TEXT INTO SEARCH BOX <--
    function fetchSuggestions(query) {
        $.ajax({
            url: '{{ route("suggest") }}',
            method: 'GET',
            data: { query: query },
            success: function(data) {
                displaySuggestions(data);
            }
        });
    }

    // Function to display suggestions and create link to all suggestion
    function displaySuggestions(suggestions) {
        var resultsDiv = $('#search-results');
        resultsDiv.empty();
        $.each(suggestions, function(index, suggestion) {
            var resultDiv = $('<div>').addClass('result-item p-2').text(suggestion.name);
        
            // Change background color when mouse hovers over the div
            resultDiv.hover(function() {
                $(this).addClass('hovered');
            }, function() {
                $(this).removeClass('hovered');
            });

            resultDiv.click(function() {
                // window.location.href = '{{ route("customer.detail", ["id" => ":id"]) }}'.replace(':id', suggestion.id);
                populateCustomerDetails(suggestion);
            });

            resultsDiv.append(resultDiv);
        });
        resultsDiv.show();
    }

    // function to populate the customer details input after user click the suggestions
    function populateCustomerDetails(suggestion){
        $('#search-results').hide();

        Object.keys(propertyToInputMap).forEach(property => {
            const inputValue = suggestion[property];
            const inputId = propertyToInputMap[property];

            // Set the value of the input box if the value is not null
            if (inputValue !== null && inputValue !== undefined) {
                document.getElementById(inputId).value = inputValue;
            }            
        });
        
        document.getElementById('left_eye_degree').value = suggestion.latest_degree?.left_eye_degree ?? '';
        original_left_eye_degree = suggestion.latest_degree?.left_eye_degree ?? '';

        document.getElementById('right_eye_degree').value = suggestion.latest_degree?.right_eye_degree ?? '';
        original_right_eye_degree = suggestion.latest_degree?.right_eye_degree ?? '';

        customerId = suggestion.id;
        disableCustomerDetailsInput();
    }

    function preventDelete(event) {
        if(event.key === 'Backspace' || event.key === 'Delete') {
            event.preventDefault(); // Prevent default behavior (deleting the digit)
            event.target.value = '1.00'; // Set input value to 0
        }
    }

    function toggleInputs() {
        var paymentStatus = document.getElementById('paymentStatus');
        var deposit = document.getElementsByClassName('deposit');
        var remaining = document.getElementsByClassName('remaining');

        if (paymentStatus.value === '2') {
            for (var i = 0; i < deposit.length; i++) {
                deposit[i].style.display = 'block';
            }

            for (var i = 0; i < remaining.length; i++) {
                remaining[i].style.display = 'block';
            }
        } else {
            for (var i = 0; i < deposit.length; i++) {
                deposit[i].style.display = 'none';
            }

            for (var i = 0; i < remaining.length; i++) {
                remaining[i].style.display = 'none';
            }

            deposit.value = 0.00;
            // console.log('deposit: ',deposit.value);
        }
    }

    function calculateRemaining(){
        var sale_deposit = parseFloat(document.getElementById("sale_deposit").value) || 0;
        var sale_price = parseFloat(document.getElementById("sale_price").value) || 0;
        
        var remainingAmount = sale_price - sale_deposit;
        
        document.getElementById("sale_remaining").value = remainingAmount.toFixed(2);
    }

    // to submit data
    function submitSale(){
        var data = {
            id: to_be_editted_degree.id,
            customers_id: to_be_editted_degree.customers_id,
            left_eye_degree: document.getElementById('updatedLeftEyeValue').value,
            right_eye_degree: document.getElementById('updatedRightEyeValue').value,
        }

        // console.log('To be updated degree data',data );
    }

    // function to auto insert today's sale_date
    function autoInsertTodayDate(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        // var currentDate = yyyy + '-' + mm + '-' + dd;
        var currentDate = dd + '-' + mm + '-' + yyyy;

        // Set the input field value to the current sale_date
        $('#sale_date').val(currentDate);
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

// initial load
    Object.keys(propertyToInputMap).forEach(property => {
        const inputId = propertyToInputMap[property];
        document.getElementById(inputId).value = '';
    });

    toggleInputs();
    calculateRemaining();
    autoInsertTodayDate();

// END-------initial load-------END


// START-----LIVE CAPTURE-----START
    $(document).ready(function(){
        // auto calculate the sale_remaining sale_price
        document.getElementById("sale_price").addEventListener("input", calculateRemaining);
        document.getElementById("sale_deposit").addEventListener("input", calculateRemaining);

        // Autocomplete functionality
        $('#customer_name').on('input', function() {
            var query = $(this).val();

            if (query.length >= 1) {
                fetchSuggestions(query);
            } else {
                $('#search-results').hide();
            }
        });    

        // hide the suggestion box once user click other element in the page
        $(document).click(function(event) {
            if (!$(event.target).closest('#customer_name, #search-results').length) {
                $('#search-results').hide();
            }
        });

        // clearing the customer informations in the form
        $('#clearCustomerInputs').click(function(){
            var customerDetailsElements = document.getElementsByClassName('customer-details');

            Object.keys(propertyToInputMap).forEach(property => {
                const inputId = propertyToInputMap[property];
                document.getElementById(inputId).value = '';
            });

            // document.getElementById('flexSwitchCheckChecked').disabled = false;

            for (var i = 0; i < customerDetailsElements.length; i++) {
                customerDetailsElements[i].disabled = false;
            }
            // $('#clearCustomerInputs').show();
            customerId = null;
        });

        // submit the information to controller
        $('#submitButton').click(function(event) { 
            // Prevent the default form submission
            event.preventDefault();

            var checkbox = document.getElementById('flexSwitchCheckChecked');
            const customerNameValue = document.getElementById('customer_name').value.trim();
            const saleDescriptionValue = document.getElementById('sale_description').value.trim();

            // if customer detail checkbox is checked
            if (checkbox.checked){
                if (customerNameValue === ''){
                    event.preventDefault();
                    document.getElementById('customer_name').classList.add('input-error');
                    document.getElementById('warning-message').textContent = '*Please insert customer name';

                }else if (saleDescriptionValue === ''){
                    event.preventDefault();
                    document.getElementById('sale_description').classList.add('input-error');
                    document.getElementById('warning-message-sale-description').textContent = '*Please insert sale description';
                
                }else{
                    var new_sale_data = {
                        // customer data
                        checkbox: true,
                        customerId: checkbox.checked ? customerId : null,
                        name: checkbox.checked ? customer_name.value : null,
                        ic_passport_num: checkbox.checked ? ic_passport_num.value : null,
                        telephone_num: checkbox.checked ? telephone_num.value : null,
                        address: checkbox.checked ? address.value : null,
                        left_eye_degree: checkbox.checked ? left_eye_degree.value : null,
                        right_eye_degree: checkbox.checked ? right_eye_degree.value : null,
                        remarks: checkbox.checked ? remarks.value : null,

                        // sale data
                        sale_date: sale_date.value,
                        description: sale_description.value,
                        price: sale_price.value,
                        is_paid: paymentStatus.value === "1" ? true : false,
                        deposit: paymentStatus.value === "1" ? 0.00 : (paymentStatus.value === "2" ? sale_deposit.value : null),
                        sale_remaining: paymentStatus.value === "1" ? 0.00 : (paymentStatus.value === "2" ? sale_remaining.value : null),
                    };
                    console.log('new_sale_data: ', new_sale_data);

                    fetch('{{ route('sale.store') }}', {
                        method: 'POST',
                        body: JSON.stringify(new_sale_data), // Convert data to JSON
                        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ',response.status);
                        }
                        return response.json();
                    })
                    .then(data =>{
                        window.location.href = '/sale/list';
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error.message);
                    });
                }
            }else{ //if customer detail checkbox is NOT checked
                if (saleDescriptionValue === ''){
                    event.preventDefault();
                    document.getElementById('sale_description').classList.add('input-error');                    
                    document.getElementById('warning-message-sale-description').textContent = '*Please insert sale description';
                
                }else{
                    var new_sale_data = {
                        // customer data
                        checkbox: false,
                        customerId: checkbox.checked ? customerId : null,
                        name: checkbox.checked ? customer_name.value : null,
                        ic_passport_num: checkbox.checked ? ic_passport_num.value : null,
                        telephone_num: checkbox.checked ? telephone_num.value : null,
                        address: checkbox.checked ? address.value : null,
                        left_eye_degree: checkbox.checked ? left_eye_degree.value : null,
                        right_eye_degree: checkbox.checked ? right_eye_degree.value : null,
                        remarks: checkbox.checked ? remarks.value : null,

                        // sale data
                        sale_date: sale_date.value,
                        description: sale_description.value,
                        price: sale_price.value,
                        is_paid: paymentStatus.value === "1" ? true : false,
                        deposit: paymentStatus.value === "1" ? 0.00 : (paymentStatus.value === "2" ? sale_deposit.value : null),
                        sale_remaining: paymentStatus.value === "1" ? 0.00 : (paymentStatus.value === "2" ? sale_remaining.value : null),
                    };
                    console.log('new_sale_data: ', new_sale_data);

                    fetch('{{ route('sale.store') }}', {
                        method: 'POST',
                        body: JSON.stringify(new_sale_data), // Convert data to JSON
                        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ',response.status);
                        }
                        return response.json();
                    })
                    .then(data =>{
                        window.location.href = '/sale/list';
                        console.log(data);
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error.message);
                    });
                }
            }
            
        });

        // customer update degree button
        $('#left_eye_degree_update').click(function(event){
            this.style.display = 'none';
            document.getElementById('left_eye_degree_confirm').style.display = 'inline-block';
            document.getElementById('left_eye_degree_cancel').style.display = 'inline-block';

            document.getElementById('left_eye_degree').disabled = false;
        });

        $('#right_eye_degree_update').click(function(event){
            this.style.display = 'none';
            document.getElementById('right_eye_degree_confirm').style.display = 'inline-block';
            document.getElementById('right_eye_degree_cancel').style.display = 'inline-block';

            document.getElementById('right_eye_degree').disabled = false;
        });

        // customer confirm degree button
        $('#left_eye_degree_confirm').click(function(event){
            this.style.display = 'none';
            document.getElementById('left_eye_degree_cancel').style.display = 'none';

            document.getElementById('left_eye_degree_update').style.display = 'inline-block';

            document.getElementById('left_eye_degree').disabled = true;
        });

        $('#right_eye_degree_confirm').click(function(event){
            this.style.display = 'none';
            document.getElementById('right_eye_degree_cancel').style.display = 'none';

            document.getElementById('right_eye_degree_update').style.display = 'inline-block';

            document.getElementById('right_eye_degree').disabled = true;
        });

        // customer cancel degree button
        $('#left_eye_degree_cancel').click(function(event){
            this.style.display = 'none';
            document.getElementById('left_eye_degree_confirm').style.display = 'none';

            document.getElementById('left_eye_degree_update').style.display = 'inline-block';

            document.getElementById('left_eye_degree').disabled = true;
            document.getElementById('left_eye_degree').value = original_left_eye_degree;
        });

        $('#right_eye_degree_cancel').click(function(event){
            this.style.display = 'none';
            document.getElementById('right_eye_degree_confirm').style.display = 'none';

            document.getElementById('right_eye_degree_update').style.display = 'inline-block';

            document.getElementById('right_eye_degree').disabled = true;
            document.getElementById('right_eye_degree').value = original_right_eye_degree;
        });

        $('#sale_date').datepicker({
            format: 'dd-mm-yyyy',  // Date format to be saved in the input field
            autoclose: true,       // Close the datepicker after selecting a date
            todayHighlight: true   // Highlight today's date
        });
    });

// END-----LIVE CAPTURE-----END
</script>
@endsection