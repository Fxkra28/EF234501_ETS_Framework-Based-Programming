<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDDStoreInventory</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="w-full bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-blue-600">EDDStoreInventory</h1>
            <div class="space-x-2">
                <a href="{{ route('login') }}" 
                   class="px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold shadow-sm hover:bg-blue-700 transition-colors">
                   Login
                </a>
                <a href="{{ route('register') }}" 
                   class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 text-sm font-semibold shadow-sm hover:bg-gray-100 transition-colors">
                   Sign Up
                </a>
            </div>
        </div>
    </nav>

    <main>
        
        <section class="w-full bg-white py-24">
            <div class="max-w-6xl mx-auto px-6 lg:px-8 flex flex-col items-center text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Simplify Your Business Operations with <span class="text-blue-600">EDDStoreInventory</span>
                </h2>
                <p class="text-lg text-gray-500 max-w-3xl mb-10">
                    EDDStoreInventory helps Business Enterprise Bureaus efficiently manage sales, 
                    track inventory, and streamline the Point of Sale (POS) process.
                </p>
                <div class="space-x-4">
                    <a href="{{ route('login') }}" 
                       class="px-5 py-2.5 rounded-md bg-blue-600 text-white font-semibold shadow-sm hover:bg-blue-700 transition-colors">
                       Get Started
                    </a>
                    <a href="{{ route('register') }}" 
                       class="px-5 py-2.5 rounded-md border border-gray-300 text-gray-700 font-semibold shadow-sm hover:bg-gray-100 transition-colors">
                       Create Account
                    </a>
                </div>
            </div>
        </section>

        <section class="w-full bg-gray-50 py-20">
            <div class="max-w-6xl mx-auto px-6 lg:px-8">
                <h3 class="text-3xl font-bold text-center mb-12 text-gray-900">Why Choose EDDStoreInventory?</h3>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-xl bg-white shadow-sm border border-gray-200">
                        <h4 class="text-lg font-semibold mb-2 text-blue-600">📊 Smart POS System</h4>
                        <p class="text-gray-500">
                            Easily record sales, handle payments, and print receipts — designed for smooth business transactions.
                        </p>
                    </div>
                    <div class="p-6 rounded-xl bg-white shadow-sm border border-gray-200">
                        <h4 class="text-lg font-semibold mb-2 text-blue-600">📦 Real-time Inventory</h4>
                        <p class="text-gray-500">
                            Automatically track stock levels and avoid over-selling or shortages with live inventory updates.
                        </p>
                    </div>
                    <div class="p-6 rounded-xl bg-white shadow-sm border border-gray-200">
                        <h4 class="text-lg font-semibold mb-2 text-blue-600">📈 Business Insights</h4>
                        <p class="text-gray-500">
                            Get data-driven insights to monitor performance and improve business efficiency effortlessly.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="text-center py-8 bg-white text-gray-500 text-sm border-t border-gray-200">
        © {{ date('Y') }} EDDStoreInventory. All rights reserved.
    </footer>

</body>
</html>