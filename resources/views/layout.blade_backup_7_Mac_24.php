<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','💻 Standard Optics Retail System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>

  <style>
    /* Define the background color when the div is hovered */
    .result-item:hover {
      background-color: #808080; /* Brown color */
      color: white; /* White text color */
      font-weight: bold; /* Bold text */
    }

    /* Style for the success message */
    /* Style for both success and error alerts */
    .alert-success, .alert-danger {
        position: fixed;
        top: 5%;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        opacity: 0.8;
    }

    /* Hide the success message by default */
    .hide {
        display: none;
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
  
    <div>
      @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        // Auto-hide the success and error messages after 3 seconds
        // setTimeout(function() {
        //     document.getElementById('success-alert').style.display = 'none';
        //     document.getElementById('error-alert').style.display = 'none';
        // }, 4000);

        $(document).ready(function() {
              setTimeout(function() {
                  $('.alert').addClass('hide');
              }, 5000); // 3000 milliseconds = 3 seconds
          });
    </script>
  </body>
</html>