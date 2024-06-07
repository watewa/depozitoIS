<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 ">
            @include('layouts.team-navigation')

            <!-- Page Heading -->
            <header class="bg-white  shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://unpkg.com/bigchaindb-driver@4.2.0/dist/browser/bigchaindb-driver.window.min.js"></script>

        <!-- JavaScript to intercept form submissions and AJAX requests -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Intercept form submissions
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        const formData = new FormData(form);
                        var object = {};
                        formData.forEach((value, key) => object[key] = value);
                        var json = JSON.stringify(object);
                        console.log(json);
                        const method = form.getAttribute('method').toUpperCase();
                        if (method === 'POST' || method === 'PATCH' || method === 'DELETE') {
                            sendBigchainDBTransaction(json).then(() => {
                                form.submit(); // Submit the form after the transaction
                            });
                        } else {
                            form.submit(); // Submit the form normally
                        }
                    });
                });

                // Function to send BigchainDB transaction
                async function sendBigchainDBTransaction(data) {
                    const API_PATH = 'http://localhost:9984/api/v1/';
                    const user = new BigchainDB.Ed25519Keypair();
                    
                    const tx = BigchainDB.Transaction.makeCreateTransaction(
                        { data: data },
                        { meta: 'empty' },
                        [BigchainDB.Transaction.makeOutput(
                            BigchainDB.Transaction.makeEd25519Condition(user.publicKey))
                        ],
                        user.publicKey
                    );

                    const txSigned = BigchainDB.Transaction.signTransaction(tx, user.privateKey);
                    let conn = new BigchainDB.Connection(API_PATH);
                    await conn.postTransactionCommit(txSigned);
                }
            });
        </script>
    </body>
</html>
