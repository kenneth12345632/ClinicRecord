# System Demonstration Script

Use this script when presenting the clinic records and medicine inventory system. It is based on the current Laravel routes, controllers, and seeded administrator account in this repository.

## Demo goal

Show how the system supports a clinic visit from account administration, patient intake, consultation, medicine dispensing, inventory tracking, reporting, and audit review.

## Recommended demo setup

1. Start the application with the repository's normal Laravel workflow.
2. Run migrations and seeders before the demo.
3. Log in with the seeded administrator account:
   - Email: `admin@clinic.local`
   - Password: `admin12345`
4. From the admin user management page, create demo users for each role:
   - BHW: `bhw@clinic.local`
   - Doctor: `doctor@clinic.local`
   - Nurse: `nurse@clinic.local`
5. Add at least one medicine batch with available stock, for example:
   - Name: `Biogesic (Paracetamol) 500mg Tablet`
   - Batch: `BATCH-DEMO-001`
   - Stock: `50`
   - Arrival date: today
   - Expiration date: a future date

If the demo environment already has realistic users, medicines, and patient records, use those instead of creating new sample data.

## Demo script

### 1. Opening

Speaker:

> Good day. Today I will demonstrate our clinic records and medicine inventory system. The system is designed for multiple clinic roles: administrator, BHW, doctor, and nurse. Each role sees only the workflow that belongs to them, while patient records, medicines, reports, and activity logs remain connected in one system.

Show:

- Login page.
- Mention that public registration is disabled, so accounts are controlled by the administrator.

### 2. Administrator dashboard and user management

Log in as `admin@clinic.local`.

Speaker:

> I am now logged in as the administrator. The admin dashboard gives an overview of patient records, today's consultations, pending consultations, low-stock medicines, weekly patient activity, recovery analytics, and recent system activity.

Show:

- Admin dashboard cards and charts.
- Recent activity section.

Then open admin user management.

Speaker:

> The administrator manages staff accounts from the user management area. Roles can be assigned as admin, BHW, nurse, or doctor. The administrator can also activate or deactivate accounts and reset passwords when needed.

Show:

- Users list.
- Create user form or edit user form.
- Role selection.
- Active status toggle or reset password action.

### 3. Medicine inventory setup

Open the medicine inventory page.

Speaker:

> The medicine inventory stores medicines by batch. Each batch tracks stock, arrival date, expiration date, dosage information, and type. This helps the clinic monitor available medicines and identify low-stock or expiring items.

Show:

- Medicine list.
- Add medicine or add stock flow.
- Expiring soon or low-stock area if data is available.
- Batch details page if available.

Add or confirm the sample medicine batch.

Speaker:

> When a medicine batch is added, the system records a stock-in transaction. Later, when medicine is dispensed for a consultation, the system will create stock-out entries so inventory history stays traceable.

### 4. BHW patient intake

Log out and log in as the BHW demo user.

Speaker:

> I am now logged in as a BHW. The BHW is responsible for creating patient intake records and capturing the initial patient information before clinical assessment.

Show:

- BHW dashboard.
- New Entry button.
- Patient record create form.

Create a demo patient record.

Suggested patient data:

- First name: `Juan`
- Middle name: `Santos`
- Last name: `Dela Cruz`
- Birthday: any valid adult birth date
- Gender: `Male`
- Civil status: `Single`
- Address/Purok: `Purok 1`
- Subjective: `Fever and headache since yesterday`
- Objective: `Patient is alert; temperature is elevated`
- Vitals: enter available temperature, blood pressure, pulse rate, respiratory rate, weight, and height

Speaker:

> After saving the intake, the patient becomes available for clinical review. At this stage, the record is waiting for a doctor or nurse assessment, so it is not yet treated as a completed registry entry.

Show:

- Confirmation message.
- Clinic records or dashboard recent records.

### 5. Doctor consultation and prescription

Log out and log in as the doctor demo user.

Speaker:

> I am now logged in as the doctor. The doctor dashboard shows patient and consultation metrics, recent records, low-stock count, and the doctor's availability status.

