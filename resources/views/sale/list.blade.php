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

        <!-- <div class="col-md-3 offset-md-1">
            <form action="" method="post">
            @csrf
            @method('post')
                <div class="input-group">
                    <input type="text" class="form-control" id="search-input" placeholder="Search Customer Name" name="query">
                    <div class="input-group-append" id="clear-search-button" style="display: none;">
                        <button class="btn btn-outline-secondary" type="button" id="clear-search-input">
                            x
                        </button>
                    </div>
                    
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">🔍</button>
                    </div>
                </div>
                <div id="search-results" class="position-absolute w-50 border border-secondary bg-white" style="z-index: 1000; display: none";></div>
            </form>
        </div> -->
    </div>
</div>

<table class="table table-bordered border border-secondary">
    <thead class="thead-dark">
        <tr>
        <th scope="col">{{__('txt.sale.name')}}</th>
        <th scope="col">{{__('txt.sale.description')}}</th>
        <th scope="col">{{__('txt.sale.price')}}</th>
        <th scope="col">{{__('txt.sale.is_paid')}}</th>
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
                        Paid
                    @else
                        Not Paid
                    @endif
                </td>
                <td>{{$sale->created_at->format('d/m/Y')}}</td>
                <!-- <td>
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{route('sale.detail', ['id' => $sale->id])}}">🔍</a>
                        </li>
                    </ul>
                </td> -->
            </tr>
        @endforeach
    </tbody>
</table>

<script>
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