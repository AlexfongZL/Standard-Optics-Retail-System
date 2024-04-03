<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ImportController;

// use Spatie\Backup\Backup;

// main page
Route::get('/', function () {
    return view('home');
})->name('home');

Route::post('/database/dump',[DatabaseController::class, 'dump'])->name('database.dump');

Route::get('/database/import', function () {
    return view('import');
})->name('database.import');

Route::post('/database/import_customer',[DatabaseController::class, 'import_customer'])->name('database.import_customer');
Route::post('/database/import_sale',[DatabaseController::class, 'import_sale'])->name('database.import_sale');

// .----------------.  .----------------.  .----------------.  .----------------.  .----------------. 
// | .--------------. || .--------------. || .--------------. || .--------------. || .--------------. |
// | |     ______   | || | _____  _____ | || |    _______   | || |  _________   | || |     ____     | |
// | |   .' ___  |  | || ||_   _||_   _|| || |   /  ___  |  | || | |  _   _  |  | || |   .'    `.   | |
// | |  / .'   \_|  | || |  | |    | |  | || |  |  (__ \_|  | || | |_/ | | \_|  | || |  /  .--.  \  | |
// | |  | |         | || |  | '    ' |  | || |   '.___`-.   | || |     | |      | || |  | |    | |  | |
// | |  \ `.___.'\  | || |   \ `--' /   | || |  |`\____) |  | || |    _| |_     | || |  \  `--'  /  | |
// | |   `._____.'  | || |    `.__.'    | || |  |_______.'  | || |   |_____|    | || |   `.____.'   | |
// | |              | || |              | || |              | || |              | || |              | |
// | '--------------' || '--------------' || '--------------' || '--------------' || '--------------' |
//  '----------------'  '----------------'  '----------------'  '----------------'  '----------------' 
//  .----------------.  .----------------.  .----------------.                                         
// | .--------------. || .--------------. || .--------------. |                                        
// | | ____    ____ | || |  _________   | || |  _______     | |                                        
// | ||_   \  /   _|| || | |_   ___  |  | || | |_   __ \    | |                                        
// | |  |   \/   |  | || |   | |_  \_|  | || |   | |__) |   | |                                        
// | |  | |\  /| |  | || |   |  _|  _   | || |   |  __ /    | |                                        
// | | _| |_\/_| |_ | || |  _| |___/ |  | || |  _| |  \ \_  | |                                        
// | ||_____||_____|| || | |_________|  | || | |____| |___| | |                                        
// | |              | || |              | || |              | |                                        
// | '--------------' || '--------------' || '--------------' |                                        
//  '----------------'  '----------------'  '----------------'                                         

Route::get('/customer/list', [
    CustomerController::class,'list'
])->name('customer.list');

Route::get('/customer/create',function () {
    return view('customer.create');
})->name('customer.create');

Route::post('/customer',[
    CustomerController::class, 'store'
])->name('customer.store');

Route::get('/customer/detail/{id}',[
    CustomerController::class, 'detail'
])->name('customer.detail');

// Route::match(['get', 'post'], '/customer/detail/{id}',[
//     CustomerController::class, 'detail'
// ])->name('customer.detail');

// Route::post('/customer/search', [
//     CustomerController::class, 'search'
// ])->name('customer.search');

Route::match(['get', 'post'], 'customer/search', [
    CustomerController::class, 'search'
])->name('customer.search');

Route::post('/customer/update',[
    CustomerController::class, 'update'
])->name('customer.update');

Route::post('/customer/add_new_degree',[
    CustomerController::class, 'add_new_degree'
])->name('customer.add_new_degree');

Route::post('/customer/fetch_all_degree',[
    CustomerController::class, 'fetch_all_degree'
])->name('customer.fetch_all_degree');

Route::post('/customer/delete_degree',[
    CustomerController::class, 'delete_degree'
])->name('customer.delete_degree');

Route::post('/customer/update_degree',[
    CustomerController::class, 'update_degree'
])->name('customer.update_degree');


// .----------------.  .----------------.  .----------------.  .----------------. 
// | .--------------. || .--------------. || .--------------. || .--------------. |
// | |    _______   | || |      __      | || |   _____      | || |  _________   | |
// | |   /  ___  |  | || |     /  \     | || |  |_   _|     | || | |_   ___  |  | |
// | |  |  (__ \_|  | || |    / /\ \    | || |    | |       | || |   | |_  \_|  | |
// | |   '.___`-.   | || |   / ____ \   | || |    | |   _   | || |   |  _|  _   | |
// | |  |`\____) |  | || | _/ /    \ \_ | || |   _| |__/ |  | || |  _| |___/ |  | |
// | |  |_______.'  | || ||____|  |____|| || |  |________|  | || | |_________|  | |
// | |              | || |              | || |              | || |              | |
// | '--------------' || '--------------' || '--------------' || '--------------' |
//  '----------------'  '----------------'  '----------------'  '----------------' 
Route::get('/sale/list', [
    SaleController::class,'list'
])->name('sale.list');

Route::get('/sale/create',function () {
    return view('sale.create');
})->name('sale.create');

Route::post('/sale',[
    SaleController::class, 'store'
])->name('sale.store');

Route::get('/sale/detail/{id}',[
    SaleController::class, 'detail'
])->name('sale.detail');

Route::post('/sale/update_sale',[
    SaleController::class, 'update_sale'
])->name('sale.update_sale');

Route::post('/sale/delete_sale',[
    SaleController::class, 'delete_sale'
])->name('sale.delete_sale');