Show:

- Doctor dashboard.
- Doctor availability toggle if visible.
- Pending patient records.

Open the pending patient record created by the BHW and add a consultation.

Speaker:

> The doctor reviews the patient information captured during intake, enters the diagnosis, adds a follow-up recommendation, and may prescribe medicine from available stock.

Suggested consultation data:

- Diagnosis: `Acute febrile illness`
- Follow-up recommendation: `Return for follow-up if fever persists for 2 days`
- Medicine: select `Biogesic (Paracetamol) 500mg Tablet`
- Quantity: `3`

Speaker:

> Saving the consultation does not immediately reduce medicine stock. Instead, prescribed medicine is placed in a dispensing queue so the BHW can confirm the actual release to the patient.

Show:

- Saved confirmation.
- Patient record or consultation history if available.

### 6. BHW medicine dispensing

Log out and log back in as the BHW demo user.

Open the dispensing queue.

Speaker:

> The BHW now sees the consultation in the medicine dispensing queue. This queue contains doctor-completed consultations with medicines that still need to be released.

Show:

- Dispensing list.
- Patient dispensing detail page.
- Pending medicine lines.

Confirm the release of the prescribed medicine.

Speaker:

> When the BHW confirms dispensing, the system deducts stock using available non-expired batches and records inventory stock-out entries. After all pending medicine lines are cleared, the visit becomes visible in the clinic records registry.

Show:

- Success message.
- Clinic Records list with the patient now visible.

### 7. Nurse workflow, if included in the demo

Log in as the nurse demo user only if you want to demonstrate nurse-specific access.

Speaker:

> The nurse role can review pending patient records and record nursing observations. Nurse entries are marked for doctor assessment, while final diagnosis and prescription remain part of the doctor workflow.

Show:

- Nurse dashboard.
- Pending patient records.
- Record creation or review screen.

Keep this section short if the main demo is focused on BHW and doctor flow.

### 8. Reports and exports

Log back in as administrator.

Open admin reports.

Speaker:

> The administrator can review clinic activity through reports. Consultation reports summarize patient volume, while medicine usage reports summarize stock-out activity from dispensing.

Show:

- Admin reports page.
- Consultation report.
- Medicine usage report.
- Export buttons if available.

Then open the inventory ledger.

Speaker:

> The inventory ledger provides traceability for stock movement. We can see stock-in entries when batches are added and stock-out entries when medicines are dispensed to patients.

Show:

- Inventory ledger.
- Low-stock list if available.
- Stock-out reference to the consultation.

### 9. Activity logs and accountability

Open admin activity logs.

Speaker:

> The system also records activity logs for important actions such as login, profile updates, user management, medicine creation, consultation saving, and medicine dispensing. This gives administrators a basic audit trail of system activity.

Show:

- Activity logs page.
- Recent actions from the demo.

### 10. Closing

Speaker:

> To summarize, the system supports the clinic workflow from patient intake to consultation, medicine dispensing, reporting, and audit monitoring. Role-based access keeps each user focused on their responsibilities, while shared records and inventory logs keep the clinic data connected and traceable.

## Quick demo checklist

- [ ] Admin can log in.
- [ ] Demo BHW, doctor, and nurse accounts exist if those roles will be shown.
- [ ] At least one medicine batch has non-expired stock.
- [ ] BHW can create a patient intake record.
- [ ] Doctor can complete a consultation and prescribe medicine.
- [ ] BHW can confirm medicine dispensing.
- [ ] Patient appears in clinic records after dispensing is cleared.
- [ ] Admin reports, inventory ledger, and activity logs show demo activity.

## If something goes wrong during the demo

- If login fails, confirm the account is active and the password was set by the administrator.
- If the doctor cannot prescribe a medicine, confirm the medicine has stock and is not expired.
- If a record does not appear in clinic records, check the dispensing queue first. Doctor-completed visits with pending medicines must be cleared by BHW before they appear in the registry.
- If reports look empty, create at least one consultation and dispense at least one medicine before opening reports.
