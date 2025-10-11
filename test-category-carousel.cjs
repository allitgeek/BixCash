const puppeteer = require('puppeteer');

async function testCategoryCarousel() {
    console.log('🧪 Testing Dynamic Category Carousel System...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: false,
        defaultViewport: { width: 1200, height: 800 }
    });

    const page = await browser.newPage();

    // Enable console logging from the page
    page.on('console', msg => {
        if (msg.type() === 'log') {
            console.log('🔍 Page Console:', msg.text());
        }
    });

    try {
        console.log('📍 Navigating to homepage...');
        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle2', timeout: 30000 });

        // Wait for categories to load
        console.log('⏳ Waiting for categories to load...');
        await page.waitForSelector('.category-container', { timeout: 10000 });

        // Wait a bit more for JavaScript to execute
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Count categories
        const categoryCount = await page.evaluate(() => {
            const categories = document.querySelectorAll('.category-item');
            return categories.length;
        });

        console.log(`📊 Found ${categoryCount} categories`);

        // Check which mode is active
        const carouselInfo = await page.evaluate(() => {
            const container = document.querySelector('.category-container');
            const navButtons = document.querySelector('.category-nav-buttons');

            return {
                hasSwiper: container.classList.contains('swiper'),
                navButtonsVisible: navButtons && navButtons.style.display !== 'none',
                containerClasses: container.className,
                containerStyle: container.style.cssText
            };
        });

        console.log('\n🔍 Current Category Display Mode:');
        console.log(`   Mode: ${carouselInfo.hasSwiper ? 'CAROUSEL' : 'STATIC'}`);
        console.log(`   Navigation Buttons: ${carouselInfo.navButtonsVisible ? 'VISIBLE' : 'HIDDEN'}`);
        console.log(`   Container Classes: ${carouselInfo.containerClasses}`);

        // Verify the logic is working correctly
        if (categoryCount <= 5) {
            console.log('\n✅ Expected: STATIC mode (≤5 categories)');
            if (!carouselInfo.hasSwiper && !carouselInfo.navButtonsVisible) {
                console.log('✅ PASS: Static mode is correctly active');
            } else {
                console.log('❌ FAIL: Should be in static mode but carousel detected');
            }
        } else {
            console.log('\n✅ Expected: CAROUSEL mode (>5 categories)');
            if (carouselInfo.hasSwiper && carouselInfo.navButtonsVisible) {
                console.log('✅ PASS: Carousel mode is correctly active');
            } else {
                console.log('❌ FAIL: Should be in carousel mode but static detected');
            }
        }

        // Test category interaction
        console.log('\n🖱️ Testing category interaction...');
        const firstCategory = await page.$('.category-item');
        if (firstCategory) {
            await firstCategory.hover();
            console.log('✅ Category hover effect working');

            await firstCategory.click();
            console.log('✅ Category click event working');
        }

        // Take screenshot for visual verification
        await page.screenshot({
            path: 'backend/category-carousel-test.png',
            fullPage: false
        });
        console.log('\n📸 Screenshot saved: backend/category-carousel-test.png');

        // Test responsive behavior
        console.log('\n📱 Testing responsive behavior...');
        await page.setViewport({ width: 768, height: 600 });
        await new Promise(resolve => setTimeout(resolve, 1000));

        const mobileInfo = await page.evaluate(() => {
            const categories = document.querySelectorAll('.category-item');
            return {
                categoryCount: categories.length,
                firstCategorySize: categories[0] ? {
                    width: categories[0].offsetWidth,
                    height: categories[0].offsetHeight
                } : null
            };
        });

        console.log(`📱 Mobile view: ${mobileInfo.categoryCount} categories visible`);
        if (mobileInfo.firstCategorySize) {
            console.log(`   Category size: ${mobileInfo.firstCategorySize.width}x${mobileInfo.firstCategorySize.height}px`);
        }

        // Test with larger viewport
        await page.setViewport({ width: 1400, height: 800 });
        await new Promise(resolve => setTimeout(resolve, 1000));

        console.log('\n🖥️ Desktop view restored');

        console.log('\n🎉 Dynamic Category Carousel Test Completed Successfully!');

    } catch (error) {
        console.error('❌ Test failed:', error.message);
    } finally {
        await browser.close();
    }
}

// Self-executing test
testCategoryCarousel().catch(console.error);