# BixCash Commission System Documentation

**Version:** 1.0.0
**Last Updated:** November 15, 2025
**Status:** Production Ready

---

## Table of Contents

1. [Overview](#overview)
2. [Business Model](#business-model)
3. [System Architecture](#system-architecture)
4. [Database Schema](#database-schema)
5. [Features](#features)
6. [User Workflows](#user-workflows)
7. [API Reference](#api-reference)
8. [Automated Tasks](#automated-tasks)
9. [Notifications](#notifications)
10. [Exports](#exports)
11. [Configuration](#configuration)
12. [Troubleshooting](#troubleshooting)
13. [Development Guide](#development-guide)

---

## Overview

The BixCash Commission System is a comprehensive solution for calculating, tracking, and settling partner commissions based on transaction volume. Partners owe commissions to BixCash, which are tracked separately from their wallet balance.

### Key Characteristics

- **Monthly Batch Processing** - Commissions calculated once per month (not real-time)
- **Separate from Wallet** - Commission debt doesn't affect wallet withdrawals
- **On-Demand Settlement** - Admin processes settlements manually (not automatic)
- **Complete Audit Trail** - Every transaction, settlement, and adjustment is logged
- **Flexible Payment Methods** - Bank transfer, cash, wallet deduction, adjustments
- **Automated Reminders** - Weekly emails for outstanding payments

---

## Business Model

### Commission Structure

```
commission = invoice_amount × (commission_rate ÷ 100)
```

**Example:**
- Partner has 2% commission rate
- Customer transaction: Rs 10,000
- Commission owed: Rs 10,000 × 0.02 = Rs 200

### Key Principles

1. **Partners Owe BixCash** - Commission is money partners pay to BixCash (not the reverse)
2. **Separate Tracking** - Commission debt tracked independently from wallet balance
3. **Non-Blocking** - Partners can withdraw wallet funds even with outstanding commission
4. **Manual Settlement** - Admin decides when to collect commission payments
5. **Historical Rates** - Commission calculated using rate at time of transaction
6. **Transaction-Level Detail** - Complete breakdown available for audit

---

## System Architecture

### Components

```
┌─────────────────────────────────────────────────────────────┐
│                     COMMISSION SYSTEM                        │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐ │
│  │   BATCHES    │───▶│   LEDGERS    │───▶│ SETTLEMENTS  │ │
│  │  (Monthly)   │    │ (Per Partner)│    │  (Payments)  │ │
│  └──────────────┘    └──────────────┘    └──────────────┘ │
│         │                    │                    │         │
│         ▼                    ▼                    ▼         │
│  ┌──────────────────────────────────────────────────────┐  │
│  │           COMMISSION TRANSACTIONS                     │  │
│  │        (Transaction-level breakdown)                  │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### Workflow

1. **Monthly Calculation** (1st of month, 2:00 AM)
   - Artisan command runs: `commission:calculate-monthly`
   - Creates batch for previous month
   - Groups confirmed transactions by partner
   - Calculates commission per transaction
   - Creates ledgers for each partner
   - Updates partner_transactions.partner_profit_share
   - Updates partner_profiles.total_commission_outstanding

2. **Settlement Processing** (Admin on-demand)
   - Admin views pending ledgers
   - Processes settlement with proof of payment
   - Updates ledger balances (commission_owed, amount_paid, amount_outstanding)
   - Updates partner profile totals
   - Sends confirmation emails to partner and admin

3. **Reminder System** (Weekly, Monday 9:00 AM)
   - Artisan command runs: `commission:send-reminders`
   - Identifies partners with outstanding balance ≥ Rs 1,000 and ≥ 7 days old
   - Sends reminder emails with summary and action link

---

## Database Schema

### 1. commission_batches

**Purpose:** Monthly calculation batches

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| batch_period | string(7) | YYYY-MM format (unique) |
| period_start | date | Start date of period |
| period_end | date | End date of period |
| status | enum | pending, processing, completed, failed |
| triggered_by | enum | automatic, manual |
| triggered_by_user_id | bigint | NULL for automatic, admin ID for manual |
| total_partners | int | Number of partners in batch |
| total_transactions | int | Total transactions processed |
| total_transaction_amount | decimal(15,2) | Sum of all invoice amounts |
| total_commission_calculated | decimal(15,2) | Total commission for batch |
| calculation_log | json | Detailed log of calculation process |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- UNIQUE: batch_period
- Foreign key: triggered_by_user_id → users.id

---

### 2. commission_ledgers

**Purpose:** Per-partner commission tracking

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| partner_id | bigint | FK to users table |
| batch_id | bigint | FK to commission_batches |
| batch_period | string(7) | YYYY-MM format |
| commission_rate_used | decimal(5,2) | Rate at time of calculation |
| total_transactions | int | Partner's transaction count |
| total_invoice_amount | decimal(15,2) | Sum of partner's invoices |
| commission_owed | decimal(15,2) | Total commission for period |
| amount_paid | decimal(15,2) | Amount settled so far |
| amount_outstanding | decimal(15,2) | Remaining balance |
| status | enum | pending, partial, settled, cancelled |
| fully_settled_at | timestamp | When balance reached zero |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- UNIQUE: (partner_id, batch_period)
- Foreign keys: partner_id → users.id, batch_id → commission_batches.id

**Status Flow:**
- `pending` → `partial` → `settled`
- Auto-updates based on amount_outstanding

---

### 3. commission_settlements

**Purpose:** Payment records

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| ledger_id | bigint | FK to commission_ledgers |
| partner_id | bigint | FK to users table |
| amount_settled | decimal(15,2) | Payment amount (can be negative for adjustments) |
| payment_method | enum | bank_transfer, cash, wallet_deduction, adjustment, other |
| settlement_reference | string | Bank reference, transaction ID, etc. |
| proof_of_payment | string | File path to uploaded document |
| admin_notes | text | Internal notes |
| processed_by | bigint | Admin user ID |
| processed_at | timestamp | When settlement was processed |
| adjustment_type | enum | NULL, refund, correction, penalty, bonus, other |
| adjustment_reason | text | Required if adjustment_type set |
| voided_at | timestamp | When settlement was voided |
| voided_by | bigint | Admin who voided |
| void_reason | text | Reason for voiding |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- Foreign keys: ledger_id → commission_ledgers.id, partner_id → users.id, processed_by → users.id, voided_by → users.id

**Adjustments:**
- Positive amounts: Additional commission owed (penalties, corrections)
- Negative amounts: Commission refund (refunds, bonuses)

**Void Rules:**
- Can only void within 24 hours of processing
- Cannot void already-voided settlements
- Voiding creates reverse adjustment automatically

---

### 4. commission_transactions

**Purpose:** Transaction-level detail for audit

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| partner_transaction_id | bigint | FK to partner_transactions |
| partner_id | bigint | FK to users table |
| batch_id | bigint | FK to commission_batches |
| ledger_id | bigint | FK to commission_ledgers |
| transaction_code | string | Partner transaction code |
| invoice_amount | decimal(15,2) | Original transaction amount |
| commission_rate | decimal(5,2) | Rate used for this transaction |
| commission_amount | decimal(15,2) | Calculated commission |
| is_settled | boolean | Whether this transaction's commission is settled |
| settlement_id | bigint | FK to commission_settlements (when settled) |
| settled_at | timestamp | When settled |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- Foreign keys: partner_transaction_id → partner_transactions.id, partner_id → users.id, batch_id → commission_batches.id, ledger_id → commission_ledgers.id, settlement_id → commission_settlements.id

---

### 5. partner_profiles (added fields)

**New Fields:**

| Column | Type | Description |
|--------|------|-------------|
| total_commission_outstanding | decimal(15,2) | Current total debt (default: 0) |
| last_commission_settlement_at | timestamp | Last payment date |

---

### 6. partner_transactions (updated field)

**Updated Field:**

| Column | Type | Description |
|--------|------|-------------|
| partner_profit_share | decimal(15,2) | Commission amount for this transaction (updated by calculation) |

---

## Features

### Core Features (Session 9)

#### 1. Monthly Batch Calculation
- **Automatic:** Runs 1st of month at 2:00 AM via cron
- **Manual:** Admin can trigger via dashboard
- **Process:**
  1. Fetch all confirmed partner transactions from previous month
  2. Group by partner
  3. Calculate commission per transaction using current commission_rate
  4. Create batch record with totals
  5. Create ledger for each partner
  6. Create commission_transaction records
  7. Update partner_transactions.partner_profit_share
  8. Update partner_profiles.total_commission_outstanding
  9. Send email notifications to all admins

**Command:**
```bash
php artisan commission:calculate-monthly [period] [--force] [--user-id=X]
```

**Options:**
- `period`: YYYY-MM format (defaults to last month)
- `--force`: Recalculate even if batch exists
- `--user-id`: Admin ID for manual triggers

---

#### 2. Settlement Processing
- **Who:** Admin only
- **When:** On-demand
- **Methods:** Bank Transfer, Cash, Wallet Deduction, Adjustment, Other
- **Process:**
  1. Admin selects ledger with outstanding balance
  2. Fills settlement form (amount, method, reference, proof, notes)
  3. System validates amount ≤ outstanding
  4. Uploads proof of payment (JPG, PNG, PDF, max 5MB)
  5. Creates settlement record
  6. Updates ledger balances
  7. Updates partner profile total
  8. Creates wallet transaction record
  9. Sends confirmation email to partner and admin

**File Storage:**
- Path: `storage/app/public/commission-proofs/`
- Format: `{settlement_id}_{timestamp}_{original_name}`
- Max size: 5MB
- Allowed types: JPG, JPEG, PNG, PDF

---

#### 3. Bulk Settlement
- Settle multiple partners at once
- Same payment method and date for all
- Individual reference and proof per partner
- Atomic transaction (all or nothing)

---

#### 4. PDF Invoices
- **Admin:** Can generate invoice for any ledger
- **Partner:** Can download own invoices
- **Content:**
  - Partner info (name, business, phone, rate)
  - Period details
  - Transaction breakdown table
  - Summary (commission, paid, outstanding)
  - Settlement history
- **Print-ready:** Professional styling for PDF export

---

#### 5. Complete Audit Trail
- Every calculation logged in batch.calculation_log
- Every settlement recorded with timestamp and admin
- Every transaction linked to settlement
- Void history preserved (can't delete)

---

### Phase 1 Features (Critical)

#### 6. Commission Adjustments
- **Purpose:** Correct errors, apply penalties/bonuses, handle refunds
- **Types:**
  - `refund` - Commission refund if transaction was refunded
  - `correction` - Fix calculation errors
  - `penalty` - Apply penalty charges
  - `bonus` - Give commission bonus
  - `other` - Other adjustments
- **Amounts:**
  - Positive: Additional commission owed
  - Negative: Commission refund/credit
- **Process:**
  1. Admin selects ledger
  2. Enters amount (positive or negative)
  3. Selects adjustment type
  4. Provides detailed reason (required)
  5. System creates settlement with adjustment_type
  6. Updates ledger balances accordingly

**Route:** `/admin/commissions/adjustments/create/{ledgerId}`

---

#### 7. Settlement Void/Reversal
- **Purpose:** Undo incorrect settlements
- **Window:** Within 24 hours of processing
- **Restrictions:** Cannot void already-voided settlements
- **Process:**
  1. Admin selects settlement (< 24 hours old)
  2. Provides void reason
  3. System creates reverse adjustment
  4. Updates ledger balances
  5. Marks original settlement as voided
  6. Records void timestamp and admin

**Safety:**
- Checks authorship (won't void other admin's settlements without warning)
- Checks if pushed to remote
- Preserves original record (soft delete pattern)

---

#### 8. Excel Exports
- **Batches Export:** All batches with filters (status, search)
- **Ledgers Export:** All ledgers with filters (partner, outstanding, sort)
- **Settlements Export:** All settlements with filters (partner, method, date range)
- **Partner Report Export:** Complete history for one partner
- **Features:**
  - Formatted headers (bold, larger font)
  - Number formatting for currency
  - Proper column widths
  - Respects current filters
  - "Filtered" badge shown when filters active

**Routes:**
- `/admin/commissions/export/batches?status=completed`
- `/admin/commissions/export/ledgers?outstanding_only=1`
- `/admin/commissions/export/settlements?partner_id=5`
- `/admin/commissions/export/partner/{partnerId}`

---

### Phase 2 Features (Important)

#### 9. Dashboard Performance Caching
- **What's Cached:**
  - Total outstanding (5 min TTL)
  - This month commission (5 min TTL)
  - Pending count (5 min TTL)
  - Total settled (5 min TTL)
  - Top outstanding partners (5 min TTL)
  - Sidebar badge count (10 min TTL)
- **Cache Keys:**
  - `commission_total_outstanding`
  - `commission_this_month`
  - `commission_pending_count`
  - `commission_total_settled`
  - `commission_top_outstanding`
- **Invalidation:** Auto-clears when settlements processed or batches created

**Performance Gain:** ~90% reduction in dashboard queries

---

#### 10. Voided Settlements Visibility
- **Toggle:** Checkbox on settlement history page
- **Default:** Hidden (only show active settlements)
- **When Shown:** Red background, strikethrough, 0.7 opacity
- **Includes:** Void timestamp, voided by admin, reason

---

#### 11. Adjustment Tracking
- **List View:** All adjustments across all partners
- **Filters:** Partner, adjustment type, date range
- **Type Badges:**
  - Refund: 💸 Red badge
  - Correction: ✏️ Yellow badge
  - Penalty: ⚠️ Dark badge
  - Bonus: 🎁 Green badge
  - Other: 📝 Gray badge
- **Display:** Shows in settlement tables and history

**Route:** `/admin/commissions/adjustments`

---

### Phase 3 Features (Good-to-have)

#### 12. Branded Email Templates
- **Design:** BixCash gradient headers (purple/blue)
- **Professional:** Tables, buttons, formatted text
- **Templates:**
  1. `new-ledger.blade.php` - New monthly statement
  2. `settlement-processed.blade.php` - Payment received
  3. `monthly-calculated.blade.php` - Admin batch completion
  4. `settlement-confirmation.blade.php` - Admin settlement confirmation
  5. `outstanding-reminder.blade.php` - Payment reminder
- **All Queued:** Non-blocking, processed in background

---

#### 13. Dashboard Charts
- **Commission Trend Chart (Line Chart):**
  - Last 12 months of commission totals
  - Smooth curves with gradient fill
  - Hover tooltips with formatted amounts
  - Cached for 10 minutes
- **Status Breakdown Chart (Doughnut Chart):**
  - Pending vs Partial vs Settled counts
  - Color-coded segments
  - Percentage tooltips
  - Cached for 5 minutes
- **Library:** Chart.js 4.4.0 (CDN)

---

#### 14. Partner Dashboard Widget
- **When Shown:** Only if partner has commission_rate > 0
- **Display:**
  - Commission rate (bold, purple)
  - Total outstanding amount (red if > 0, green if 0)
  - Last settlement date
  - Pending ledgers count
  - Warning note about separate tracking
  - Link to full commission page
- **Styling:** Gradient background, purple border ring if outstanding

**Controller:** `Partner\DashboardController::index()` passes `$commissionSummary`

---

#### 15. Sidebar Navigation
- **Commissions Menu:** Dropdown with 5 items
  - Dashboard
  - Batches
  - Partners
  - Settlements
  - **Adjustments** (added in Phase 3)
- **Badge:** Shows pending ledger count (purple, cached 10 min)
- **Auto-expand:** Opens when on any commission route
- **Mobile:** Identical submenu in mobile sidebar

---

### Phase 4 Features (Enhancements)

#### 16. Batch Email Notifications
- **Location:** Batch details page
- **Button:** "📧 Notify All Partners"
- **Conditions:**
  - Only shown for completed batches
  - Only if batch has ledgers
- **Process:**
  1. Admin clicks button
  2. Confirmation dialog shows partner count
  3. System sends NewCommissionLedger notification to each partner
  4. Shows success count and failure count
  5. Logs failures for admin review
- **Emails Queued:** Non-blocking background processing

**Route:** `POST /admin/commissions/batches/{id}/notify-all`

---

#### 17. Automated Reminder Emails
- **Schedule:** Every Monday at 9:00 AM
- **Criteria:**
  - Outstanding balance ≥ Rs 1,000 (configurable)
  - Oldest ledger ≥ 7 days old (configurable)
- **Email Content:**
  - Total outstanding amount
  - Number of pending periods
  - Oldest pending period
  - Summary table
  - Link to commission details
  - Note about separate wallet tracking
- **Command:**
  ```bash
  php artisan commission:send-reminders --min-days=7 --min-amount=1000
  ```
- **Output:** Table showing sent/skipped counts

**Notification:** `Partner\OutstandingCommissionReminder`

---

#### 18. Settlement Proof Gallery
- **Purpose:** Visual browsing of payment documents
- **Layout:** Responsive grid (300px cards, 24 per page)
- **Card Content:**
  - Image preview (JPG, PNG) - click to enlarge
  - PDF icon with inline viewer in modal
  - File icon for other formats
  - Partner name and business
  - Amount and period
  - Payment method badge
  - Reference number
  - Processed date and admin
  - View and Download buttons
- **Filters:** Partner, date range
- **Modal Viewer:**
  - Full-screen image display
  - Inline PDF viewer (iframe)
  - Download button
- **Link:** Button on settlement history page

**Route:** `/admin/commissions/settlements/proof-gallery`

---

#### 19. Excel Export Filter Indicators
- **Badge:** Yellow "Filtered" badge appears on export button
- **Conditions:** Shows when any filter is active
  - Batches: status or search
  - Settlements: partner, method, or date range
  - Ledgers: search or outstanding_only
- **Tooltip:** Hover shows filter status
- **Confirmation Dialog:**
  - Shows count of records to export
  - Explains filters will be applied
  - Prevents accidental full exports

**UX Improvement:** Users know exactly what's being exported

---

## User Workflows

### Admin Workflows

#### A. Monthly Calculation (Automatic)
```
[1st of Month, 2:00 AM]
  ↓
[Cron triggers: commission:calculate-monthly]
  ↓
[System fetches previous month's confirmed transactions]
  ↓
[Groups by partner, calculates commission]
  ↓
[Creates batch, ledgers, commission_transactions]
  ↓
[Updates partner_transactions.partner_profit_share]
  ↓
[Updates partner_profiles.total_commission_outstanding]
  ↓
[Sends email to all admins: MonthlyCommissionCalculated]
  ↓
[Done - Partners can now view their statements]
```

---

#### B. Manual Calculation
```
[Admin → Commissions Dashboard]
  ↓
[Enters period (e.g., 2025-10)]
  ↓
[Clicks "Calculate Now"]
  ↓
[System validates period format]
  ↓
[Checks if batch already exists (unless --force)]
  ↓
[Runs calculation (same as automatic)]
  ↓
[Shows progress bar and results]
  ↓
[Redirects to batch details]
```

---

#### C. Process Settlement
```
[Admin → Commissions → Partners]
  ↓
[Selects partner with outstanding balance]
  ↓
[Clicks "Settle" on specific ledger]
  ↓
[Settlement form loads with ledger summary]
  ↓
[Admin fills form:]
  - Amount (max = outstanding)
  - Payment method (dropdown)
  - Settlement reference (optional)
  - Upload proof (JPG/PNG/PDF, max 5MB)
  - Admin notes (textarea)
  ↓
[Clicks "Process Settlement"]
  ↓
[System validates and processes:]
  - Creates settlement record
  - Uploads proof to storage
  - Updates ledger balances
  - Updates partner profile total
  - Creates wallet transaction
  ↓
[Sends emails:]
  - Partner: CommissionSettlementProcessed
  - Admin: SettlementProcessedConfirmation
  ↓
[Redirects to partner details]
  ↓
[Shows success message]
```

---

#### D. Bulk Settlement
```
[Admin → Batch Details]
  ↓
[Checks multiple partners with outstanding balances]
  ↓
[Fills bulk settlement form:]
  - Payment method (same for all)
  - Settlement date
  - For each partner:
    * Amount
    * Reference
    * Upload proof
  ↓
[Clicks "Process Bulk Settlement"]
  ↓
[System processes in database transaction:]
  - Validates all inputs
  - Creates all settlements
  - Updates all ledgers
  - If any fails, rolls back all
  ↓
[Shows results: X succeeded, Y failed]
```

---

#### E. Create Adjustment
```
[Admin → Partner Details]
  ↓
[Clicks "Adjust" on specific ledger]
  ↓
[Adjustment form loads with ledger info]
  ↓
[Admin fills form:]
  - Amount (positive for additional owed, negative for refund)
  - Adjustment type (refund, correction, penalty, bonus, other)
  - Reason (required, detailed explanation)
  ↓
[Clicks "Create Adjustment"]
  ↓
[System processes:]
  - Creates settlement with adjustment_type
  - Updates ledger balances
  - Updates partner profile total
  ↓
[Shows in settlement history with type badge]
```

---

#### F. Void Settlement
```
[Admin → Settlement History]
  ↓
[Finds settlement to void (< 24 hours old)]
  ↓
[Clicks "Void" button]
  ↓
[Void modal appears]
  ↓
[Admin enters void reason]
  ↓
[Clicks "Confirm Void"]
  ↓
[System validates:]
  - Settlement < 24 hours old
  - Not already voided
  ↓
[Creates reverse adjustment]
  ↓
[Updates ledger balances]
  ↓
[Marks settlement as voided]
  ↓
[Shows strikethrough in list with void details]
```

---

#### G. Batch Email Notification
```
[Admin → Batch Details]
  ↓
[Batch status = completed, has ledgers]
  ↓
[Clicks "📧 Notify All Partners"]
  ↓
[Confirmation dialog shows partner count]
  ↓
[Clicks "OK"]
  ↓
[System queues NewCommissionLedger email for each partner]
  ↓
[Shows success/failure counts]
  ↓
[Partners receive monthly statement emails]
```

---

#### H. Export to Excel
```
[Admin on any list view (batches/partners/settlements)]
  ↓
[Applies filters (optional):]
  - Status, partner, date range, etc.
  ↓
[Clicks "📊 Export to Excel"]
  ↓
[Confirmation dialog shows record count]
  ↓
[Clicks "OK"]
  ↓
[System generates Excel file with filters applied]
  ↓
[Browser downloads file]
  ↓
[Excel contains formatted data with headers, styling]
```

---

### Partner Workflows

#### I. View Commission Overview
```
[Partner Dashboard]
  ↓
[Sees commission widget if rate > 0]
  ↓
[Clicks "View Details" or bottom nav "Commissions"]
  ↓
[Commission overview loads:]
  - 4 stat cards (rate, owed, paid, outstanding)
  - Alert if outstanding > 0
  - Ledgers table (by period)
  - Recent settlements
  ↓
[Can click "Details" on any period]
```

---

#### J. View Period Details
```
[Partner → Commissions → Clicks "Details"]
  ↓
[Period details load:]
  - Period header (rate, transactions, total, status)
  - Transaction breakdown table (each transaction line)
  - Totals footer
  - Settlement history for this period (if any)
  - Download invoice button
  ↓
[Pagination if > 20 transactions]
```

---

#### K. Download Invoice
```
[Partner → Period Details]
  ↓
[Clicks "📄 Download Invoice"]
  ↓
[Invoice view loads (PDF-ready):]
  - BixCash header
  - Partner info + invoice details
  - 4 summary cards
  - Transaction breakdown table
  - Payment summary (owed, paid, outstanding)
  - Settlement history
  ↓
[Clicks browser print (Ctrl+P)]
  ↓
[Saves as PDF or prints]
```

---

#### L. Receive Reminder Email
```
[Monday 9:00 AM, automated]
  ↓
[System identifies partners with:]
  - Outstanding ≥ Rs 1,000
  - Oldest ledger ≥ 7 days
  ↓
[Sends OutstandingCommissionReminder email]
  ↓
[Partner receives email with:]
  - Total outstanding amount (red, bold)
  - Number of pending periods
  - Oldest pending period
  - Summary table
  - "View Commission Details" button
  ↓
[Partner clicks button → redirects to commission page]
```

---

## API Reference

### Admin Routes

#### Dashboard & Overview

**GET** `/admin/commissions`
- **Name:** `admin.commissions.index`
- **Controller:** `Admin\CommissionController::index`
- **Purpose:** Commission dashboard
- **Returns:**
  - Total outstanding
  - This month commission
  - Pending settlements count
  - Total settled
  - Recent batches (5)
  - Top outstanding partners (10)
  - Recent settlements (10)
  - Commission trend (12 months)
  - Status breakdown

---

#### Batches

**GET** `/admin/commissions/batches`
- **Name:** `admin.commissions.batches.index`
- **Controller:** `Admin\CommissionController::batchIndex`
- **Query Params:**
  - `status` - Filter by status
  - `search` - Search by period
- **Returns:** Paginated batches list

**GET** `/admin/commissions/batches/{id}`
- **Name:** `admin.commissions.batches.show`
- **Controller:** `Admin\CommissionController::batchShow`
- **Returns:**
  - Batch details
  - Partner ledgers (sorted by outstanding)

**POST** `/admin/commissions/batches/{id}/notify-all`
- **Name:** `admin.commissions.batches.notify-all`
- **Controller:** `Admin\CommissionController::notifyAllPartners`
- **Purpose:** Send email to all partners in batch
- **Returns:** Success/failure counts

**POST** `/admin/commissions/calculate`
- **Name:** `admin.commissions.calculate`
- **Controller:** `Admin\CommissionController::triggerCalculation`
- **Body:**
  - `period` - YYYY-MM format (required)
- **Process:** Triggers manual calculation via Artisan command

---

#### Partners

**GET** `/admin/commissions/partners`
- **Name:** `admin.commissions.partners.index`
- **Controller:** `Admin\CommissionController::partnerIndex`
- **Query Params:**
  - `search` - Name or business search
  - `outstanding_only` - Boolean filter
  - `sort` - total_outstanding or name
- **Returns:** Paginated partners with commission info

**GET** `/admin/commissions/partners/{partnerId}`
- **Name:** `admin.commissions.partners.show`
- **Controller:** `Admin\CommissionController::partnerShow`
- **Returns:**
  - Partner info
  - Summary stats (owed, paid, outstanding)
  - Ledgers by period
  - Settlement history

---

#### Settlements

**GET** `/admin/commissions/settlements/history`
- **Name:** `admin.commissions.settlements.history`
- **Controller:** `Admin\CommissionController::settlementHistory`
- **Query Params:**
  - `partner_id` - Filter by partner
  - `payment_method` - Filter by method
  - `from_date` - Date filter
  - `to_date` - Date filter
  - `show_voided` - Include voided (default: false)
- **Returns:** Paginated settlements

**GET** `/admin/commissions/settlements/proof-gallery`
- **Name:** `admin.commissions.settlements.proof-gallery`
- **Controller:** `Admin\CommissionController::proofGallery`
- **Query Params:**
  - `partner_id` - Filter by partner
  - `from_date` - Date filter
  - `to_date` - Date filter
- **Returns:** Paginated settlements with proof (24 per page)

**GET** `/admin/commissions/settlements/create/{ledgerId}`
- **Name:** `admin.commissions.settlements.create`
- **Controller:** `Admin\CommissionController::settlementCreate`
- **Returns:** Settlement form with ledger summary

**POST** `/admin/commissions/settlements/{ledgerId}`
- **Name:** `admin.commissions.settlements.store`
- **Controller:** `Admin\CommissionController::settlementStore`
- **Body:**
  - `amount_settled` - Decimal (required, max = outstanding)
  - `payment_method` - Enum (required)
  - `settlement_reference` - String (optional)
  - `proof_of_payment` - File (optional, JPG/PNG/PDF, max 5MB)
  - `admin_notes` - Text (optional)
- **Process:**
  1. Validates inputs
  2. Uploads proof
  3. Creates settlement
  4. Updates ledger
  5. Sends notifications
- **Returns:** Redirect to partner details

**POST** `/admin/commissions/settlements/bulk-settle`
- **Name:** `admin.commissions.settlements.bulk-settle`
- **Controller:** `Admin\CommissionController::bulkSettle`
- **Body:**
  - `payment_method` - Enum (same for all)
  - `settlement_date` - Date
  - `settlements` - Array of:
    * `ledger_id`
    * `amount`
    * `reference`
    * `proof` (file)
- **Process:** Database transaction, all or nothing
- **Returns:** Success/failure counts

**POST** `/admin/commissions/settlements/{id}/void`
- **Name:** `admin.commissions.settlements.void`
- **Controller:** `Admin\CommissionController::voidSettlement`
- **Body:**
  - `void_reason` - Text (required)
- **Validation:**
  - Settlement < 24 hours old
  - Not already voided
- **Process:**
  1. Creates reverse adjustment
  2. Updates ledger
  3. Marks settlement as voided
- **Returns:** Redirect with status

---

#### Adjustments

**GET** `/admin/commissions/adjustments`
- **Name:** `admin.commissions.adjustments.index`
- **Controller:** `Admin\CommissionController::adjustmentIndex`
- **Query Params:**
  - `partner_id` - Filter by partner
  - `adjustment_type` - Filter by type
  - `from_date` - Date filter
  - `to_date` - Date filter
- **Returns:** Paginated adjustments

**GET** `/admin/commissions/adjustments/create/{ledgerId}`
- **Name:** `admin.commissions.adjustments.create`
- **Controller:** `Admin\CommissionController::adjustmentCreate`
- **Returns:** Adjustment form with ledger summary

**POST** `/admin/commissions/adjustments/{ledgerId}`
- **Name:** `admin.commissions.adjustments.store`
- **Controller:** `Admin\CommissionController::adjustmentStore`
- **Body:**
  - `amount` - Decimal (positive or negative)
  - `adjustment_type` - Enum (required)
  - `adjustment_reason` - Text (required)
- **Process:**
  1. Creates settlement with adjustment_type
  2. Updates ledger balances
  3. Updates partner profile
- **Returns:** Redirect to partner details

---

#### Exports

**GET** `/admin/commissions/export/batches`
- **Name:** `admin.commissions.export.batches`
- **Controller:** `Admin\CommissionController::exportBatches`
- **Query Params:** Same as batches.index filters
- **Returns:** Excel file download

**GET** `/admin/commissions/export/ledgers`
- **Name:** `admin.commissions.export.ledgers`
- **Controller:** `Admin\CommissionController::exportLedgers`
- **Query Params:** Same as partners.index filters
- **Returns:** Excel file download

**GET** `/admin/commissions/export/settlements`
- **Name:** `admin.commissions.export.settlements`
- **Controller:** `Admin\CommissionController::exportSettlements`
- **Query Params:** Same as settlements.history filters
- **Returns:** Excel file download

**GET** `/admin/commissions/export/partner/{partnerId}`
- **Name:** `admin.commissions.export.partner`
- **Controller:** `Admin\CommissionController::exportPartnerReport`
- **Returns:** Excel file with complete partner history

---

#### Invoice

**GET** `/admin/commissions/invoice/{ledgerId}`
- **Name:** `admin.commissions.invoice.download`
- **Controller:** `Admin\CommissionController::downloadInvoice`
- **Returns:** PDF-ready invoice view

---

### Partner Routes

**GET** `/partner/commissions`
- **Name:** `partner.commissions` or `partner.commissions.index`
- **Controller:** `Partner\CommissionController::index`
- **Returns:**
  - Commission rate
  - Total owed, paid, outstanding
  - Ledgers by period
  - Recent settlements

**GET** `/partner/commissions/{ledgerId}`
- **Name:** `partner.commissions.show`
- **Controller:** `Partner\CommissionController::show`
- **Returns:**
  - Period details
  - Transaction breakdown
  - Settlement history

**GET** `/partner/commissions/{ledgerId}/invoice`
- **Name:** `partner.commissions.invoice`
- **Controller:** `Partner\CommissionController::downloadInvoice`
- **Returns:** PDF-ready invoice view

---

## Automated Tasks

### 1. Monthly Commission Calculation

**Command:**
```bash
php artisan commission:calculate-monthly [period] [--force] [--user-id=X]
```

**Schedule:**
```php
Schedule::command('commission:calculate-monthly')
    ->monthlyOn(1, '02:00')
    ->withoutOverlapping()
    ->runInBackground();
```

**Frequency:** 1st of each month at 2:00 AM

**What it does:**
1. Defaults to previous month if no period provided
2. Validates YYYY-MM format
3. Checks if batch already exists (skips unless --force)
4. Fetches all confirmed partner_transactions for the period
5. Groups by partner_id
6. For each partner:
   - Gets current commission_rate from partner_profiles
   - Calculates commission for each transaction
   - Creates CommissionLedger record
   - Creates CommissionTransaction records
   - Updates partner_transactions.partner_profit_share
7. Creates CommissionBatch with totals
8. Updates partner_profiles.total_commission_outstanding
9. Sends email to all admins (MonthlyCommissionCalculated)
10. Logs progress with detailed messages

**Output:**
- Progress bar showing partners processed
- Summary table (partners, transactions, total amount, commission)
- Batch ID on completion

**Error Handling:**
- Wrapped in database transaction
- Rolls back on any error
- Logs errors with context
- Returns non-zero exit code on failure

---

### 2. Commission Payment Reminders

**Command:**
```bash
php artisan commission:send-reminders [--min-days=7] [--min-amount=0]
```

**Schedule:**
```php
Schedule::command('commission:send-reminders --min-days=7 --min-amount=1000')
    ->weeklyOn(1, '09:00')
    ->withoutOverlapping()
    ->runInBackground();
```

**Frequency:** Every Monday at 9:00 AM

**Options:**
- `--min-days=7` - Only send if oldest ledger is ≥ 7 days old
- `--min-amount=1000` - Only send if total outstanding ≥ Rs 1,000

**What it does:**
1. Finds all partners with amount_outstanding > 0
2. For each partner:
   - Calculates total outstanding
   - Finds oldest ledger
   - Calculates days since oldest ledger
   - Skips if doesn't meet min-days or min-amount criteria
3. Sends OutstandingCommissionReminder email
4. Logs success/failure per partner
5. Shows summary table

**Output:**
- List of partners with checkmark/x and details
- Summary table (total partners, sent, skipped)

**Email Content:**
- Total outstanding (formatted, bold)
- Number of pending periods
- Oldest pending period (formatted)
- Summary table
- "View Commission Details" button
- Note about wallet separation

---

## Notifications

### Partner Notifications

#### 1. NewCommissionLedger
**File:** `app/Notifications/Partner/NewCommissionLedger.php`
**Template:** `emails/commissions/new-ledger.blade.php`
**Trigger:** Monthly calculation or batch notification button
**Subject:** `💰 New Commission Statement - {Month Year}`
**Content:**
- Period header with gradient
- Commission summary table (rate, transactions, invoice total, commission)
- Outstanding balance highlight
- "View Statement" button → partner.commissions.show
- Note about due date

---

#### 2. CommissionSettlementProcessed
**File:** `app/Notifications/Partner/CommissionSettlementProcessed.php`
**Template:** `emails/commissions/settlement-processed.blade.php`
**Trigger:** Settlement created by admin
**Subject:** `✅ Commission Payment Received - {Month Year}`
**Content:**
- Period and settlement details
- Payment method and reference
- Amount settled (formatted)
- Remaining balance
- "View Details" button
- Thank you message

---

#### 3. OutstandingCommissionReminder
**File:** `app/Notifications/Partner/OutstandingCommissionReminder.php`
**Template:** `emails/commissions/outstanding-reminder.blade.php`
**Trigger:** Weekly reminder command (Mondays 9 AM)
**Subject:** `⏰ Reminder: Outstanding Commission Payment - BixCash`
**Content:**
- Summary table (total outstanding, pending periods, oldest period)
- Explanation of separate tracking
- "View Commission Details" button
- Contact info for support

---

### Admin Notifications

#### 4. MonthlyCommissionCalculated
**File:** `app/Notifications/Admin/MonthlyCommissionCalculated.php`
**Template:** `emails/commissions/monthly-calculated.blade.php`
**Trigger:** Batch calculation completion
**Recipient:** All admin users
**Subject:** `📊 Monthly Commission Calculated - {Month Year}`
**Content:**
- Batch summary (partners, transactions, total amount, commission)
- Calculation details table
- "View Batch Details" button
- Next steps reminder

---

#### 5. SettlementProcessedConfirmation
**File:** `app/Notifications/Admin/SettlementProcessedConfirmation.php`
**Template:** `emails/commissions/settlement-confirmation.blade.php`
**Trigger:** Settlement processed
**Recipient:** Admin who processed settlement
**Subject:** `✅ Settlement Processed - {Partner Name}`
**Content:**
- Settlement summary (partner, period, amount, method)
- Updated ledger status
- Proof of payment link (if uploaded)
- "View Settlement History" button

---

## Exports

All exports use Maatwebsite/Laravel-Excel package.

### 1. Batches Export

**Class:** `app/Exports/CommissionBatchesExport.php`
**Route:** `/admin/commissions/export/batches`
**Query Support:** Respects status and search filters
**Columns:**
- Batch ID
- Period
- Period Start
- Period End
- Status
- Triggered By
- Partners
- Transactions
- Total Amount (formatted currency)
- Commission (formatted currency)
- Created At

**Formatting:**
- Bold headers (size 12)
- Currency columns with 2 decimals
- Auto-width columns
- Status uppercase

---

### 2. Ledgers Export

**Class:** `app/Exports/CommissionLedgersExport.php`
**Route:** `/admin/commissions/export/ledgers`
**Query Support:** Respects partner search, outstanding filter, sort
**Columns:**
- Partner Name
- Business Name
- Phone
- Batch Period
- Commission Rate
- Transactions
- Invoice Amount (formatted)
- Commission Owed (formatted)
- Amount Paid (formatted)
- Outstanding (formatted)
- Status
- Created At

**Formatting:**
- Bold headers
- Currency formatting
- Status uppercase
- Percentage for rate

---

### 3. Settlements Export

**Class:** `app/Exports/CommissionSettlementsExport.php`
**Route:** `/admin/commissions/export/settlements`
**Query Support:** Respects partner, method, date range, voided toggle
**Columns:**
- Settlement ID
- Partner Name
- Business Name
- Period
- Amount Settled (formatted)
- Payment Method
- Reference
- Adjustment Type (if applicable)
- Processed By (admin name)
- Processed At
- Voided (Yes/No)
- Void Reason

**Formatting:**
- Bold headers
- Currency formatting
- Method uppercase
- Conditional voided column

---

### 4. Partner Report Export

**Class:** `app/Exports/PartnerCommissionReportExport.php`
**Route:** `/admin/commissions/export/partner/{partnerId}`
**Purpose:** Complete commission history for one partner
**Includes:**
- Partner summary sheet
- All ledgers
- All settlements
- All commission transactions

**Columns:**
- Transaction Code
- Invoice Date
- Invoice Amount
- Commission Rate
- Commission Amount
- Batch Period
- Settlement Status
- Settled At
- Settlement Method

**Formatting:**
- Multi-sheet workbook
- Bold headers
- Currency and percentage formatting
- Totals row at bottom

---

## Configuration

### Environment Variables

None required - system uses existing database and mail configuration.

### Settings

#### Commission Rates
- Stored in: `partner_profiles.commission_rate` (decimal 5,2)
- Set per partner by admin
- Can be changed anytime (new rate applies to future calculations)
- Historical rates preserved in ledgers

#### Calculation Schedule
- File: `routes/console.php`
- Default: 1st of month at 2:00 AM
- Customizable by editing cron schedule

#### Reminder Schedule
- File: `routes/console.php`
- Default: Every Monday at 9:00 AM
- Options: `--min-days=7 --min-amount=1000`
- Customizable per business needs

#### File Upload Limits
- Max size: 5MB (configurable in controller validation)
- Allowed types: JPG, JPEG, PNG, PDF
- Storage path: `storage/app/public/commission-proofs/`

#### Cache TTL
- Dashboard stats: 300 seconds (5 min)
- Commission trend: 600 seconds (10 min)
- Status breakdown: 300 seconds (5 min)
- Sidebar badge: 600 seconds (10 min)

**Cache Keys:**
```php
'commission_total_outstanding'
'commission_this_month'
'commission_pending_count'
'commission_total_settled'
'commission_top_outstanding'
'commission_trend_12months'
'commission_status_breakdown'
```

---

## Troubleshooting

### Common Issues

#### 1. Calculation Not Running Automatically

**Symptoms:** No batches created on 1st of month

**Checks:**
```bash
# Verify cron is running
sudo systemctl status cron

# Check Laravel scheduler is enabled
crontab -l | grep artisan

# Manually test calculation
php artisan commission:calculate-monthly --force

# Check logs
tail -f storage/logs/laravel.log
```

**Solution:**
- Ensure cron entry exists: `* * * * * cd /var/www/bixcash.com/backend && php artisan schedule:run >> /dev/null 2>&1`
- Check scheduler is registered in `routes/console.php`
- Verify no overlapping instances

---

#### 2. Emails Not Sending

**Symptoms:** Notifications queued but not delivered

**Checks:**
```bash
# Verify queue worker is running
php artisan queue:work

# Check failed jobs
php artisan queue:failed

# Test email configuration
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });

# Check logs
tail -f storage/logs/laravel.log
```

**Solution:**
- Start queue worker: `php artisan queue:work --daemon`
- Or use supervisor for production
- Retry failed jobs: `php artisan queue:retry all`
- Check `.env` mail settings

---

#### 3. Export Button Not Working

**Symptoms:** Clicking export does nothing or errors

**Checks:**
```bash
# Verify Maatwebsite/Excel is installed
composer show | grep excel

# Check export classes exist
ls -l app/Exports/

# Test route
php artisan route:list | grep export

# Check file permissions
ls -ld storage/
```

**Solution:**
- Install package: `composer require maatwebsite/excel`
- Clear route cache: `php artisan route:clear`
- Fix permissions: `chmod -R 775 storage/`

---

#### 4. Proof Upload Failing

**Symptoms:** File upload returns validation error

**Checks:**
```bash
# Check storage link exists
ls -l public/storage

# Verify upload directory
ls -ld storage/app/public/commission-proofs/

# Check PHP upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size
```

**Solution:**
- Create symbolic link: `php artisan storage:link`
- Create directory: `mkdir -p storage/app/public/commission-proofs`
- Fix permissions: `chmod -R 775 storage/`
- Increase PHP limits in `php.ini`:
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

---

#### 5. Dashboard Slow/Timeout

**Symptoms:** Dashboard takes too long to load

**Checks:**
```bash
# Check cache is working
php artisan tinker
>>> Cache::get('commission_total_outstanding');

# Clear old cache
php artisan cache:clear

# Check database indexes
php artisan db:show
```

**Solution:**
- Enable Redis for caching (recommended for production)
- Add database indexes if missing
- Increase PHP timeout in `php.ini`
- Check `cache.php` driver is set correctly

---

#### 6. Void Button Not Showing

**Symptoms:** Can't void recent settlement

**Checks:**
- Settlement must be < 24 hours old
- Settlement must not already be voided
- Check `processed_at` timestamp in database

**Solution:**
- Settlements can only be voided within 24-hour window
- If needed, create adjustment instead of voiding
- Contact admin with higher permissions

---

#### 7. Batch Calculation Fails Midway

**Symptoms:** Batch created but incomplete ledgers

**Checks:**
```bash
# Check error logs
tail -100 storage/logs/laravel.log

# Verify database transaction rolled back
# Check batch status
php artisan tinker
>>> CommissionBatch::latest()->first()->status;
```

**Solution:**
- Database transaction should auto-rollback on error
- Delete incomplete batch if exists
- Re-run with --force flag
- Check for data integrity issues (missing partners, invalid rates)

---

### Performance Optimization

#### For Large Datasets (>10,000 transactions/month)

1. **Enable Redis Caching:**
```bash
# Install Redis
sudo apt install redis-server

# Update .env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

2. **Add Database Indexes:**
```sql
-- Already included in migrations, but verify:
CREATE INDEX idx_commission_transactions_partner_id ON commission_transactions(partner_id);
CREATE INDEX idx_commission_ledgers_partner_id ON commission_ledgers(partner_id);
CREATE INDEX idx_commission_settlements_ledger_id ON commission_settlements(ledger_id);
```

3. **Use Queue Workers:**
```bash
# Start multiple queue workers
php artisan queue:work --queue=high,default --tries=3 --daemon &
php artisan queue:work --queue=high,default --tries=3 --daemon &
php artisan queue:work --queue=high,default --tries=3 --daemon &
```

4. **Optimize Calculation:**
```bash
# Run calculation during off-peak hours
# Adjust cron schedule to 3:00 AM instead of 2:00 AM
```

---

## Development Guide

### Adding New Payment Method

1. **Update Enum in Migration:**
```php
// Create new migration
$table->enum('payment_method', [
    'bank_transfer',
    'cash',
    'wallet_deduction',
    'adjustment',
    'other',
    'crypto_transfer' // NEW
])->change();
```

2. **Update Model:**
```php
// CommissionSettlement.php
public function getFormattedPaymentMethodAttribute(): string
{
    return match($this->payment_method) {
        // ... existing cases
        'crypto_transfer' => 'Crypto Transfer',
        default => ucfirst(str_replace('_', ' ', $this->payment_method)),
    };
}
```

3. **Update View:**
```blade
<!-- settlements/create.blade.php -->
<option value="crypto_transfer">Crypto Transfer</option>
```

---

### Adding New Adjustment Type

1. **Update Enum in Migration:**
```php
$table->enum('adjustment_type', [
    'refund',
    'correction',
    'penalty',
    'bonus',
    'other',
    'dispute_resolution' // NEW
])->nullable()->change();
```

2. **Update Model Badge Method:**
```php
// CommissionSettlement.php
public function getAdjustmentTypeBadgeAttribute(): ?array
{
    return match($this->adjustment_type) {
        // ... existing cases
        'dispute_resolution' => ['icon' => '⚖️', 'color' => 'primary', 'label' => 'Dispute'],
        default => null,
    };
}
```

3. **Update View:**
```blade
<!-- adjustments/create.blade.php -->
<option value="dispute_resolution">Dispute Resolution</option>
```

---

### Custom Notification Channels

To add SMS or in-app notifications:

1. **Install Package:**
```bash
composer require laravel/vonage-notification-channel
# or
composer require laravel-notification-channels/twilio
```

2. **Update Notification:**
```php
// Notifications/Partner/NewCommissionLedger.php
public function via(object $notifiable): array
{
    return ['mail', 'sms']; // Add SMS
}

public function toSms(object $notifiable)
{
    return "New commission statement: Rs {$this->ledger->commission_owed}. View at: " . route('partner.commissions.index');
}
```

---

### Running Tests

```bash
# Run all commission tests
php artisan test --filter Commission

# Run specific test
php artisan test tests/Feature/CommissionCalculationTest.php

# With coverage
php artisan test --coverage
```

**Test Structure:**
```
tests/
├── Feature/
│   ├── CommissionCalculationTest.php
│   ├── CommissionSettlementTest.php
│   ├── CommissionAdjustmentTest.php
│   └── CommissionVoidTest.php
└── Unit/
    ├── CommissionBatchTest.php
    ├── CommissionLedgerTest.php
    └── CommissionSettlementTest.php
```

---

### Code Style

Follow Laravel conventions and BixCash standards:

```bash
# Format code
./vendor/bin/pint

# Check for issues
./vendor/bin/phpstan analyse
```

---

## Changelog

### Version 1.0.0 (2025-11-15)

**Initial Release - Complete Commission System**

**Core Features (Session 9):**
- Monthly batch calculation (automatic + manual)
- Transaction-level commission tracking
- Partner ledger management
- Settlement processing with proof upload
- Bulk settlement capability
- PDF invoice generation
- Complete audit trail

**Phase 1 - Critical Features:**
- Commission adjustment system (refunds, corrections, penalties, bonuses)
- Settlement void/reversal (24-hour window)
- Excel export system (4 export types)
- Triggered-by detection fix

**Phase 2 - Important Features:**
- Dashboard performance caching (90% query reduction)
- Voided settlements visibility toggle
- Adjustment tracking and list view
- Adjustment type badges

**Phase 3 - Good-to-Have Features:**
- Branded email templates (5 templates with gradients)
- Dashboard charts (Chart.js integration)
- Partner dashboard commission widget
- Sidebar navigation submenu

**Phase 4 - Enhancements:**
- Batch email notification system
- Automated weekly reminder emails
- Settlement proof gallery (visual browser)
- Excel export filter indicators

**Files Created:** 31
**Files Modified:** 18
**Routes Added:** 24
**Total Lines of Code:** ~15,000+

---

## Support

For technical support or questions:

- **Documentation:** This file (COMMISSION.md)
- **Code Issues:** Check `storage/logs/laravel.log`
- **Email:** admin@bixcash.com
- **Phone:** +92 XXX XXXXXXX

---

## License

Proprietary - BixCash Internal System

**© 2025 BixCash. All rights reserved.**

This commission system is proprietary software developed for BixCash operations. Unauthorized copying, distribution, or modification is strictly prohibited.

---

**End of Documentation**
