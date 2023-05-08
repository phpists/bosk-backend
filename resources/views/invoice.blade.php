<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>
<body style="padding-left: 50px; padding-right: 50px">

<table style="border-collapse: collapse; width: 100%">
    <tr>
        <td style="padding: 10px;font-weight: 500; font-size: 28px;font-family: sans-serif">Invoice</td>
        <td style="padding: 10px;text-align: right;font-weight: 500;font-size: 28px;font-family: sans-serif">{{$invoice->name}}</td>
    </tr>
</table>
<table style="width: 100%; font-family: sans-serif; border-collapse: separate; border-spacing: 10px; border-radius: 10px;table-layout: fixed">
    <tr style="font-family: sans-serif">
        <td style="border: 2px solid #aaa; border-radius: 10px; padding: 20px; box-sizing: border-box;">
            <p style="margin-bottom: 15px; padding-bottom: 0; font-size: 16px">Provider</p>
            <p style="font-size: 24px; margin-top: 0;margin-bottom: 15px; font-weight: normal">{{$invoice->user->name}}</p>
            <p style="margin-bottom: 70px; margin-top: 0; padding: 0; font-size: 16px;">{{$invoice->user->address}}</p>
            <p>Identification number: <span style="margin-left: 15px; font-size: 16px;">{{$invoice->user->tax_id}}</span></p>
        </td>
        <td style="border: 2px solid #aaa; border-radius: 10px;padding: 20px; font-size: 16px; box-sizing: border-box;vertical-align: top;">
            <p style="margin-bottom: 15px;  font-size: 16px;padding-bottom: 0">Purchaser</p>
            <p style="font-size: 24px; margin-top: 0;margin-bottom: 10px; font-weight: normal">{{$invoice->customer->client_name}}</p>
        </td>
    </tr>
    <tr>
        <td style="border: 2px solid #aaa; border-radius: 10px; padding: 20px; box-sizing: border-box;vertical-align: top;">
            <p style="margin-bottom: 5px; padding-bottom: 0; font-size: 16px">Payment</p>
            <p style="font-size: 16px; margin-top: 0;font-weight: normal">Payment in cash</p>
        </td>
        <td style="border: 2px solid #aaa; border-radius: 10px; padding: 20px; box-sizing: border-box;">
            <table style="border-collapse: collapse; width: 100%; margin-bottom: 10px">
                <tr>
                    <td style="font-weight: 400;font-size: 16px;font-family: sans-serif">Issue date</td>
                    <td style="text-align: right;font-weight: 400;font-size: 20px;font-family: sans-serif">{{$invoice->issue_date}}</td>
                </tr>
            </table>
            <table style="border-collapse: collapse; width: 100%; margin-bottom: 30px">
                <tr>
                    <td style="font-weight: 400;font-size: 16px;font-family: sans-serif">Due date</td>
                    <td style="text-align: right;font-weight: 400;font-size: 20px;font-family: sans-serif">{{$invoice->due_date}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="font-family: sans-serif;width: 100%;border-collapse: collapse;box-sizing: border-box;margin-top: 10px">
    <tr style="background: #e3e3e3; box-sizing: border-box;">
        <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">Item</td>
        <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">Quantity</td>
        <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">Unit</td>
        <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">Price per item</td>
        <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">Amount</td>
    </tr>
        @foreach($invoice->invoice_items as $invoice_item)
            <tr style="box-sizing: border-box;">
                <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">
                    {{$invoice_item->name}}</td>
                <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">
                    {{$invoice_item->quantity}}
                </td>
                <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">
                    {{$invoice_item->unit}}
                </td>
                <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">
                    {{$invoice_item->price}}
                </td>
                <td style="padding: 10px 6px;box-sizing: border-box;font-size: 16px;border-bottom: 1px solid #aaa">
                    {{$invoice_item->amount}}
                </td>
            </tr>
        @endforeach
</table>
<div style="margin-right: 0; margin-top: 40px; width: 200px; float: right;font-family: sans-serif">
    <span style="float: left;font-size: 20px;">Subtotal:</span>
    <span style="float: right;font-size: 20px;">{{$invoice->subtotal}}</span>
</div>
<div style="clear: both"></div>
<div style="margin-right: 0; margin-top: 20px; width: 200px; float: right;font-family: sans-serif">
    <span style="float: left;font-size: 20px;">Tax:</span>
    <span style="float: right;font-size: 20px;">{{$invoice->tax}}</span>
</div>
<div style="clear: both"></div>
<div style="margin-right: 0; margin-top: 20px; width: 200px; float: right;font-family: sans-serif">
    <span style="float: left;font-size: 24px; font-weight: bold">Total:</span>
    <span style="float: right;font-size: 24px; font-weight: bold">{{$invoice->total}}</span>
</div>

</body>
</html>
