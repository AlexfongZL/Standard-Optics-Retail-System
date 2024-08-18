@extends ('layoutNavigate')
@section('title',__('txt.link.sale.details'))
@section('content')

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
    <div class="alert alert-success" role="alert" style="display: none;">
        {{ session('success') }}
    </div>
@if(session('error'))
    <div class="alert alert-danger" role="alert" id="errorAlert">
        {{ session('error') }}
    </div>
@endif
    <div class="alert alert-danger" role="alert" id="errorAlert" style="display: none;">
        {{ session('error') }}
    </div>

<div id="saleData" data-customer-id="{{ $sale_details->id }}"></div>

<form method="POST" action="{{ route('sale.update_sale', ['sales_id' => $sale_details->id]) }}">
    @csrf
    @method('post')
    <div class="input-group mb-1">
        <span class="input-group-text" id="basic-addon1">{{__('txt.customer.name')}}:</span>

            @if($sale_details->customer_details)
                <a class="form-control" 
                href="{{ route('customer.detail', ['id' => $sale_details->customer_details->id]) }}" 
                style="color: #007bff; text-decoration: underline; cursor: pointer;">
                    {{ $sale_details->customer_details->name ?? '**Not Available' }}
                </a>
            @else
                <input type="text" class="form-control" aria-describedby="basic-addon1" value="**Not Available" disabled>
            @endif

        <span class="input-group-text" id="basic-addon1">{{__('txt.customer.telephone')}}:</span>
        <input type="text" class="form-control" name="telephone_num" aria-describedby="basic-addon1" value="{{ $sale_details->customer_details->telephone_num ?? '' }}" maxlength="30" disabled>
    </div>
    <p></p><p></p><p></p>
    <div class="input-group mb-1">
        <span class="input-group-text" id="basic-addon1">{{__('txt.sale.description')}}:</span>
        <input type="text" class="form-control capitalize" name="sale_description" aria-describedby="basic-addon1" value="{{ $sale_details->description ?? '' }}" maxlength="255">
    </div>

    <div class="d-grid ">
        <button id="saveButton" style="display: none;" class="btn btn-success" type="submit">Save</button>
    </div>
</form><p></p>

<form method="POST" action="{{ route('sale.delete_sale', ['id' => $sale_details->id]) }}">
    @csrf
    @method('post')
    <input type="hidden" name="fromAJAX" value="{{ false }}">
    <button class="delButton btn btn-danger" type="submit">🗑 Delete This Sale</button>
</form><p></p>

