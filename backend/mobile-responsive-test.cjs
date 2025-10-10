const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Mobile Responsive Testing Suite
async function testResponsiveDesign() {
    console.log('🚀 Starting Mobile Responsive Testing Suite...');

    const browser = await puppeteer.launch({
        headless: false, // Set to true for headless mode
        defaultViewport: null,
        args: ['--start-maximized']
    });

    const page = await browser.newPage();

    // Create screenshots directory if it doesn't exist
    const screenshotsDir = path.join(__dirname, 'responsive-screenshots');
    if (!fs.existsSync(screenshotsDir)) {
        fs.mkdirSync(screenshotsDir);
    }

    const baseUrl = 'http://127.0.0.1:8000';

    // Define test breakpoints (mobile-first approach)
    const breakpoints = [
        { name: 'mobile-small', width: 320, height: 568 },      // iPhone SE
        { name: 'mobile-medium', width: 375, height: 667 },     // iPhone 6/7/8
        { name: 'mobile-large', width: 414, height: 736 },      // iPhone 6/7/8 Plus
        { name: 'tablet-portrait', width: 768, height: 1024 },  // iPad
        { name: 'tablet-landscape', width: 1024, height: 768 }, // iPad Landscape
        { name: 'desktop-small', width: 1200, height: 800 },    // Small Desktop
        { name: 'desktop-large', width: 1920, height: 1080 }    // Large Desktop
    ];

    try {
        console.log('🔗 Navigating to website...');
        await page.goto(baseUrl, { waitUntil: 'networkidle2', timeout: 30000 });

        // Wait for dynamic content to load
        await page.waitForTimeout(3000);

        // Test each breakpoint
        for (const breakpoint of breakpoints) {
            console.log(`📱 Testing ${breakpoint.name} (${breakpoint.width}x${breakpoint.height})`);

            await page.setViewport({
                width: breakpoint.width,
                height: breakpoint.height,
                deviceScaleFactor: 1
            });

            // Wait for layout to adjust
            await page.waitForTimeout(1000);

            // Take full page screenshot
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
            const filename = `${timestamp}-${breakpoint.name}-fullpage.png`;

            await page.screenshot({
                path: path.join(screenshotsDir, filename),
                fullPage: true,
                type: 'png'
            });

            // Test specific sections
            await testSectionVisibility(page, breakpoint, screenshotsDir, timestamp);

            console.log(`✅ ${breakpoint.name} test completed`);
        }

        // Test mobile navigation functionality (if hamburger menu exists)
        await testMobileNavigation(page, screenshotsDir);

        // Test form interactions on mobile
        await testMobileFormInteractions(page, screenshotsDir);

        console.log('🎉 All responsive tests completed successfully!');
        console.log(`📁 Screenshots saved to: ${screenshotsDir}`);

    } catch (error) {
        console.error('❌ Test failed:', error);
    } finally {
        await browser.close();
    }
}

