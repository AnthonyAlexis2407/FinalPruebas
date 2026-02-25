<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header {
            border-bottom: 2px solid #444;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header table {
            width: 100%;
        }

        .company-info h1 {
            margin: 0;
            color: #f43f5e;
            /* BobaCat Pink */
            font-size: 28px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .company-info p {
            margin: 2px 0;
            color: #7f8c8d;
            font-size: 12px;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h2 {
            margin: 0;
            color: #7f8c8d;
            font-size: 24px;
            font-weight: 300;
            text-transform: uppercase;
        }

        .invoice-info p {
            margin: 2px 0;
            font-weight: bold;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .products-table th {
            background-color: #fff1f2;
            /* Soft Pink */
            color: #e11d48;
            /* Deep Pink Text */
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #fb7185;
        }

        .products-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .products-table tr:last-child td {
            border-bottom: none;
        }

        .products-table .text-right {
            text-align: right;
        }

        .products-table .text-center {
            text-align: center;
        }

        .total-section {
            width: 100%;
            text-align: right;
        }

        .total-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .total-table td {
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }

        .total-table .total-row td {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            border-top: 2px solid #444;
            border-bottom: none;
            padding-top: 15px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table>
                <tr>
                    <td class="company-info">
                        <h1>BOBACAT</h1>
                        <p>Av. de las Bebidas 456, Ciudad Gourmet</p>
                        <p>RUC: 20601234567</p>
                        <p>Tel: +51 912 345 678 | Email: hola@bobacat.com</p>
                    </td>
                    <td class="invoice-info">
                        <h2>Factura</h2>
                        <p>Nro: {{ str_pad($sale->id, 8, '0', STR_PAD_LEFT) }}</p>
                        <p>Fecha: {{ $sale->created_at->format('d/m/Y') }}</p>
                        <p>Hora: {{ $sale->created_at->format('H:i A') }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Order Items -->
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Producto / Descripción</th>
                    <th class="text-center">Presentación</th>
                    <th class="text-right">Precio Unit.</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->details as $detail)
                    <tr>
                        <td>
                            <strong style="color: #333;">{{ $detail->productSize->product->name }}</strong>
                            <br>
                            <span style="font-size: 11px; color: #888;">Código:
                                {{ $detail->productSize->product->code }}</span>
                        </td>
                        <td class="text-center">{{ $detail->productSize->size->number }}</td>
                        <td class="text-right">S/. {{ number_format($detail->unit_price, 2) }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">S/. {{ number_format($detail->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">S/. {{ number_format($sale->total, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>IGV / Impuestos (0%):</strong></td>
                    <td class="text-right">S/. 0.00</td>
                </tr>
                <tr class="total-row">
                    <td>Total a Pagar:</td>
                    <td class="text-right">${{ number_format($sale->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por su compra. Esperamos verle pronto.</p>
            <p>Para cambios o devoluciones, por favor presente este documento dentro de los 30 días posteriores a la
                compra.</p>
        </div>
    </div>
</body>

</html>