<div class="container text-center border border-primary p-2">
    <div class="row align-items-start">
        <div class="col">
            <h1 class="fs-5 text-body-emphasis align-items-center">
                {{__('txt.sale.details.payment_history')}}
            </h1>

            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">{{__('txt.sale.details.date')}}</th>
                        <th scope="col">{{__('txt.sale.details.pay_description')}}</th>
                        <th scope="col" style="text-align: right;">{{__('txt.sale.details.amount')}}</th>
                        <th colspan="2">
                            <button type="button" class="btn btn-outline-success" id="addPaymentButton" style="min-width: 100px; font-weight: bold;">+</button>
                            <!-- <a href="{{ route('installment.add_new_installment', ['sales_id' => '49','payment_amount' => '100.00']) }}">add</a> -->

                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- full price -->
                    <tr class="sale-price">
                        <td id="sale-date">{{ $sale_details->created_at->format('d-m-Y') }}</td>
                        <td>{{__('txt.sale.details.price')}}</td>
                        <td class="payments exclude" id="sale-price" style="text-align: right;">{{ $sale_details->price }}</td>
                        <td>
                            <button class="editButton btn btn-warning">
                                ✏️
                            </button>
                            <button class="saveButton btn btn-success" style="display: none;fontWeight: bold;">
                                Save
                            </button>
                        </td>
                        <td>
                            <button class="cancelButton btn btn-danger" style="display: none;">
                                Cancel
                            </button>
                        </td>
                    </tr>

                    <!-- deposit -->
                    <!-- <tr style="border-bottom: 1px solid black;"> -->
                    <tr class="sale-deposit">
                        <td></td>
                        <td>{{__('txt.sale.details.deposit')}}</td>
                        <td class="payments exclude" id="sale-deposit" style="text-align: right;">({{ $sale_details->deposit }})</td>
                        <td>
                            <button class="editButton btn btn-warning">
                                ✏️
                            </button>
                            <button class="saveButton btn btn-success" style="display: none;fontWeight: bold;">
                                Save
                            </button>
                        </td>
                        <td>
                            <button class="cancelButton btn btn-danger" style="display: none;">
                                Cancel
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="5">
                            <p></p>
                        </td>
                    </tr>

                    <!-- <tr style="border-bottom: 2px solid black;border-top: 2px solid black;"> -->
                    <tr style="border-bottom: 1px solid black;font-weight: bold;">
                        <!-- <td colspan="5" class="fs-6 text-body-emphasis align-items-center" style="border-bottom: 1px solid black;font-weight: bold;">Installment History</td> -->
                        
                    </tr>
                </tbody>

                <tbody id="installmentRows">
                        @foreach ($installments as $installment)
                        <tr class="sale-installment">
                            <td id="installment-date">{{$installment->created_at->format('d-m-Y')}}</td>
                            <td>{{__('txt.sale.details.installment')}}</td> 
                            <td class="payments" style="text-align: right;">({{$installment->payment_amount}})</td>
                            <td>
                                <button class="editButton btn btn-warning" data-id="{{ $installment->id }}">
                                    ✏️
                                </button>
                                <button class="saveButton btn btn-success" style="display: none;fontWeight: bold;">
                                    Save
                                </button>
                            </td>
                            <td>
                                <button class="deleteButton btn btn-danger" data-id="{{ $installment->id }}" style="fontWeight: bold;">
                                    X
                                </button>
                                <button class="cancelButton btn btn-danger" style="display: none;">
                                    Cancel
                                </button>
                            </td>
                        </tr>
                        @endforeach

                        <tr>
                            <th colspan="2">{{__('txt.sale.details.remaining')}} (RM)</th>
                            <th style="text-decoration: underline; text-align: right;" id="remaining-payment"></th>
                            <th colspan="2">
                                @if($sale_details->is_paid)
                                    <span class="badge bg-success ms-2">Fully Paid</span>
                                @else
                                    <span class="badge bg-danger ms-2">Not Fully Paid</span>
                                @endif
                            </td>
                        </tr>
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

    // to fetch all installment
    // function fetchAllInstallment(){
    //     fetch('{{ route('installment.fetch_all_installment') }}', {
    //         method: 'POST',
    //         body: JSON.stringify({ sales_id: {{ $sale_details->id }} }),
    //         headers: { 
    //             'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
    //             'Content-Type': 'application/json' }
    //     })
    //     .then(response => {
    //         if (!response.ok) {
    //             throw new Error('Network response was not ok');
    //         }
    //         return response.json();
    //     })
    //     .then(data => {
    //         // Construct the HTML content for the tbody with the updated data
    //         var bodyContent = '';
    //         data.forEach(item => {
    //             for (var i = 0; i < item.length; i++) {
    //                 bodyContent += `
    //                 <tr class="sale-installment">
    //                     <td>${formatDate(item[i].created_at)}</td>
    //                     <td>{{__('txt.sale.details.installment')}}</td>
    //                     <td class="payments" style="text-align: right;">(${item[i].payment_amount})</td>
    //                     <td>
    //                         <button class="editButton btn btn-warning" data-id="${item[i].id}">
    //                             ✏️
    //                         </button>
    //                         <button class="saveButton btn btn-success" style="display: none;fontWeight: bold;">
    //                             Save
    //                         </button>
    //                     </td>
    //                     <td>
    //                         <button class="deleteButton btn btn-danger" data-id="${item[i].id}" style="fontWeight: bold;">
    //                             X
    //                         </button>
    //                         <button class="cancelButton btn btn-danger" style="display: none;">
    //                             Cancel
    //                         </button>
    //                     </td>
    //                 </tr>`;
    //             }
    //         });
    //         document.getElementById('installmentRows').innerHTML = bodyContent;
            
    //         remainingBodyContent = `<tr>
    //                                     <th colspan="2">{{__('txt.sale.details.remaining')}} (RM)</th>
    //                                     <th style="text-decoration: underline; text-align: right;" id="remaining-payment"></th>
    //                                 </tr>`;

    //         const remainingRow = document.createElement('tr');
    //         remainingRow.innerHTML = remainingBodyContent;
    //         const installmentRowsElement = document.getElementById('installmentRows');

    //         installmentRowsElement.appendChild(remainingRow);

    //         addButton.disabled = false;

    //         // calculate remaining, add event listener to delete and edit button
    //         calculateRemaining();
    //         addDeleteEventListenerToButton();
    //         addEditEventListenerToButton();

    //         // console.log('All installments data: ', data);
    //     })
    //     .catch(error => {
    //         console.error('Error fetching installments:', error);
    //     });
    // }

    // this function is called after javascript render a new tbody during the runtime
    function addDeleteEventListenerToButton(){
        document.querySelectorAll('.deleteButton').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                var to_be_deleted_installment = {
                    sales_id: {{ $sale_details->id }},
                    installment_id: id, // installment id
                };

                // console.log('To be deleted degree: ',to_be_delete_degree);

                fetch('{{ route('installment.delete_installment') }}', {
                    method: 'POST',
                    body: JSON.stringify(to_be_deleted_installment),
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
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error performing deletion:', error);
                });
            });
        });
    }

    // function to add edit button event listener to Full Price, Deposit, Installment.
    function addEditEventListenerToButton(){
        document.querySelectorAll('.editButton').forEach(button => {
            button.addEventListener('click', function() {

                // get installment id
                const id = this.getAttribute('data-id');
                const row = button.closest('tr');
                const deleteButton = row.querySelector('.deleteButton');

                var to_be_editted_installment = {
                    sales_id: {{ $sale_details->id }},
                    installment_id: id ? id : null, // installment id
                };
        
                // Get the installment payment cells
                // var paymentCell = row.querySelector('.payments');

                // Get the payment cells
                var paymentCell = row.querySelector('.payments');

                // Get the current values
                var value = paymentCell.textContent.trim().replace(/[()]/g, "");

                if(row.classList.contains('sale-price')){
                    var dateCell = row.querySelector('#sale-date');
                    var dateValue = dateCell.textContent;

                    dateCell.innerHTML = `<input type="text" class="form-control" id="newSaleDate" name="newSaleDate" autocomplete="off" value="${dateValue}">`;

                    paymentCell.innerHTML = `<input class="form-control" style="text-align: right;" id="newPriceValue" type="number" value="${value}" maxlength="255" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)" value="1.00" onkeydown="preventDelete(event)">`;
                    
                    $('#newSaleDate').datepicker({
                        format: 'dd-mm-yyyy', // Date format
                        autoclose: true,
                        todayHighlight: true
                    });
                }
                if(row.classList.contains('sale-deposit')){
                    paymentCell.innerHTML = `<input class="form-control" style="text-align: right;" id="newDepositValue" type="number" value="${value}" maxlength="255" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)" value="1.00" onkeydown="preventDelete(event)">`;
                }
                if(row.classList.contains('sale-installment')){
                    var dateCell = row.querySelector('#installment-date');
                    var dateValue = dateCell.textContent;

                    dateCell.innerHTML = `<input type="text" class="form-control" id="newInstallmentDate" name="newInstallmentDate" autocomplete="off" value="${dateValue}">`;

                    paymentCell.innerHTML = `<input class="form-control" style="text-align: right;" id="newInstallmentValue" type="number" value="${value}" maxlength="255" step="0.01" pattern="\d+(\.\d{1,2})?" oninput="this.setCustomValidity('')" oninvalid="this.setCustomValidity('Please enter a valid number with up to two decimal places')" onchange="this.value = parseFloat(this.value).toFixed(2)" value="1.00" onkeydown="preventDelete(event)">`;
                    
                    $('#newInstallmentDate').datepicker({
                        format: 'dd-mm-yyyy', // Date format
                        autoclose: true,
                        todayHighlight: true
                    });
                }                
                
                // hide the edit and delete button
                button.style.display = 'none';
                // row.querySelector('.deleteButton').style.display = 'none';
                if(deleteButton){deleteButton.style.display = 'none';}

                // show save and cancel button
                row.querySelector('.saveButton').style.display = 'inline-block';
                row.querySelector('.cancelButton').style.display = 'inline-block';

                // Disable all other isntallments' edit & delete buttons
                toggleButtons('editButton', false);
                toggleButtons('deleteButton', false);

                // when save edit button clicked
                row.querySelector('.saveButton').addEventListener('click', function(){
                    if(to_be_editted_installment.installment_id !== null){
                        paymentUpdate(to_be_editted_installment, true);
                    }
                    if(to_be_editted_installment.installment_id === null){
                        paymentUpdate(to_be_editted_installment, false);
                    }
                });

                // when cancel button clicked
                row.querySelector('.cancelButton').addEventListener('click', function(){
                    window.location.reload();
                });
            });
        });
    }

    // function to edit payment (Full Price & date, Deposit, Installment & date)
    function paymentUpdate(to_be_editted_installment,isUpdateInstallment){
        var data = {
            installment_id: to_be_editted_installment.installment_id,
            sales_id : to_be_editted_installment.sales_id,
            
            new_sale_date: document.getElementById('newSaleDate') ? document.getElementById('newSaleDate').value : null,
            new_price_value: document.getElementById('newPriceValue') ? document.getElementById('newPriceValue').value.trim().replace(/[()]/g, "") : null,
            
            new_deposit_value: document.getElementById('newDepositValue') ? document.getElementById('newDepositValue').value.trim().replace(/[()]/g, "") : null,
            
            new_installment_date: document.getElementById('newInstallmentDate') ? document.getElementById('newInstallmentDate').value : null,
            new_installment_value: document.getElementById('newInstallmentValue') ? document.getElementById('newInstallmentValue').value.trim().replace(/[()]/g, "") : null,
        }

        if(isUpdateInstallment){
            fetch('{{ route('installment.update_installment') }}', {
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
                const formattedMessage = data.message.replace(/\n/g, '<br>');
                localStorage.setItem('flashMessage', formattedMessage);
                localStorage.setItem('flashStatus', data.status);

                // Reload the page
                window.location.reload();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error.message);
            });
        }

        if(!isUpdateInstallment){
            // update sales's price or deposit only
            fetch('{{ route('sale.update_sale') }}', {
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
                const formattedMessage = data.message.replace(/\n/g, '<br>');
                localStorage.setItem('flashMessage', formattedMessage);
                localStorage.setItem('flashStatus', data.status);

                // Reload the page
                window.location.reload();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error.message);
            });
        }
        
    }

    // function to calculate remaining amount to be paid
    function calculateRemaining() {
        // const installmentPayments = document.querySelectorAll('.payments');
        const installmentPayments = document.querySelectorAll('.payments:not(.exclude)');
        const salePriceElement    = document.getElementById("sale-price");
        const saleDepositElement  = document.getElementById("sale-deposit");

        const priceValue = salePriceElement.textContent.trim().replace(/[()]/g, "");
        const depositValue = saleDepositElement.textContent.trim().replace(/[()]/g, "");

        let totalAmount = 0;
        let remainingAmount = 0;
        let nilRemainingAmount = 0;

        for (const paymentElement of installmentPayments) {
            const amount = parseFloat(paymentElement.textContent.replace(/[()]/g, '')); // Remove parentheses and convert to number
            totalAmount += amount;
        }

        remainingAmount = priceValue - depositValue - totalAmount;

        const remainingAmountDisplay = document.getElementById('remaining-payment');
        
        if(!{{ $sale_details->is_paid }}){
            remainingAmountDisplay.textContent = `${remainingAmount.toFixed(2)}`;
        }else{
            remainingAmountDisplay.textContent = `${nilRemainingAmount.toFixed(2)}`;
        }
    }

    // function to make amount not less than RM 1.00
    function preventDelete(event) {
        if(event.key === 'Backspace' || event.key === 'Delete') {
            event.preventDefault(); // Prevent default behavior (deleting the digit)
            event.target.value = '1.00'; // Set input value to 0
        }
    }

    // auto capitalize all the alphabet input
    function capitalize(){
        document.querySelectorAll('.capitalize').forEach(input => {
            // Add an event listener for the 'input' event
            input.addEventListener('input', function() {
                // Capitalize the input value
                this.value = this.value.toUpperCase();
            });
        });
    }

    // Function to check if any input has changed
    function checkChanges() {
        // Show the save button if there are changes in any input or textarea
        const hasChanges = Array.from(inputs).some(input => input.value.trim() !== '') || textarea.value.trim() !== '';
        saveButton.style.display = hasChanges ? 'block' : 'none';
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


    // ## THESE DECLARATIONS AND FUNCTIONS BELOW WILL BE LOADED WHEN REFRESH OR GO INTO THIS PAGE ##
    // ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓

    const addButton = document.getElementById('addPaymentButton');
    const inputs = document.querySelectorAll('input[type="text"]');

    capitalize();
    addEditEventListenerToButton();
    addDeleteEventListenerToButton();
    calculateRemaining();
    inputs.forEach(input => input.addEventListener('input', checkChanges));

    // Add event listener to the addPaymentButton (to add installment payment)
    document.getElementById('addPaymentButton').addEventListener('click', function() {
        document.getElementById('addPaymentButton').disabled = true; // Add disabled attribute to make the button unclickable

        // Create three input fields
        var dateInput = document.createElement('input');
        dateInput.id = 'installment_date';
        dateInput.name = 'installment_date';
        dateInput.type = 'text';
        dateInput.className = 'form-control text-center';
        dateInput.value = formatDate(new Date()); // Set value to today's date
        dateInput.disabled = false; // Disable the input

       
        

        var installmentPaymentAmountInput = document.createElement('input'); // Create the input element
        installmentPaymentAmountInput.type = 'number'; // Use the 'number' type for decimals
        installmentPaymentAmountInput.className = 'form-control';

        // Create a "Save" button
        var saveButton = document.createElement('button');
        saveButton.type = 'button';
        saveButton.className = 'btn btn-outline-success';
        saveButton.textContent = '✓';
        saveButton.style.fontWeight = 'bold';
        saveButton.disabled = true;

        // Add event listeners to the input fields
        installmentPaymentAmountInput.addEventListener('input', toggleSaveButton);

        // Function to toggle the save button based on input values
        function toggleSaveButton() {
            var payment_amount = installmentPaymentAmountInput.value.trim().replace(/[()]/g, "");

            if (!/^[\d.]+$/.test(payment_amount)) { // Allow numbers and decimal points
                // alert('Please enter a valid payment amount with numbers and optional decimal point.');
                saveButton.disabled = true; // Disable the button
            } else {
                saveButton.disabled = false; // Enable the button
            }
        }

        // Add event listener to the "Save" button. Once user click "Save",
        // send the data to route.
        saveButton.addEventListener('click', function() {
            // var payment_amount = installmentPaymentAmountInput.value.trim().replace(/[()]/g, "");
            var payment_amount = installmentPaymentAmountInput.value;

            var installment_new_payment = {
                // id: {{ $sale_details->id }},
                sales_id: {{ $sale_details->id }},
                payment_amount: payment_amount,
                installment_date: installment_date.value,
            };
            
            console.log("Sent data: ",installment_new_payment);

            fetch('{{ route('installment.add_new_installment') }}', {
                method: 'POST',
                body: JSON.stringify(installment_new_payment), // Convert data to JSON
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Content-Type': 'application/json' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not okie dokie: ',response.status);
                }
                return response.json();
            })
            .then(data =>{
                const formattedMessage = data.message.replace(/\n/g, '<br>');
                localStorage.setItem('flashMessage', formattedMessage);
                localStorage.setItem('flashStatus', data.status);

                // Reload the page
                window.location.reload();
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
            // Show the "addPaymentButton" button again
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

        var  installmentText = document.createElement('td');
        const installmentPaymentText = document.createTextNode('Installment Payment');
        installmentText.className = 'text-center';
        installmentText.appendChild(installmentPaymentText);
        newRow.appendChild( installmentText);

        var  installmentPaymentTD = document.createElement('td'); // Only create the td element once
        installmentPaymentAmountInput.addEventListener("change", function() {
            this.value = parseFloat(this.value).toFixed(2); // Format to 2 decimal places
        });
         installmentPaymentTD.appendChild(installmentPaymentAmountInput);
        newRow.appendChild( installmentPaymentTD);

        // Create a cell for the "Save" button
        var saveButtonCell = document.createElement('td');
        saveButtonCell.appendChild(saveButton);
        newRow.appendChild(saveButtonCell);

        // Create a cell for the "Cancel" button
        var cancelButtonCell = document.createElement('td');
        cancelButtonCell.appendChild(cancelButton);
        newRow.appendChild(cancelButtonCell);

        // Get the first row in the tbody
        var firstRow = document.getElementById('installmentRows').rows[0];

        // Insert the new row before the first row
        document.getElementById('installmentRows').insertBefore(newRow, firstRow);

         // Initialize the datepicker on the newly created input field
         $('#installment_date').datepicker({
            format: 'dd-mm-yyyy', // Date format
            autoclose: true,
            todayHighlight: true
        });
        
    });


    document.addEventListener('DOMContentLoaded', function () {
        const flashMessage = localStorage.getItem('flashMessage');
        const flashStatus = localStorage.getItem('flashStatus');
        const banner = document.querySelector('.alert');

        if (flashMessage && flashStatus) {
            banner.textContent = flashMessage;

            // Set the banner class based on success or failure
            if (flashStatus === 'success') {
                banner.classList.add('alert-success');
            } else if (flashStatus === 'failure') {
                banner.classList.add('alert-danger');
            }

            banner.style.display = 'block';

            // Clear the message and status from local storage
            localStorage.removeItem('flashMessage');
            localStorage.removeItem('flashStatus');

            // Optionally hide the banner after a delay
            setTimeout(() => {
                banner.style.display = 'none';
            }, 10000); // Adjust the delay as needed
        }
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