<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ticket #{{ $sale->id }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000000;
            font-weight: bold;
            margin: 0;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .bold {
            font-weight: 900;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .border-top {
            border-top: 1px dashed #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* Ensure distinct black color for thermal printers */
        * {
            color: #000 !important;
        }
    </style>
</head>

<body>
    <div class="text-center mb-2" style="margin-top: 5px;">
        <div class="bold" style="font-size: 14px;">BOBACAT</div>
        <div>Av. de las Bebidas 456, Ciudad</div>
        <div>RUC: 20601234567</div>
    </div>

    <div class="mb-2">
        Ticket: #{{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}<br>
        Fecha: {{ $sale->created_at->format('d/m/Y H:i') }}
    </div>

    <div class="border-top">
        <table>
            @foreach ($sale->details as $detail)
                <tr>
                    <td colspan="2" class="bold">{{ $detail->productSize->product->name }} (Env:
                        {{ $detail->productSize->size->number }})</td>
                </tr>
                <tr>
                    <td>{{ $detail->quantity }} x S/. {{ number_format($detail->unit_price, 2) }}</td>
                    <td class="text-end">S/. {{ number_format($detail->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="border-top text-end bold" style="font-size: 13px; padding: 8px 0;">
        TOTAL: S/. {{ number_format($sale->total, 2) }}
    </div>

    <div class="text-center border-top" style="padding-top: 8px;">
        ¡Gracias por su visita!<br>
        Vuelva pronto
    </div>
</body>

</html>
