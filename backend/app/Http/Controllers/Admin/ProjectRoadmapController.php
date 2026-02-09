<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Project Roadmap Controller
 * 
 * This controller serves the Project Roadmap page - a living document
 * that tracks all features: completed, in-progress, and planned.
 * 
 * IMPORTANT: This is a reference document for MegaMind (AI assistant)
 * and the development team. Update this file when features are completed.
 * 
 * Access: Super Admin only
 * 
 * Last Updated: 2026-02-09
 */
class ProjectRoadmapController extends Controller
{
    /**
     * Display the Project Roadmap
     */
    public function index()
    {
        // Define all project modules and their status
        $roadmap = $this->getRoadmapData();
        
        return view('admin.roadmap.index', compact('roadmap'));
    }

    /**
     * Get the complete roadmap data
     * 
     * Status values:
     * - 'done' = Completed and tested
     * - 'in_progress' = Currently being worked on
     * - 'planned' = Scheduled for future development
     * - 'not_started' = Defined but not yet scheduled
     * 
     * @return array
     */
    private function getRoadmapData(): array
    {
        return [
            // ===== ADMIN PANEL =====
            'admin_panel' => [
                'title' => 'Admin Panel',
                'description' => 'Backend management system for administrators',
                'overall_progress' => 95,
                'features' => [
                    [
                        'name' => 'Authentication & Login',
                        'status' => 'done',
                        'description' => 'Admin login with role-based access',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'Dashboard',
                        'status' => 'done',
                        'description' => 'Statistics overview, quick stats, recent activity',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'User Management',
                        'status' => 'done',
                        'description' => 'CRUD for users, role assignment, status toggle',
                        'completed_date' => '2025-10-15',
                    ],
                    [
                        'name' => 'Roles & Permissions',
                        'status' => 'done',
                        'description' => '5-tier role system with granular permissions',
                        'completed_date' => '2025-11-13',
                    ],
                    [
                        'name' => 'Hero Slides Management',
                        'status' => 'done',
                        'description' => 'CRUD with drag-drop reorder, scheduling, preview',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'Categories Management',
                        'status' => 'done',
                        'description' => 'CRUD with file upload, SEO fields, icon preview',
                        'completed_date' => '2025-12-01',
                    ],
                    [
                        'name' => 'Brands Management',
                        'status' => 'done',
                        'description' => 'CRUD with file upload, commission rates, featured status',
                        'completed_date' => '2025-10-15',
                    ],
                    [
                        'name' => 'Promotions Management',
                        'status' => 'done',
                        'description' => 'CRUD with discount types, drag-drop reorder',
                        'completed_date' => '2025-10-20',
                    ],
                    [
                        'name' => 'Customer Management',
                        'status' => 'done',
                        'description' => 'View customers, transactions, wallet details',
                        'completed_date' => '2025-10-20',
                    ],
                    [
                        'name' => 'Partner Management',
                        'status' => 'done',
                        'description' => 'View partners, pending approvals, transactions',
                        'completed_date' => '2025-10-22',
                    ],
                    [
                        'name' => 'Customer Queries',
                        'status' => 'done',
                        'description' => 'View and reply to customer support queries',
                        'completed_date' => '2025-10-14',
                    ],
                    [
                        'name' => 'Commission Management',
                        'status' => 'done',
                        'description' => 'Dashboard, batches, settlements, adjustments',
                        'completed_date' => '2025-11-15',
                    ],
                    [
                        'name' => 'Withdrawal Settings',
                        'status' => 'done',
                        'description' => 'Configure limits, enable/disable withdrawals',
                        'completed_date' => '2025-11-12',
                    ],
                    [
                        'name' => 'Email Settings',
                        'status' => 'done',
                        'description' => 'Configure SMTP and email templates',
                        'completed_date' => '2025-10-14',
                    ],
                    [
                        'name' => 'WhatsApp Settings',
                        'status' => 'done',
                        'description' => 'Configure WhatsApp OTP integration',
                        'completed_date' => '2025-11-30',
                    ],
                    [
                        'name' => 'Project Roadmap Page',
                        'status' => 'done',
                        'description' => 'This page - project status tracking',
                        'completed_date' => '2026-02-09',
                    ],
                ],
            ],

            // ===== WITHDRAWAL SYSTEM =====
            'withdrawal_system' => [
                'title' => 'Withdrawal System',
                'description' => 'Customer withdrawal requests and admin processing',
                'overall_progress' => 40,
                'features' => [
                    [
                        'name' => 'Database Schema',
                        'status' => 'done',
                        'description' => 'withdrawal_settings, withdrawal_requests tables',
                        'completed_date' => '2025-11-12',
                    ],
                    [
                        'name' => 'Models',
                        'status' => 'done',
                        'description' => 'WithdrawalSettings, WithdrawalRequest models',
                        'completed_date' => '2025-11-12',
                    ],
                    [
                        'name' => 'Fraud Detection Service',
                        'status' => 'done',
                        'description' => '7 risk checks with scoring system',
                        'completed_date' => '2025-11-12',
                    ],
                    [
                        'name' => 'Admin Withdrawal List',
                        'status' => 'in_progress',
                        'description' => 'List pending/completed withdrawals with filters',
                        'notes' => 'View exists but needs controller methods',
                    ],
                    [
                        'name' => 'Admin Approve/Reject',
                        'status' => 'not_started',
                        'description' => 'Approve with bank reference, reject with reason',
                    ],
                    [
                        'name' => 'Customer TPIN Verification',
                        'status' => 'not_started',
                        'description' => 'Require TPIN before withdrawal request',
                    ],
                    [
                        'name' => 'Wallet Deduction on Request',
                        'status' => 'not_started',
                        'description' => 'Deduct balance immediately when request created',
                    ],
                    [
                        'name' => 'Refund on Rejection',
                        'status' => 'not_started',
                        'description' => 'Auto-refund wallet when request rejected/cancelled',
                    ],
                    [
                        'name' => 'Customer Cancel Withdrawal',
                        'status' => 'not_started',
                        'description' => 'Allow customers to cancel pending requests',
                    ],
                    [
                        'name' => 'Email Notifications',
                        'status' => 'planned',
                        'description' => 'Email on request/approve/reject',
                    ],
                ],
            ],

            // ===== CUSTOMER PORTAL =====
            'customer_portal' => [
                'title' => 'Customer Portal',
                'description' => 'Customer-facing dashboard and features',
                'overall_progress' => 75,
                'features' => [
                    [
                        'name' => 'Firebase Authentication',
                        'status' => 'done',
                        'description' => 'Phone number login with Firebase',
                        'completed_date' => '2025-10-14',
                    ],
                    [
                        'name' => 'OTP Verification System',
                        'status' => 'done',
                        'description' => 'WhatsApp → Firebase → Ufone cascade',
                        'completed_date' => '2025-11-30',
                    ],
                    [
                        'name' => 'Dashboard',
                        'status' => 'done',
                        'description' => 'Wallet balance, recent transactions, brands',
                        'completed_date' => '2025-10-15',
                    ],
                    [
                        'name' => 'Profile Management',
                        'status' => 'done',
                        'description' => 'Update profile, TPIN setup, bank details',
                        'completed_date' => '2025-11-11',
                    ],
                    [
                        'name' => 'Wallet View',
                        'status' => 'done',
                        'description' => 'Balance, transactions, withdrawal form',
                        'completed_date' => '2025-11-05',
                    ],
                    [
                        'name' => 'Purchase History',
                        'status' => 'done',
                        'description' => 'View all cashback transactions',
                        'completed_date' => '2025-11-12',
                    ],
                    [
                        'name' => 'Bank Details with OTP',
                        'status' => 'done',
                        'description' => 'Add/update bank details with OTP verification',
                        'completed_date' => '2025-11-11',
                    ],
                    [
                        'name' => 'TPIN Setup',
                        'status' => 'done',
                        'description' => '4-digit TPIN for secure transactions',
                        'completed_date' => '2025-11-11',
                    ],
                    [
                        'name' => 'Withdrawal Request Form',
                        'status' => 'in_progress',
                        'description' => 'Submit withdrawal with limit validation',
                        'notes' => 'Form exists, needs TPIN integration',
                    ],
                    [
                        'name' => 'Referral System',
                        'status' => 'planned',
                        'description' => 'Refer friends and earn bonus',
                    ],
                    [
                        'name' => 'Push Notifications',
                        'status' => 'planned',
                        'description' => 'Notifications for cashback, withdrawals',
                    ],
                ],
            ],

            // ===== PARTNER PORTAL =====
            'partner_portal' => [
                'title' => 'Partner Portal',
                'description' => 'Partner/merchant management system',
                'overall_progress' => 60,
                'features' => [
                    [
                        'name' => 'Partner Registration',
                        'status' => 'done',
                        'description' => 'Business registration and approval flow',
                        'completed_date' => '2025-10-22',
                    ],
                    [
                        'name' => 'Partner Dashboard',
                        'status' => 'done',
                        'description' => 'Overview of sales and commissions',
                        'completed_date' => '2025-10-22',
                    ],
                    [
                        'name' => 'Transaction Recording',
                        'status' => 'done',
                        'description' => 'Record customer purchases',
                        'completed_date' => '2025-11-13',
                    ],
                    [
                        'name' => 'Commission Tracking',
                        'status' => 'done',
                        'description' => 'View earned and pending commissions',
                        'completed_date' => '2025-11-14',
                    ],
                    [
                        'name' => 'Settlement History',
                        'status' => 'done',
                        'description' => 'View settlement payments received',
                        'completed_date' => '2025-11-15',
                    ],
                    [
                        'name' => 'QR Code Payments',
                        'status' => 'planned',
                        'description' => 'Generate QR for customer scanning',
                    ],
                    [
                        'name' => 'Partner Reports',
                        'status' => 'planned',
                        'description' => 'Detailed sales and analytics reports',
                    ],
                ],
            ],

            // ===== WEBSITE FRONTEND =====
            'website_frontend' => [
                'title' => 'Website Frontend',
                'description' => 'Public-facing marketing website',
                'overall_progress' => 100,
                'features' => [
                    [
                        'name' => 'Hero Slider',
                        'status' => 'done',
                        'description' => 'Dynamic carousel with Swiper.js',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'Brands Section',
                        'status' => 'done',
                        'description' => 'Category icons and brand carousel',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'Promotions Grid',
                        'status' => 'done',
                        'description' => 'Dynamic promotions from database',
                        'completed_date' => '2025-10-20',
                    ],
                    [
                        'name' => 'How It Works',
                        'status' => 'done',
                        'description' => 'Explanation for customers and vendors',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'Dashboard Preview',
                        'status' => 'done',
                        'description' => 'Interactive mockup of customer dashboard',
                        'completed_date' => '2025-10-11',
                    ],
                    [
                        'name' => 'Mobile Responsive',
                        'status' => 'done',
                        'description' => 'Optimized for all screen sizes',
                        'completed_date' => '2025-10-11',
                    ],
                ],
            ],

            // ===== FUTURE ROADMAP =====
            'future_features' => [
                'title' => 'Future Roadmap',
                'description' => 'Features planned for future development',
                'overall_progress' => 0,
                'features' => [
                    [
                        'name' => 'Mobile App (Customer)',
                        'status' => 'planned',
                        'description' => 'Native iOS/Android app for customers',
                    ],
                    [
                        'name' => 'Mobile App (Partner)',
                        'status' => 'planned',
                        'description' => 'Native app for partners to record sales',
                    ],
                    [
                        'name' => 'Loyalty Points System',
                        'status' => 'planned',
                        'description' => 'Points-based rewards in addition to cashback',
                    ],
                    [
                        'name' => 'Multi-language Support',
                        'status' => 'planned',
                        'description' => 'Urdu and English language options',
                    ],
                    [
                        'name' => 'Advanced Analytics',
                        'status' => 'planned',
                        'description' => 'Detailed reports and insights dashboard',
                    ],
                    [
                        'name' => 'API for Partners',
                        'status' => 'planned',
                        'description' => 'REST API for partner integrations',
                    ],
                    [
                        'name' => 'Automated Settlements',
                        'status' => 'planned',
                        'description' => 'Auto-process settlements on schedule',
                    ],
                ],
            ],
        ];
    }
}
