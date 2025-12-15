@component('mail::message')
# 👋 Welcome to Thnaya Clinic!

Dear {{ $patient->first_name }},

Welcome to **Thnaya Clinic**! We are delighted to have you as our valued patient.

Your account has been successfully created and is now ready to use. You can now:

@component('mail::panel')
✅ Book appointments online anytime
✅ View your medical records securely
✅ Receive appointment reminders
✅ Track your billing and payments
✅ Manage your notification preferences
✅ Access your prescription history
@endcomponent

**Getting Started:**

1. Visit our clinic or login to your account
2. Complete your patient profile
3. Book your first appointment
4. Receive appointment confirmations via SMS or email

**Need Help?**

If you have any questions or need assistance, please don't hesitate to contact us:
- 📞 Phone: +20 123 456 7890
- 📧 Email: support@thnaya-clinic.com
- 🏥 Visit us in person at Thnaya Clinic

We look forward to seeing you soon!

@component('mail::button', ['url' => config('app.url')])
Login to Your Account
@endcomponent

Best regards,

**Thnaya Clinic Team** 🏥

---
*This is an automated message. Please do not reply to this email.*
@endcomponent
