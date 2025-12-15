@component('mail::message')
# 📅 Appointment Reminder

Dear {{ $patient->first_name }},

This is a reminder that you have an upcoming appointment at **Thnaya Clinic**.

@component('mail::panel')
**Appointment Details:**

📅 **Date:** {{ $appointmentDate }}
🕐 **Time:** {{ $appointmentTime }}
🏥 **Location:** Thnaya Clinic
@endcomponent

**Please Note:**
- Please arrive **10 minutes early** to allow time for check-in
- Bring your **insurance card** and **ID**
- If you need to reschedule, please let us know as soon as possible
- For emergency inquiries, call us immediately

**How to Prepare:**
1. Eat a light meal before your appointment (unless instructed otherwise)
2. Wear comfortable clothing
3. Bring any relevant medical documents
4. Have your insurance information ready

**Need to Cancel or Reschedule?**

If you cannot make this appointment, please contact us immediately:
- 📞 Phone: +20 123 456 7890
- 📧 Email: appointments@thnaya-clinic.com

@component('mail::button', ['url' => route('appointments.show', $appointment)])
View Appointment Details
@endcomponent

We look forward to seeing you!

Best regards,

**Thnaya Clinic Team** 🏥

---
*If you did not request this appointment reminder, please contact us immediately.*
@endcomponent
