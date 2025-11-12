# URL Routing Guide - Insurance ERP v2.0

## Complete Menu to Controller Mapping

This document maps every menu item in the navigation to its corresponding PHP controller and method.

---

## 🔵 DASHBOARD Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| Overview | `/dashboard` | Dashboard.php | index() | ✅ Ready |
| Company Dashboard | `/dashboard/company` | Dashboard.php | company() | ✅ Ready |
| Branch Dashboard | `/dashboard/branch` | Dashboard.php | branch() | ✅ Ready |
| Notifications | `/notifications` | Notifications.php | index() | ✅ Ready |

---

## 🟣 MASTERS Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| Customer Management | `/customers` | Customers.php | index() | ✅ Ready |
| Agent Management | `/agents` | Agents.php | index() | ✅ Ready |
| Broker Management | `/brokers` | Brokers.php | index() | ✅ Ready |
| Supplier Management | `/suppliers` | Suppliers.php | index() | ✅ Ready |
| Staff Management | `/hr/employees` | Hr.php | employees() | ✅ Ready |
| Product Management | `/products` | Products.php | index() | ✅ Ready |
| Account Masters | `/accounts` | Accounts.php | index() | ✅ Ready |

---

## 🟢 INSURANCE Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| Policy Management | `/insurance/policies` | Insurance.php | policies() | ✅ Ready |
| Claims Management | `/insurance/claims` | Insurance.php | claims() | ✅ Ready |
| Underwriting | `/insurance/underwriting` | Insurance.php | underwriting() | ⚠️ Needs view |
| Premium Collection | `/insurance/premium` | Insurance.php | premium() | ✅ Ready |
| Commission Processing | `/insurance/commission` | Insurance.php | commission() | ✅ Ready |
| Renewals | `/insurance/renewals` | Insurance.php | renewals() | ✅ Ready |
| Endorsements | `/insurance/endorsements` | Insurance.php | endorsements() | ✅ Ready |

---

## 🟡 ACCOUNTING Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| Chart of Accounts | `/accounts` | Accounts.php | index() | ✅ Ready |
| Journal Entries | `/accounting/journal` | Accounting.php | journal() | ✅ Ready |
| General Ledger | `/accounting/ledger` | Accounting.php | ledger() | ✅ Ready |
| Daybook | `/accounting/daybook` | Accounting.php | daybook() | ✅ Ready |
| Receipts | `/receipts` | Receipts.php | index() | ✅ Ready |
| Payments | `/payments` | Payments.php | index() | ✅ Ready |
| Bank Reconciliation | `/accounting/bank-reconciliation` | Accounting.php | bank_reconciliation() | ✅ Ready |
| Trial Balance | `/reports/trial-balance` | Reports.php | trial_balance() | ✅ Ready |
| Balance Sheet | `/reports/balance-sheet` | Reports.php | balance_sheet() | ✅ Ready |
| Credit Note | `/accounting/credit-note` | Accounting.php | credit_note() | ✅ Ready |
| Debit Note | `/accounting/debit-note` | Accounting.php | debit_note() | ✅ Ready |

---

## 🔷 TRANSACTIONS Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| Sales Invoices | `/sales` | Sales.php | index() | ✅ Ready |
| Quotations | `/quotations` | Quotations.php | index() | ✅ Ready |
| Purchase Bills | `/purchases` | Purchases.php | index() | ✅ Ready |
| Receipts | `/receipts` | Receipts.php | index() | ✅ Ready |
| Payments | `/payments` | Payments.php | index() | ✅ Ready |
| Journal Entries | `/accounting/journal` | Accounting.php | journal() | ✅ Ready |

---

## 🌹 HR & PAYROLL Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| Employee Management | `/hr/employees` | Hr.php | employees() | ✅ Ready |
| Attendance | `/hr/attendance` | Hr.php | attendance() | ✅ Ready |
| Leave Management | `/hr/leave` | Hr.php | leave() | ✅ Ready |
| Payroll Processing | `/hr/payroll` | Hr.php | payroll() | ✅ Ready |
| Salary Structures | `/hr/salary` | Hr.php | salary() | ✅ Ready |
| Performance | `/hr/performance` | Hr.php | performance() | ✅ Ready |

