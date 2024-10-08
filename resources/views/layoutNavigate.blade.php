<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','💻 Standard Optics Retail System')</title>
    
    <!-- will have to change these CDN to local one day! -->
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    
    <!-- local file -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
</head>

    <style>
        /* Define the background color when the div is hovered */
        .result-item:hover {
        background-color: #808080; /* Brown color */
        color: white; /* White text color */
        font-weight: bold; /* Bold text */
        }

        /* Style for the success/alert message */
        /* Style for both success and error alerts */
        .alert-success, .alert-danger {
            position: fixed;
            top: 5%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            /* opacity: 0.8; */
            opacity: 1;
        }

        /* Hide the success message by default */
        .hide {
            display: none;
        }

        .warning-message {
            color: red;
            margin-top: 5px; /* Adjust as needed */
        }

        .input-error {
            border: 1px solid #dc3545 !important; /* Dimming border */
        }

    </style>

    <body>
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert" id="errorAlert">
                {{ session('error') }}
            </div>
        @endif

        <div class="container mt-1">
            <ul class="nav nav-pills {{ request()->is('/') ? 'hide' : '' }}">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('home')}}">💻  📊</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('customer/list') ? 'active' : '' }}" aria-current="page" href="{{route('customer.list')}}">{{__('txt.link.customer.show_all')}}</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('customer/create') ? 'active' : '' }}" href="{{route('customer.create')}}">{{__('txt.link.customer.add_new')}}</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('sale/list') ? 'active bg-success' : '' }}" href="{{route('sale.list')}}">{{__('txt.link.sale.show_all')}}</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('sale/create') ? 'active bg-success' : '' }}" href="{{route('sale.create')}}">{{__('txt.link.sale.add_new')}}</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('installment/list') ? 'active bg-warning' : '' }}" style="{{ request()->is('installment/list') ? 'color: black;' : '' }}" href="{{route('installment.list')}}">{{__('txt.link.installment.show_all')}}</a>
                </li>
            </ul>
            @yield('content')
        </div>

        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').addClass('hide');
                }, 5000); // 5000 milliseconds = 5 seconds
            });
        </script>
    </body>
</html>