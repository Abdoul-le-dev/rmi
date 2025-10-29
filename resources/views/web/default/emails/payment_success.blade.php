@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1>Payment Successful</h1>
        <p>Dear {{ $order->user->full_name }},</p>
        <p>Your payment for order #{{ $order->id }} was successful. Thank you for your purchase!</p>
        <p>Your Purchase Details:</p>
        <ul>
            <li>Order ID: {{ $order->id }}</li>
            <li>Amount Paid: {{ $order->total_amount }}</li>
            <li>Products Purchased:</li>
            <ul>
                @foreach ($carts as $cart)
                    @php
                        $cartItemInfo = $cart->getItemInfo();
                    @endphp
                    <li>
                        {{ $cartItemInfo['title'] }}
                    </li>
                @endforeach
            </ul>
        </ul>
        <p>Thank you for shopping with us!</p>
    </td>
@endsection
