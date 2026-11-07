<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h1>Invoice {{ $order->number }}</h1>
    <p>Date: {{ $order->created_at?->format('Y-m-d') }}</p>
    <p>Total: {{ $order->currency }} {{ $order->grand_total }}</p>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Line total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->line_total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
