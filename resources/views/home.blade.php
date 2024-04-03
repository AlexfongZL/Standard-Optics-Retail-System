@extends ('layoutNavigate')

@section('content')
<div class="container px-4 py-5" id="featured-3">
  <h2 class="pb-2 border-bottom">💻 {{__('txt.home.system_name')}} 📊</h2>
  <form method="POST" action="{{ route('database.dump') }}">
    @csrf  <button type="submit" class="btn btn-outline-dark">Backup</button>
  </form>

  <form method="GET" action="{{ route('database.import') }}">
    @csrf  <button id="importData" type="submit" class="btn btn-outline-dark">Import Data</button>
  </form>
  
  <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
    <div class="feature col">
      <h3 class="fs-2 text-body-emphasis">👥 {{__('txt.home.customer.title')}}</h3>
      <p><button id="showAllCustomerButton" class="btn btn-primary" type="submit">{{__('txt.home.customer.show_all')}}</button></p>
      <p><button id="addNewCustomerButton" class="btn btn-primary" type="submit">{{__('txt.home.customer.add_new')}}</button></p>        
    </div>

    <div class="feature col">
      <h3 class="fs-2 text-body-emphasis">💰 {{__('txt.home.sale.title')}}</h3>
      <p><button id="showAllSaleButton" class="btn btn-success" type="submit">{{__('txt.home.sale.show_all')}}</button></p>
      <p><button id="addNewSaleButton" class="btn btn-success" type="submit">{{__('txt.home.sale.add_new')}}</button></p>
    </div>

    <div class="feature col">        
      <h3 class="fs-2 text-body-emphasis">📅 {{__('txt.home.installment.title')}}</h3>
      <p><button id="showAllInstallmentButton" class="btn btn-warning" type="submit">{{__('txt.home.installment.show_all')}}</button></p>
    </div>
  </div>
</div>

  <script>
    // Function to redirect to a specified URL
    function redirectTo(url) {
        window.location.href = url;
    }

    // Add event listeners to the buttons
    document.getElementById('showAllCustomerButton').addEventListener('click', function() {
        redirectTo('/customer/list');
    });

    document.getElementById('addNewCustomerButton').addEventListener('click', function() {
        redirectTo('/customer/create');
    });

    document.getElementById('showAllSaleButton').addEventListener('click', function() {
        redirectTo('/sale/list');
    });

    document.getElementById('addNewSaleButton').addEventListener('click', function() {
        redirectTo('/sale/create');
    });

    document.getElementById('showAllInstallmentButton').addEventListener('click', function() {
        redirectTo('/installment/list');
    });
</script>
@endsection