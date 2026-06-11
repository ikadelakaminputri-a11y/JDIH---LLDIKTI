<?php

return [

    'title' => 'Login',

    'heading' => '',

    'actions' => [

        'register' => [
            'before' => 'or',
            'label' => 'sign up for an account',
        ],

        'request_password_reset' => [
            'label' => 'Forgot password?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Masukkan alamat email',
        ],

        'password' => [
            'label' => 'Masukkan password',
        ],

        'remember' => [
            'label' => 'Ingat saya',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Masuk',
            ],

        ],

    ],

    'multi_factor' => [

        'heading' => 'Verifikasi identitas Anda',

        'subheading' => 'Untuk melanjutkan proses masuk, Anda perlu memverifikasi identitas Anda.',

        'form' => [

            'provider' => [
                'label' => 'Bagaimana Anda ingin melakukan verifikasi?',
            ],

            'actions' => [

                'authenticate' => [
                    'label' => 'Konfirmasikan masuk',
                ],

            ],

        ],

    ],

    'messages' => [

        'failed' => 'Password atau kata sandi salah, silakan coba lagi.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Terlalu banyak upaya masuk',
            'body' => 'Silakan coba lagi dalam :seconds detik.',
        ],

    ],

];