// .----------------.  .-----------------. .----------------.  .----------------.  .----------------.  .----------------. 
// | .--------------. || .--------------. || .--------------. || .--------------. || .--------------. || .--------------. |
// | |     _____    | || | ____  _____  | || |    _______   | || |  _________   | || |      __      | || |   _____      | |
// | |    |_   _|   | || ||_   \|_   _| | || |   /  ___  |  | || | |  _   _  |  | || |     /  \     | || |  |_   _|     | |
// | |      | |     | || |  |   \ | |   | || |  |  (__ \_|  | || | |_/ | | \_|  | || |    / /\ \    | || |    | |       | |
// | |      | |     | || |  | |\ \| |   | || |   '.___`-.   | || |     | |      | || |   / ____ \   | || |    | |   _   | |
// | |     _| |_    | || | _| |_\   |_  | || |  |`\____) |  | || |    _| |_     | || | _/ /    \ \_ | || |   _| |__/ |  | |
// | |    |_____|   | || ||_____|\____| | || |  |_______.'  | || |   |_____|    | || ||____|  |____|| || |  |________|  | |
// | |              | || |              | || |              | || |              | || |              | || |              | |
// | '--------------' || '--------------' || '--------------' || '--------------' || '--------------' || '--------------' |
//  '----------------'  '----------------'  '----------------'  '----------------'  '----------------'  '----------------' 
//  .----------------.  .----------------.  .----------------.  .-----------------. .----------------.                     
// | .--------------. || .--------------. || .--------------. || .--------------. || .--------------. |                    
// | |   _____      | || | ____    ____ | || |  _________   | || | ____  _____  | || |  _________   | |                    
// | |  |_   _|     | || ||_   \  /   _|| || | |_   ___  |  | || ||_   \|_   _| | || | |  _   _  |  | |                    
// | |    | |       | || |  |   \/   |  | || |   | |_  \_|  | || |  |   \ | |   | || | |_/ | | \_|  | |                    
// | |    | |   _   | || |  | |\  /| |  | || |   |  _|  _   | || |  | |\ \| |   | || |     | |      | |                    
// | |   _| |__/ |  | || | _| |_\/_| |_ | || |  _| |___/ |  | || | _| |_\   |_  | || |    _| |_     | |                    
// | |  |________|  | || ||_____||_____|| || | |_________|  | || ||_____|\____| | || |   |_____|    | |                    
// | |              | || |              | || |              | || |              | || |              | |                    
// | '--------------' || '--------------' || '--------------' || '--------------' || '--------------' |                    
//  '----------------'  '----------------'  '----------------'  '----------------'  '----------------'                     
Route::get('/installment/list', [
    InstallmentController::class,'list'
])->name('installment.list');

Route::post('/installment/update_installment',[
    InstallmentController::class, 'update_installment'
])->name('installment.update_installment');

Route::post('/installment/fetch_all_installment',[
    InstallmentController::class, 'fetch_all_installment'
])->name('installment.fetch_all_installment');

Route::post('/installment/delete_installment',[
    InstallmentController::class, 'delete_installment'
])->name('installment.delete_installment');

Route::post('/installment/add_new_installment',[
    InstallmentController::class, 'add_new_installment'
])->name('installment.add_new_installment');

// Route::get('/installment/add_new_installment',[
//     InstallmentController::class, 'add_new_installment'
// ])->name('installment.add_new_installment');

// .----------------.  .----------------.  .----------------.  .----------------.  .----------------. 
// | .--------------. || .--------------. || .--------------. || .--------------. || .--------------. |
// | |   ______     | || |      __      | || |     ______   | || |  ___  ____   | || |              | |
// | |  |_   _ \    | || |     /  \     | || |   .' ___  |  | || | |_  ||_  _|  | || |              | |
// | |    | |_) |   | || |    / /\ \    | || |  / .'   \_|  | || |   | |_/ /    | || |    ______    | |
// | |    |  __'.   | || |   / ____ \   | || |  | |         | || |   |  __'.    | || |   |______|   | |
// | |   _| |__) |  | || | _/ /    \ \_ | || |  \ `.___.'\  | || |  _| |  \ \_  | || |              | |
// | |  |_______/   | || ||____|  |____|| || |   `._____.'  | || | |____||____| | || |              | |
// | |              | || |              | || |              | || |              | || |              | |
// | '--------------' || '--------------' || '--------------' || '--------------' || '--------------' |
//  '----------------'  '----------------'  '----------------'  '----------------'  '----------------' 
//  .----------------.  .-----------------. .----------------.                                         
// | .--------------. || .--------------. || .--------------. |                                        
// | |  _________   | || | ____  _____  | || |  ________    | |                                        
// | | |_   ___  |  | || ||_   \|_   _| | || | |_   ___ `.  | |                                        
// | |   | |_  \_|  | || |  |   \ | |   | || |   | |   `. \ | |                                        
// | |   |  _|  _   | || |  | |\ \| |   | || |   | |    | | | |                                        
// | |  _| |___/ |  | || | _| |_\   |_  | || |  _| |___.' / | |                                        
// | | |_________|  | || ||_____|\____| | || | |________.'  | |                                        
// | |              | || |              | || |              | |                                        
// | '--------------' || '--------------' || '--------------' |                                        
//  '----------------'  '----------------'  '----------------'                                         

Route::get('/suggest', [
    CustomerController::class, 'suggest'
])->name('suggest');