@component('mail::message')
# ❌ Insurance Claim Rejected

Dear {{ $patient->first_name }},

We regret to inform you that your insurance claim has been **rejected** by your insurance company.

@component('mail::panel')
**Claim Rejection Details:**

❌ **Status:** REJECTED
📅 **Decision Date:** {{ $rejectionDate }}
🏥 **Clinic:** Thnaya Clinic

**Reason for Rejection:**
{{ $reason }}
@endcomponent

**What Happened:**

Your insurance company has reviewed your claim and determined that the requested services are not covered under your current insurance plan. This decision is based on your policy terms and coverage limits.

**Your Options:**

1. **Appeal the Decision**
   - You have the right to appeal this rejection
   - Contact your insurance company to start the appeals process
   - We can provide supporting documentation

2. **Request Explanation**
   - Ask your insurance company for detailed explanation
   - Review your insurance policy documentation
   - Check for coverage limitations or exclusions

3. **Financial Responsibility**
   - As the claim was rejected, the services cost becomes your responsibility
   - View your payment options below
   - Contact our billing department for payment plans

**What We Can Do:**

We can help you:
- ✓ Provide detailed medical documentation
- ✓ Submit an appeal on your behalf
- ✓ Clarify service codes and descriptions
- ✓ Offer payment plan options
- ✓ Explain the services provided

@component('mail::button', ['url' => route('insurance.show', $insurance)])
View Claim Details
@endcomponent

**Need Assistance?**

Please contact our insurance and billing department:
- 📞 Phone: +20 123 456 7890
- 📧 Email: insurance@thnaya-clinic.com
- 🏥 Visit us in person at Thnaya Clinic

**Payment Information:**

If you choose not to appeal, please arrange payment for the services rendered:
- View your invoice in your patient portal
- Set up a payment plan with our billing department
- Multiple payment methods accepted

**Next Steps:**

1. Review this rejection notice carefully
2. Contact your insurance company if you disagree
3. Let us know if you want to appeal
4. Arrange payment if applicable

We understand this is disappointing news. Our team is here to help you navigate this situation and find the best solution for your healthcare needs.

Best regards,

**Thnaya Clinic - Insurance & Billing Department** 🏥

---
*If you believe this rejection is in error, please contact us immediately.*
@endcomponent
