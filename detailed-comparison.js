const puppeteer = require('puppeteer');
const path = require('path');

async function detailedComparison() {
    console.log('🔍 Detailed analysis of grow money card...');

    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: { width: 1200, height: 800 }
    });

    try {
        const page = await browser.newPage();
        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle0' });
        await page.waitForSelector('.customer-dashboard-section', { timeout: 10000 });

        await page.evaluate(() => {
            document.querySelector('.customer-dashboard-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        await new Promise(resolve => setTimeout(resolve, 2000));

        // Test 1: Current state
        console.log('📸 Test 1: Current state');
        const cardElement = await page.$('.dashboard-card.grow-money');
        await cardElement.screenshot({
            path: path.join(__dirname, 'backend', 'test-1-current.png'),
            type: 'png'
        });

        // Test 2: Add a colored background to the page to see transparency
        console.log('🎨 Test 2: Colored page background');
        await page.evaluate(() => {
            document.body.style.backgroundColor = '#ff69b4'; // Hot pink to easily see transparency
        });

        await new Promise(resolve => setTimeout(resolve, 1000));
        await cardElement.screenshot({
            path: path.join(__dirname, 'backend', 'test-2-pink-bg.png'),
            type: 'png'
        });

        // Test 3: Add background to card container specifically
        console.log('🎨 Test 3: Card container background');
        await page.evaluate(() => {
            const section = document.querySelector('.customer-dashboard-section');
            if (section) {
                section.style.backgroundColor = '#00ff00'; // Green background
            }
        });

        await new Promise(resolve => setTimeout(resolve, 1000));
        await cardElement.screenshot({
            path: path.join(__dirname, 'backend', 'test-3-green-section.png'),
            type: 'png'
        });

        // Test 4: Check if there are any white paths that could be causing issues
        const analysis = await page.evaluate(() => {
            const img = document.querySelector('.dashboard-card.grow-money .card-full-image');
            const card = document.querySelector('.dashboard-card.grow-money');

            // Get computed styles
            const imgStyles = window.getComputedStyle(img);
            const cardStyles = window.getComputedStyle(card);

            return {
                image: {
                    src: img.src,
                    display: imgStyles.display,
                    background: imgStyles.background,
                    backgroundColor: imgStyles.backgroundColor,
                    width: imgStyles.width,
                    height: imgStyles.height,
                    objectFit: imgStyles.objectFit
                },
                card: {
                    background: cardStyles.background,
                    backgroundColor: cardStyles.backgroundColor,
                    borderRadius: cardStyles.borderRadius,
                    boxShadow: cardStyles.boxShadow
                }
            };
        });

        console.log('📊 Analysis results:', JSON.stringify(analysis, null, 2));

        console.log('✅ Test screenshots saved:');
        console.log('   - test-1-current.png (normal state)');
        console.log('   - test-2-pink-bg.png (with pink body background)');
        console.log('   - test-3-green-section.png (with green section background)');

    } catch (error) {
        console.error('❌ Error during detailed comparison:', error.message);
    } finally {
        await browser.close();
    }
}

detailedComparison();