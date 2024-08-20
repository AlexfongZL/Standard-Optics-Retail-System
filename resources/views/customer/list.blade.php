@extends ('layoutNavigate')
@section('title',__('txt.link.customer.show_all'))
@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif


<div class="container mt-1">    
    <div class="row">
        <div class="col-md-4 ">
            {{ $customers->onEachSide(1)->appends(request()->query())->links() }}
        </div>

        <div class="col-md-3 offset-md-1">
            <form action="{{ route('customer.search') }}" method="post">
            @csrf
            @method('post')
                <div class="input-group">
                    <input autocomplete="off" type="text" class="form-control" id="search-input" placeholder="Search Customer Name" name="query">
                    <div class="input-group-append" id="clear-search-button" style="display: none;">
                        <!-- Clear text button -->
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
        </div>

        

    </div>
</div>

<table class="table table-bordered border border-secondary mt-2">
    <thead class="thead-dark">
        <tr>
        <th scope="col">{{__('txt.customer.name')}}</th>
        <!-- <th scope="col">{{__('txt.customer.ic_passport')}}</th> -->
        <th scope="col">{{__('txt.customer.telephone')}}</th>
        <th scope="col">{{__('txt.customer.address')}}</th>
        <th scope="col">{{__('txt.customer.left_eye_degree')}}</th>
        <th scope="col">{{__('txt.customer.right_eye_degree')}}</th>
        <th scope="col">{{__('txt.customer.remarks')}}</th>
        <th scope="col">{{__('txt.customer.created_at')}}</th>
        </tr>
    </thead>

    <tbody class="table-group-divider">
        @foreach($customers as $customer)
            <tr>
                <td><a class="link-opacity-100" href="{{route('customer.detail', ['id' => $customer->id])}}">{{$customer->name}}</a></td>
                <!-- <td>{{$customer->ic_passport_num}}</td> -->
                <td>{{$customer->telephone_num}}</td>
                <td>{{$customer->address}}</td>
                <td>{{$customer->latest_left_eye_degree}}</td>
                <td>{{$customer->latest_right_eye_degree}}</td>
                <td>{{$customer->remarks}}</td>
                <td>{{ $customer->created_at->format('d/m/Y') }}</td>
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
            // console.log('in auto complete');

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