<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#ffffff">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="light-content">
    <meta name="color-scheme" content="light only">
    <title>BixCash - Shop to Earn</title>

    <!-- Performance Optimization: Resource Hints -->
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link rel="preload" href="/images/logos/logos-01.png" as="image">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Content Loading Styles -->
    <style>
        /* Loading spinner animation */
        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Loading states */
        .loading-slide {
            background: linear-gradient(135deg, var(--bix-green) 0%, var(--bix-navy) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 500px;
        }

        .loading-categories,
        .loading-brands {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        /* Smooth transitions for dynamic content */
        .category-item,
        .brand-slide {
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        .category-item:nth-child(1) { animation-delay: 0.1s; }
        .category-item:nth-child(2) { animation-delay: 0.2s; }
        .category-item:nth-child(3) { animation-delay: 0.3s; }
        .category-item:nth-child(4) { animation-delay: 0.4s; }
        .category-item:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced hover effects */
        .category-item:hover {
            transform: translateY(-5px) scale(1.02);
        }

        .brand-slide:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Loading shimmer effect */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Error states */
        .error-message {
            background: #fee;
            color: #c53030;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #fed7d7;
            text-align: center;
            margin: 1rem 0;
        }

        /* Navigation active state enhancement */
        nav a.active {
            color: var(--bix-green);
            font-weight: 600;
            position: relative;
        }

        nav a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--bix-green);
            border-radius: 1px;
        }

        /* Header Layout Override */
        .main-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        .main-header nav {
            display: flex !important;
            margin-left: auto !important;
            margin-right: 2rem !important;
        }

        .auth-btn {
            background: linear-gradient(135deg, #93db4d 0%, #76d37a 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(147, 219, 77, 0.25);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .auth-btn:hover {
            background: linear-gradient(135deg, #85c441 0%, #68c26a 100%);
            box-shadow: 0 4px 15px rgba(147, 219, 77, 0.4);
            transform: translateY(-1px);
        }

        .auth-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(147, 219, 77, 0.25);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .loading-spinner {
                width: 30px;
                height: 30px;
            }

            .category-item {
                margin: 0.25rem;
                padding: 0.75rem;
            }

            .auth-btn {
                font-size: 0.7rem;
                padding: 0.4rem 0.8rem;
            }

            .main-header nav {
                margin-right: 1rem !important;
            }
        }

        /* Navigation Icons - White Background Fix */
        .nav-button {
            background: white !important;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .nav-button img.dashboard-icon-img {
            background: white;
            border-radius: 8px;
            padding: 4px;
        }

        /* Category Carousel Navigation - Clean Design Matching Brands Section */
        .category-carousel-wrapper {
            position: relative;
            width: 100%;
            padding: 0 4rem; /* Add padding for arrow space */
        }

        .category-nav-buttons {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            z-index: 20;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .category-button-prev,
        .category-button-next {
            color: var(--bix-dark-blue);
            background: none;
            width: 40px;
            height: 40px;
            margin-top: -20px;
            z-index: 20;
            transition: color 0.3s ease;
            position: absolute;
            top: 50%;
            pointer-events: auto;
            cursor: pointer;
        }

        .category-button-prev:hover,
        .category-button-next:hover {
            color: var(--bix-light-green);
        }

        .category-button-prev {
            left: 10px;
        }

        .category-button-next {
            right: 10px;
        }

        .category-button-prev:after,
        .category-button-next:after {
            font-size: 24px;
            font-weight: bold;
        }

        /* Responsive adjustments for category navigation */
        @media (max-width: 768px) {
            .category-carousel-wrapper {
                padding: 0 3rem;
            }

            .category-button-prev,
            .category-button-next {
                width: 35px;
                height: 35px;
                margin-top: -17.5px;
            }

            .category-button-prev {
                left: -10px;
            }

            .category-button-next {
                right: -10px;
            }

            .category-button-prev:after,
            .category-button-next:after {
                font-size: 20px;
            }
        }
        /* Promotions Loading Styles */
        .promotions-loading {
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 4rem 2rem;
            min-height: 200px;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border: 0.25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        .text-success {
            color: var(--bix-green) !important;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .promotions-error {
            text-align: center;
            padding: 2rem;
            color: #666;
            grid-column: 1 / -1;
        }

        .promotions-error button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: var(--bix-green);
            color: var(--bix-dark-blue);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .promotions-error button:hover {
            background: var(--bix-light-green);
            transform: translateY(-2px);
        }

        /* =================================
           COMPREHENSIVE MOBILE RESPONSIVENESS SYSTEM
           ================================= */

        /* Enhanced Responsive Breakpoint System */
        :root {
            /* Fluid Typography Using CSS Clamp */
            --text-xs: clamp(0.75rem, 0.7rem + 0.25vw, 0.875rem);
            --text-sm: clamp(0.875rem, 0.8rem + 0.375vw, 1rem);
            --text-base: clamp(1rem, 0.9rem + 0.5vw, 1.125rem);
            --text-lg: clamp(1.125rem, 1rem + 0.625vw, 1.25rem);
            --text-xl: clamp(1.25rem, 1.1rem + 0.75vw, 1.5rem);
            --text-2xl: clamp(1.5rem, 1.3rem + 1vw, 2rem);
            --text-3xl: clamp(1.875rem, 1.5rem + 1.875vw, 3rem);
            --text-4xl: clamp(2.25rem, 1.8rem + 2.25vw, 4rem);
            --text-5xl: clamp(3rem, 2rem + 5vw, 5.5rem);

            /* Fluid Spacing System */
            --space-xs: clamp(0.25rem, 0.2rem + 0.25vw, 0.5rem);
            --space-sm: clamp(0.5rem, 0.4rem + 0.5vw, 1rem);
            --space-md: clamp(1rem, 0.8rem + 1vw, 2rem);
            --space-lg: clamp(1.5rem, 1rem + 2.5vw, 4rem);
            --space-xl: clamp(2rem, 1.5rem + 2.5vw, 6rem);
            --space-2xl: clamp(3rem, 2rem + 5vw, 8rem);
        }

        /* MOBILE NAVIGATION SYSTEM */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 44px;
            height: 44px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            position: relative;
            z-index: 1001;
            /* Enhanced touch target */
            min-width: 44px;
            min-height: 44px;
            touch-action: manipulation;
        }

        .mobile-menu-btn:hover {
            background-color: rgba(2, 28, 71, 0.1);
        }

        .mobile-menu-btn:focus {
            outline: 2px solid var(--bix-green);
            outline-offset: 2px;
        }

        /* Hamburger Lines */
        .hamburger-line {
            display: block;
            width: 24px;
            height: 3px;
            background-color: var(--bix-dark-blue);
            margin: 3px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        /* Hamburger Animation - X when active */
        .mobile-menu-btn.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: scale(0);
        }

        .mobile-menu-btn.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Mobile Navigation Overlay - IMPROVED: Smoother animation without "popping" */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(2, 28, 71, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            /* FIXED: Removed translateY animation to prevent "popping" */
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Navigation Content - IMPROVED: Gentler scale animation */
        .mobile-nav-content {
            position: relative;
            width: 90%;
            max-width: 400px;
            max-height: 90vh;
            background: var(--bix-white);
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            /* FIXED: Reduced scale change and smoother easing */
            transform: scale(0.95);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .mobile-nav-overlay.active .mobile-nav-content {
            transform: scale(1);
        }

        /* Mobile Navigation Header */
        .mobile-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 2px solid var(--bix-light-gray-2);
        }

        .mobile-nav-logo {
            height: 40px;
            width: auto;
        }

        .mobile-nav-close {
            width: 44px;
            height: 44px;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.3s ease;
            font-size: 24px;
            color: var(--bix-dark-blue);
        }

        .mobile-nav-close:hover {
            background-color: var(--bix-light-gray-2);
        }

        .mobile-nav-close:focus {
            outline: 2px solid var(--bix-green);
            outline-offset: 2px;
        }

        /* Mobile Navigation Menu */
        .mobile-nav {
            padding: 1rem 0 2rem;
        }

        .mobile-nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .mobile-nav li {
            margin: 0;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: var(--bix-dark-blue);
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 0;
            position: relative;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.active {
            background-color: var(--bix-light-gray-1);
            color: var(--bix-green);
            transform: translateX(8px);
        }

        .mobile-nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--bix-green);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .mobile-nav-link:hover::before,
        .mobile-nav-link.active::before {
            transform: scaleY(1);
        }

        /* Mobile Authentication Button */
        .mobile-nav-auth {
            padding: 1rem 1.5rem 0;
            border-top: 2px solid var(--bix-light-gray-2);
            margin-top: 1rem;
        }

        .mobile-auth-btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #93db4d 0%, #76d37a 100%);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(147, 219, 77, 0.3);
            transition: all 0.3s ease;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-auth-btn:hover {
            background: linear-gradient(135deg, #85c441 0%, #68c26a 100%);
            box-shadow: 0 6px 20px rgba(147, 219, 77, 0.4);
            transform: translateY(-2px);
        }

        /* =================================
           RESPONSIVE NAVIGATION CONTROL
           ================================= */

        /* Desktop navigation visible by default */
        .desktop-nav,
        .desktop-auth {
            display: flex;
        }

        /* Hide mobile elements on desktop - use media query for proper specificity */
        @media (min-width: 769px) {
            .mobile-menu-btn,
            .mobile-nav-overlay {
                display: none;
            }
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            /* Show mobile menu button */
            .mobile-menu-btn {
                display: flex;
            }

            /* Hide desktop navigation */
            .desktop-nav,
            .desktop-auth {
                display: none;
            }

            /* Enable mobile navigation overlay */
            .mobile-nav-overlay {
                display: flex;
            }

            /* Adjust header padding on mobile */
            .main-header {
                padding: 1rem 1.5rem;
            }

            /* Smaller logo on mobile */
            .main-header .logo img {
                height: 45px;
            }
        }

        /* Ultra-small mobile adjustments */
        @media (max-width: 480px) {
            .mobile-nav-content {
                width: 95%;
                margin: 1rem;
            }

            .mobile-nav-header {
                padding: 1rem 1rem 0.5rem;
            }

            .mobile-nav-logo {
                height: 35px;
            }

            .mobile-nav-link {
                padding: 0.8rem 1rem;
                font-size: 1rem;
            }

            .mobile-auth-btn {
                font-size: 1rem;
                padding: 0.8rem;
            }
        }

        /* Prevent body scroll when mobile menu is open */
        body.mobile-menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        /* Enhanced touch targets for mobile */
        @media (max-width: 768px) {
            .mobile-nav-link,
            .mobile-auth-btn,
            .mobile-menu-btn,
            .mobile-nav-close {
                min-height: 44px; /* Apple's recommended touch target size */
                min-width: 44px;
            }

            /* CRITICAL: Force mobile menu button to show */
            .mobile-menu-btn {
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                align-items: center !important;
                width: 44px !important;
                height: 44px !important;
                background: none !important;
                border: none !important;
                cursor: pointer !important;
                padding: 8px !important;
                border-radius: 8px !important;
                position: relative !important;
                z-index: 1001 !important;
                transition: background-color 0.3s ease !important;
            }

            /* =================================
               ENHANCED RESPONSIVE BREAKPOINT SYSTEM
               ================================= */

            /* IMPROVED MOBILE BREAKPOINTS - Fixed hero responsiveness issues */

            /* Ultra-small mobile (320px and below) */
            @media (max-width: 320px) {
                body {
                    padding-top: 70px;
                }

                .main-header {
                    padding: var(--space-xs) var(--space-sm);
                    height: 70px; /* Fixed header height */
                }

                .main-header .logo img {
                    height: 35px;
                }

                /* FIXED: Simplified hero height calculation */
                .hero-slider {
                    min-height: 400px; /* Safe minimum */
                    height: 60vh; /* Simple viewport-based height */
                    margin-top: 0; /* Removed complex margin/padding calculations */
                    padding-top: 0;
                    /* Ensure proper positioning */
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                }

                /* Fluid typography for ultra-small screens */
                .brands-section h2,
                .promotions-title,
                .dashboard-main-title,
                .contact-title,
                .how-it-works-section .main-heading {
                    font-size: var(--text-3xl);
                    line-height: 1.1;
                }
            }

            /* Mobile portrait (321px - 480px) */
            @media (min-width: 321px) and (max-width: 480px) {
                body {
                    padding-top: 80px;
                }

                .main-header {
                    padding: var(--space-sm) var(--space-md);
                    height: 80px; /* Fixed header height */
                }

                .main-header .logo img {
                    height: 40px;
                }

                /* FIXED: Simplified hero height calculation */
                .hero-slider {
                    min-height: 450px; /* Safe minimum */
                    height: 65vh; /* Simple viewport-based height */
                    margin-top: 0; /* Removed complex margin/padding calculations */
                    padding-top: 0;
                    /* Ensure proper positioning */
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                }

                /* Enhanced typography */
                .brands-section h2,
                .promotions-title,
                .dashboard-main-title,
                .contact-title,
                .how-it-works-section .main-heading {
                    font-size: var(--text-4xl);
                    line-height: 1.1;
                }
            }

            /* Mobile landscape (481px - 767px) */
            @media (min-width: 481px) and (max-width: 767px) {
                body {
                    padding-top: 90px;
                }

                .main-header {
                    height: 90px; /* Fixed header height */
                }

                /* FIXED: Simplified hero height calculation */
                .hero-slider {
                    min-height: 500px; /* Safe minimum */
                    height: 70vh; /* Simple viewport-based height */
                    margin-top: 0; /* Removed complex margin/padding calculations */
                    padding-top: 0;
                    /* Ensure proper positioning */
                    position: relative;
                    width: 100%;
                    overflow: hidden;
                }

                /* Typography scaling */
                .brands-section h2,
                .promotions-title,
                .dashboard-main-title,
                .contact-title,
                .how-it-works-section .main-heading {
                    font-size: var(--text-4xl);
                }
            }

            /* Tablet portrait (768px - 1023px) */
            @media (min-width: 768px) and (max-width: 1023px) {
                .brands-section h2,
                .promotions-title,
                .dashboard-main-title,
                .contact-title,
                .how-it-works-section .main-heading {
                    font-size: var(--text-5xl);
                }
            }

            /* Tablet landscape & Small Desktop (1024px - 1199px) */
            @media (min-width: 1024px) and (max-width: 1199px) {
                .brands-section h2,
                .promotions-title,
                .dashboard-main-title,
                .contact-title,
                .how-it-works-section .main-heading {
                    font-size: var(--text-5xl);
                }
            }

            /* Large Desktop (1200px+) */
            @media (min-width: 1200px) {
                .brands-section h2,
                .promotions-title,
                .dashboard-main-title,
                .contact-title,
                .how-it-works-section .main-heading {
                    font-size: 5.5rem;
                }
            }

            /* CRITICAL: Mobile Navigation Control */
            @media (max-width: 768px) {
                .main-header .desktop-nav,
                .main-header nav.desktop-nav,
                nav.desktop-nav,
                .desktop-nav,
                .desktop-auth {
                    display: none !important;
                }

                .mobile-nav-overlay {
                    display: flex !important;
                }

                /* Enhanced touch targets for mobile */
                .mobile-menu-btn {
                    display: flex !important;
                    flex-direction: column !important;
                    justify-content: center !important;
                    align-items: center !important;
                    width: 44px !important;
                    height: 44px !important;
                    background: none !important;
                    border: none !important;
                    cursor: pointer !important;
                    padding: 8px !important;
                    border-radius: 8px !important;
                    position: relative !important;
                    z-index: 1001 !important;
                    transition: background-color 0.3s ease !important;
                    touch-action: manipulation !important;
                }

                /* iOS Safari zoom prevention */
                .form-input,
                .form-textarea,
                input[type="text"],
                input[type="email"],
                input[type="tel"],
                textarea {
                    font-size: 16px !important;
                    transform: translateZ(0);
                    -webkit-font-smoothing: antialiased;
                }

                /* Enhanced touch-friendly spacing */
                .category-item,
                .brand-slide,
                .promotion-card,
                .nav-button,
                .social-link {
                    min-height: 44px;
                    min-width: 44px;
                    touch-action: manipulation;
                }

                /* Mobile-optimized grid systems */
                .promotions-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: var(--space-md);
                    padding: 0 var(--space-sm);
                }

                .category-container {
                    display: grid !important;
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: var(--space-md);
                    padding: 0 var(--space-sm);
                    justify-items: center;
                }

                /* Center the last item if odd number of categories */
                .category-item:last-child:nth-child(odd) {
                    grid-column: 1 / -1;
                    justify-self: center;
                }

                /* Mobile-first spacing */
                .brands-section,
                .promotions-section,
                .how-it-works-section,
                .customer-dashboard-section,
                .contact-section {
                    padding: var(--space-xl) var(--space-md);
                }
            }

            /* Show mobile overlay */
            .mobile-nav-overlay {
                display: flex !important;
            }

            /* =================================
               PERFORMANCE & ACCESSIBILITY OPTIMIZATIONS
               ================================= */

            /* Reduced motion support for accessibility */
            @media (prefers-reduced-motion: reduce) {
                .chat-dots span,
                .green-sparkle,
                .category-item,
                .brand-slide,
                .promotion-card,
                .dashboard-card,
                .social-link,
                .nav-button,
                .mobile-menu-btn,
                .mobile-nav-overlay,
                .mobile-nav-content {
                    animation: none !important;
                    transition-duration: 0.01ms !important;
                }

                .dashboard-card:hover,
                .promotion-card:hover,
                .social-link:hover,
                .nav-button:hover,
                .form-submit-btn:hover,
                .category-item:hover,
                .brand-slide:hover {
                    transform: none !important;
                }
            }

            /* Enhanced focus indicators for accessibility */
            .mobile-menu-btn:focus,
            .mobile-nav-close:focus,
            .mobile-nav-link:focus,
            .mobile-auth-btn:focus,
            .category-item:focus,
            .brand-slide:focus,
            .promotion-card:focus {
                outline: 3px solid var(--bix-green);
                outline-offset: 2px;
            }

            /* Screen reader support */
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            /* High contrast mode support */
            @media (prefers-contrast: high) {
                .mobile-menu-btn,
                .mobile-nav-link,
                .category-item,
                .brand-slide,
                .promotion-card {
                    border: 2px solid;
                }
            }

            /* Performance optimizations */
            .category-item,
            .brand-slide,
            .promotion-card,
            .dashboard-card,
            .mobile-nav-overlay,
            .mobile-nav-content {
                will-change: transform;
                contain: layout style paint;
            }

            /* GPU acceleration for smooth animations */
            .mobile-nav-overlay,
            .mobile-nav-content,
            .hamburger-line {
                transform: translateZ(0);
                backface-visibility: hidden;
                perspective: 1000px;
            }

            /* Enhanced mobile carousel touch gestures */
            .brands-carousel-container,
            .swiper-container {
                touch-action: pan-y pinch-zoom;
            }

            /* Mobile-specific image optimization */
            @media (max-width: 768px) {
                img {
                    image-rendering: -webkit-optimize-contrast;
                    image-rendering: crisp-edges;
                }
            }

            /* REMOVED: Dark mode support to maintain consistent BixCash branding */
            /* Forces light theme on all devices regardless of system preference */
        }
    </style>
</head>
<body>

    <header class="main-header">
        <a href="/" class="logo">
            <img src="/images/logos/logos-01.png" alt="BixCash Logo">
        </a>

        <!-- Desktop Navigation -->
        <nav class="desktop-nav">
            <ul>
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#brands">Brands</a></li>
                <li><a href="#how-it-works">How It Works</a></li>
                <li><a href="#partner">Partner with us</a></li>
                <li><a href="#promotions">Promotions</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
        </nav>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle mobile menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <a href="{{ route('login') }}" class="auth-btn desktop-auth">Sign In</a>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobile-nav-overlay">
        <div class="mobile-nav-content">
            <div class="mobile-nav-header">
                <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="mobile-nav-logo">
                <button class="mobile-nav-close" id="mobile-nav-close" aria-label="Close mobile menu">
                    <span>&times;</span>
                </button>
            </div>
            <nav class="mobile-nav">
                <ul>
                    <li><a href="#home" class="mobile-nav-link">Home</a></li>
                    <li><a href="#brands" class="mobile-nav-link">Brands</a></li>
                    <li><a href="#how-it-works" class="mobile-nav-link">How It Works</a></li>
                    <li><a href="#partner" class="mobile-nav-link">Partner with us</a></li>
                    <li><a href="#promotions" class="mobile-nav-link">Promotions</a></li>
                    <li><a href="#contact" class="mobile-nav-link">Contact Us</a></li>
                </ul>
                <div class="mobile-nav-auth">
                    <a href="{{ route('login') }}" class="mobile-auth-btn">Sign In</a>
                </div>
            </nav>
        </div>
    </div>

    <section id="home" class="hero-slider">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Slides will be injected here by JavaScript -->
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section id="brands" class="brands-section">
        <div class="brands-container">
            <div class="category-carousel-wrapper">
                <div class="category-container">
                    <!-- Categories will be injected by JavaScript -->
                </div>
                <!-- Navigation buttons (hidden by default, shown only when carousel is active) -->
                <div class="category-nav-buttons" style="display: none;">
                    <div class="swiper-button-prev category-button-prev"></div>
                    <div class="swiper-button-next category-button-next"></div>
                </div>
            </div>
            <h2><span class="green-text">Explore</span> Brands</h2>
            <div class="swiper brands-carousel-container">
                <div class="swiper-wrapper">
                    <!-- Brands will be injected by JavaScript -->
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works-section">
        <h2 class="main-heading"><span class="green-text">How it</span> Works</h2>

        <div class="content-wrapper">
            <div class="column">
                <h3>For Customers</h3>
                <ol>
                    <li><span class="number-circle">1</span> Shop from BixCash-selected vendors.</li>
                    <li><span class="number-circle">2</span> Vendor enters your number at checkout.</li>
                    <li><span class="number-circle">3</span> Confirm transaction &rarr; Earn your share of profit.</li>
                </ol>
            </div>
            <div class="column">
                <h3 class="navy-blue">For Vendors</h3>
                <ol>
                    <li><span class="number-circle">1</span> Register with BixCash (exclusive spot in your category).</li>
                    <li><span class="number-circle">2</span> Get guaranteed BixCash customer traffic.</li>
                    <li><span class="number-circle">3</span> Share small commission &rarr; Earn loyal customers + share in profit pool.</li>
                </ol>
            </div>
        </div>

        <h2 class="main-heading"><span class="green-text">Why</span> BixCash</h2>

        <div class="content-wrapper">
            <div class="column">
                <h3>For Customers</h3>
                <ol>
                    <li><span class="number-circle">1</span> Not cashback. Not discount.</li>
                    <li><span class="number-circle">2</span> Real profit-sharing.</li>
                    <li><span class="number-circle">3</span> Every purchase grows your earnings.</li>
                </ol>
            </div>
            <div class="column">
                <h3 class="navy-blue">For Vendors</h3>
                <ol>
                    <li><span class="number-circle">1</span> Exclusive rights in your category in your area.</li>
                    <li><span class="number-circle">2</span> We send you customers directly.</li>
                    <li><span class="number-circle">3</span> Profit share makes customers return again & again.</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Customer Dashboard Section -->
    <section id="partner" class="customer-dashboard-section">
        <div class="dashboard-container">
            <!-- Dashboard Title -->
            <h2 class="dashboard-main-title">
                <span class="dashboard-green">Customer</span> <span class="dashboard-blue">Dashboard</span>
            </h2>

            <!-- Dashboard Content Grid -->
            <div class="dashboard-content-grid">

                <!-- Left Side: Dashboard Interface -->
                <div class="dashboard-left-content">
                    <!-- Computer/Monitor with Dashboard -->
                    <div class="monitor-container">
                        <div class="monitor-screen">
                            <!-- User Profile Circle -->
                            <div class="dashboard-user-profile">
                                <div class="user-avatar-large">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                                <div class="user-stars">
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                    <span class="star">★</span>
                                    <span class="star filled">★</span>
                                </div>
                            </div>

                            <!-- Chat Bubbles -->
                            <div class="chat-elements">
                                <div class="chat-bubble-main">
                                    <div class="chat-dots">
                                        <span></span><span></span><span></span>
                                    </div>
                                </div>
                                <div class="activity-feed-mock">
                                    <div class="feed-item">
                                        <div class="feed-dot"></div>
                                        <div class="feed-line"></div>
                                    </div>
                                    <div class="feed-item">
                                        <div class="feed-dot"></div>
                                        <div class="feed-line"></div>
                                    </div>
                                    <div class="feed-item">
                                        <div class="feed-dot"></div>
                                        <div class="feed-line"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Charts Mock -->
                            <div class="charts-mock">
                                <!-- Line Chart -->
                                <div class="mock-chart line-chart">
                                    <svg viewBox="0 0 100 40">
                                        <polyline points="5,35 15,25 25,30 35,20 45,25 55,15 65,20 75,10 85,15 95,5"
                                                  stroke="var(--bix-light-green)" stroke-width="2" fill="none"/>
                                        <circle cx="25" cy="30" r="2" fill="var(--bix-light-green)"/>
                                        <circle cx="55" cy="15" r="2" fill="var(--bix-light-green)"/>
                                        <circle cx="85" cy="15" r="2" fill="var(--bix-light-green)"/>
                                    </svg>
                                </div>

                                <!-- Bar Chart -->
                                <div class="mock-chart bar-chart">
                                    <div class="bar" style="height: 60%"></div>
                                    <div class="bar" style="height: 80%"></div>
                                    <div class="bar" style="height: 40%"></div>
                                    <div class="bar" style="height: 90%"></div>
                                    <div class="bar" style="height: 70%"></div>
                                </div>

                                <!-- Calendar -->
                                <div class="mock-chart calendar-mock">
                                    <div class="calendar-grid-mock">
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell active"></div>
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell"></div>
                                        <div class="cal-cell"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monitor Stand -->
                        <div class="monitor-stand"></div>
                        <div class="monitor-base"></div>
                    </div>
                </div>

                <!-- Right Side: Action Cards -->
                <div class="dashboard-right-content">
                    <!-- Grow Your Money Card -->
                    <div class="dashboard-card grow-money">
                        <img src="/images/elements/grow your money icon.png" alt="Grow your Money" class="card-full-image">
                        <div class="card-sparkles">
                            <div class="green-sparkle sparkle-1">✦</div>
                            <div class="green-sparkle sparkle-2">✦</div>
                            <div class="green-sparkle sparkle-3">✦</div>
                            <div class="green-sparkle sparkle-4">✦</div>
                        </div>
                    </div>

                    <!-- Rewards Card -->
                    <div class="dashboard-card rewards">
                        <img src="/images/elements/rewards icon.png" alt="Rewards" class="card-full-image">
                        <div class="card-sparkles">
                            <div class="green-sparkle sparkle-1">✦</div>
                            <div class="green-sparkle sparkle-2">✦</div>
                            <div class="green-sparkle sparkle-3">✦</div>
                        </div>
                    </div>

                    <!-- Shop & Earn Card -->
                    <div class="dashboard-card shop-earn">
                        <img src="/images/elements/shop and earn.png" alt="Shop & Earn" class="card-full-image">
                        <div class="card-sparkles">
                            <div class="green-sparkle sparkle-1">✦</div>
                            <div class="green-sparkle sparkle-2">✦</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Navigation Icons - Full Width Below Both Columns -->
            <div class="bottom-nav-icons">
                <!-- Cash Back Button -->
                <div class="nav-icon-item">
                    <div class="nav-button cash-back-btn">
                        <img src="/images/elements/dashboard icons.png" alt="Cash Back" class="dashboard-icon-img">
                    </div>
                    <span class="nav-label">Cash Back</span>
                </div>

                <!-- Wallet Button -->
                <div class="nav-icon-item">
                    <div class="nav-button wallet-btn">
                        <img src="/images/elements/wallet icon.png" alt="Wallet" class="dashboard-icon-img">
                    </div>
                    <span class="nav-label">Wallet</span>
                </div>

                <!-- Transaction Button -->
                <div class="nav-icon-item">
                    <div class="nav-button transaction-btn">
                        <img src="/images/elements/transaction icon.png" alt="Transaction" class="dashboard-icon-img">
                    </div>
                    <span class="nav-label">Transaction</span>
                </div>

                <!-- Receipt Button -->
                <div class="nav-icon-item">
                    <div class="nav-button receipt-btn">
                        <img src="/images/elements/receipt icon.png" alt="Receipt" class="dashboard-icon-img">
                    </div>
                    <span class="nav-label">Receipt</span>
                </div>

                <!-- Withdrawal Button -->
                <div class="nav-icon-item">
                    <div class="nav-button withdrawal-btn">
                        <img src="/images/elements/withdrawal icon.png" alt="Withdrawal" class="dashboard-icon-img">
                    </div>
                    <span class="nav-label">Withdrawal</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Promotions Section -->
    <section id="promotions" class="promotions-section">
        <div class="promotions-container">
            <h2 class="promotions-title">Promotions</h2>
            <p class="promotions-description">Enjoy Up To 60% OFF on your favorite brands nationwide, all year long.</p>

            <div class="promotions-grid" id="promotions-grid">
                <!-- Loading placeholder -->
                <div class="promotions-loading" id="promotions-loading">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading promotions...</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="contact-section">
        <div class="contact-container">
            <div class="contact-content">
                <!-- Left Side - Title -->
                <div class="contact-title-side">
                    <h2 class="contact-title">Send your<br>Query</h2>
                </div>

                <!-- Right Side - Contact Form -->
                <div class="contact-form-side">
                    <form class="contact-form" id="contactForm">
                        @csrf
                        <div id="formMessages" style="display: none; padding: 1rem; margin-bottom: 1rem; border-radius: 5px; font-weight: 500;"></div>

                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="Name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" placeholder="Write Message" class="form-textarea" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="form-submit-btn" id="submitBtn">
                            <span id="submitBtnText">Submit</span>
                            <span id="submitBtnLoader" style="display: none;">Sending...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Left Side - Logo and Social Media -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="footer-logo-img">
                    </div>
                    <div class="footer-social">
                        @php
                            $socialMediaLinks = \App\Models\SocialMediaLink::enabled()->ordered()->get();
                        @endphp
                        @foreach($socialMediaLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer" class="social-link {{ strtolower($socialLink->platform) }}" title="{{ ucfirst($socialLink->platform) }}">
                                @if($socialLink->icon_file)
                                    <img src="{{ asset('storage/' . $socialLink->icon_file) }}" alt="{{ $socialLink->platform }}" style="width: 32px; height: 32px; object-fit: contain;">
                                @else
                                    <i class="{{ $socialLink->icon }}" style="font-size: 1.2rem;"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="footer-links">
                    <!-- About Us Column -->
                    <div class="footer-column">
                        <h3 class="footer-column-title">About Us</h3>
                        <ul class="footer-menu">
                            <li><a href="#home" class="footer-link">Home</a></li>
                            <li><a href="#partner" class="footer-link">Partner with us</a></li>
                            <li><a href="#promotions" class="footer-link">Promotions</a></li>
                        </ul>
                    </div>

                    <!-- Brands Column -->
                    <div class="footer-column">
                        <h3 class="footer-column-title">Brands</h3>
                        <ul class="footer-menu">
                            <li><a href="#" class="footer-link">Clothing</a></li>
                            <li><a href="#" class="footer-link">Home Appliances</a></li>
                            <li><a href="#" class="footer-link">Entertainment</a></li>
                            <li><a href="#" class="footer-link">Food</a></li>
                        </ul>
                    </div>

                    <!-- Contact Us Column -->
                    <div class="footer-column">
                        <h3 class="footer-column-title">Contact Us</h3>
                        <div class="footer-contact">
                            <p class="contact-item">021 111 222 333</p>
                            <p class="contact-item">info@bixcash.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Mobile Navigation System
            initializeMobileNavigation();

            // Performance Optimization: Parallel API calls with loading states
            showLoadingStates();

            Promise.all([
                fetch('/api/slides').then(handleResponse),
                fetch('/api/categories').then(handleResponse),
                fetch('/api/brands').then(handleResponse)
            ]).then(([slidesData, categoriesData, brandsData]) => {
                // Initialize all content simultaneously
                initializeHeroSlider(slidesData.success ? slidesData.data : []);
                populateCategories(categoriesData.success ? categoriesData.data : []);
                populateBrands(brandsData.success ? brandsData.data : []);
                initializeCarousels();
                hideLoadingStates();
            }).catch(error => {
                console.error('Error loading data:', error);
                hideLoadingStates();
                initializeFallbackContent();
            });

            // Handle API response format
            function handleResponse(response) {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            }

            // =================================
            // MOBILE NAVIGATION SYSTEM
            // =================================
            function initializeMobileNavigation() {
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileNavOverlay = document.getElementById('mobile-nav-overlay');
                const mobileNavClose = document.getElementById('mobile-nav-close');
                const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
                const body = document.body;

                // Check if elements exist
                if (!mobileMenuBtn || !mobileNavOverlay || !mobileNavClose) {
                    console.log('Mobile navigation elements not found');
                    return;
                }

                // Open mobile menu
                function openMobileMenu() {
                    mobileNavOverlay.classList.add('active');
                    mobileMenuBtn.classList.add('active');
                    body.classList.add('mobile-menu-open');

                    // Focus management for accessibility
                    setTimeout(() => {
                        mobileNavClose.focus();
                    }, 100);
                }

                // Close mobile menu
                function closeMobileMenu() {
                    mobileNavOverlay.classList.remove('active');
                    mobileMenuBtn.classList.remove('active');
                    body.classList.remove('mobile-menu-open');

                    // Return focus to hamburger button
                    mobileMenuBtn.focus();
                }

                // Toggle mobile menu
                function toggleMobileMenu() {
                    if (mobileNavOverlay.classList.contains('active')) {
                        closeMobileMenu();
                    } else {
                        openMobileMenu();
                    }
                }

                // Event Listeners
                mobileMenuBtn.addEventListener('click', toggleMobileMenu);
                mobileNavClose.addEventListener('click', closeMobileMenu);

                // Close menu when clicking on overlay background
                mobileNavOverlay.addEventListener('click', function(e) {
                    if (e.target === mobileNavOverlay) {
                        closeMobileMenu();
                    }
                });

                // Close menu when pressing Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileNavOverlay.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });

                // Handle mobile navigation link clicks
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Remove active class from all mobile nav links
                        mobileNavLinks.forEach(navLink => {
                            navLink.classList.remove('active');
                        });

                        // Add active class to clicked link
                        this.classList.add('active');

                        // Close mobile menu
                        closeMobileMenu();

                        // Navigate to section (reuse existing smooth scroll logic)
                        const targetId = this.getAttribute('href').substring(1);
                        const targetElement = document.getElementById(targetId);

                        if (targetElement) {
                            // Update desktop nav active state too
                            document.querySelectorAll('.desktop-nav a').forEach(navLink => {
                                navLink.classList.remove('active');
                            });

                            const desktopLink = document.querySelector(`.desktop-nav a[href="#${targetId}"]`);
                            if (desktopLink) {
                                desktopLink.classList.add('active');
                            }

                            // Smooth scroll to target
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });

                // Handle window resize - close mobile menu if window becomes too wide
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768 && mobileNavOverlay.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });

                // Sync desktop and mobile navigation active states
                function syncNavigationStates() {
                    const sections = ['home', 'brands', 'how-it-works', 'partner', 'promotions', 'contact'];
                    const scrollPosition = window.scrollY + 150; // Offset for header

                    for (let i = sections.length - 1; i >= 0; i--) {
                        const section = document.getElementById(sections[i]);
                        if (section && section.offsetTop <= scrollPosition) {
                            // Update desktop navigation
                            document.querySelectorAll('.desktop-nav a').forEach(link => {
                                link.classList.remove('active');
                            });

                            const desktopActiveLink = document.querySelector(`.desktop-nav a[href="#${sections[i]}"]`);
                            if (desktopActiveLink) {
                                desktopActiveLink.classList.add('active');
                            }

                            // Update mobile navigation
                            mobileNavLinks.forEach(link => {
                                link.classList.remove('active');
                            });

                            const mobileActiveLink = document.querySelector(`.mobile-nav-link[href="#${sections[i]}"]`);
                            if (mobileActiveLink) {
                                mobileActiveLink.classList.add('active');
                            }

                            break;
                        }
                    }
                }

                // Listen for scroll events to sync navigation states
                window.addEventListener('scroll', throttle(syncNavigationStates, 100));

                console.log('✅ Mobile navigation system initialized');
            }

            // Loading state management
            function showLoadingStates() {
                // Hero slider loading
                const heroWrapper = document.querySelector('#home .swiper-wrapper');
                if (heroWrapper) {
                    heroWrapper.innerHTML = `
                        <div class="swiper-slide loading-slide">
                            <div class="slide-content">
                                <div class="loading-spinner"></div>
                                <p>Loading hero content...</p>
                            </div>
                        </div>`;
                }

                // Categories loading
                const categoryContainer = document.querySelector('.category-container');
                if (categoryContainer) {
                    categoryContainer.innerHTML = '<div class="loading-categories">Loading categories...</div>';
                }

                // Brands loading
                const brandsWrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');
                if (brandsWrapper) {
                    brandsWrapper.innerHTML = '<div class="loading-brands">Loading brands...</div>';
                }
            }

            function hideLoadingStates() {
                // Remove any loading indicators
                document.querySelectorAll('.loading-slide, .loading-categories, .loading-brands').forEach(el => {
                    el.style.display = 'none';
                });
            }

            // YouTube URL conversion function
            function convertToYouTubeEmbed(url) {
                if (!url) return null;

                // Check if it's already an embed URL
                if (url.includes('youtube.com/embed/')) {
                    return url + (url.includes('?') ? '&' : '?') + 'autoplay=1&mute=1&loop=1&playlist=' + extractVideoId(url);
                }

                // Extract video ID from different YouTube URL formats
                const videoId = extractVideoId(url);
                if (!videoId) return null;

                // Return embeddable URL with autoplay
                return `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=1&loop=1&playlist=${videoId}`;
            }

            // Extract YouTube video ID from various URL formats
            function extractVideoId(url) {
                const regex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
                const match = url.match(regex);
                return match ? match[1] : null;
            }

            // Initialize Hero Slider with enhanced functionality
            function initializeHeroSlider(slides) {
                const swiperWrapper = document.querySelector('#home .swiper-wrapper');
                if (!swiperWrapper) return;

                swiperWrapper.innerHTML = '';

                if (!slides || slides.length === 0) {
                    // Default slide
                    const defaultSlide = document.createElement('div');
                    defaultSlide.classList.add('swiper-slide');
                    defaultSlide.style.cssText = `
                        background: linear-gradient(135deg, var(--bix-green) 0%, var(--bix-navy) 100%);
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        text-align: center;
                        min-height: 500px;
                    `;
                    defaultSlide.innerHTML = `
                        <div class="slide-content">
                            <h1 style="font-size: 3rem; margin-bottom: 1rem;">Welcome to BixCash</h1>
                            <p style="font-size: 1.5rem; opacity: 0.9;">Shop to Earn - Real Profit Sharing</p>
                            <button style="background: white; color: var(--bix-navy); padding: 1rem 2rem; border: none; border-radius: 25px; font-weight: bold; margin-top: 2rem; cursor: pointer;">Get Started</button>
                        </div>
                    `;
                    swiperWrapper.appendChild(defaultSlide);
                } else {
                    slides.forEach(slide => {
                        const slideElement = document.createElement('div');
                        slideElement.classList.add('swiper-slide');

                        // Handle different media types - Clean visual slides without text overlays
                        if (slide.media_type === 'video') {
                            // Check if it's a YouTube URL and convert to embed format
                            const youtubeEmbedUrl = convertToYouTubeEmbed(slide.media_path);

                            if (youtubeEmbedUrl) {
                                // YouTube video - use iframe
                                slideElement.innerHTML = `
                                    <iframe src="${youtubeEmbedUrl}"
                                            style="width: 100%; height: 100%; border: none;"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                `;
                            } else {
                                // Direct video file - use video tag
                                slideElement.innerHTML = `
                                    <video autoplay muted loop style="width: 100%; height: 100%; object-fit: cover;">
                                        <source src="${slide.media_path}" type="video/mp4">
                                    </video>
                                `;
                            }

                            // Add click handler for target URL if provided
                            if (slide.target_url) {
                                slideElement.style.cursor = 'pointer';
                                slideElement.addEventListener('click', () => {
                                    window.open(slide.target_url, '_blank');
                                });
                            }
                        } else {
                            // Use img tag with object-fit for better responsive behavior
                            slideElement.style.cssText = `
                                position: relative;
                                width: 100%;
                                height: 100%;
                                overflow: hidden;
                                ${slide.target_url ? 'cursor: pointer;' : ''}
                            `;

                            const imgElement = document.createElement('img');
                            imgElement.src = slide.media_path;
                            imgElement.alt = slide.title || 'Hero Slide';
                            imgElement.style.cssText = `
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                object-position: center;
                                display: block;
                            `;

                            slideElement.appendChild(imgElement);

                            // Add click handler for target URL if provided
                            if (slide.target_url) {
                                slideElement.addEventListener('click', () => {
                                    window.open(slide.target_url, '_blank');
                                });
                            }
                        }

                        swiperWrapper.appendChild(slideElement);
                    });
                }
            }

            // Initialize all carousels with enhanced error handling
            function initializeCarousels() {
                try {
                    // Check how many slides we have for hero carousel
                    const heroSlides = document.querySelectorAll('#home .swiper-slide');
                    const hasMultipleSlides = heroSlides.length > 1;

                    // Hero carousel with enhanced options
                    const heroSwiper = new Swiper('#home .swiper-container', {
                        loop: hasMultipleSlides,
                        autoplay: hasMultipleSlides ? {
                            delay: 5000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true
                        } : false,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                            dynamicBullets: true
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        },
                        lazy: true,
                        effect: 'fade',
                        fadeEffect: {
                            crossFade: true
                        },
                        speed: 1000,
                        on: {
                            init: function () {
                                console.log('Hero carousel initialized');
                            },
                            slideChange: function () {
                                // Add any slide change analytics here
                            }
                        }
                    });

                    // Initialize brands carousel separately
                    initializeBrandsCarousel();

                } catch (error) {
                    console.error('Error initializing carousels:', error);
                }
            }

            // Separate brands carousel initialization for reuse during filtering
            function initializeBrandsCarousel() {
                try {
                    // Wait for brands to be populated first
                    const brandsContainer = document.querySelector('.brands-carousel-container');
                    const brandsWrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');

                    if (!brandsContainer || !brandsWrapper) {
                        console.log('Brands container not found');
                        return null;
                    }

                    // Check if there are any brand slides
                    const brandSlides = brandsWrapper.querySelectorAll('.brand-slide');
                    if (brandSlides.length === 0) {
                        console.log('No brand slides found');
                        return null;
                    }

                    // Destroy existing brands carousel if it exists
                    const existingBrandsCarousel = brandsContainer.swiper;
                    if (existingBrandsCarousel) {
                        existingBrandsCarousel.destroy(true, true);
                    }

                    // Initialize new brands carousel
                    const brandsSwiper = new Swiper('.brands-carousel-container', {
                        loop: brandSlides.length > 1 ? true : false,
                        slidesPerView: 'auto',
                        spaceBetween: 20,
                        centeredSlides: false,
                        freeMode: true,
                        autoplay: brandSlides.length > 3 ? {
                            delay: 3000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true
                        } : false,
                        navigation: {
                            nextEl: '.brands-carousel-container .swiper-button-next',
                            prevEl: '.brands-carousel-container .swiper-button-prev'
                        },
                        lazy: {
                            loadPrevNext: true,
                        },
                        grabCursor: true,
                        breakpoints: {
                            320: {
                                slidesPerView: 2,
                                spaceBetween: 10,
                                freeMode: false
                            },
                            640: {
                                slidesPerView: 3,
                                spaceBetween: 15,
                                freeMode: false
                            },
                            768: {
                                slidesPerView: 4,
                                spaceBetween: 20,
                                freeMode: true
                            },
                            1024: {
                                slidesPerView: 'auto',
                                spaceBetween: 20,
                                freeMode: true
                            },
                        },
                        on: {
                            init: function () {
                                console.log('Brands carousel initialized with', brandSlides.length, 'slides');
                            },
                            slideChange: function () {
                                // Optional: Add analytics or other slide change events
                            }
                        }
                    });

                    return brandsSwiper;
                } catch (error) {
                    console.error('Error initializing brands carousel:', error);
                    return null;
                }
            }

            // Get container references
            const categoryContainer = document.querySelector('.category-container');
            const brandsSwiperWrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');

            // Enhanced Categories Population with dynamic carousel/static display
            function populateCategories(categories) {
                const categoryContainer = document.querySelector('.category-container');
                const categoryNavButtons = document.querySelector('.category-nav-buttons');

                if (!categoryContainer) return;

                if (!categories || categories.length === 0) {
                    categoryContainer.innerHTML = '<div class="no-categories">No categories available</div>';
                    return;
                }

                // Determine if we need carousel (more than 5 categories) or static display
                const needsCarousel = categories.length > 5;

                // Destroy existing carousel if it exists
                const existingCarousel = categoryContainer.swiper;
                if (existingCarousel) {
                    existingCarousel.destroy(true, true);
                }

                categoryContainer.innerHTML = '';

                if (needsCarousel) {
                    // CAROUSEL MODE: Convert to Swiper carousel for 6+ categories
                    console.log(`Categories (${categories.length}): Using carousel mode`);

                    // Set up carousel structure
                    categoryContainer.classList.add('swiper');
                    const swiperWrapper = document.createElement('div');
                    swiperWrapper.classList.add('swiper-wrapper');

                    categories.forEach(category => {
                        const categoryElement = createCategoryElement(category, true); // true = carousel mode
                        swiperWrapper.appendChild(categoryElement);
                    });

                    categoryContainer.appendChild(swiperWrapper);

                    // Show navigation buttons
                    if (categoryNavButtons) {
                        categoryNavButtons.style.display = 'block';
                    }

                    // Initialize Swiper carousel with FIXED width to respect original CSS
                    setTimeout(() => {
                        const categoryCarousel = new Swiper(categoryContainer, {
                            slidesPerView: 'auto', // Let slides maintain their CSS width
                            spaceBetween: 96, // 6rem gap as per original CSS (96px)
                            centeredSlides: false,
                            loop: categories.length > 5,
                            navigation: {
                                nextEl: '.category-button-next',
                                prevEl: '.category-button-prev'
                            },
                            breakpoints: {
                                320: {
                                    slidesPerView: 'auto',
                                    spaceBetween: 32 // 2rem for mobile
                                },
                                640: {
                                    slidesPerView: 'auto',
                                    spaceBetween: 48 // 3rem for tablet
                                },
                                768: {
                                    slidesPerView: 'auto',
                                    spaceBetween: 64 // 4rem for larger tablet
                                },
                                1024: {
                                    slidesPerView: 'auto',
                                    spaceBetween: 96 // 6rem for desktop
                                }
                            },
                            on: {
                                init: function () {
                                    console.log('Categories carousel initialized with', categories.length, 'categories');
                                }
                            }
                        });
                    }, 100);

                } else {
                    // STATIC MODE: Use flex layout for 5 or fewer categories
                    console.log(`Categories (${categories.length}): Using static mode`);

                    // Remove carousel classes and set up static layout
                    categoryContainer.classList.remove('swiper');
                    categoryContainer.style.cssText = 'display: flex; justify-content: center; flex-wrap: wrap; gap: 6rem; margin-bottom: 3rem;';

                    categories.forEach(category => {
                        const categoryElement = createCategoryElement(category, false); // false = static mode
                        categoryContainer.appendChild(categoryElement);
                    });

                    // Hide navigation buttons
                    if (categoryNavButtons) {
                        categoryNavButtons.style.display = 'none';
                    }
                }
            }

            // Helper function to create category elements (used by both modes) - ORIGINAL DESIGN PRESERVED
            function createCategoryElement(category, isCarouselMode = false) {
                const categoryElement = document.createElement('div');

                if (isCarouselMode) {
                    categoryElement.classList.add('swiper-slide', 'category-item');
                } else {
                    categoryElement.classList.add('category-item');
                }

                // CORRECT ORIGINAL CSS STYLING FROM app.css
                categoryElement.style.cssText = `
                    background-color: var(--bix-white);
                    border: 2px solid var(--bix-dark-blue);
                    border-radius: 8px;
                    padding: 1.2rem;
                    width: 120px;
                    height: 160px;
                    text-align: center;
                    transition: all 0.3s ease;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    cursor: pointer;
                `;

                // CORRECT ORIGINAL IMAGE STYLING - 90px height as per CSS
                const img = document.createElement('img');
                img.src = category.icon_path;
                img.alt = category.name;
                img.loading = 'lazy';
                img.decoding = 'async';
                img.style.cssText = 'height: 90px; margin-bottom: 0.5rem;';

                img.onerror = function() {
                    this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMzAiIGZpbGw9IiNmMGYwZjAiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBmaWxsPSIjY2NjIi8+Cjwvc3ZnPgo8L3N2Zz4K';
                };

                // CORRECT ORIGINAL SPAN STYLING - bold font weight as per CSS
                const span = document.createElement('span');
                span.textContent = category.name;
                span.style.cssText = 'color: var(--bix-dark-blue); font-weight: bold;';

                categoryElement.appendChild(img);
                categoryElement.appendChild(span);

                // CORRECT ORIGINAL HOVER EFFECTS - border color change as per CSS
                categoryElement.addEventListener('mouseenter', function() {
                    this.style.borderColor = 'var(--bix-green)';
                    this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
                });

                categoryElement.addEventListener('mouseleave', function() {
                    this.style.borderColor = 'var(--bix-dark-blue)';
                    this.style.boxShadow = 'none';
                });

                // Add click handler for category filtering
                categoryElement.addEventListener('click', function() {
                    filterBrandsByCategory(category.id, category.name);
                });

                return categoryElement;
            }

            // Enhanced Brands Population with carousel support
            function populateBrands(brands) {
                const brandsSwiperWrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');
                if (!brandsSwiperWrapper) return;

                if (!brands || brands.length === 0) {
                    brandsSwiperWrapper.innerHTML = '<div class="no-brands">No brands available</div>';
                    return;
                }

                brandsSwiperWrapper.innerHTML = '';
                brands.forEach(brand => {
                    const brandElement = document.createElement('div');
                    brandElement.classList.add('swiper-slide', 'brand-slide');

                    const img = document.createElement('img');
                    img.alt = brand.name;
                    img.loading = 'lazy';
                    img.decoding = 'async';

                    // Better error handling for brand images
                    img.onerror = function() {
                        console.log(`Failed to load brand logo: ${brand.logo_path} for ${brand.name}`);
                        // Create a simple placeholder div instead of SVG with text
                        const placeholder = document.createElement('div');
                        placeholder.style.cssText = `
                            width: 120px;
                            height: 80px;
                            background: #f8f9fa;
                            border: 2px dashed #dee2e6;
                            border-radius: 8px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: #6c757d;
                            font-size: 10px;
                            text-align: center;
                            font-family: Arial, sans-serif;
                        `;
                        placeholder.textContent = 'Logo';
                        this.parentNode.replaceChild(placeholder, this);
                    };

                    // Set the image source after error handler is attached
                    if (brand.logo_path && brand.logo_path.trim() !== '') {
                        img.src = brand.logo_path;
                    } else {
                        // If no logo path, trigger error handler to show placeholder
                        img.onerror();
                        return;
                    }

                    // Hover effects are handled by CSS

                    // Add click handler for brand details
                    brandElement.addEventListener('click', function() {
                        if (brand.website_url) {
                            window.open(brand.website_url, '_blank');
                        }
                    });

                    brandElement.appendChild(img);
                    brandsSwiperWrapper.appendChild(brandElement);
                });
            }

            // Category filtering function
            function filterBrandsByCategory(categoryId, categoryName) {
                fetch(`/api/brands?category_id=${categoryId}`)
                    .then(handleResponse)
                    .then(data => {
                        if (data.success) {
                            populateBrands(data.data);
                            // Reinitialize brands carousel after filtering
                            initializeBrandsCarousel();

                            // Show filter notification
                            showNotification(`Showing brands for: ${categoryName}`);
                        }
                    })
                    .catch(error => {
                        console.error('Error filtering brands:', error);
                        showNotification('Error filtering brands');
                    });
            }

            function initializeFallbackContent() {
                // Fallback for hero slider
                const swiperWrapper = document.querySelector('#home .swiper-wrapper');
                if (swiperWrapper) {
                    swiperWrapper.innerHTML = `<div class="swiper-slide" style="background-color: var(--bix-green);">
                        <div class="slide-content"><h1>Welcome to BixCash</h1><p>Shop to Earn</p></div>
                    </div>`;
                }
                initializeCarousels();
            }

            // Load promotions from API and render them
            async function loadPromotions() {
                try {
                    const response = await fetch('/api/promotions');
                    const result = await response.json();

                    if (result.success && result.data) {
                        renderPromotions(result.data);
                    } else {
                        showPromotionError('Failed to load promotions');
                    }
                } catch (error) {
                    console.error('Error loading promotions:', error);
                    showPromotionError('Unable to load promotions at this time');
                }
            }

            // Render promotions dynamically
            function renderPromotions(promotions) {
                const promotionsGrid = document.getElementById('promotions-grid');
                const loadingElement = document.getElementById('promotions-loading');

                if (!promotionsGrid) return;

                // Remove loading spinner
                if (loadingElement) {
                    loadingElement.remove();
                }

                // Clear existing content
                promotionsGrid.innerHTML = '';

                // Generate promotion cards
                promotions.forEach((promotion, index) => {
                    const brandSlug = promotion.brand_name.toLowerCase()
                        .replace(/[^a-z0-9]/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');

                    const promotionCard = document.createElement('div');
                    promotionCard.className = 'promotion-card';
                    promotionCard.innerHTML = `
                        <div class="promotion-logo">
                            <img src="${promotion.logo_url || `https://via.placeholder.com/150x80/ffffff/000000?text=${encodeURIComponent(promotion.brand_name)}`}"
                                 alt="${promotion.brand_name}"
                                 data-brand="${brandSlug}"
                                 loading="lazy"
                                 decoding="async"
                                 onerror="this.src='https://via.placeholder.com/150x80/ffffff/000000?text=${encodeURIComponent(promotion.brand_name)}'">
                        </div>
                        <div class="promotion-discount">
                            <span class="discount-text">${promotion.discount_text}</span>
                        </div>
                    `;

                    promotionsGrid.appendChild(promotionCard);
                });

                // Load promotion images after rendering
                loadPromotionImages();
            }

            // Show promotion loading error
            function showPromotionError(message) {
                const promotionsGrid = document.getElementById('promotions-grid');
                const loadingElement = document.getElementById('promotions-loading');

                if (loadingElement) {
                    loadingElement.remove();
                }

                if (promotionsGrid) {
                    promotionsGrid.innerHTML = `
                        <div class="promotions-error" style="grid-column: 1 / -1; text-align: center; padding: 2rem; color: #666;">
                            <p>${message}</p>
                            <button onclick="loadPromotions()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: var(--bix-green); color: var(--bix-dark-blue); border: none; border-radius: 5px; cursor: pointer;">
                                Retry
                            </button>
                        </div>
                    `;
                }
            }

            // Optimized promotion images loading
            function loadPromotionImages() {
                const promotionImages = document.querySelectorAll('.promotion-logo img[data-brand]');
                const imagePromises = [];

                promotionImages.forEach(img => {
                    const brandName = img.getAttribute('data-brand');
                    const promise = checkImageExists(brandName).then(imagePath => {
                        if (imagePath) {
                            img.src = imagePath;
                            img.loading = 'lazy';
                            img.decoding = 'async';
                        }
                    });
                    imagePromises.push(promise);
                });

                return Promise.all(imagePromises);
            }

            function checkImageExists(brandName) {
                return new Promise((resolve) => {
                    const extensions = ['webp', 'png', 'jpg', 'jpeg'];
                    let index = 0;

                    function tryNext() {
                        if (index >= extensions.length) {
                            resolve(null);
                            return;
                        }

                        const img = new Image();
                        const path = `/images/promotions/${brandName}.${extensions[index]}`;

                        img.onload = () => resolve(path);
                        img.onerror = () => {
                            index++;
                            tryNext();
                        };
                        img.src = path;
                    }

                    tryNext();
                });
            }

            // Load promotions from API after DOM is ready
            loadPromotions();

            // Initialize smooth scroll navigation
            initializeSmoothScroll();

            // Initialize notification system
            createNotificationContainer();

            // Smooth scroll navigation system
            function initializeSmoothScroll() {
                // Add smooth scroll behavior to navigation links
                document.querySelectorAll('nav a[href^="#"], .footer-link[href^="#"]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();

                        const targetId = this.getAttribute('href').substring(1);
                        const targetElement = document.getElementById(targetId);

                        if (targetElement) {
                            // Remove active class from all nav links
                            document.querySelectorAll('nav a').forEach(navLink => {
                                navLink.classList.remove('active');
                            });

                            // Add active class to clicked link
                            this.classList.add('active');

                            // Smooth scroll to target
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });

                // Update active navigation on scroll
                window.addEventListener('scroll', throttle(updateActiveNavigation, 100));
            }

            // Update active navigation based on scroll position
            function updateActiveNavigation() {
                const sections = ['home', 'brands', 'how-it-works', 'partner', 'promotions', 'contact'];
                const scrollPosition = window.scrollY + 100;

                for (let i = sections.length - 1; i >= 0; i--) {
                    const section = document.getElementById(sections[i]);
                    if (section && section.offsetTop <= scrollPosition) {
                        // Remove active class from all nav links
                        document.querySelectorAll('nav a').forEach(link => {
                            link.classList.remove('active');
                        });

                        // Add active class to current section link
                        const activeLink = document.querySelector(`nav a[href="#${sections[i]}"]`);
                        if (activeLink) {
                            activeLink.classList.add('active');
                        }
                        break;
                    }
                }
            }

            // Notification system
            function createNotificationContainer() {
                if (document.getElementById('notification-container')) return;

                const container = document.createElement('div');
                container.id = 'notification-container';
                container.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 10000;
                    pointer-events: none;
                `;
                document.body.appendChild(container);
            }

            function showNotification(message, type = 'info') {
                const container = document.getElementById('notification-container');
                if (!container) return;

                const notification = document.createElement('div');
                notification.style.cssText = `
                    background: ${type === 'error' ? '#e74c3c' : type === 'success' ? '#27ae60' : '#3498db'};
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    margin-bottom: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    transform: translateX(100%);
                    transition: transform 0.3s ease;
                    pointer-events: auto;
                    font-weight: 500;
                    max-width: 300px;
                `;
                notification.textContent = message;

                container.appendChild(notification);

                // Animate in
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 10);

                // Animate out and remove
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 4000);
            }

            // Utility functions
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function throttle(func, limit) {
                let inThrottle;
                return function() {
                    const args = arguments;
                    const context = this;
                    if (!inThrottle) {
                        func.apply(context, args);
                        inThrottle = true;
                        setTimeout(() => inThrottle = false, limit);
                    }
                }
            }

            // Contact Form AJAX Submission
            const contactForm = document.getElementById('contactForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitBtnLoader = document.getElementById('submitBtnLoader');
            const formMessages = document.getElementById('formMessages');

            if (contactForm) {
                contactForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Disable submit button
                    submitBtn.disabled = true;
                    submitBtnText.style.display = 'none';
                    submitBtnLoader.style.display = 'inline';

                    // Hide previous messages
                    formMessages.style.display = 'none';

                    // Get form data
                    const formData = new FormData(contactForm);

                    try {
                        const response = await fetch('{{ route("contact.store") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            // Show success message
                            formMessages.textContent = data.message || 'Thank you for contacting us! We will get back to you soon.';
                            formMessages.style.display = 'block';
                            formMessages.style.backgroundColor = '#d4edda';
                            formMessages.style.color = '#155724';
                            formMessages.style.border = '1px solid #c3e6cb';

                            // Reset form
                            contactForm.reset();

                            // Hide success message after 5 seconds
                            setTimeout(() => {
                                formMessages.style.display = 'none';
                            }, 5000);
                        } else {
                            // Show error message
                            let errorMessage = 'An error occurred. Please try again.';

                            if (data.errors) {
                                errorMessage = Object.values(data.errors).flat().join('<br>');
                            } else if (data.message) {
                                errorMessage = data.message;
                            }

                            formMessages.innerHTML = errorMessage;
                            formMessages.style.display = 'block';
                            formMessages.style.backgroundColor = '#f8d7da';
                            formMessages.style.color = '#721c24';
                            formMessages.style.border = '1px solid #f5c6cb';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        formMessages.textContent = 'Network error. Please check your connection and try again.';
                        formMessages.style.display = 'block';
                        formMessages.style.backgroundColor = '#f8d7da';
                        formMessages.style.color = '#721c24';
                        formMessages.style.border = '1px solid #f5c6cb';
                    } finally {
                        // Re-enable submit button
                        submitBtn.disabled = false;
                        submitBtnText.style.display = 'inline';
                        submitBtnLoader.style.display = 'none';
                    }
                });
            }
        });
    </script>

</body>
</html>