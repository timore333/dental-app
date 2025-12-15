@component('mail::message')
# ✅ Appointment Confirmed

Dear {{ $patient->first_name }},

Your appointment at **Thnaya Clinic** has been successfully scheduled!

@component('mail::panel')
**Appointment Confirmation:**

📅 **Date:** {{ $appointmentDate }}
🕐 **Time:** {{ $appointmentTime }}
🏥 **Location:** Thnaya Clinic
✅ **Status:** Confirmed

**Your Confirmation Number:**
`{{ $appointment->id }}`
@endcomponent

**What's Next?**

1. **Calendar Reminder:** Add this appointment to your calendar
2. **Arrive Early:** Please come 10 minutes before your appointment
3. **Bring Documents:** Have your insurance card and ID ready
4. **Questions?** Contact us before your appointment

**Appointment Guidelines:**

- ✓ Arrive 10 minutes early
- ✓ Bring insurance card and ID
- ✓ List any current medications
- ✓ Bring relevant medical documents
- ✓ Be prepared to discuss your medical history

**Change Your Appointment?**

If you need to reschedule or cancel:
- 📞 Phone: +20 123 456 7890
- 📧 Email: appointments@thnaya-clinic.com
- 🌐 Online: Visit your patient portal

@component('mail::button', ['url' => route('appointments.show', $appointment)])
View Full Details
@endcomponent

**Additional Information:**

- You will receive a reminder 24 hours before your appointment
- Allow approximately 30-45 minutes for your visit
- Parking is available at our clinic
- We are wheelchair accessible

We look forward to seeing you on **{{ $appointmentDate }}**!

Best regards,

**Thnaya Clinic Team** 🏥

---
*Please keep this email for your records.*
@endcomponent
