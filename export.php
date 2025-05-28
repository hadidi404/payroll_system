<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 40px;
        }

        .payslip-container {
            background: #fff;
            padding: 25px 40px;
            max-width: 700px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .section {
            margin-top: 30px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
        }

        .summary td {
            font-weight: bold;
        }

        .total {
            background-color: #e8f5e9;
            font-weight: bold;
            color: #2e7d32;
        }
    </style>
</head>
<body>

<div class="payslip-container">
    <h2>Payslip for May 2025</h2>

    <div class="section">
        <div class="section-title">Employee Information</div>
        <table>
            <tr><td>Employee Name:</td><td>John Doe</td></tr>
            <tr><td>Employee ID:</td><td>EMP12345</td></tr>
            <tr><td>Designation:</td><td>Software Engineer</td></tr>
            <tr><td>Department:</td><td>Technology</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Earnings</div>
        <table>
            <tr><th>Description</th><th>Amount</th></tr>
            <tr><td>Basic Pay</td><td>$4,000.00</td></tr>
            <tr><td>HRA</td><td>$1,500.00</td></tr>
            <tr><td>Transport Allowance</td><td>$300.00</td></tr>
            <tr><td>Other Allowances</td><td>$200.00</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Deductions</div>
        <table>
            <tr><th>Description</th><th>Amount</th></tr>
            <tr><td>Tax</td><td>$500.00</td></tr>
            <tr><td>Health Insurance</td><td>$150.00</td></tr>
            <tr><td>Provident Fund</td><td>$300.00</td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Summary</div>
        <table class="summary">
            <tr><td>Total Earnings:</td><td>$6,000.00</td></tr>
            <tr><td>Total Deductions:</td><td>$950.00</td></tr>
            <tr class="total"><td>Net Pay:</td><td>$5,050.00</td></tr>
        </table>
    </div>
</div>

</body>
</html>
