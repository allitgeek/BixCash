const puppeteer = require('puppeteer');
const path = require('path');

async function testNewImages() {
    console.log('🔍 Testing new PNG images on the website...');

    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: { width: 1200, height: 800 }
    });

    try {
        const page = await browser.newPage();

        // Listen for console logs and errors from the page
        page.on('console', msg => {
            if (msg.type() === 'error') {
                console.log('❌ Browser error:', msg.text());
            }
        });

        // Listen for failed requests
        page.on('requestfailed', req => {
            console.log('❌ Failed request:', req.url());
        });

        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle0' });

        // Wait for the dashboard section to load
        await page.waitForSelector('.customer-dashboard-section', { timeout: 10000 });

        // Scroll to dashboard section
        await page.evaluate(() => {
            document.querySelector('.customer-dashboard-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        await new Promise(resolve => setTimeout(resolve, 2000));

        // Check all the images we updated
        const imageAnalysis = await page.evaluate(() => {
            const results = [];

            // Cash back icon
            const cashBackImg = document.querySelector('.nav-button.cash-back-btn img');
            if (cashBackImg) {
                results.push({
                    name: 'Cash Back Icon',
                    src: cashBackImg.src,
                    loaded: cashBackImg.complete && cashBackImg.naturalHeight !== 0,
                    width: cashBackImg.naturalWidth,
                    height: cashBackImg.naturalHeight
                });
            }

            // Grow your money icon
            const growMoneyImg = document.querySelector('.dashboard-card.grow-money img');
            if (growMoneyImg) {
                results.push({
                    name: 'Grow Your Money Icon',
                    src: growMoneyImg.src,
                    loaded: growMoneyImg.complete && growMoneyImg.naturalHeight !== 0,
                    width: growMoneyImg.naturalWidth,
                    height: growMoneyImg.naturalHeight
                });
            }

            // Rewards icon
            const rewardsImg = document.querySelector('.dashboard-card.rewards img');
            if (rewardsImg) {
                results.push({
                    name: 'Rewards Icon',
                    src: rewardsImg.src,
                    loaded: rewardsImg.complete && rewardsImg.naturalHeight !== 0,
                    width: rewardsImg.naturalWidth,
                    height: rewardsImg.naturalHeight
                });
            }

            // Shop and earn icon
            const shopEarnImg = document.querySelector('.dashboard-card.shop-earn img');
            if (shopEarnImg) {
                results.push({
                    name: 'Shop & Earn Icon',
                    src: shopEarnImg.src,
                    loaded: shopEarnImg.complete && shopEarnImg.naturalHeight !== 0,
                    width: shopEarnImg.naturalWidth,
                    height: shopEarnImg.naturalHeight
                });
            }

            return results;
        });

        console.log('📊 Image Analysis Results:');
        imageAnalysis.forEach(img => {
            console.log(`\n🖼️  ${img.name}:`);
            console.log(`   Source: ${img.src}`);
            console.log(`   Loaded: ${img.loaded ? '✅' : '❌'}`);
            console.log(`   Dimensions: ${img.width}x${img.height}`);
        });

        // Take a screenshot of the dashboard section
        await page.screenshot({
            path: path.join(__dirname, 'backend', 'current-dashboard-state.png'),
            type: 'png',
            fullPage: false
        });

        console.log('\n📸 Screenshot saved as: current-dashboard-state.png');

        // Check if browser cache might be causing issues
        console.log('\n🔄 Checking cache status...');
        const cacheInfo = await page.evaluate(() => {
            return {
                userAgent: navigator.userAgent,
                cookieEnabled: navigator.cookieEnabled,
                onLine: navigator.onLine
            };
        });

        console.log('Browser info:', cacheInfo);

    } catch (error) {
        console.error('❌ Error during image testing:', error.message);
    } finally {
        await browser.close();
    }
}

testNewImages();