<?php

return [
    'receipt_prefix' => env('PAYMENT_RECEIPT_PREFIX', 'RCP'),
    'advance_credit_expiry_days' => env('ADVANCE_CREDIT_EXPIRY_DAYS', 365),
    'auto_apply_credits' => env('AUTO_APPLY_ADVANCE_CREDITS', false),
    'overpayment_handling' => env('PAYMENT_OVERPAYMENT_HANDLING', 'create_credit'),
    'payment_methods' => ['cash', 'cheque', 'card', 'bank_transfer', 'insurance'],
    'receipt_email_enabled' => env('PAYMENT_RECEIPT_EMAIL_ENABLED', true),
    'receipt_pdf_enabled' => env('PAYMENT_RECEIPT_PDF_ENABLED', true),
];
