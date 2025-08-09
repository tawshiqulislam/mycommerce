<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order {{ $status === 'pending' ? 'Placed' : ucfirst($status) }}: #{{ $order->code }}</title>
</head>
<body>
    <h1>Order {{ $status === 'pending' ? 'Placed' : ucfirst($status) }}</h1>
    <p>Your order (#{{ $order->code }}) has been {{ $status === 'pending' ? 'Placed' : $status }}.</p>
    @include('email.invoice_template', ['order' => $order])
</body>
</html>
