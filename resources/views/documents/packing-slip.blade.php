<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Packing slip {{ $order->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        ul { padding-left: 18px; }
    </style>
</head>
<body>
    <h1>Packing slip — {{ $order->number }}</h1>
    <p>Customer: {{ $order->customer?->first_name }} {{ $order->customer?->last_name }}</p>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->quantity }} × {{ $item->name }}</li>
        @endforeach
    </ul>
</body>
</html>
