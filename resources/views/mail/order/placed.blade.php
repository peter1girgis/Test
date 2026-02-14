<x-mail::message>
# Order Placed successfully

thank you for your order . your order number is {{ $order->id }}.
You can view your order details by clicking the button below.

<x-mail::button :url="$url">
Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
