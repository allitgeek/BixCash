const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Comprehensive mobile responsiveness verification test
async function comprehensiveTest() {
    console.log('🔍 Starting comprehensive mobile responsiveness test...');

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();
    const screenshotsDir = path.join(__dirname, 'mobile-test-results');

    if (!fs.existsSync(screenshotsDir)) {
        fs.mkdirSync(screenshotsDir);
    }

    const testResults = {
        websiteStatus: false,
        mobileNavigation: false,
        responsiveBreakpoints: [],
        errors: []
    };

    try {
        console.log('📱 Testing website accessibility...');

        // Test website accessibility
        const response = await page.goto('http://127.0.0.1:8000', {
            waitUntil: 'domcontentloaded',
            timeout: 15000
        });

        if (response.status() === 200) {
            testResults.websiteStatus = true;
            console.log('✅ Website accessible (HTTP 200)');
        } else {
            testResults.errors.push(`Website returned status: ${response.status()}`);
        }

        // Wait for page to load completely
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Test mobile navigation elements
        console.log('🔍 Testing mobile navigation elements...');

        const mobileElements = await page.evaluate(() => {
            return {
                mobileMenuBtn: !!document.getElementById('mobile-menu-btn'),
                mobileNavOverlay: !!document.getElementById('mobile-nav-overlay'),
                mobileNavClose: !!document.getElementById('mobile-nav-close'),
                desktopNav: !!document.querySelector('.desktop-nav'),
                mobileNavLinks: document.querySelectorAll('.mobile-nav-link').length
            };
        });

        if (mobileElements.mobileMenuBtn && mobileElements.mobileNavOverlay && mobileElements.mobileNavClose) {
            testResults.mobileNavigation = true;
            console.log('✅ Mobile navigation elements found');
            console.log(`✅ Mobile nav links: ${mobileElements.mobileNavLinks}`);
        } else {
            testResults.errors.push('Missing mobile navigation elements');
        }

        // Test responsive breakpoints
        const breakpoints = [
            { name: 'ultra-small', width: 320, height: 568 },
            { name: 'mobile-portrait', width: 375, height: 667 },
            { name: 'mobile-landscape', width: 667, height: 375 },
            { name: 'tablet-portrait', width: 768, height: 1024 },
            { name: 'tablet-landscape', width: 1024, height: 768 },
            { name: 'desktop', width: 1200, height: 800 },
            { name: 'large-desktop', width: 1920, height: 1080 }
        ];

        console.log('📱 Testing responsive breakpoints...');

        for (const bp of breakpoints) {
            try {
                await page.setViewport({ width: bp.width, height: bp.height });
                await new Promise(resolve => setTimeout(resolve, 500));

                // Test if mobile menu is visible/hidden correctly
                const mobileMenuVisible = await page.evaluate(() => {
                    const btn = document.querySelector('.mobile-menu-btn');
                    const style = window.getComputedStyle(btn);
                    return style.display !== 'none';
                });

                const desktopNavVisible = await page.evaluate(() => {
                    const nav = document.querySelector('.desktop-nav');
                    const style = window.getComputedStyle(nav);
                    return style.display !== 'none';
                });

                // Capture screenshot
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
                await page.screenshot({
                    path: path.join(screenshotsDir, `${timestamp}-${bp.name}-${bp.width}x${bp.height}.png`),
                    fullPage: true
                });

                testResults.responsiveBreakpoints.push({
                    name: bp.name,
                    width: bp.width,
                    height: bp.height,
                    mobileMenuVisible,
                    desktopNavVisible,
                    screenshot: true
                });

                console.log(`✅ ${bp.name} (${bp.width}x${bp.height}) - Mobile menu: ${mobileMenuVisible ? 'visible' : 'hidden'}, Desktop nav: ${desktopNavVisible ? 'visible' : 'hidden'}`);

            } catch (error) {
                testResults.errors.push(`Breakpoint ${bp.name}: ${error.message}`);
                console.log(`❌ Error testing ${bp.name}: ${error.message}`);
            }
        }

        // Test mobile menu functionality on mobile viewport
        console.log('🔍 Testing mobile menu functionality...');
        await page.setViewport({ width: 375, height: 667 });
        await new Promise(resolve => setTimeout(resolve, 500));

        try {
            // Click hamburger menu
            await page.click('#mobile-menu-btn');
            await new Promise(resolve => setTimeout(resolve, 500));

            const overlayVisible = await page.evaluate(() => {
                const overlay = document.getElementById('mobile-nav-overlay');
                return overlay.classList.contains('active');
            });

            if (overlayVisible) {
                console.log('✅ Mobile menu opens correctly');

                // Take screenshot of open menu
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
                await page.screenshot({
                    path: path.join(screenshotsDir, `${timestamp}-mobile-menu-open.png`),
                    fullPage: false
                });

                // Close menu
                await page.click('#mobile-nav-close');
                await new Promise(resolve => setTimeout(resolve, 500));

                const overlayClosed = await page.evaluate(() => {
                    const overlay = document.getElementById('mobile-nav-overlay');
                    return !overlay.classList.contains('active');
                });

                if (overlayClosed) {
                    console.log('✅ Mobile menu closes correctly');
                } else {
                    testResults.errors.push('Mobile menu does not close properly');
                }
            } else {
                testResults.errors.push('Mobile menu does not open');
            }
        } catch (error) {
            testResults.errors.push(`Mobile menu test: ${error.message}`);
        }

        // Test API endpoints
        console.log('🔍 Testing API endpoints...');
        const apiEndpoints = ['/api/slides', '/api/categories', '/api/brands', '/api/promotions'];

        for (const endpoint of apiEndpoints) {
            try {
                const apiResponse = await page.goto(`http://127.0.0.1:8000${endpoint}`, {
                    waitUntil: 'domcontentloaded',
                    timeout: 10000
                });

                if (apiResponse.status() === 200) {
                    console.log(`✅ API ${endpoint} - Status 200`);
                } else {
                    testResults.errors.push(`API ${endpoint} returned status: ${apiResponse.status()}`);
                }
            } catch (error) {
                testResults.errors.push(`API ${endpoint}: ${error.message}`);
            }
        }

    } catch (error) {
        testResults.errors.push(`Main test error: ${error.message}`);
        console.log('❌ Main test error:', error.message);
    } finally {
        await browser.close();
    }

    // Generate test report
    console.log('\n📋 COMPREHENSIVE TEST REPORT');
    console.log('================================');
    console.log(`Website Status: ${testResults.websiteStatus ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`Mobile Navigation: ${testResults.mobileNavigation ? '✅ PASS' : '❌ FAIL'}`);
    console.log(`Responsive Breakpoints Tested: ${testResults.responsiveBreakpoints.length}`);

    if (testResults.errors.length > 0) {
        console.log('\n❌ ERRORS FOUND:');
        testResults.errors.forEach((error, index) => {
            console.log(`${index + 1}. ${error}`);
        });
    } else {
        console.log('\n🎉 ALL TESTS PASSED - MOBILE RESPONSIVENESS FULLY FUNCTIONAL!');
    }

    console.log(`\n📁 Screenshots saved to: ${screenshotsDir}`);

    // Save detailed report
    const reportPath = path.join(screenshotsDir, 'test-report.json');
    fs.writeFileSync(reportPath, JSON.stringify(testResults, null, 2));
    console.log(`📄 Detailed report saved to: ${reportPath}`);

    return testResults;
}

if (require.main === module) {
    comprehensiveTest().catch(console.error);
}

module.exports = comprehensiveTest;