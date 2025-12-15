@component('mail::message')
# 💳 Payment Receipt

Dear {{ $patient->first_name }},

Thank you for your payment! We have successfully received your payment at **Thnaya Clinic**.

@component('mail::panel')
**Payment Details:**

💰 **Amount Paid:** {{ $amount }} EGP
📅 **Payment Date:** {{ $paymentDate }}
✅ **Status:** Received
🏥 **Clinic:** Thnaya Clinic
@endcomponent

**Receipt Information:**

| Details | Amount |
|---------|--------|
| Total Amount Paid | {{ $amount }} EGP |
| Payment Method | {{ $payment->payment_method }} |
| Transaction ID | `{{ $payment->transaction_id }}` |
| Date | {{ $paymentDate }} |

**What's Included:**

✅ Services provided
✅ Professional consultation
✅ Treatment materials
✅ Follow-up support

**Your Records:**

This receipt is now available in your patient portal:
- View payment history
- Download receipts anytime
- Track upcoming bills
- Schedule payments

@component('mail::button', ['url' => route('payments.show', $payment)])
View Full Receipt
@endcomponent

**Need an Invoice for Insurance?**

If you need a detailed invoice for insurance purposes, please reply to this email or contact us:
- 📞 Phone: +20 123 456 7890
- 📧 Email: billing@thnaya-clinic.com

**Tax Information:**

Your payment may be tax-deductible. Keep this receipt for your tax records.

**Thank You!**

We appreciate your prompt payment and your trust in Thnaya Clinic. If you have any questions about this receipt, please don't hesitate to contact us.

Best regards,

**Thnaya Clinic - Billing Department** 💳

---
*Please keep this email for your records. It serves as your official payment receipt.*
@endcomponent
