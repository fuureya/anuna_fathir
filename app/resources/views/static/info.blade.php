@extends('layouts.app-public')

@section('content')
<div class="container mx-auto px-4 max-w-4xl">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-semibold mb-4">Library Information</h1>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Latest News & Updates</h2>
            <p class="text-gray-700">
                Welcome to the Library Information Center. Here you can find the latest news and updates about our library services.
            </p>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Opening Hours</h3>
            <table class="w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">Day</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">Monday - Friday</td>
                        <td class="border border-gray-300 px-4 py-2">08:00 - 16:00</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">Saturday</td>
                        <td class="border border-gray-300 px-4 py-2">09:00 - 14:00</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">Sunday</td>
                        <td class="border border-gray-300 px-4 py-2">Closed</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Contact Information</h3>
            <p class="text-gray-700">
                For inquiries, please contact us via:
                <br>WhatsApp: <a href="https://wa.me/6281114128989" class="text-blue-600 hover:underline">+62 811 1412 8989</a>
                <br>Email: <a href="mailto:perpustakaandaerah@gmail.com" class="text-blue-600 hover:underline">perpustakaandaerah@gmail.com</a>
            </p>
        </div>

        <div>
            <h3 class="text-lg font-semibold mb-2">Address</h3>
            <p class="text-gray-700">
                Jl. G. Bawakaraeng No. 10, Kota Makassar, Sulawesi Selatan
            </p>
        </div>
    </div>
</div>
@endsection
