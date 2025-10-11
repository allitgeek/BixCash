const puppeteer = require('puppeteer');
const path = require('path');

async function compareDesigns() {
    console.log('🔍 Comparing grow money card designs...');

    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: { width: 1200, height: 800 }
    });

    try {
        const page = await browser.newPage();

        // Navigate to the local site
        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle0' });
        await page.waitForSelector('.customer-dashboard-section', { timeout: 10000 });

        // Scroll to the customer dashboard section
        await page.evaluate(() => {
            document.querySelector('.customer-dashboard-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        await new Promise(resolve => setTimeout(resolve, 2000));

        console.log('📸 Taking current state screenshot...');
        const cardElement = await page.$('.dashboard-card.grow-money');
        await cardElement.screenshot({
            path: path.join(__dirname, 'backend', 'grow-money-current.png'),
            type: 'png'
        });

        // Now temporarily hide the SVG to see what the card looks like without it
        console.log('🔍 Testing card without SVG image...');
        await page.evaluate(() => {
            const img = document.querySelector('.dashboard-card.grow-money .card-full-image');
            if (img) {
                img.style.display = 'none';
            }
        });

        await new Promise(resolve => setTimeout(resolve, 1000));

        console.log('📸 Taking screenshot without SVG...');
        await cardElement.screenshot({
            path: path.join(__dirname, 'backend', 'grow-money-without-svg.png'),
            type: 'png'
        });

        // Test with different background colors to see transparency
        console.log('🎨 Testing with colored background...');
        await page.evaluate(() => {
            const card = document.querySelector('.dashboard-card.grow-money');
            const img = document.querySelector('.dashboard-card.grow-money .card-full-image');
            if (card) {
                card.style.backgroundColor = '#ff0000'; // Red background to test transparency
            }
            if (img) {
                img.style.display = 'block'; // Show the image again
            }
        });

        await new Promise(resolve => setTimeout(resolve, 1000));

        console.log('📸 Taking screenshot with red background...');
        await cardElement.screenshot({
            path: path.join(__dirname, 'backend', 'grow-money-red-bg.png'),
            type: 'png'
        });

        console.log('✅ All comparison screenshots saved:');
        console.log('   - grow-money-current.png (normal state)');
        console.log('   - grow-money-without-svg.png (card without SVG)');
        console.log('   - grow-money-red-bg.png (with red background to test transparency)');

        // Check what colors are actually in the SVG
        const svgColors = await page.evaluate(() => {
            const img = document.querySelector('.dashboard-card.grow-money .card-full-image');
            return {
                src: img ? img.src : null,
                naturalWidth: img ? img.naturalWidth : null,
                naturalHeight: img ? img.naturalHeight : null,
                computed: {
                    backgroundColor: img ? window.getComputedStyle(img).backgroundColor : null,
                    background: img ? window.getComputedStyle(img).background : null
                }
            };
        });

        console.log('🎨 SVG properties:', JSON.stringify(svgColors, null, 2));

    } catch (error) {
        console.error('❌ Error during comparison:', error.message);
    } finally {
        await browser.close();
    }
}

compareDesigns();