@extends ('layoutNavigate')
@section('title',__('txt.link.customer.add_new'))
@section('content')
<!-- <div class="container mt-1"> -->
<div class="container mt-2">
<div class="row align-items-start">
    
<div class="col border border-primary p-3">

    <h1 class="fs-5 text-body-emphasis align-items-center" style="text-decoration: underline;">
        Customer Details
    </h1>

    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked="true" onclick="toggleCustomerDetails()">
        <label class="form-check-label h6" for="flexSwitchCheckChecked">Insert Customer Details</label>
    </div>

    <form method="post" action="{{ route('customer.store') }}">
    @csrf
    @method('post')
        <div class="input-group">
            <span class="input-group-text" id="basic-addon1">Customer Name</span>
            <input type="text" id="search-input" class="customer-details form-control" name="name" aria-describedby="basic-addon1" maxlength="255">
            
        </div>
        
        <div id="search-results" class="position-absolute w-50 border border-secondary bg-white" style="z-index: 1000; display: none; left: 18.55%;";></div>

        <div class="input-group mt-3 mb-3">
            <span class="input-group-text" id="basic-addon1">IC/Passport No.</span>
            <input type="text" class="customer-details form-control" name="ic_passport_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tel. No.</span>
            <input type="text" class="customer-details form-control" name="telephone_num" aria-describedby="basic-addon1" maxlength="30">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Home Address</span>
            <input type="text" class="customer-details form-control" name="address" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Left SPH</span>
            <button class="btn btn-outline-success" type="button">Update</button>
            <button class="btn btn-outline-success" type="button" style="fontWeight: bold;">✓</button>
            <button class="btn btn-outline-danger" type="button" style="fontWeight: bold;">X</button>
            <input type="text" class="customer-details form-control" name="left_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Right SPH</span>
            <button class="btn btn-outline-success" type="button">Update</button>
            <button class="btn btn-outline-success" type="button" style="fontWeight: bold;">✓</button>
            <button class="btn btn-outline-danger" type="button" style="fontWeight: bold;">X</button>
            <input type="text" class="customer-details form-control" name="right_eye_degree" aria-describedby="basic-addon1" maxlength="255">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text">Remarks</span>
            <textarea class="customer-details form-control" name="remarks" maxlength="255"></textarea>
        </div>
    </form>
</div>

<div class="col">
    <h1 class="fs-5 text-body-emphasis align-items-center mt-3 mb-3" style="text-decoration: underline;">
        Sales Details
    </h1>

    <form method="post" action="{{ route('customer.store') }}">
    @csrf
    @method('post')
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Description</span>
            <textarea class="form-control" name="description" maxlength="255"></textarea>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Price</span>
            <span class="input-group-text">RM</span>
            <!-- <input type="text" class="form-control" name="price" aria-describedby="basic-addon1" maxlength="30"> -->
            <input class="form-control" type="number" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)">
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Payment Status</span>
            <!-- <input type="text" class="form-control" name="payment_status" aria-describedby="basic-addon1" maxlength="30"> -->
            <select class="form-select" aria-label="Default select example">
                <option value="1" selected>Fully Paid</option>
                <option value="2">Not Yet Fully Paid</option>
            </select>
        </div>

        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Deposit</span>
            <span class="input-group-text">RM</span>
            <!-- <input type="text" class="form-control" name="deposit" aria-describedby="basic-addon1" maxlength="255"> -->
            <input class="form-control" type="number" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)">
        </div>

        <!-- <div class="input-group mb-3" style="display: none;"> -->
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Remaining</span>
            <span class="input-group-text">RM</span>
            <input class="form-control" type="number" disabled>
        </div>
    </form>
    </div>

</div>

    <div class="row align-items-start mt-3">
            <button id="saveButton" class="btn btn-success" type="submit">Save</button>
    </div>
</div>

<script>
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
        } else {
            // If not checked, disable all elements with the "customer-details" class
            for (var i = 0; i < customerDetailsElements.length; i++) {
                customerDetailsElements[i].disabled = true;
                customerDetailsElements[i].value = '';
            }
        }
    }

    $(document).ready(function()
    {
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

        // Function to display suggestions
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
                window.location.href = '{{ route("customer.detail", ["id" => ":id"]) }}'.replace(':id', suggestion.id);
            });

                resultsDiv.append(resultDiv);
            });
            resultsDiv.show();
        }

        // Autocomplete functionality
        $('#search-input').on('input', function() {
            var query = $(this).val();

            console.log('in auto complete');

            if (query.length >= 1) {
                fetchSuggestions(query);
            } else {
                $('#search-results').hide();
            }
        });    

        $(document).click(function(event) {
            if (!$(event.target).closest('#search-input, #search-results').length) {
                $('#search-results').hide();
            }
        });

        // Clear text button functionality
        $('#clear-search-input').click(function() {
            $('#search-input').val('').focus();
            $('#clear-search-button').hide();
            $('#search-results').hide();
        });

        // Show or hide clear text button based on input value
        $('#search-input').on('input', function() {
            if ($(this).val().length > 0) {
                $('#clear-search-button').show();
            } else {
                $('#clear-search-button').hide();
            }
        });
    });


</script>
@endsection