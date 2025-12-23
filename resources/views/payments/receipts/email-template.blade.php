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
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header {
            background: linear-gradient(135deg, #1a6b7f 0%, #0d4a58 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .clinic-logo {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .clinic-subtitle {
            font-size: 12px;
            opacity: 0.9;
        }

        .receipt-title {
            font-size: 20px;
            margin-top: 20px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 10px;
        }

        .receipt-number {
            font-size: 16px;
            margin-top: 10px;
            font-weight: bold;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            margin-bottom: 20px;
        }

        .greeting h2 {
            color: #1a6b7f;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            color: #1a6b7f;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            font-size: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .info-value {
            text-align: right;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table thead {
            background-color: #f5f5f5;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #1a6b7f;
            border-bottom: 2px solid #1a6b7f;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
        }

        .summary-box {
            background-color: #f9f9f9;
            border-left: 4px solid #1a6b7f;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .summary-row.total {
            border-top: 2px solid #1a6b7f;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
            color: #1a6b7f;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-value {
            text-align: right;
        }

        .amount-paid {
            color: #27ae60;
            font-weight: bold;
        }

        .amount-due {
            color: #e74c3c;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
        }

        .status-badge.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-badge.partial {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #e0e0e0;
        }

        .footer a {
            color: #1a6b7f;
            text-decoration: none;
        }

        .button {
            display: inline-block;
            background-color: #1a6b7f;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #0d4a58;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 20px 0;
        }

        [dir="rtl"] .info-row {
            direction: rtl;
        }

        [dir="rtl"] .summary-row {
            direction: rtl;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }

            .content {
                padding: 20px;
            }

            table th, table td {
                padding: 8px 5px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="clinic-logo">🦷</div>
            <div class="clinic-name">{{ config('app.clinic_name', __('messages.clinic_name')) }}</div>
            <div class="clinic-subtitle">{{ __('messages.thank_you_payment') }}</div>
            <div class="receipt-title">{{ __('messages.receipt') }}</div>
            <div class="receipt-number">#{{ $payment->receipt_number }}</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                <h2>{{ __('messages.hello') }}, {{ $payment->patient->first_name }}! 👋</h2>
                <p>{{ __('messages.thank_you_payment') }}. {{ __('messages.receipt_sent') }}</p>
            </div>

            <!-- Patient Information -->
            <div class="section">
                <div class="section-title">{{ __('messages.patient') }}</div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.patient_name') }}:</div>
                    <div class="info-value">{{ $payment->patient->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.file_number') }}:</div>
                    <div class="info-value">{{ $payment->patient->file_number ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.phone') }}:</div>
                    <div class="info-value">{{ $payment->patient->phone ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.email_address') }}:</div>
                    <div class="info-value">{{ $payment->patient->email ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Receipt Information -->
            <div class="section">
                <div class="section-title">{{ __('messages.receipt') }}</div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.receipt_number') }}:</div>
                    <div class="info-value"><strong>{{ $payment->receipt_number }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.date') }}:</div>
                    <div class="info-value">{{ $payment->payment_date->format('Y-m-d H:i') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.payment_method') }}:</div>
                    <div class="info-value">{{ ucfirst($payment->payment_method) }}</div>
                </div>
                @if($payment->reference_number)
                <div class="info-row">
                    <div class="info-label">{{ __('messages.reference_number') }}:</div>
                    <div class="info-value">{{ $payment->reference_number }}</div>
                </div>
                @endif
            </div>

            <!-- Bill Information -->
            @if($payment->bill)
            <div class="section">
                <div class="section-title">{{ __('messages.bill') }}</div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.bill') }} #:</div>
                    <div class="info-value">{{ $payment->bill->bill_number }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.invoice_date') }}:</div>
                    <div class="info-value">{{ $payment->bill->bill_date->format('Y-m-d') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">{{ __('messages.due_date') }}:</div>
                    <div class="info-value">{{ $payment->bill->due_date->format('Y-m-d') }}</div>
                </div>

                @if($payment->bill->billItems->count() > 0)
                <div style="margin-top: 15px;">
                    <strong style="color: #1a6b7f;">{{ __('messages.bill_items') }}:</strong>
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('messages.description') }}</th>
                                <th style="text-align: right;">{{ __('messages.quantity') }}</th>
                                <th style="text-align: right;">{{ __('messages.unit_price') }}</th>
                                <th style="text-align: right;">{{ __('messages.total_price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payment->bill->billItems as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td style="text-align: right;">{{ $item->quantity }}</td>
                                <td style="text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                                <td style="text-align: right;"><strong>{{ number_format($item->total_price, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @endif

            <!-- Payment Summary -->
            <div class="summary-box">
                <div class="section-title">{{ __('messages.payment_summary') }}</div>

                @if($payment->bill)
                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.total_amount') }}:</div>
                    <div class="summary-value">{{ number_format($payment->bill->total_amount, 2) }}</div>
                </div>
                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.paid_amount') }}:</div>
                    <div class="summary-value">{{ number_format($payment->bill->paid_amount, 2) }}</div>
                </div>
                @endif

                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.payment_amount') }}:</div>
                    <div class="summary-value amount-paid">{{ number_format($payment->amount, 2) }}</div>
                </div>

                @if($payment->bill && $payment->bill->getAmountDue() > 0)
                <div class="summary-row">
                    <div class="summary-label">{{ __('messages.amount_remaining') }}:</div>
                    <div class="summary-value amount-due">{{ number_format($payment->bill->getAmountDue(), 2) }}</div>
                </div>
                @endif

                <!-- Payment Status -->
                @if($payment->bill)
                    @if($payment->bill->isPaid())
                    <span class="status-badge success">✓ {{ __('messages.paid_in_full') }}</span>
                    @else
                    <span class="status-badge partial">{{ __('messages.partial_payment') }}</span>
                    @endif
                @else
                    <span class="status-badge success">✓ {{ __('messages.payment_received') }}</span>
                @endif
            </div>

            <!-- Notes -->
            @if($payment->notes)
            <div class="section">
                <div class="section-title">{{ __('messages.notes') }}</div>
                <p>{{ $payment->notes }}</p>
            </div>
            @endif

            <!-- CTA -->
            <div style="text-align: center;">
                <a href="{{ route('payments.receipt', $payment) }}" class="button">{{ __('messages.view_full_receipt') }}</a>
            </div>

            <div class="divider"></div>

            <!-- Footer -->
            <div class="section">
                <div class="section-title">{{ __('messages.contact_us') }}</div>
                <p style="text-align: center;">
                    📞 {{ config('app.clinic_phone', __('messages.clinic_phone')) }}<br>
                    📧 {{ config('app.clinic_email', __('messages.clinic_email')) }}<br>
                    📍 {{ config('app.clinic_address', __('messages.clinic_address')) }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>{{ config('app.clinic_name', __('messages.clinic_name')) }}</strong><br>
                {{ __('messages.thank_you') }}<br>
                <small>{{ __('messages.payment_terms_desc') }}</small>
            </p>
            <p style="margin-top: 15px; font-size: 10px; color: #ccc;">
                {{ __('messages.print_date') }}: {{ now()->format('Y-m-d H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>
