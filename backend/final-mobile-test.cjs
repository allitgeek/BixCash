const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function testMobileResponsiveness() {
    console.log('🔍 Testing mobile responsiveness after CSS fix...');

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

        // Wait a bit for CSS to apply
        await page.waitForTimeout(2000);

        // Check if mobile menu button is visible
        const mobileButtonVisible = await page.evaluate(() => {
            const btn = document.querySelector('.mobile-menu-btn');
            if (!btn) return { exists: false, reason: 'Element not found' };

            const style = window.getComputedStyle(btn);
            const rect = btn.getBoundingClientRect();

            return {
                exists: true,
                display: style.display,
                visibility: style.visibility,
                opacity: style.opacity,
                zIndex: style.zIndex,
                width: rect.width,
                height: rect.height,
                isVisible: style.display !== 'none' && style.visibility !== 'hidden' && style.opacity !== '0',
                hasContent: btn.children.length > 0
            };
        });

        // Check if desktop navigation is hidden
        const desktopNavHidden = await page.evaluate(() => {
            const nav = document.querySelector('.desktop-nav');
            if (!nav) return { exists: false };

            const style = window.getComputedStyle(nav);
            return {
                exists: true,
                display: style.display,
                isHidden: style.display === 'none'
            };
        });

        console.log('\n📱 MOBILE TEST RESULTS:');
        console.log('======================');
        console.log('Mobile Button:', JSON.stringify(mobileButtonVisible, null, 2));
        console.log('Desktop Nav:', JSON.stringify(desktopNavHidden, null, 2));

        // Test if mobile menu can be clicked
        if (mobileButtonVisible.isVisible) {
            console.log('\n🖱️ Testing mobile menu functionality...');

            try {
                await page.click('.mobile-menu-btn');
                await page.waitForTimeout(1000);

                const overlayActive = await page.evaluate(() => {
                    const overlay = document.querySelector('.mobile-nav-overlay');
                    return overlay ? overlay.classList.contains('active') : false;
                });

                console.log('Mobile menu opens:', overlayActive ? '✅ YES' : '❌ NO');

                if (overlayActive) {
                    // Try to close it
                    await page.click('.mobile-nav-close');
                    await page.waitForTimeout(500);

                    const overlayClosed = await page.evaluate(() => {
                        const overlay = document.querySelector('.mobile-nav-overlay');
                        return overlay ? !overlay.classList.contains('active') : false;
                    });

                    console.log('Mobile menu closes:', overlayClosed ? '✅ YES' : '❌ NO');
                }
            } catch (error) {
                console.log('❌ Mobile menu interaction failed:', error.message);
            }
        }

        // Take a screenshot for verification
        const screenshotsDir = path.join(__dirname, 'mobile-test-final');
        if (!fs.existsSync(screenshotsDir)) {
            fs.mkdirSync(screenshotsDir);
        }

        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-final-test.png'),
            fullPage: true
        });

        console.log(`\n📁 Screenshot saved to: ${screenshotsDir}/mobile-final-test.png`);

        // Final verdict
        if (mobileButtonVisible.isVisible && desktopNavHidden.isHidden) {
            console.log('\n🎉 SUCCESS: Mobile responsiveness is working!');
            return true;
        } else {
            console.log('\n❌ FAILED: Mobile responsiveness still has issues');
            return false;
        }

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
    testMobileResponsiveness()
        .then(success => {
            console.log(`\n📋 Test completed: ${success ? 'PASS' : 'FAIL'}`);
            process.exit(success ? 0 : 1);
        })
        .catch(console.error);
}

module.exports = testMobileResponsiveness;