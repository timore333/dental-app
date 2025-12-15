@component('mail::message')
# 🎄 Happy Holidays from Thnaya Clinic!

Dear {{ $patient->first_name }},

**Happy Holidays!** 🎄✨

As the holiday season approaches, we want to take a moment to thank you for your trust and continued support. It has been a privilege to serve you this year.

@component('mail::panel')
**Holiday Special Offer! 🎁**

Enjoy special holiday pricing on your dental care!

**Limited Time Offer:**
- 25% discount on all services
- Free professional cleaning with treatment
- Extended appointment hours
- Priority booking available
@endcomponent

**Thank You For Choosing Thnaya Clinic:**

This year, we're grateful to have served many wonderful patients like you. Your health and satisfaction are our top priorities.

**Holiday Season Benefits:**

✨ Special rates on preventive care
✨ Family packages available
✨ Gift certificates for loved ones
✨ Flexible appointment scheduling
✨ Extended hours for your convenience

**Plan Ahead for the New Year:**

Don't wait until January! Start the new year with a healthy smile:

1. **Schedule a checkup** - Preventive care
2. **Plan treatments** - Get started early
3. **Use your insurance** - Max out your benefits
4. **Gift dental care** - Perfect gift for loved ones

**Holiday Gift Ideas:**

Looking for the perfect gift? Give the gift of health:
- 💝 Dental gift certificates
- 💝 Professional cleaning packages
- 💝 Cosmetic consultation packages
- 💝 Family dental packages

@component('mail::button', ['url' => route('appointments.create')])
Book Your Holiday Appointment
@endcomponent

**Holiday Hours:**

- **December 1-24:** Regular hours
- **December 25-26:** Closed
- **December 27-31:** Limited hours (Emergency only)
- **January 1:** Closed (New Year's Day)
- **January 2:** Reopens with regular hours

**Contact Us:**

- 📞 Phone: +20 123 456 7890
- 📧 Email: appointments@thnaya-clinic.com
- 🏥 Visit us: Thnaya Clinic
- 🌐 Online booking: Available 24/7

**New Year's Resolutions?**

Make dental health one of your resolutions for the new year:
- Regular checkups (2x yearly)
- Daily flossing
- Proper brushing technique
- Healthier lifestyle choices

We wish you and your family a wonderful holiday season filled with health, happiness, and joy!

**Warmest regards,**

**Thnaya Clinic Team** 🏥

---
*This offer is valid through December 31st. Terms and conditions apply.*
@endcomponent
