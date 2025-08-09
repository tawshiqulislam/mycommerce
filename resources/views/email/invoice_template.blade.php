<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->code }}</title>
    <style>
        /* Your existing styles */

    </style>
</head>
<body class="relative">
    <section>
        <table class="w-full">
            <tr>
                <td valign="top">
                    <div class="text-xl">
                        {{ config('company.name') }}
                    </div>
                </td>
                <td>
                    <div class="text-right">
                        <h2 class="text-xl font-semibold ">
                            Order: #{{ $order->code }}
                        </h2>
                        <table class="w-auto mt-4 inline-table">
                            <tr>
                                <td>Order date:</td>
                                <td class="text-gray-500">{{ $order->created_at->translatedFormat('d/M/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </section>
    <section>
        <table class="w-full">
            <tr>
                <td>
                    <div>
                        <div class="font-semibold">Billed to:</div>
                        <div class="mt-2">
                            <div>{{ $order->data->user->name }}</div>
                            <div class="mt-1">{{ $order->data->user->email }}</div>
                            <div class="mt-1">+{{ $order->data->user->phone }}</div>
                            <address class="mt-1 not-italic ">
                                {{ $order->data->user->address }}<br>
                                {{ $order->data->user->city }}
                            </address>
                        </div>
                    </div>
                </td>
                <td valign="bottom ">
                    <div class="text-right">

                    </div>
                </td>
            </tr>
        </table>
    </section>
    <section>
        <div class="border rounded-lg">
            <table class="w-full">
                <thead>
                    <tr class="">
                        <th class="p-3 text-left ">Item</th>
                        <th class="p-3 text-left ">Price</th>
                        <th class="p-3 text-left ">Quantity</th>
                        <th class="p-3 text-right ">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->order_products as $item)
                    <tr class="border-t">
                        <td class="p-3 text-left">{{ $item->name }}</td>
                        <td class="p-3 text-left whitespace-nowrap">{{ number_format($item->price, 2) }}</td>
                        <td class="p-3 text-left">{{ $item->quantity }}</td>
                        <td class="p-3 text-right whitespace-nowrap">{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="border-t-2">
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3 text-right">Subtotal:</td>
                        <td class="p-3 text-right whitespace-nowrap medium ">{{ number_format($order->sub_total, 2) }}</td>
                    </tr>
                    <tr class="border-t ">
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3 text-right">Shipping:</td>
                        <td class="p-3 text-right whitespace-nowrap medium">
                            {{ number_format($order->shipping, 2) }}
                        </td>
                    </tr>
                    <tr class="border-t ">
                        <td class="p-3"></td>
                        <td class="p-3"></td>
                        <td class="p-3 text-right">Total:</td>
                        <td class="p-3 text-right whitespace-nowrap medium">
                            {{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    <section>
        <div>
            <h4 class="text-lg font-semibold title">Thank you!</h4>
            <p class="mt-2">
                If you have any questions, please contact us at:
            </p>
            <div class="mt-2">
                {{ config('company.name') }}
                <p>{{ config('company.email') }}</p>
                <p class="mt-1 not-italic">{{ config('company.phone') }}</p>
                <address class="mt-1 not-italic">
                    {{ config('company.address') }}
                </address>
            </div>
            <p class="mt-2 text-sm text-gray-500">© {{ date('Y') }} {{ config('company.name') }} Inc.</p>
        </div>
    </section>
</body>
</html>
