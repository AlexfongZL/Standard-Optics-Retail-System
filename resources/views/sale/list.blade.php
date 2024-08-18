@extends ('layoutNavigate')
@section('title',__('txt.link.sale.show_all'))
@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif


<div class="container mt-1">    
    <div class="row">
        <div class="col-md-4 ">
                {{ $sales->onEachSide(1)->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<table class="table table-bordered border border-secondary">
    <thead class="thead-dark">
        <tr>
        <th scope="col">{{__('txt.sale.name')}}</th>
        <th scope="col">{{__('txt.sale.description')}}</th>
        <th scope="col">{{__('txt.sale.price')}}</th>
        <th><span class="badge bg-warning text-dark ms-2 fs-6">Payment</span> Status</th>
        <th scope="col">{{__('txt.sale.created_at')}}</th>
        </tr>
    </thead>

    <tbody class="table-group-divider">
        @foreach($sales as $sale)
            <tr>
                @if(isset($sale->customer_name))
                    <td><a class="link-opacity-100" href="{{route('customer.detail', ['id' => $sale->customer_id])}}">{{$sale->customer_name}}</a></td>
                @else
                    <td>{{$sale->customer_name ?? '**Not Available'}}</td>
                @endif
                <th>
                    <a href="{{route('sale.detail', ['id' => $sale->id])}}">
                        {{ $sale->description }}
                    </a>
                </th>
                <td>{{$sale->price}}</td>
                <td>
                    @if($sale->is_paid)
                        <span class="badge bg-success ms-2">Fully Paid</span>
                    @else
                        <span class="badge bg-danger ms-2">Not Fully Paid</span>
                    @endif
                </td>
                <td>{{$sale->created_at->format('d-m-Y')}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $(document).ready(function(){
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