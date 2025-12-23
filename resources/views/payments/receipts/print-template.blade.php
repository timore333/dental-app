<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.receipt') }} - {{ $payment->receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding: 20px;
            margin: 0 auto;
            background-color: white;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border: 2px solid #333;
            page-break-after: always;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .clinic-logo {
            font-size: 24px;
            font-weight: bold;
            color: #1a6b7f;
            margin-bottom: 5px;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .clinic-info {
            font-size: 11px;
            color: #666;
            margin-top: 10px;
        }

        .clinic-contact {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }

        .receipt-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            color: #1a6b7f;
        }

        /* Receipt Details */
        .receipt-details {
            margin: 30px 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .detail-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            color: #1a6b7f;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 50% 50%;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
        }

        .detail-value {
            text-align: right;
        }

        /* Bill Items */
        .bill-section {
            margin: 30px 0;
        }

        .bill-section h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            color: #1a6b7f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        table thead {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #1a6b7f;
            border: 1px solid #ddd;
        }

        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Summary */
        .summary {
            margin: 30px 0;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .summary-row {
            display: grid;
            grid-template-columns: 60% 40%;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .summary-row.total {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 14px;
            color: #1a6b7f;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-value {
            text-align: right;
        }

        /* Payment Confirmation */
        .payment-status {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px solid #27ae60;
            background-color: #f0fdf4;
            border-radius: 5px;
        }

        .payment-status.success {
            border-color: #27ae60;
            background-color: #f0fdf4;
            color: #27ae60;
        }

        .payment-status.partial {
            border-color: #f59e0b;
            background-color: #fffbeb;
            color: #f59e0b;
        }

        .status-text {
            font-size: 16px;
            font-weight: bold;
        }

        .status-amount {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            font-size: 10px;
            color: #999;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin: 40px 0;
        }

        .signature-box {
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
        }

        .signature-label {
            font-size: 11px;
            font-weight: bold;
            margin-top: 5px;
        }

        .thank-you {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #1a6b7f;
            font-weight: bold;
        }

        .notes {
            margin: 20px 0;
            padding: 15px;
            background-color: #f0f9ff;
            border-left: 4px solid #1a6b7f;
            font-size: 10px;
            line-height: 1.5;
        }

        /* RTL Support */
        [dir="rtl"] .detail-row {
            direction: rtl;
        }

        [dir="rtl"] .summary-row {
            direction: rtl;
        }

        [dir="rtl"] .signature-section {
            grid-template-columns: 1fr 1fr;
        }

        /* Print Optimization */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }

            .receipt-container {
                border: none;
                page-break-inside: avoid;
            }
        }

        .amount-due {
            color: #e74c3c;
            font-weight: bold;
        }

        .amount-paid {
            color: #27ae60;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="receipt-container">
            <!-- Header -->
            <div class="header">
                <div class="clinic-logo">🦷</div>
                <div class="clinic-name">{{ config('app.clinic_name', __('messages.clinic_name')) }}</div>
                <div class="clinic-info">
                    {{ config('app.clinic_address', __('messages.clinic_address')) }}
                </div>
                <div class="clinic-contact">
                    {{ config('app.clinic_phone', __('messages.clinic_phone')) }} |
                    {{ config('app.clinic_email', __('messages.clinic_email')) }}
                </div>
                <div class="clinic-contact">
                    {{ __('messages.license_number') }}: {{ config('app.clinic_license', 'DC-2024-001') }}
                </div>
                <div class="receipt-title">{{ __('messages.receipt') }}</div>
            </div>

            <!-- Receipt Details -->
            <div class="receipt-details">
                <div class="detail-section">
                    <h3>{{ __('messages.receipt_number') }}</h3>
                    <div class="detail-row">
                        <div class="detail-label">{{ __('messages.receipt_number') }}:</div>
                        <div class="detail-value" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};">
                            <strong>{{ $payment->receipt_number }}</strong>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">{{ __('messages.date') }}:</div>
                        <div class="detail-value" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};">
                            {{ $payment->payment_date->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>{{ __('messages.patient') }}</h3>
                    <div class="detail-row">
                        <div class="detail-label">{{ __('messages.patient_name') }}:</div>
                        <div class="detail-value" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};">
                            {{ $payment->patient->name }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">{{ __('messages.file_number') }}:</div>
                        <div class="detail-value" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};">
                            {{ $payment->patient->file_number ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">{{ __('messages.phone') }}:</div>
                        <div class="detail-value" style="text-align: {{ app()->getLocale() === 'ar' ? 'left' : 'right' }};">
                            {{ $payment->patient->phone ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bill Information -->
            @if($payment->bill)
            <div class="bill-section">
                <h3>{{ __('messages.bill') }}</h3>
                <table>
                    <tbody>
                        <tr>
                            <td><strong>{{ __('messages.bill') }} #</strong></td>
                            <td class="text-right">{{ $payment->bill->bill_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('messages.invoice_date') }}</strong></td>
                            <td class="text-right">{{ $payment->bill->bill_date->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('messages.due_date') }}</strong></td>
                            <td class="text-right">{{ $payment->bill->due_date->format('Y-m-d') }}</td>
                        </tr>
                        @if($payment->bill->doctor)
                        <tr>
                            <td><strong>{{ __('messages.doctor_name') }}</strong></td>
                            <td class="text-right">{{ $payment->bill->doctor->name ?? 'N/A' }}</td>
                        </tr>
                        @endif
                        @if($payment->bill->insuranceCompany)
                        <tr>
                            <td><strong>{{ __('messages.insurance_company') }}</strong></td>
                            <td class="text-right">{{ $payment->bill->insuranceCompany->name }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bill Items -->
            @if($payment->bill->billItems->count() > 0)
            <div class="bill-section">
                <h3>{{ __('messages.bill_items') }}</h3>
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('messages.description') }}</th>
                            <th class="text-right">{{ __('messages.quantity') }}</th>
                            <th class="text-right">{{ __('messages.unit_price') }}</th>
                            <th class="text-right">{{ __('messages.total_price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payment->bill->billItems as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right"><strong>{{ number_format($item->total_price, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
            @endif

            <!-- Payment Summary -->
            <div class="summary">
                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.total_amount') }}:</div>
                    <div class="summary-value">
                        @if($payment->bill)
                            {{ number_format($payment->bill->total_amount, 2) }}
                        @else
                            N/A
                        @endif
                    </div>
                </div>

                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.paid_amount') }}:</div>
                    <div class="summary-value">
                        @if($payment->bill)
                            {{ number_format($payment->bill->paid_amount, 2) }}
                        @else
                            {{ number_format($payment->amount, 2) }}
                        @endif
                    </div>
                </div>

                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.payment_amount') }}:</div>
                    <div class="summary-value amount-paid">
                        {{ number_format($payment->amount, 2) }}
                    </div>
                </div>

                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.payment_method') }}:</div>
                    <div class="summary-value">{{ ucfirst($payment->payment_method) }}</div>
                </div>

                @if($payment->reference_number)
                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.reference_number') }}:</div>
                    <div class="summary-value">{{ $payment->reference_number }}</div>
                </div>
                @endif

                @if($payment->bill && $payment->bill->getAmountDue() > 0)
                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.amount_remaining') }}:</div>
                    <div class="summary-value amount-due">
                        {{ number_format($payment->bill->getAmountDue(), 2) }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Payment Status -->
            @if($payment->bill)
            <div class="payment-status {{ $payment->bill->isPaid() ? 'success' : 'partial' }}">
                @if($payment->bill->isPaid())
                    <div class="status-text">✓ {{ __('messages.paid_in_full') }}</div>
                @else
                    <div class="status-text">{{ __('messages.partial_payment') }}</div>
                    <div class="status-amount">{{ number_format($payment->bill->getAmountDue(), 2) }}</div>
                    <div style="font-size: 11px;">{{ __('messages.amount_remaining') }}</div>
                @endif
            </div>
            @else
            <div class="payment-status success">
                <div class="status-text">✓ {{ __('messages.payment_received') }}</div>
                <div class="status-amount">{{ number_format($payment->amount, 2) }}</div>
            </div>
            @endif

            <!-- Notes -->
            @if($payment->notes)
            <div class="notes">
                <strong>{{ __('messages.notes') }}:</strong><br>
                {{ $payment->notes }}
            </div>
            @endif

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div style="height: 40px;"></div>
                    <div class="signature-label">{{ __('messages.authorized_by') }}</div>
                </div>
                <div class="signature-box">
                    <div style="height: 40px;"></div>
                    <div class="signature-label">{{ __('messages.patient_name') }}</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="thank-you">{{ __('messages.thank_you') }}</div>
                <div style="text-align: center; margin-top: 20px; font-size: 9px;">
                    {{ __('messages.contact_us') }}: {{ config('app.clinic_phone') }}<br>
                    {{ __('messages.print_date') }}: {{ now()->format('Y-m-d H:i:s') }}<br>
                    {{ __('messages.payment_terms_desc') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
