<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\Customers;
use App\Models\Degrees;
use App\Imports\CustomerImport;
use App\Imports\SaleImport;

class DatabaseController extends Controller
{
    public function dump(): \Illuminate\Http\RedirectResponse // Return type declaration
    {
        try {
            // Run the php artisan backup:run --only-db command
            Artisan::call('backup:run', ['--only-db' => true]);

            $output = Artisan::output();
            
            // Check if the command has the word "Backup completed!"
            if (strpos($output, 'Backup completed!') !== false) {
                return back()->with('success', 'Backup success!');
            } else {
                return back()->withErrors(['error' => 'Backup fail! Please contact admin!']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create database dump: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Database dump created successfully!');
    }

    public function import_customer(Request $request){
        $request->validate([
            'import_file'=>[
                'required',
                'file'
            ]
        ]);

        Excel::import(new CustomerImport,$request->file('import_file'));

        return redirect()->back()->with('success', 'Excel data imported successfully!');
    }

    public function import_sale(Request $request){
        $request->validate([
            'import_file'=>[
                'required',
                'file'
            ]
        ]);

        Excel::import(new SaleImport,$request->file('import_file'));

        return redirect()->back()->with('success', 'Excel data imported successfully!');
    }
}
