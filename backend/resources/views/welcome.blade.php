<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BixCash</title>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        @vite(['resources/css/app.css'])
</head>
<body>

    <header class="main-header">
        <a href="/" class="logo">
            <img src="/images/logos/logos-01.png" alt="BixCash Logo">
        </a>
        <nav>
            <ul>
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#partner">Partner with us</a></li>
                <li><a href="#brands">Brands</a></li>
                <li><a href="#promotions">Promotions</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <section id="hero" class="hero-slider">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Slides will be injected here by JavaScript -->
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <section class="brands-section">
        <div class="category-container">
            <!-- Categories will be injected by JavaScript -->
        </div>
        <h2><span class="green-text">Explore</span> Brands</h2>
        <div class="brands-carousel-container">
            <div class="swiper-wrapper">
                <!-- Brands will be injected by JavaScript -->
            </div>
        </div>
    </section>

    <section class="how-it-works-section">
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
    <section class="customer-dashboard-section">
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

                    <!-- Bottom Navigation Icons -->
                    <div class="bottom-nav-icons">
                        <!-- Cash Back Button -->
                        <div class="nav-icon-item">
                            <div class="nav-button cash-back-btn">
                                <img src="/images/elements/dashboard icons.jpg" alt="Cash Back" class="dashboard-icon-img">
                            </div>
                            <span class="nav-label">Cash Back</span>
                        </div>

                        <!-- Wallet Button -->
                        <div class="nav-icon-item">
                            <div class="nav-button wallet-btn">
                                <div class="wallet-icon">
                                    <div class="wallet-body"></div>
                                    <div class="wallet-flap"></div>
                                </div>
                            </div>
                            <span class="nav-label">Wallet</span>
                        </div>

                        <!-- Transaction Button -->
                        <div class="nav-icon-item">
                            <div class="nav-button transaction-btn">
                                <div class="transaction-icon">
                                    <div class="dollar-sign">$</div>
                                    <div class="transaction-lines">
                                        <div class="line"></div>
                                        <div class="line"></div>
                                    </div>
                                </div>
                            </div>
                            <span class="nav-label">Transaction</span>
                        </div>

                        <!-- Receipt Button -->
                        <div class="nav-icon-item">
                            <div class="nav-button receipt-btn">
                                <div class="receipt-icon">
                                    <div class="receipt-paper">
                                        <div class="receipt-line"></div>
                                        <div class="receipt-line"></div>
                                        <div class="receipt-line short"></div>
                                    </div>
                                </div>
                            </div>
                            <span class="nav-label">Receipt</span>
                        </div>

                        <!-- Withdrawal Button -->
                        <div class="nav-icon-item">
                            <div class="nav-button withdrawal-btn">
                                <div class="withdrawal-icon">
                                    <div class="money-stack">
                                        <div class="bill"></div>
                                        <div class="bill"></div>
                                        <div class="bill"></div>
                                    </div>
                                    <div class="down-arrow">↓</div>
                                </div>
                            </div>
                            <span class="nav-label">Withdrawal</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Action Cards -->
                <div class="dashboard-right-content">
                    <!-- Grow Your Money Card -->
                    <div class="dashboard-card grow-money">
                        <img src="/images/elements/grow your money icon.jpg" alt="Grow your Money" class="card-full-image">
                        <div class="card-sparkles">
                            <div class="green-sparkle sparkle-1">✦</div>
                            <div class="green-sparkle sparkle-2">✦</div>
                            <div class="green-sparkle sparkle-3">✦</div>
                            <div class="green-sparkle sparkle-4">✦</div>
                        </div>
                    </div>

                    <!-- Rewards Card -->
                    <div class="dashboard-card rewards">
                        <img src="/images/elements/rewards icon.jpg" alt="Rewards" class="card-full-image">
                        <div class="card-sparkles">
                            <div class="green-sparkle sparkle-1">✦</div>
                            <div class="green-sparkle sparkle-2">✦</div>
                            <div class="green-sparkle sparkle-3">✦</div>
                        </div>
                    </div>

                    <!-- Shop & Earn Card -->
                    <div class="dashboard-card shop-earn">
                        <img src="/images/elements/shop and earn.jpg" alt="Shop & Earn" class="card-full-image">
                        <div class="card-sparkles">
                            <div class="green-sparkle sparkle-1">✦</div>
                            <div class="green-sparkle sparkle-2">✦</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Promotions Section -->
    <section class="promotions-section">
        <div class="promotions-container">
            <h2 class="promotions-title">Promotions</h2>
            <p class="promotions-description">Enjoy Up To 60% OFF on your favorite brands nationwide, all year long.</p>

            <div class="promotions-grid">
                <!-- Row 1 -->
                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffffff/000000?text=SAYA" alt="SAYA" data-brand="saya">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Upto 20% Off</span>
                    </div>
                </div>

                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffffff/000000?text=JUNAID+JAMSHED" alt="Junaid Jamshed" data-brand="junaid-jamshed">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Upto 30% Off</span>
                    </div>
                </div>

                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffffff/000000?text=GUL+AHMED" alt="Gul Ahmed" data-brand="gul-ahmed">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Flat 20% Off</span>
                    </div>
                </div>

                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffffff/dd0000?text=Bata" alt="Bata" data-brand="bata">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Flat 50% Off</span>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffd700/000000?text=Tayto" alt="Tayto" data-brand="tayto">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Upto 30% Off</span>
                    </div>
                </div>

                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/dc143c/ffffff?text=KFC" alt="KFC" data-brand="kfc">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Upto 20% Off</span>
                    </div>
                </div>

                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffffff/000000?text=Joyland" alt="Joyland" data-brand="joyland">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Flat 50% Off</span>
                    </div>
                </div>

                <div class="promotion-card">
                    <div class="promotion-logo">
                        <img src="https://via.placeholder.com/150x80/ffffff/000000?text=SAPPHIRE" alt="Sapphire" data-brand="sapphire">
                    </div>
                    <div class="promotion-discount">
                        <span class="discount-text">Flat 50% Off</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="contact-section">
        <div class="contact-container">
            <div class="contact-content">
                <!-- Left Side - Title -->
                <div class="contact-title-side">
                    <h2 class="contact-title">Send your<br>Query</h2>
                </div>

                <!-- Right Side - Contact Form -->
                <div class="contact-form-side">
                    <form class="contact-form" id="contactForm">
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="Name" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" placeholder="Write Message" class="form-textarea" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="form-submit-btn">Submit</button>
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
                        <a href="#" class="social-link linkedin">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link instagram">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link twitter">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link youtube">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link tiktok">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </a>
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
            // --- Hero Slider --- 
            fetch('/api/slides')
                .then(response => response.json())
                .then(data => {
                    const swiperWrapper = document.querySelector('#hero .swiper-wrapper');
                    if(data.length === 0) {
                        swiperWrapper.innerHTML = `<div class="swiper-slide" style="background-color: var(--bix-green);">
                            <div class="slide-content"><h1>Welcome to BixCash</h1><p>No slides found.</p></div>
                        </div>`;
                    } else {
                        data.forEach(slide => {
                            const slideElement = document.createElement('div');
                            slideElement.classList.add('swiper-slide');
                            slideElement.style.backgroundImage = `url(${slide.media_path})`;
                            slideElement.innerHTML = `<div class="slide-content"><h1>${slide.title}</h1><p>${slide.description}</p></div>`;
                            swiperWrapper.appendChild(slideElement);
                        });
                    }
                    new Swiper('#hero .swiper-container', {
                        loop: true,
                        autoplay: { delay: 5000 },
                        pagination: { el: '.swiper-pagination', clickable: true },
                        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    });
                })
                .catch(error => console.error('Error fetching slides:', error));

            // --- Brands Section --- 
            const categoryContainer = document.querySelector('.category-container');
            const brandsSwiperWrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');

            fetch('/api/categories')
                .then(response => response.json())
                .then(populateCategories)
                .catch(error => console.error('Error fetching categories:', error));

            fetch('/api/brands')
                .then(response => response.json())
                .then(data => {
                    populateBrands(data);
                    new Swiper('.brands-carousel-container', {
                        loop: true,
                        slidesPerView: 5,
                        spaceBetween: 30,
                        autoplay: { delay: 3000 },
                        breakpoints: {
                            320: { slidesPerView: 2, spaceBetween: 10 },
                            640: { slidesPerView: 3, spaceBetween: 20 },
                            1024: { slidesPerView: 5, spaceBetween: 30 },
                        }
                    });
                })
                .catch(error => console.error('Error fetching brands:', error));

            function populateCategories(categories) {
                if (!categories || categories.length === 0) return;
                categoryContainer.innerHTML = '';
                categories.forEach(category => {
                    const categoryElement = document.createElement('div');
                    categoryElement.classList.add('category-item');
                    categoryElement.innerHTML = `
                        <img src="${category.icon_path}" alt="${category.name}">
                        <span>${category.name}</span>
                    `;
                    categoryContainer.appendChild(categoryElement);
                });
            }

            function populateBrands(brands) {
                if (!brands || brands.length === 0) return;
                brandsSwiperWrapper.innerHTML = '';
                brands.forEach(brand => {
                    const brandElement = document.createElement('div');
                    brandElement.classList.add('swiper-slide', 'brand-slide');
                    brandElement.innerHTML = `<img src="${brand.logo_path}" alt="${brand.name}">`;
                    brandsSwiperWrapper.appendChild(brandElement);
                });
            }

            // Handle promotion images loading
            function loadPromotionImages() {
                const promotionImages = document.querySelectorAll('.promotion-logo img[data-brand]');

                promotionImages.forEach(img => {
                    const brandName = img.getAttribute('data-brand');
                    const testImg = new Image();

                    testImg.onload = function() {
                        // Image exists, replace the placeholder
                        img.src = `/images/promotions/${brandName}.png`;
                    };

                    testImg.onerror = function() {
                        // Try .jpg if .png fails
                        const testImgJpg = new Image();
                        testImgJpg.onload = function() {
                            img.src = `/images/promotions/${brandName}.jpg`;
                        };
                        testImgJpg.onerror = function() {
                            // Keep placeholder if both fail
                            console.log(`No image found for ${brandName}`);
                        };
                        testImgJpg.src = `/images/promotions/${brandName}.jpg`;
                    };

                    testImg.src = `/images/promotions/${brandName}.png`;
                });
            }

            // Load promotion images after DOM is ready
            loadPromotionImages();

            // Contact form handling
            const contactForm = document.getElementById('contactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get form data
                    const formData = new FormData(contactForm);
                    const name = formData.get('name');
                    const email = formData.get('email');
                    const message = formData.get('message');

                    // Simple validation
                    if (!name || !email || !message) {
                        alert('Please fill in all fields');
                        return;
                    }

                    // Show success message (placeholder for now)
                    alert('Thank you for your message! We will get back to you soon.');

                    // Reset form
                    contactForm.reset();
                });
            }
        });
    </script>

</body>
</html>