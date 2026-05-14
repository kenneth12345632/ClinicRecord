# Barangay Banilad Health Center System User Guide

This guide explains how staff can use the Barangay Banilad Health Center web system for patient records, consultations, medicine inventory, medicine release, reports, and user administration.

## Who Should Use This Guide

- Barangay Health Workers (BHW)
- Nurses
- Doctors
- System Administrators

## What the System Is For

The system helps the health center move daily clinic work into one web-based platform. It is used to:

- Record patient information and vital signs
- Track patient consultations
- Review pending patients
- Save nurse assessments
- Save doctor diagnosis and follow-up recommendations
- Prescribe medicines
- Confirm medicine release
- Monitor medicine stock, batches, and expiration dates
- Generate patient, diagnosis, consultation, and medicine usage reports
- Manage system users and activity logs

## Basic Requirements

Before using the system, make sure you have:

- A device with a web browser
- A stable internet connection
- An active account created by the administrator
- Your assigned email address and password

Recommended browsers:

- Google Chrome
- Microsoft Edge
- Mozilla Firefox
- Safari

## Logging In and Out

### Log In

1. Open the system website.
2. Enter your email address.
3. Enter your password.
4. Click **Sign In**.
5. Wait for your dashboard to open.

Your dashboard and menu options depend on your assigned role.

### Log Out

1. Go to the user menu at the bottom of the sidebar.
2. Click **Log Out**.
3. Wait until the login page appears.

Always log out when using a shared computer.

## Account and Profile

All users can update their own profile.

### Update Profile

1. Open the user menu at the bottom of the sidebar.
2. Click **View Profile**.
3. Update your name, email address, or profile photo.
4. Save the changes.
5. Check for the success message.

If you cannot log in or your account is inactive, contact the system administrator.

## Main Menus

The sidebar shows different options depending on your role.

| Menu | Purpose |
| --- | --- |
| Dashboard | Shows system summaries and important clinic information. |
| Patient Records | Shows saved patient records and consultation history. |
| Add New Consultation | Used by BHW users to encode a new patient intake or consultation. |
| Pending Patient | Used by nurses and doctors to review patients waiting for assessment. |
| Medicine queue | Shows prescribed medicines waiting for BHW release confirmation. |
| Medicine Inventory | Shows medicine stocks, batches, dosage details, arrival dates, and expiration dates. |
| Reports | Shows report summaries and export options. |
| User Management | Allows administrators to create and update user accounts. |
| Activity Logs | Allows administrators to review recorded system actions. |
| Inventory Ledger | Allows administrators to review stock movement history. |

## Role Guide: Barangay Health Worker (BHW)

BHW users mainly handle patient intake, patient record updates, medicine release, medicine inventory viewing, and BHW reports.

### BHW Dashboard

Use the dashboard to see:

- Total patient records
- Today's consultations
- Low-stock medicine count
- Recent patient records
- Weekly patient record trend

### Add a New Patient Consultation

1. Click **Add New Consultation**.
2. Enter the patient's name and personal details.
3. Enter birthday, gender, civil status, contact number, and address or purok.
4. Enter the consultation date.
5. Add vital signs if available:
   - Temperature
   - Blood pressure
   - Pulse rate
   - Respiratory rate
   - Weight
   - Height
   - BMI
6. Review the details.
7. Save the record.

After saving, the record enters the clinic workflow for nurse or doctor assessment.

### View Patient Records

1. Click **Patient Records**.
2. Use the search field to find a patient.
3. Open the patient record.
4. Review patient details, vital signs, consultation history, diagnosis, medicines, and laboratory files.

### Edit a Patient Record

1. Open the patient record.
2. Click the edit option if available.
3. Update the allowed patient details or vital signs.
4. Save the changes.

BHW users should not encode doctor diagnosis or prescriptions in the intake record.

### Release Prescribed Medicines

When a doctor prescribes medicines, the record appears in **Medicine queue**.

1. Click **Medicine queue**.
2. Open the patient visit.
3. Review the prescribed medicines and quantities.
4. Enter the released quantity if only part of the prescription can be released.
5. Confirm the release.
6. Read the system message.

When release is complete, the visit is published to the patient record registry.

### Check Medicine Inventory

1. Open **Reports**.
2. Click **Medicine Inventory**.
3. Review medicine names, stock, batch details, arrival dates, and expiration dates.
4. Watch for low-stock or expiring medicines.

### View BHW Reports

1. Open **Reports**.
2. Choose **Diagnosis Reports** or **Patient Records Report**.
3. Review the displayed information.
4. Export the report if needed.

## Role Guide: Nurse

