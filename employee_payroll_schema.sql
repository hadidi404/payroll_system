
-- Drop tables if they already exist (optional but recommended for clean setup)
DROP TABLE IF EXISTS payroll_info;
DROP TABLE IF EXISTS employee_info;

-- Create employee_info table
CREATE TABLE employee_info (
    id INT(11) NOT NULL PRIMARY KEY,
    name TEXT NOT NULL,
    position TEXT NOT NULL,
    status TEXT NOT NULL,
    board_lodging TEXT NOT NULL,
    lodging_address VARCHAR(255) DEFAULT NULL,
    food_allowance TEXT NOT NULL
);

-- Create payroll_info table with foreign key to employee_info
CREATE TABLE payroll_info (
    payroll_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    employee_id INT(11) NOT NULL,
    days_worked INT(11) DEFAULT 0,
    hours_worked DECIMAL(5,2) DEFAULT 0.00,
    overtime_hours DECIMAL(5,2) DEFAULT 0.00,
    daily_wage_rate DECIMAL(10,2) DEFAULT NULL,
    overtime_rate DECIMAL(10,2) DEFAULT NULL,
    gross_pay DECIMAL(10,2) DEFAULT NULL,
    sss DECIMAL(10,2) DEFAULT 0.00,
    philhealth DECIMAL(10,2) DEFAULT 0.00,
    pagibig DECIMAL(10,2) DEFAULT 0.00,
    tax DECIMAL(10,2) DEFAULT 0.00,
    net_pay DECIMAL(10,2) DEFAULT NULL,
    others DECIMAL(10,2) DEFAULT NULL,
    taxable_income DECIMAL(10,2) DEFAULT 0.00,
    deductions TEXT DEFAULT NULL,
    FOREIGN KEY (employee_id) REFERENCES employee_info(id) ON DELETE CASCADE
);
