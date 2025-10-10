const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function debugMobileResponsiveness() {
    console.log('🔍 Starting mobile responsiveness debug test...');

    const browser = await puppeteer.launch({
        headless: false, // Show browser for debugging
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
        defaultViewport: null
    });

    const page = await browser.newPage();
    const screenshotsDir = path.join(__dirname, 'mobile-debug-screenshots');

    if (!fs.existsSync(screenshotsDir)) {
        fs.mkdirSync(screenshotsDir);
    }

    try {
        console.log('📱 Loading website...');
        await page.goto('http://127.0.0.1:8000', {
            waitUntil: 'networkidle0',
            timeout: 15000
        });

        // Test desktop view first
        console.log('🖥️ Testing desktop view (1200x800)...');
        await page.setViewport({ width: 1200, height: 800 });
        await page.waitForTimeout(1000);

        const desktopElements = await page.evaluate(() => {
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            const desktopNav = document.querySelector('.desktop-nav');
            const desktopAuth = document.querySelector('.desktop-auth');

            return {
                mobileBtn: {
                    exists: !!mobileBtn,
                    display: mobileBtn ? window.getComputedStyle(mobileBtn).display : 'not found',
                    visibility: mobileBtn ? window.getComputedStyle(mobileBtn).visibility : 'not found'
                },
                desktopNav: {
                    exists: !!desktopNav,
                    display: desktopNav ? window.getComputedStyle(desktopNav).display : 'not found'
                },
                desktopAuth: {
                    exists: !!desktopAuth,
                    display: desktopAuth ? window.getComputedStyle(desktopAuth).display : 'not found'
                }
            };
        });

        console.log('Desktop View Results:');
        console.log('- Mobile button exists:', desktopElements.mobileBtn.exists);
        console.log('- Mobile button display:', desktopElements.mobileBtn.display);
        console.log('- Desktop nav exists:', desktopElements.desktopNav.exists);
        console.log('- Desktop nav display:', desktopElements.desktopNav.display);

        // Take desktop screenshot
        await page.screenshot({
            path: path.join(screenshotsDir, 'desktop-1200x800.png'),
            fullPage: true
        });

        // Test mobile view (iPhone SE)
        console.log('📱 Testing mobile view (iPhone SE - 375x667)...');
        await page.setViewport({ width: 375, height: 667 });
        await page.waitForTimeout(2000); // Wait for CSS to apply

        const mobileElements = await page.evaluate(() => {
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            const desktopNav = document.querySelector('.desktop-nav');
            const desktopAuth = document.querySelector('.desktop-auth');
            const mobileOverlay = document.querySelector('.mobile-nav-overlay');

            // Get all styles for mobile button
            const mobileBtnStyles = mobileBtn ? {
                display: window.getComputedStyle(mobileBtn).display,
                visibility: window.getComputedStyle(mobileBtn).visibility,
                opacity: window.getComputedStyle(mobileBtn).opacity,
                position: window.getComputedStyle(mobileBtn).position,
                zIndex: window.getComputedStyle(mobileBtn).zIndex,
                width: window.getComputedStyle(mobileBtn).width,
                height: window.getComputedStyle(mobileBtn).height,
                backgroundColor: window.getComputedStyle(mobileBtn).backgroundColor,
                border: window.getComputedStyle(mobileBtn).border
            } : 'not found';

            return {
                viewport: {
                    width: window.innerWidth,
                    height: window.innerHeight
                },
                mobileBtn: {
                    exists: !!mobileBtn,
                    styles: mobileBtnStyles,
                    innerHTML: mobileBtn ? mobileBtn.innerHTML : 'not found',
                    classList: mobileBtn ? Array.from(mobileBtn.classList) : 'not found'
                },
                desktopNav: {
                    exists: !!desktopNav,
                    display: desktopNav ? window.getComputedStyle(desktopNav).display : 'not found'
                },
                desktopAuth: {
                    exists: !!desktopAuth,
                    display: desktopAuth ? window.getComputedStyle(desktopAuth).display : 'not found'
                },
                mobileOverlay: {
                    exists: !!mobileOverlay,
                    display: mobileOverlay ? window.getComputedStyle(mobileOverlay).display : 'not found'
                }
            };
        });

        console.log('\nMobile View Results:');
        console.log('- Viewport:', mobileElements.viewport);
        console.log('- Mobile button exists:', mobileElements.mobileBtn.exists);
        console.log('- Mobile button classes:', mobileElements.mobileBtn.classList);
        console.log('- Mobile button innerHTML:', mobileElements.mobileBtn.innerHTML);
        if (mobileElements.mobileBtn.styles !== 'not found') {
            console.log('- Mobile button styles:');
            Object.entries(mobileElements.mobileBtn.styles).forEach(([key, value]) => {
                console.log(`  ${key}: ${value}`);
            });
        }
        console.log('- Desktop nav display:', mobileElements.desktopNav.display);
        console.log('- Desktop auth display:', mobileElements.desktopAuth.display);

        // Take mobile screenshot
        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-375x667.png'),
            fullPage: true
        });

        // Test if we can force show the mobile button
        console.log('🔧 Attempting to force show mobile button...');
        await page.evaluate(() => {
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            if (mobileBtn) {
                mobileBtn.style.display = 'flex';
                mobileBtn.style.backgroundColor = 'red';
                mobileBtn.style.border = '2px solid blue';
                mobileBtn.style.zIndex = '9999';
                mobileBtn.style.position = 'relative';
            }
        });

        await page.waitForTimeout(1000);
        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-forced-button.png'),
            fullPage: true
        });

        // Check CSS media queries
        console.log('🎨 Checking CSS media query support...');
        const mediaQueryTest = await page.evaluate(() => {
            // Test if media queries are working
            const testDiv = document.createElement('div');
            testDiv.style.cssText = `
                display: none;
                @media (max-width: 768px) {
                    display: block;
                }
            `;
            document.body.appendChild(testDiv);

            const mediaQueryWorks = window.getComputedStyle(testDiv).display === 'block';
            document.body.removeChild(testDiv);

            return {
                mediaQuerySupport: mediaQueryWorks,
                userAgent: navigator.userAgent,
                screenWidth: screen.width,
                screenHeight: screen.height,
                devicePixelRatio: window.devicePixelRatio
            };
        });

        console.log('Media Query Test Results:');
        console.log('- Media queries working:', mediaQueryTest.mediaQuerySupport);
        console.log('- User agent:', mediaQueryTest.userAgent);
        console.log('- Screen dimensions:', `${mediaQueryTest.screenWidth}x${mediaQueryTest.screenHeight}`);
        console.log('- Device pixel ratio:', mediaQueryTest.devicePixelRatio);

        // Try clicking the mobile button if it exists
        try {
            const mobileBtn = await page.$('.mobile-menu-btn');
            if (mobileBtn) {
                console.log('🖱️ Attempting to click mobile button...');
                await mobileBtn.click();
                await page.waitForTimeout(1000);

                const overlayVisible = await page.evaluate(() => {
                    const overlay = document.querySelector('.mobile-nav-overlay');
                    return overlay ? overlay.classList.contains('active') : false;
                });

                console.log('- Mobile overlay opened:', overlayVisible);

                await page.screenshot({
                    path: path.join(screenshotsDir, 'mobile-menu-opened.png'),
                    fullPage: true
                });
            }
        } catch (error) {
            console.log('- Could not click mobile button:', error.message);
        }

    } catch (error) {
        console.error('❌ Test error:', error.message);
    } finally {
        await browser.close();
        console.log(`\n📁 Screenshots saved to: ${screenshotsDir}`);
        console.log('📋 Debug test completed!');
    }
}

if (require.main === module) {
    debugMobileResponsiveness().catch(console.error);
}

module.exports = debugMobileResponsiveness;