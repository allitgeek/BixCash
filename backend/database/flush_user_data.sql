-- ================================================================
-- BIXCASH DATA FLUSH SCRIPT
-- ================================================================
-- Purpose: Remove all partner/customer data for production reset
--
-- PRESERVES: Admin users, roles, permissions, brands, categories,
--            system settings, slides, promotions
--
-- DELETES: All partners, customers, transactions, commissions,
--          wallets, withdrawals, profit sharing data
--
-- ================================================================
-- !!! BACKUP YOUR DATABASE BEFORE RUNNING THIS SCRIPT !!!
--
-- Run this command first:
-- mysqldump -u root -p bixcash > bixcash_backup_$(date +%Y%m%d_%H%M%S).sql
-- ================================================================

-- ================================================================
-- STEP 1: PRE-DELETION VERIFICATION (Run these first to see counts)
-- ================================================================

SELECT '========== PRE-DELETION COUNTS ==========' AS '';

SELECT 'Users to delete (partners + customers)' AS info,
       COUNT(*) AS count
FROM users
WHERE role_id IN (SELECT id FROM roles WHERE name IN ('partner', 'customer'));

SELECT 'Admin users to PRESERVE' AS info,
       COUNT(*) AS count
FROM users
WHERE role_id IN (SELECT id FROM roles WHERE name IN ('super_admin', 'admin', 'moderator'));

SELECT 'Partner profiles' AS info, COUNT(*) AS count FROM partner_profiles;
SELECT 'Customer profiles' AS info, COUNT(*) AS count FROM customer_profiles;
SELECT 'Partner transactions' AS info, COUNT(*) AS count FROM partner_transactions;
SELECT 'Purchase history' AS info, COUNT(*) AS count FROM purchase_history;
SELECT 'Wallets' AS info, COUNT(*) AS count FROM wallets;
SELECT 'Wallet transactions' AS info, COUNT(*) AS count FROM wallet_transactions;
SELECT 'Withdrawal requests' AS info, COUNT(*) AS count FROM withdrawal_requests;
SELECT 'Commission batches' AS info, COUNT(*) AS count FROM commission_batches;
SELECT 'Commission ledgers' AS info, COUNT(*) AS count FROM commission_ledgers;
SELECT 'Commission settlements' AS info, COUNT(*) AS count FROM commission_settlements;
SELECT 'Commission transactions' AS info, COUNT(*) AS count FROM commission_transactions;
SELECT 'Profit batches' AS info, COUNT(*) AS count FROM profit_batches;
SELECT 'Profit sharing distributions' AS info, COUNT(*) AS count FROM profit_sharing_distributions;
SELECT 'Customer queries' AS info, COUNT(*) AS count FROM customer_queries;
SELECT 'Query replies' AS info, COUNT(*) AS count FROM query_replies;

SELECT '=========================================' AS '';
SELECT 'If counts look correct, proceed with deletion below' AS '';
SELECT '=========================================' AS '';

-- ================================================================
-- STEP 2: DISABLE FOREIGN KEY CHECKS
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- STEP 3: TRUNCATE ALL TRANSACTION/DEPENDENT TABLES
-- Order: Most dependent tables first
-- ================================================================

-- Commission System (most nested dependencies)
TRUNCATE TABLE commission_transactions;
TRUNCATE TABLE commission_settlements;
TRUNCATE TABLE commission_ledgers;
TRUNCATE TABLE commission_batches;

-- Profit Sharing
TRUNCATE TABLE profit_sharing_distributions;
TRUNCATE TABLE profit_batches;

-- Customer Support
TRUNCATE TABLE query_replies;
TRUNCATE TABLE customer_queries;

-- Wallet & Transactions
TRUNCATE TABLE wallet_transactions;
TRUNCATE TABLE withdrawal_requests;
TRUNCATE TABLE wallets;

-- Partner Transactions & Purchase History
TRUNCATE TABLE partner_transactions;
TRUNCATE TABLE purchase_history;

-- User Profiles
TRUNCATE TABLE partner_profiles;
TRUNCATE TABLE customer_profiles;

-- OTP verifications (user-related)
TRUNCATE TABLE otp_verifications;

-- ================================================================
-- STEP 4: DELETE PARTNER & CUSTOMER USERS (Preserve admins)
-- ================================================================

DELETE FROM users
WHERE role_id IN (
    SELECT id FROM roles WHERE name IN ('partner', 'customer')
);

-- ================================================================
-- STEP 5: CLEAN UP BRAND PARTNER REFERENCES
-- (Brands are preserved but partner_id should be nullified)
-- ================================================================

UPDATE brands SET partner_id = NULL WHERE partner_id IS NOT NULL;

-- ================================================================
-- STEP 6: RE-ENABLE FOREIGN KEY CHECKS
-- ================================================================

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- STEP 7: RESET AUTO-INCREMENT (Optional - for cleaner IDs)
-- Uncomment if you want IDs to start from 1 again
-- ================================================================

-- ALTER TABLE users AUTO_INCREMENT = 1;
-- ALTER TABLE partner_profiles AUTO_INCREMENT = 1;
-- ALTER TABLE customer_profiles AUTO_INCREMENT = 1;
-- ALTER TABLE wallets AUTO_INCREMENT = 1;
-- ALTER TABLE wallet_transactions AUTO_INCREMENT = 1;
-- ALTER TABLE partner_transactions AUTO_INCREMENT = 1;
-- ALTER TABLE purchase_history AUTO_INCREMENT = 1;
-- ALTER TABLE withdrawal_requests AUTO_INCREMENT = 1;
-- ALTER TABLE commission_batches AUTO_INCREMENT = 1;
-- ALTER TABLE commission_ledgers AUTO_INCREMENT = 1;
-- ALTER TABLE commission_settlements AUTO_INCREMENT = 1;
-- ALTER TABLE commission_transactions AUTO_INCREMENT = 1;
-- ALTER TABLE profit_batches AUTO_INCREMENT = 1;
-- ALTER TABLE profit_sharing_distributions AUTO_INCREMENT = 1;
-- ALTER TABLE customer_queries AUTO_INCREMENT = 1;
-- ALTER TABLE query_replies AUTO_INCREMENT = 1;

-- ================================================================
-- STEP 8: POST-DELETION VERIFICATION
-- ================================================================

SELECT '========== POST-DELETION VERIFICATION ==========' AS '';

SELECT 'Remaining users (should be admins only)' AS info, COUNT(*) AS count FROM users;

SELECT 'Admin users preserved' AS info, COUNT(*) AS count
FROM users u
JOIN roles r ON u.role_id = r.id
WHERE r.name IN ('super_admin', 'admin', 'moderator');

SELECT 'Partner profiles (should be 0)' AS info, COUNT(*) AS count FROM partner_profiles;
SELECT 'Customer profiles (should be 0)' AS info, COUNT(*) AS count FROM customer_profiles;
SELECT 'Partner transactions (should be 0)' AS info, COUNT(*) AS count FROM partner_transactions;
SELECT 'Wallets (should be 0)' AS info, COUNT(*) AS count FROM wallets;
SELECT 'Commission batches (should be 0)' AS info, COUNT(*) AS count FROM commission_batches;

SELECT 'Brands preserved' AS info, COUNT(*) AS count FROM brands;
SELECT 'Categories preserved' AS info, COUNT(*) AS count FROM categories;
SELECT 'Roles preserved' AS info, COUNT(*) AS count FROM roles;

SELECT '================================================' AS '';
SELECT 'DATA FLUSH COMPLETE!' AS '';
SELECT '================================================' AS '';
