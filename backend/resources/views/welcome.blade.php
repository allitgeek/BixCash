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
        });
    </script>

</body>
</html>