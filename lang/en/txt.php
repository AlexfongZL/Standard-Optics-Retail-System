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
        ],

        // sale page and buttons
        'sale' =>[
            'title' => 'Sale',
            'show_all' => '💰📋 Show All Sale',
            'add_new' => '💰➕ Add New Sale',
        ],

        // installment page and buttons
        'installment' =>[
            'title' => 'Overdue Payment',
            'show_all' => '📅📋 Show All Overdue Payment',
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
        'ic_passport' => '🪪',
        'telephone' => '☎️',
        'address' => '🏡',
        'left_eye_degree' => '👁️ Left',
        'right_eye_degree' => 'Right 👁️',
        'remarks' => '📝',
        'created_at' => '📆',
        
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
        'name' => 'Name',
        'description' => 'Description',
        'price' => 'Price (RM)',
        'is_paid' => 'Payment Status',
        'created_at' => '📆',
    ],

];
