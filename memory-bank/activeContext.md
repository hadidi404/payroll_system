# Active Context

## Current Work Focus
Implementing new features for employee data management, specifically adding an hourly rate input and automating non-taxable deduction calculations.

## Recent Changes
- **Fixed Delete Functionality:** Modified `php/delete.php` to correctly handle multiple employee IDs passed via the `ids` GET parameter from `php/dashboard.php`. This resolved the "No employee ID specified" error.
- **Updated Add Employee Form (`php/add.php`):**
    - Added an "Hour Rate" input field.
    - Removed manual input fields for SSS, Pag-IBIG, and PhilHealth deductions.
    - Implemented PHP logic to automatically calculate SSS (5%), Pag-IBIG (2%), and PhilHealth (2.5%) based on `hour_rate * 160` (monthly salary assumption).
    - Updated the `INSERT` query and `bind_param` to accommodate the new `hour_rate` and calculated deduction fields.

## Next Steps
- **Database Schema Update:** The `employee_info` table in the database needs to be updated to include the new columns:
    - `hour_rate` (e.g., `DECIMAL(10,2)`)
    - `sss_deduction` (e.g., `DECIMAL(10,2)`)
    - `pagibig_deduction` (e.g., `DECIMAL(10,2)`)
    - `philhealth_deduction` (e.g., `DECIMAL(10,2)`)
- **Display Deductions:** Update `php/dashboard.php` or `php/payslip.php` to display the newly calculated deductions.
- **Edit Functionality:** Update `php/edit.php` to handle the new `hour_rate` and calculated deductions.
