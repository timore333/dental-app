@component('mail::message')
# 🎂 Happy Birthday!

Dear {{ $patient->first_name }},

**Happy Birthday!** 🎉🎂

On this special day, we want to wish you a wonderful birthday and a fantastic year ahead! Thank you for being our valued patient.

@component('mail::panel')
**Special Birthday Offer! 🎁**

Enjoy an **exclusive discount** on your next visit to Thnaya Clinic!

**Claim Your Special Offer:**
- 20% discount on services
- Valid for 30 days from today
- Can be combined with insurance
- No booking required
@endcomponent

**Your Birthday Gift:**

As a token of our appreciation, we're offering you:

✨ Special discount on all services
✨ Complimentary teeth cleaning (with any treatment)
✨ Free consultation for cosmetic services
✨ Priority appointment booking
✨ Extended appointment time

**How to Use Your Offer:**

1. Book an appointment online or call us
2. Mention this is your birthday month
3. Present this email at your visit
4. Enjoy your special discount!

**Schedule Your Birthday Treat:**

@component('mail::button', ['url' => route('appointments.create')])
Book Your Appointment
@endcomponent

**Why Choose Thnaya Clinic?**

- ✓ Expert dental care
- ✓ Modern equipment
- ✓ Comfortable environment
- ✓ Professional team
- ✓ Affordable pricing

**Make This Birthday Special:**

Don't miss this opportunity to take care of yourself! Whether it's a regular checkup or a cosmetic treatment, now is the perfect time.

**Contact Us:**

- 📞 Phone: +20 123 456 7890
- 📧 Email: appointments@thnaya-clinic.com
- 🌐 Website: www.thnaya-clinic.com
- 📍 Visit us: Thnaya Clinic

**Valid Until:**
{{ now()->addDays(30)->format('F j, Y') }}

---

Once again, **Happy Birthday!** 🎂

We hope you have a fantastic year filled with good health and happiness. We look forward to seeing you soon!

Best regards,

**Thnaya Clinic Team** 🏥

---
*This offer is valid for 30 days. Terms and conditions apply.*
@endcomponent