---

## 💜 REPORTS Menu

### Financial Reports

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Balance Sheet | `/reports/balance-sheet` | Reports.php | balance_sheet() | ✅ Ready |
| Profit & Loss | `/reports/profit-loss` | Reports.php | profit_loss() | ✅ Ready |
| Cash Flow Statement | `/reports/cash-flow` | Reports.php | cash_flow() | ✅ Ready |
| Trial Balance | `/reports/trial-balance` | Reports.php | trial_balance() | ✅ Ready |
| Accounts Receivable | `/reports/receivable` | Reports.php | receivable() | ✅ Ready |
| Accounts Payable | `/reports/payable` | Reports.php | payable() | ✅ Ready |

### Books of Accounts

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Sales Book | `/reports/sales-book` | Reports.php | sales_book() | ✅ Ready |
| Purchase Book | `/reports/purchase-book` | Reports.php | purchase_book() | ✅ Ready |
| Cash Book | `/reports/cash-book` | Reports.php | cash_book() | ✅ Ready |
| Bank Book | `/reports/bank-book` | Reports.php | bank_book() | ✅ Ready |
| Day Book | `/reports/day-book` | Reports.php | day_book() | ✅ Ready |
| Quotation Book | `/reports/quotation-book` | Reports.php | quotation_book() | ✅ Ready |

### Sales Reports

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Sales Summary | `/reports/sales-summary` | Reports.php | sales_summary() | ✅ Ready |
| Customer Wise Sales | `/reports/customer-sales` | Reports.php | customer_sales() | ✅ Ready |
| Product Wise Sales | `/reports/product-sales` | Reports.php | product_sales() | ✅ Ready |
| Salesman Wise Sales | `/reports/salesman-sales` | Reports.php | salesman_sales() | ✅ Ready |
| Credit Sales Report | `/reports/credit-sales` | Reports.php | credit_sales() | ✅ Ready |

### Purchase Reports

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Purchase Summary | `/reports/purchase-summary` | Reports.php | purchase_summary() | ✅ Ready |
| Supplier Wise Purchase | `/reports/supplier-purchase` | Reports.php | supplier_purchase() | ✅ Ready |
| Product Wise Purchase | `/reports/product-purchase` | Reports.php | product_purchase() | ✅ Ready |

### Insurance Reports

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Policy Register | `/reports/policy-register` | Reports.php | policy_register() | ✅ Ready |
| Claims Register | `/reports/claims-register` | Reports.php | claims_register() | ✅ Ready |
| Premium Analysis | `/reports/premium-analysis` | Reports.php | premium_analysis() | ✅ Ready |
| Commission Report | `/reports/commission` | Reports.php | commission() | ✅ Ready |
| Loss Ratio Analysis | `/reports/loss-ratio` | Reports.php | loss_ratio() | ✅ Ready |
| Renewal Tracker | `/reports/renewals` | Reports.php | renewals() | ✅ Ready |

### Customer Reports

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Customer List | `/reports/customer-list` | Reports.php | customer_list() | ✅ Ready |
| Customer Ledger | `/reports/customer-ledger` | Reports.php | customer_ledger() | ✅ Ready |
| Customer Ageing | `/reports/customer-ageing` | Reports.php | customer_ageing() | ✅ Ready |
| Outstanding Report | `/reports/outstanding` | Reports.php | outstanding() | ✅ Ready |

### HR Reports

| Report Name | URL | Controller | Method | Status |
|-------------|-----|------------|--------|--------|
| Attendance Report | `/reports/attendance` | Reports.php | attendance_report() | ✅ Ready |
| Leave Report | `/reports/leave` | Reports.php | leave_report() | ✅ Ready |
| Payroll Report | `/reports/payroll` | Reports.php | payroll_report() | ✅ Ready |
| Salary Register | `/reports/salary-register` | Reports.php | salary_register() | ✅ Ready |

---

## ⚙️ SETTINGS Menu

