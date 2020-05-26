<?php

return [
    'order' => [
        'rejected' => [
            'customer' => [
                'subject' => 'Sorry – we\'re unable to process your order',
            ],
        ],
        'accepted' => [
            'customer' => [
                'subject' => 'Good News – we\'re processing your order',
            ],
            'merchant' => [
                'subject' => 'Another order placed!',
            ],
        ],
        'reminder' => [
            'merchant' => [
                'subject' => 'Order # <orderid> not yet accepted',
            ],
        ],
        'placed' => [
            'customer' => [
                'subject' => 'We\'ve received your order'
            ],
            'merchant' => [
                'subject' => 'Order # <orderid> Placed',
            ],
        ],
        'cancelled' => [
            'customer' => [
                'subject' => 'Sorry – we\'ve been unable to acknowledge your order',
            ],
            'merchant' => [
                'subject' => 'Order # <orderid> Cancelled',
            ],
        ],
    ],
    'register' =>[
        'merchant' => [
            'subject' => 'Welcome to Awe-der',
        ],
        'awe-der-merchant' => [
            'subject' => 'A new merchant has signed up',
        ],
    ],
];