Nurse users review patients from BHW intake, add assessment notes, and forward patients for doctor review.

### Nurse Dashboard

Use the dashboard to see:

- Total patient records
- Today's consultations
- Low-stock medicine count
- Recent patient records
- Weekly patient record trend

### Review Pending Patients

1. Click **Pending Patient**.
2. Review the list of patients waiting for nurse assessment.
3. Search for a patient if needed.
4. Open the patient record.

### Add Nurse Assessment

1. Open the pending patient.
2. Start a new consultation from the selected patient record.
3. Enter the consultation date.
4. Enter subjective notes.
5. Enter objective notes.
6. Upload laboratory images if needed.
7. Save the consultation.

After saving, the patient is marked for doctor assessment.

### View Patient History

1. Click **Patient Records**.
2. Search for the patient.
3. Open the patient record.
4. Review previous visits, vital signs, nurse notes, doctor diagnosis, medicines, and files.

### View Medicine Inventory

1. Click **Medicine Inventory**.
2. Review available medicines, stock levels, and expiration dates.

Nurse users can use this information when coordinating patient care and medicine availability.

## Role Guide: Doctor

Doctor users review pending patients, save diagnosis and follow-up recommendations, prescribe medicines, upload laboratory files, and monitor recovery.

### Doctor Dashboard

Use the dashboard to see:

- Total patient records
- Today's consultations
- Low-stock medicine count
- Recent patient records
- Weekly patient record trend
- Doctor availability status

### Set Availability

1. Open the doctor dashboard.
2. Find the availability control.
3. Toggle availability to active or inactive.
4. Confirm that the success message appears.

### Review Pending Patients

1. Click **Pending Patient**.
2. Review the patients waiting for doctor assessment.
3. Open a patient record.
4. Check BHW intake details, vital signs, nurse notes, and attached laboratory files.

### Save Doctor Consultation

1. Open the selected pending patient.
2. Start a consultation from the patient record.
3. Enter the consultation date.
4. Enter the diagnosis.
5. Enter follow-up recommendations.
6. Upload laboratory images if needed.
7. Save the record.

### Prescribe Medicines

1. In the consultation form, go to the medicines section.
2. Select the medicine.
3. Enter the quantity.
4. Add more medicine lines if needed.
5. Save the consultation.

Important: Saving the doctor consultation does not immediately reduce medicine stock. The prescription is sent to the **Medicine queue** and stock is deducted only after the BHW confirms release.

### Review Patient History

1. Click **Patient Records**.
2. Search for the patient.
3. Open the patient record.
4. Review consultation history, diagnosis, previous medicines, laboratory files, and consultation team details.

### Monitor Recovery

Recovery monitoring helps identify patients who are improving, recovered, still being monitored, or showing no improvement.

Common recovery labels:

| Label | Meaning |
| --- | --- |
| Recovered | The patient improved and the condition is considered resolved. |
| Improving | The patient is getting better but still needs monitoring. |
| Monitoring | The patient still needs follow-up. |
| No Improvement | The patient has repeated visits or symptoms without clear progress. |
| Worsened | The patient's condition became worse and may need urgent follow-up. |

## Role Guide: System Administrator

Administrators manage system access, users, reports, logs, and inventory oversight.

### Admin Dashboard

The admin dashboard shows:

- Total patient records
- Today's patient records
- Total consultations
- Pending consultations
- Low-stock medicines
- Recent activity logs
- Weekly patient record trend
- Recovery analytics

### Create a User Account

1. Click **User Management**.
2. Click **Create User** or **Add User**.
3. Enter the user's name and email address.
4. Choose the correct role:
   - Admin
   - BHW
   - Nurse
   - Doctor
5. Set the initial password.
6. Set the user as active if the account should be usable.
7. Save the account.

### Edit a User Account

1. Click **User Management**.
2. Search for the user.
3. Click **Edit**.
4. Update the user's details, role, status, or profile photo.
5. Save the changes.

The system prevents an administrator from accidentally removing their own admin role.

### Activate or Deactivate a User

1. Open **User Management**.
2. Find the user.
3. Use the status action to activate or deactivate the account.
4. Confirm the action.

Inactive users cannot log in.

### Reset a Password

1. Open **User Management**.
2. Open the user's account.
3. Click **Reset Password**.
4. Enter and confirm the new password.
5. Save the new password.

### View Reports

1. Click **Reports**.
2. Review consultation and medicine usage reports.
3. Export the report file if needed.

### View Activity Logs

1. Click **Activity Logs**.
2. Review recorded actions such as logins, logouts, profile updates, user changes, patient record actions, consultation actions, and medicine actions.

