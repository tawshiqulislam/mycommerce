<div>
    <div class=" table-list-wrp">
        <table class="table-list">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($getState() as $product)
                <tr>
                    <td>
                        {{ $product->name }}
                        <div class="flex gap-5">
                            <span> {{ $product->color }}</span>
                        </div>
                    </td>
                    <td class="whitespace-nowrap">
                        {{ Number::currency($product->price) }}
                    </td>
                    <td align="center">
                        {{ $product->quantity }}
                    </td>
                    <td class="whitespace-nowrap">
                        {{ Number::currency($product->total) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pt-8 flex justify-end ">
        <dl class="sm:max-w-sm w-80 font-medium text-sm space-y-5 ">
            <x-descripction-list title="Total" :description="Number::currency($getRecord()->total + $getRecord()->vat - $getRecord()->vat_negation)" />
            <x-descripction-list title="+VAT" :description="Number::currency($getRecord()->vat)" />
            <x-descripction-list title="-Discount" :description="Number::currency($getRecord()->vat_negation)" />
            <x-descripction-list title="Total Paid" :description="Number::currency($getRecord()->total)" />
        </dl>
    </div>
</div>