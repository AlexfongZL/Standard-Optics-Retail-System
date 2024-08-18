<?php

return [

    'link' => [
        // top navigation bar
        'customer_page' => 'Customer',
        'sale_page' => 'Sale',
        'installment_page' => 'Installment',

        // customer page and buttons
        'customer' =>[
            'title' => 'Customer',
            'show_all' => '👥📋 Show All Customer',
            'add_new' => '👥➕ Add New Customer',
            'details' => '👥🔎 Customer Details',
        ],

        // sale page and buttons
        'sale' =>[
            'title' => 'Sale',
            'show_all' => '💰📋 Show All Sale',
            'add_new' => '💰➕ Add New Sale',
            'details' => '💰🔎 Sale Details',
        ],

        // installment page and buttons
        'installment' =>[
            'title' => 'Overdue Payment',
            'show_all' => '📅📋 Show All Overdue Payment',
            'details' => '📅🔎 Payment Details', // N/A
        ],
    ],

    'home' => [
        // top navigation bar
        'system_name' => 'Standard Optics Retail System',

        // customer page and buttons
        'customer' =>[
            'title' => 'Customer',
            'show_all' => '📋 Show All Customer',
            'add_new' => '➕ Add New Customer',
        ],

        // sale page and buttons
        'sale' =>[
            'title' => 'Sale',
            'show_all' => '📋 Show All Sale',
            'add_new' => '➕ Add New Sale',
        ],

        // installment page and buttons
        'installment' =>[
            'title' => 'Overdue Payment',
            'show_all' => '📋 Show All Overdue Payment',
        ],
        
    ],

    'customer' => [
        'name' => 'Name',
        'ic_passport' => '🪪 IC/Passport',
        'telephone' => '☎️ Telephone',
        'address' => '🏡 Address',
        'left_eye_degree' => '👁️ Left',
        'right_eye_degree' => 'Right 👁️',
        'remarks' => '📝 Remarks',
        'created_at' => '📆 Date',
        
        'details' => [
            'vision_history' => '👓 Vision History 🕶️',
            'left_eye_degree' => '👁️ Left',
            'right_eye_degree' => 'Right 👁️',
            

            'sales_history' => '💰 Sales History 💵',
            'sales_description' => 'Description',
            'sales_price' => 'Price (RM)',
            'sales_payment' => 'Payment Status',
            'create_date' => '📆',
        ],
    ],

    'sale' => [
        'name' => 'Customer Name',
        'description' => 'Sale Description',
        'price' => 'Price (RM)',
        'deposit' => 'Deposit (RM)',
        'paid_installment' => 'Paid Installment (RM)',
        'remaining' => 'Remaining (RM)',
        'created_at' => '📆',
        'details' => [
            'payment_history' => 'Payment History',
            'price' => 'Full Price',
            'deposit' => 'Deposit',
            'remaining' => 'Remaninig',
            'date' => 'Date',
            'pay_description' => 'Payment Description',
            'amount' => 'Amount (RM)',
            'installment' => 'Installment Payment',
        ],
    ],

];
