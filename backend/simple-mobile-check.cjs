const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function simpleMobileCheck() {
    console.log('🔍 Simple mobile responsiveness check...');

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

        // Give time for JavaScript to initialize without waitForTimeout
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Check if mobile elements exist and are visible
        const mobileCheck = await page.evaluate(() => {
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            const desktopNav = document.querySelector('.desktop-nav');
            const desktopAuth = document.querySelector('.desktop-auth');

            const getElementInfo = (element) => {
                if (!element) return { exists: false };

                const style = window.getComputedStyle(element);
                const rect = element.getBoundingClientRect();

                return {
                    exists: true,
                    display: style.display,
                    visibility: style.visibility,
                    opacity: style.opacity,
                    width: rect.width,
                    height: rect.height,
                    isVisible: style.display !== 'none' && style.visibility !== 'hidden' && parseFloat(style.opacity) > 0
                };
            };

            return {
                viewport: {
                    width: window.innerWidth,
                    height: window.innerHeight
                },
                mobileBtn: getElementInfo(mobileBtn),
                desktopNav: getElementInfo(desktopNav),
                desktopAuth: getElementInfo(desktopAuth),
                bodyClass: document.body.className
            };
        });

        console.log('\n📱 MOBILE CHECK RESULTS:');
        console.log('======================');
        console.log(`Viewport: ${mobileCheck.viewport.width}x${mobileCheck.viewport.height}`);
        console.log(`Mobile Button Exists: ${mobileCheck.mobileBtn.exists}`);
        if (mobileCheck.mobileBtn.exists) {
            console.log(`Mobile Button Visible: ${mobileCheck.mobileBtn.isVisible}`);
            console.log(`Mobile Button Display: ${mobileCheck.mobileBtn.display}`);
            console.log(`Mobile Button Size: ${mobileCheck.mobileBtn.width}x${mobileCheck.mobileBtn.height}`);
        }
        console.log(`Desktop Nav Hidden: ${!mobileCheck.desktopNav.isVisible}`);
        console.log(`Desktop Auth Hidden: ${!mobileCheck.desktopAuth.isVisible}`);

        // Take screenshot
        const screenshotsDir = path.join(__dirname, 'mobile-check-results');
        if (!fs.existsSync(screenshotsDir)) {
            fs.mkdirSync(screenshotsDir);
        }

        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-check.png'),
            fullPage: true
        });

        console.log(`\n📁 Screenshot saved to: ${screenshotsDir}/mobile-check.png`);

        // Final verdict
        const success = mobileCheck.mobileBtn.exists && mobileCheck.mobileBtn.isVisible && !mobileCheck.desktopNav.isVisible;
        console.log(`\n🎯 RESULT: ${success ? 'SUCCESS - Mobile responsiveness working!' : 'FAILED - Mobile responsiveness not working'}`);

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
    simpleMobileCheck()
        .then(success => {
            console.log(`\n📋 Test completed: ${success ? 'PASS' : 'FAIL'}`);
            process.exit(success ? 0 : 1);
        })
        .catch(console.error);
}

module.exports = simpleMobileCheck;