Activity logs help administrators monitor system use and investigate issues.

### View Inventory Ledger

1. Click **Inventory Ledger**.
2. Review stock-in and stock-out entries.
3. Check the medicine, user, quantity, balance after transaction, reference, and date.
4. Check the low-stock medicine list.

## Medicine Inventory Guide

The inventory module tracks medicines by batch or lot.

Medicine details may include:

- Medicine name
- Type
- Dosage value and unit
- Batch number
- Stock
- Arrival date
- Expiration date

### Add Medicine or Stock

1. Open **Medicine Inventory**.
2. Click the add medicine or add stock option.
3. Enter the required medicine information.
4. Enter stock quantity.
5. Enter arrival and expiration dates.
6. Save the record.

### Edit Medicine Details

1. Open **Medicine Inventory**.
2. Select the medicine or batch.
3. Click **Edit** if available.
4. Update the details.
5. Save the changes.

### Review Expiring or Low-Stock Medicines

1. Open **Medicine Inventory** or **Inventory Ledger**.
2. Check low-stock warnings.
3. Check medicines close to expiration.
4. Coordinate restocking or proper handling based on health center policy.

## Patient Consultation Workflow

The system follows this common flow:

1. BHW creates the patient intake or consultation record.
2. Nurse reviews pending patients and adds assessment notes when needed.
3. Doctor reviews the patient and saves diagnosis and follow-up recommendation.
4. Doctor prescribes medicine if needed.
5. BHW confirms medicine release in the medicine queue.
6. The consultation becomes part of the patient record registry and history.

## Important System Statuses

| Status or Message | What It Means |
| --- | --- |
| Waiting for doctor/nurse | The patient has been encoded but still needs clinical assessment. |
| For doctor assessment | Nurse assessment was saved and the record is ready for doctor review. |
| Awaiting BHW dispensing | The doctor prescribed medicine and BHW release is required. |
| Published to registry | The consultation is visible in patient records. |
| Account inactive | The user account is disabled and cannot log in. |
| Insufficient stock | The selected medicine does not have enough available stock. |

## Good Data Entry Practices

- Use the patient's correct name and birthday to avoid duplicate records.
- Enter the consultation date correctly.
- Review vital signs before saving.
- Use clear nurse assessment notes.
- Use clear doctor diagnosis and follow-up recommendations.
- Select medicines carefully and check available stock.
- Upload only relevant laboratory images.
- Avoid deleting or changing medicine batches unless necessary.

## Troubleshooting

| Issue | What to Check | What to Do |
| --- | --- | --- |
| You cannot log in | Email, password, account status | Re-enter credentials or contact the administrator. |
| You see an inactive account message | Your account has been disabled | Contact the administrator. |
| You cannot open a menu | Your role may not have access | Use the correct account or ask the administrator. |
| A patient record will not save | Required fields may be missing | Review highlighted fields and complete them. |
| A medicine cannot be selected | It may be out of stock or expired | Choose another medicine or update inventory. |
| Medicine release fails | Stock may be insufficient | Check inventory and release only available quantity. |
| Laboratory upload fails | File type or size may be invalid | Use JPG, JPEG, PNG, or WEBP files within the allowed size. |
| Export does not download | Browser or connection issue | Allow downloads and try again. |
| Data looks incorrect | Record was encoded incorrectly | Report it to authorized staff or the administrator. |

## User Reminders

- Keep your password private.
- Use only your own account.
- Log out after every session.
- Do not change records without proper reason.
- Check patient information before saving.
- Follow health center policy for medicine release and inventory changes.
- Report system access issues to the administrator.

## Quick Glossary

| Term | Meaning |
| --- | --- |
| BHW | Barangay Health Worker. |
| Patient Record | The saved patient profile and consultation history. |
| Consultation | A recorded patient visit. |
| Pending Patient | A patient waiting for nurse or doctor assessment. |
| Vital Signs | Health measurements such as temperature, blood pressure, pulse rate, respiratory rate, weight, height, and BMI. |
| Diagnosis | Doctor's medical finding. |
| Follow-up Recommendation | Doctor's instruction for monitoring or next steps. |
| Medicine Queue | List of prescriptions waiting for BHW release confirmation. |
| Inventory Ledger | History of medicine stock movement. |
| Activity Logs | Records of user actions in the system. |

## Summary

The Barangay Banilad Health Center system supports the daily work of the clinic by organizing patient records, consultations, medicine inventory, medicine release, reports, and user management. Each role has its own responsibilities, and the system is most effective when users enter accurate information, follow the correct workflow, and coordinate with other health center staff.
