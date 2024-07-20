<x-mail::message>
# Hello {{ $name }}

The shipping process was successful

A balance was charged: {{ $balance }},<br><br>
Shipping Point: {{ $shippingPoint }}<br><br>
Employee: {{ $employee }},<br><br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
