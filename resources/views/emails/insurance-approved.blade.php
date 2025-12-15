@component('mail::message')
# ✅ Insurance Claim Approved

Dear {{ $patient->first_name }},

**Great news!** Your insurance claim has been **APPROVED**! 🎉

@component('mail::panel')
**Claim Approval Details:**

✅ **Status:** APPROVED
💰 **Coverage Amount:** {{ $coverageAmount }} EGP
📅 **Approval Date:** {{ $approvalDate }}
🏥 **Clinic:** Thnaya Clinic
@endcomponent

**What This Means:**

Your insurance company has approved coverage for your medical services. The approved amount of **{{ $coverageAmount }} EGP** will be processed according to your insurance policy.

**Next Steps:**

1. **No Action Required:** We will handle the insurance claim processing
2. **Payment Coordination:** Your insurance will be billed directly
3. **Your Responsibility:** Pay any remaining balance or copay
4. **Documentation:** Keep this approval email for your records

**Coverage Details:**

- ✅ Medical services covered
- ✅ Treatment costs approved
- ✅ Covered percentage: As per your insurance policy
- ✅ Processing time: 7-14 business days

**Your Outstanding Balance:**

| Item | Amount |
|------|--------|
| Total Service Cost | {{ $totalCost ?? 'N/A' }} EGP |
| Insurance Coverage | {{ $coverageAmount }} EGP |
| Your Responsibility | {{ $patientResponsibility ?? 'To be calculated' }} EGP |

**Questions About Your Coverage?**

If you have questions about the approval or your coverage:
- 📞 Contact your insurance company
- 📧 Email us: insurance@thnaya-clinic.com
- 🏥 Call Thnaya Clinic: +20 123 456 7890

@component('mail::button', ['url' => route('insurance.show', $insurance)])
View Full Approval Details
@endcomponent

**Important Reminders:**

- Keep this approval email for your records
- This approval is valid for 12 months from the approval date
- Any changes to your insurance plan may affect coverage
- Contact us if you have additional medical needs

**Thank You!**

Thank you for choosing Thnaya Clinic for your healthcare. We're committed to providing excellent care and supporting your insurance claims process.

Best regards,

**Thnaya Clinic - Insurance Department** 🏥

---
*This email confirms your insurance claim approval. Please keep it for your records.*
@endcomponent
