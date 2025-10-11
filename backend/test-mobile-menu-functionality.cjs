const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function testMobileMenuFunctionality() {
    console.log('🔍 Testing mobile menu functionality...');

    let browser;
    try {
        browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });

        const page = await browser.newPage();

        // Test mobile viewport (iPhone SE)
        await page.setViewport({ width: 375, height: 667 });

        // Navigate to the website
        await page.goto('http://127.0.0.1:8000', {
            waitUntil: 'domcontentloaded',
            timeout: 10000
        });

        // Give time for JavaScript to initialize
        await new Promise(resolve => setTimeout(resolve, 2000));

        console.log('📱 Testing mobile menu interaction...');

        // Step 1: Check initial state
        const initialState = await page.evaluate(() => {
            const overlay = document.querySelector('.mobile-nav-overlay');
            const btn = document.querySelector('.mobile-menu-btn');
            return {
                overlayExists: !!overlay,
                overlayActive: overlay ? overlay.classList.contains('active') : false,
                btnExists: !!btn,
                btnActive: btn ? btn.classList.contains('active') : false
            };
        });

        console.log('Initial state:', initialState);

        // Step 2: Click hamburger menu to open
        await page.click('.mobile-menu-btn');
        await new Promise(resolve => setTimeout(resolve, 1000));

        const openState = await page.evaluate(() => {
            const overlay = document.querySelector('.mobile-nav-overlay');
            const btn = document.querySelector('.mobile-menu-btn');
            return {
                overlayActive: overlay ? overlay.classList.contains('active') : false,
                btnActive: btn ? btn.classList.contains('active') : false,
                bodyClass: document.body.className
            };
        });

        console.log('After opening:', openState);

        // Take screenshot of open menu
        const screenshotsDir = path.join(__dirname, 'mobile-menu-test');
        if (!fs.existsSync(screenshotsDir)) {
            fs.mkdirSync(screenshotsDir);
        }

        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-menu-open.png'),
            fullPage: false
        });

        // Step 3: Click close button to close
        await page.click('.mobile-nav-close');
        await new Promise(resolve => setTimeout(resolve, 1000));

        const closedState = await page.evaluate(() => {
            const overlay = document.querySelector('.mobile-nav-overlay');
            const btn = document.querySelector('.mobile-menu-btn');
            return {
                overlayActive: overlay ? overlay.classList.contains('active') : false,
                btnActive: btn ? btn.classList.contains('active') : false,
                bodyClass: document.body.className
            };
        });

        console.log('After closing:', closedState);

        // Take screenshot of closed menu
        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-menu-closed.png'),
            fullPage: false
        });

        console.log(`\n📁 Screenshots saved to: ${screenshotsDir}/`);

        // Check functionality
        const menuOpens = openState.overlayActive && openState.btnActive;
        const menuCloses = !closedState.overlayActive && !closedState.btnActive;

        console.log('\n🎯 FUNCTIONALITY TEST RESULTS:');
        console.log('==============================');
        console.log(`✅ Menu opens: ${menuOpens ? 'YES' : 'NO'}`);
        console.log(`✅ Menu closes: ${menuCloses ? 'YES' : 'NO'}`);

        const success = menuOpens && menuCloses;
        console.log(`\n🎯 OVERALL RESULT: ${success ? 'SUCCESS - Mobile menu fully functional!' : 'FAILED - Mobile menu has issues'}`);

        return success;

    } catch (error) {
        console.error('❌ Test error:', error.message);
        return false;
    } finally {
        if (browser) {
            await browser.close();
        }
    }
}

// Run the test
if (require.main === module) {
    testMobileMenuFunctionality()
        .then(success => {
            console.log(`\n📋 Test completed: ${success ? 'PASS' : 'FAIL'}`);
            process.exit(success ? 0 : 1);
        })
        .catch(console.error);
}

module.exports = testMobileMenuFunctionality;