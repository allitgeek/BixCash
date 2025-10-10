const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Simple responsive verification
async function quickTest() {
    console.log('📱 Quick responsive test starting...');

    const browser = await puppeteer.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });

    const page = await browser.newPage();
    const screenshotsDir = path.join(__dirname, 'responsive-screenshots');

    if (!fs.existsSync(screenshotsDir)) {
        fs.mkdirSync(screenshotsDir);
    }

    try {
        await page.goto('http://127.0.0.1:8000', {
            waitUntil: 'domcontentloaded',
            timeout: 10000
        });

        // Test key breakpoints
        const breakpoints = [
            { name: 'mobile', width: 375, height: 667 },
            { name: 'tablet', width: 768, height: 1024 },
            { name: 'desktop', width: 1200, height: 800 }
        ];

        for (const bp of breakpoints) {
            await page.setViewport({ width: bp.width, height: bp.height });
            await page.waitForTimeout(1000);

            const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
            await page.screenshot({
                path: path.join(screenshotsDir, `${timestamp}-${bp.name}.png`),
                fullPage: true
            });

            console.log(`✅ ${bp.name} screenshot captured`);
        }

    } catch (error) {
        console.log('⚠️ Test issue:', error.message);
    } finally {
        await browser.close();
        console.log('📁 Screenshots saved to responsive-screenshots/');
    }
}

if (require.main === module) {
    quickTest().catch(console.error);
}