@component('mail::message')
# ⚠️ Payment Overdue Notice

Dear {{ $patient->first_name }},

This is a reminder that **your payment is now overdue**. Please pay your outstanding bill at your earliest convenience.

@component('mail::panel')
**Your Outstanding Balance:**

💰 **Amount Due:** {{ $amount }} EGP
📅 **Due Date:** {{ $payment->due_date->format('F j, Y') }}
❌ **Status:** Overdue
🏥 **Clinic:** Thnaya Clinic

**Please Pay Immediately**
@endcomponent

**What You Owe:**

Your bill for services rendered on {{ $payment->created_at->format('F j, Y') }} remains unpaid.

| Details | Amount |
|---------|--------|
| Service Cost | {{ $amount }} EGP |
| Days Overdue | Days |
| Late Fee (if applicable) | Contact us |

**Why This Matters:**

- Outstanding balances affect your account
- Late payments may impact your credit
- Your next appointment may be held
- Insurance claims may be delayed

**How to Pay:**

We offer **multiple convenient payment methods:**

💳 **Credit/Debit Card**
- Secure online payment
- Instant confirmation

🏦 **Bank Transfer**
- Direct transfer to clinic account
- Include invoice number

💵 **Cash Payment**
- Pay at clinic reception
- Get receipt immediately

📱 **Mobile Payment**
- Use your mobile banking app
- Fast and secure

@component('mail::button', ['url' => route('payments.pay', $payment)])
Pay Your Bill Now
@endcomponent

**Payment Plans Available:**

If you're having difficulty paying the full amount:
- **Installment plans** - Spread payments over time
- **Flexible terms** - Customized to your situation
- **No interest** - For qualified patients

**Need Help?**

Contact our billing department:
- 📞 Phone: +20 123 456 7890 (Extension: Billing)
- 📧 Email: billing@thnaya-clinic.com
- 🏥 Visit us in person at Thnaya Clinic
- 💬 Text us for quick inquiries

**Payment Options:**

| Method | Details |
|--------|---------|
| Online | Available 24/7, Instant |
| Bank Transfer | Include invoice #, 1-2 days |
| Card | Secure, Instant |
| Cash | At clinic, Receipt given |

**Important Notice:**

If payment is not received within **7 days**, we may:
- Suspend future appointments
- Report to credit bureaus
- Refer to collections agency
- Pursue legal action

**Let Us Help:**

We understand that unexpected medical bills can be challenging. Please don't hesitate to reach out if you need:
- Payment arrangements
- Insurance clarification
- Invoice details
- Itemized billing

**Act Now:**

Please pay your outstanding balance of **{{ $amount }} EGP** today. Every day of delay increases your financial obligation and may affect your account status.

**Your Account Status:**

- Current Balance: {{ $amount }} EGP
- Days Overdue: (will be calculated)
- Next Follow-up: 7 days
- Impact: Account suspension possible

---

**We Value Your Business:**

Thank you for choosing Thnaya Clinic. We look forward to your payment and continued service to your dental health care needs.

Best regards,

**Thnaya Clinic - Collections Department** 💳

---
*Please respond within 7 days to avoid further action.*
@endcomponent
