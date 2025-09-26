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
        });
    </script>

</body>
</html>