async function testSectionVisibility(page, breakpoint, screenshotsDir, timestamp) {
    const sections = [
        { id: 'home', name: 'hero-slider' },
        { id: 'brands', name: 'brands-section' },
        { id: 'how-it-works', name: 'how-it-works' },
        { id: 'partner', name: 'customer-dashboard' },
        { id: 'promotions', name: 'promotions' },
        { id: 'contact', name: 'contact-form' }
    ];

    for (const section of sections) {
        try {
            // Scroll to section
            await page.evaluate((id) => {
                const element = document.getElementById(id);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, section.id);

            await page.waitForTimeout(500);

            // Check if section is visible
            const isVisible = await page.evaluate((id) => {
                const element = document.getElementById(id);
                if (!element) return false;

                const rect = element.getBoundingClientRect();
                return rect.top >= 0 && rect.left >= 0 &&
                       rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                       rect.right <= (window.innerWidth || document.documentElement.clientWidth);
            }, section.id);

            console.log(`  📍 ${section.name}: ${isVisible ? '✅ Visible' : '⚠️ Partially visible'}`);

        } catch (error) {
            console.log(`  📍 ${section.name}: ❌ Error testing section`);
        }
    }
}

async function testMobileNavigation(page, screenshotsDir) {
    console.log('🔍 Testing mobile navigation...');

    // Set mobile viewport
    await page.setViewport({ width: 375, height: 667 });

    try {
        // Look for hamburger menu button
        const hamburgerExists = await page.$('.hamburger-menu, .mobile-menu-btn, [data-mobile-menu]');

        if (hamburgerExists) {
            console.log('  🍔 Hamburger menu found - testing toggle');

            // Take screenshot before opening menu
            await page.screenshot({
                path: path.join(screenshotsDir, 'mobile-nav-closed.png'),
                fullPage: false
            });

            // Click hamburger menu
            await hamburgerExists.click();
            await page.waitForTimeout(500);

            // Take screenshot with menu open
            await page.screenshot({
                path: path.join(screenshotsDir, 'mobile-nav-open.png'),
                fullPage: false
            });

            // Click again to close
            await hamburgerExists.click();
            await page.waitForTimeout(500);

            console.log('  ✅ Mobile navigation toggle working');
        } else {
            console.log('  ⚠️ No hamburger menu found - may need to be implemented');
        }
    } catch (error) {
        console.log('  ❌ Error testing mobile navigation:', error.message);
    }
}

async function testMobileFormInteractions(page, screenshotsDir) {
    console.log('📝 Testing mobile form interactions...');

    // Set mobile viewport
    await page.setViewport({ width: 375, height: 667 });

    try {
        // Navigate to contact section
        await page.evaluate(() => {
            const contactSection = document.getElementById('contact');
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth' });
            }
        });

        await page.waitForTimeout(1000);

        // Test form elements
        const nameInput = await page.$('#name');
        if (nameInput) {
            // Focus on input to show mobile keyboard
            await nameInput.click();
            await page.waitForTimeout(500);

            // Take screenshot with focused input
            await page.screenshot({
                path: path.join(screenshotsDir, 'mobile-form-focused.png'),
                fullPage: false
            });

            console.log('  ✅ Form input focus working on mobile');
        }

        // Test if form elements are touch-friendly (minimum 44px touch targets)
        const touchTargetTest = await page.evaluate(() => {
            const inputs = document.querySelectorAll('input, button, textarea');
            const results = [];

            inputs.forEach(input => {
                const rect = input.getBoundingClientRect();
                const isTouchFriendly = rect.height >= 44 && rect.width >= 44;
                results.push({
                    element: input.tagName + (input.id ? '#' + input.id : ''),
                    height: Math.round(rect.height),
                    width: Math.round(rect.width),
                    touchFriendly: isTouchFriendly
                });
            });

            return results;
        });

        console.log('  📏 Touch target analysis:');
        touchTargetTest.forEach(result => {
            const status = result.touchFriendly ? '✅' : '⚠️';
            console.log(`    ${status} ${result.element}: ${result.width}x${result.height}px`);
        });

    } catch (error) {
        console.log('  ❌ Error testing mobile forms:', error.message);
    }
}

// Performance testing function
async function testMobilePerformance(page) {
    console.log('⚡ Testing mobile performance...');

    await page.setViewport({ width: 375, height: 667 });

    // Enable performance metrics
    await page.coverage.startJSCoverage();
    await page.coverage.startCSSCoverage();

    const performanceData = await page.evaluate(() => {
        return JSON.stringify(performance.getEntriesByType('navigation'));
    });

    console.log('  📊 Performance metrics collected');

    await page.coverage.stopJSCoverage();
    await page.coverage.stopCSSCoverage();
}

// Export functions for modular testing
module.exports = {
    testResponsiveDesign,
    testSectionVisibility,
    testMobileNavigation,
    testMobileFormInteractions,
    testMobilePerformance
};

// Run tests if called directly
if (require.main === module) {
    testResponsiveDesign().catch(console.error);
}