| Menu Item | URL | Controller | Method | Status |
|-----------|-----|------------|--------|--------|
| System Settings | `/settings` | Settings.php | index() | ✅ Ready |
| Company Settings | `/settings/company` | Settings.php | company() | ✅ Ready |
| User Preferences | `/settings/preferences` | Settings.php | preferences() | ✅ Ready |
| Backup & Restore | `/settings/backup` | Settings.php | backup() | ✅ Ready |

---

## 📋 Controller Files Summary

| Controller | Location | Purpose | Methods Count |
|------------|----------|---------|---------------|
| Dashboard.php | `controllers/` | Main dashboard views | 3 |
| Notifications.php | `controllers/` | Notification management | 6 |
| Customers.php | `controllers/` | Customer CRUD | 5+ |
| Agents.php | `controllers/` | Agent CRUD | 5+ |
| Brokers.php | `controllers/` | Broker CRUD | 5+ |
| Suppliers.php | `controllers/` | Supplier CRUD | 5+ |
| Products.php | `controllers/` | Product CRUD | 5+ |
| Accounts.php | `controllers/` | Chart of Accounts | 5+ |
| Insurance.php | `controllers/` | Insurance module | 7 |
| Accounting.php | `controllers/` | Accounting operations | 10 |
| Sales.php | `controllers/` | Sales/Invoice management | 5+ |
| Purchases.php | `controllers/` | Purchase management | 5+ |
| Quotations.php | `controllers/` | Quotation management | 5+ |
| Receipts.php | `controllers/` | Receipt management | 5+ |
| Payments.php | `controllers/` | Payment management | 5+ |
| Hr.php | `controllers/` | HR & Payroll module | 11 |
| Reports.php | `controllers/` | All reports | 30+ |
| Settings.php | `controllers/` | System settings | 4 |

---

## 🔗 URL Pattern Rules

### CodeIgniter URL Structure
```
http://your-domain.com/[controller]/[method]/[parameters]
```

### Examples:
```
/dashboard              → Dashboard::index()
/dashboard/company      → Dashboard::company()
/customers              → Customers::index()
/customers/add          → Customers::add()
/customers/view/123     → Customers::view(123)
/insurance/policies     → Insurance::policies()
/reports/balance-sheet  → Reports::balance_sheet()
```

### URL with Hyphens
CodeIgniter automatically converts:
- `/reports/balance-sheet` → `Reports::balance_sheet()`
- `/accounting/bank-reconciliation` → `Accounting::bank_reconciliation()`
- `/reports/customer-sales` → `Reports::customer_sales()`

---

## 🎯 How to Add New Menu Items

### 1. Add to Navigation (modern_layout.php)
```html
<a href="<?php echo base_url('your-url'); ?>" class="...">
    <i class="fas fa-icon"></i>
    <span>Menu Label</span>
</a>
```

### 2. Create Controller Method
```php
public function your_method() {
    $data['page_title'] = 'Your Page Title';
    $data['breadcrumbs'] = [
        ['title' => 'Dashboard', 'url' => base_url('dashboard')],
        ['title' => 'Your Page']
    ];
    $data['main_content'] = 'your_folder/your_view';
    $this->load->view('templates/modern_layout', $data);
}
```

### 3. Create View File
```
application/views/your_folder/your_view.php
```

---

## ✅ Status Legend

- **✅ Ready** - Controller method exists and functional
- **⚠️ Needs view** - Controller exists but view file needs creation
- **❌ Missing** - Controller method doesn't exist yet

---

## 📝 Notes

1. **All controllers require authentication** - Users must be logged in
2. **All views use modern_layout.php** - Consistent design across all pages
3. **Breadcrumbs are automatic** - Set in each controller method
4. **Base URL configuration** - Update in `application/config/config.php`

---

## 🚀 Next Steps

To complete the system, create view files for methods marked as "⚠️ Needs view":

1. Insurance underwriting view
2. Placeholder views for reports (can copy existing report structure)
3. Settings configuration forms
4. HR module forms

All controller logic is ready - only UI templates need to be created!

---

**Last Updated:** November 12, 2025
**Version:** 2.0.0
