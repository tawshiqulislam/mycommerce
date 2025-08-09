<table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
    <tr>
        <td style="font-size: 20px; font-weight: bold; text-align: center;">{{ $settings['company']['name'] }}</td>
    </tr>
    <tr>
        <td style="font-size: 10px; text-align: center;">{{ $settings['company']['phone'] }}</td>
    </tr>
    <tr>
        <td style="font-size: 10px; text-align: center;">{{ $settings['company']['email'] }}</td>
    </tr>
    <tr>
        <td style="font-size: 10px; text-align: center;">{{ $settings['company']['site'] }}</td>
    </tr>
    <tr>
        <td style="font-size: 10px; text-align: center;">{{ $settings['company']['address'] }}</td>
    </tr>
</table>


<div style="font-size: 10px; padding: 0; margin: 0;">
    <div style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 5px; width: 100%;">
        <table style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border-bottom: 1px solid #000; text-align: left; padding: 2px; text-align: left;">Name</th>
                    <th style="border-bottom: 1px solid #000; text-align: left; padding: 2px; text-align: center;">Price</th>
                    <th style="border-bottom: 1px solid #000; text-align: left; padding: 2px; text-align: center;">Qty</th>
                    <th style="border-bottom: 1px solid #000; text-align: left; padding: 2px; text-align: center;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td style="border-bottom: 1px solid #000; padding: 2px;">
                        {{ $product['name'] }}
                        <div style="color: #666; font-size: 8px;">
                            {{ $product['color'] }}
                        </div>
                    </td>
                    <td style="border-bottom: 1px solid #000; padding: 2px;">
                        {{ Number::currency($product['price']) }}
                    </td>
                    <td style="border-bottom: 1px solid #000; padding: 2px; text-align: center;">
                        {{ $product['quantity'] }}
                    </td>
                    <td style="border-bottom: 1px solid #000; padding: 2px;">
                        {{ Number::currency($product['price'] * $product['quantity']) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="color: #666; font-size: 10px; text-align: left;">Total Price</td>
                <td style="font-weight: bold; font-size: 10px; text-align: right;">{{ Number::currency($posOrder->total - $posOrder->vat + $posOrder->vat_negation) }}</td>
            </tr>
            <tr>
                <td style="color: #666; font-size: 10px; text-align: left;">Total VAT</td>
                <td style="font-weight: bold; font-size: 10px; text-align: right;">{{ Number::currency($posOrder->vat) }}</td>
            </tr>
            <tr>
                <td style="color: #666; font-size: 10px; text-align: left;">Total Discount</td>
                <td style="font-weight: bold; font-size: 10px; text-align: right;">{{ Number::currency($posOrder->vat_negation) }}</td>
            </tr>
            <tr>
                <td style="color: #666; font-size: 10px; text-align: left;">Total Charge</td>
                <td style="font-weight: bold; font-size: 10px; text-align: right;">{{ Number::currency($posOrder->total) }}</td>
            </tr>
        </table>
    </div>
</div>

<table style="width: 100%; border-collapse: collapse; font-size: 10px; padding: 0; margin: 0;">
    <tr>
        <td>©{{ date('Y') }} {{ $settings['company']['name'] }} all rights reserved.</td>
    </tr>
</table>