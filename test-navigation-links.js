const puppeteer = require('puppeteer');
const path = require('path');

async function testNavigationLinks() {
    console.log('🔍 Testing navigation icon changes...');

    const browser = await puppeteer.launch({
        headless: false,
        defaultViewport: { width: 1200, height: 800 }
    });

    try {
        const page = await browser.newPage();

        // Monitor network requests for images
        page.on('request', request => {
            if (request.url().includes('/images/elements/')) {
                console.log('📥 Requesting:', request.url());
            }
        });

        page.on('response', response => {
            if (response.url().includes('/images/elements/')) {
                console.log(`📤 Response ${response.status()}: ${response.url()}`);
            }
        });

        console.log('🔍 Loading page...');
        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle0' });

        // Wait for dashboard section
        await page.waitForSelector('.customer-dashboard-section', { timeout: 10000 });

        // Scroll to dashboard
        await page.evaluate(() => {
            document.querySelector('.customer-dashboard-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        await new Promise(resolve => setTimeout(resolve, 3000));

        // Check all navigation icons
        const navigationAnalysis = await page.evaluate(() => {
            const results = [];

            // Check each navigation icon
            const icons = [
                { selector: '.nav-button.cash-back-btn img', name: 'Cash Back' },
                { selector: '.nav-button.wallet-btn img', name: 'Wallet' },
                { selector: '.nav-button.transaction-btn img', name: 'Transaction' },
                { selector: '.nav-button.receipt-btn img', name: 'Receipt' },
                { selector: '.nav-button.withdrawal-btn img', name: 'Withdrawal' }
            ];

            icons.forEach(({ selector, name }) => {
                const img = document.querySelector(selector);
                if (img) {
                    const rect = img.getBoundingClientRect();

                    results.push({
                        name: name,
                        selector: selector,
                        src: img.src,
                        alt: img.alt,
                        loaded: img.complete && img.naturalHeight !== 0,
                        naturalWidth: img.naturalWidth,
                        naturalHeight: img.naturalHeight,
                        displayWidth: Math.round(rect.width),
                        displayHeight: Math.round(rect.height),
                        visible: rect.width > 0 && rect.height > 0
                    });
                } else {
                    // Check if there's a CSS-created icon instead
                    const parentDiv = document.querySelector(selector.replace(' img', ''));
                    if (parentDiv) {
                        const hasCSS = parentDiv.innerHTML.includes('div');
                        results.push({
                            name: name,
                            selector: selector,
                            found: false,
                            hasCSS: hasCSS,
                            innerHTML: parentDiv.innerHTML.substring(0, 100) + '...'
                        });
                    } else {
                        results.push({
                            name: name,
                            selector: selector,
                            found: false,
                            hasCSS: false
                        });
                    }
                }
            });

            return results;
        });

        console.log('\n📊 NAVIGATION ICONS ANALYSIS:');
        console.log('='.repeat(60));

        navigationAnalysis.forEach(icon => {
            console.log(`\n🖼️  ${icon.name}:`);
            if (icon.found === false) {
                console.log('   ❌ IMG element not found');
                if (icon.hasCSS) {
                    console.log('   ⚠️  Still has CSS-created content:', icon.innerHTML);
                } else {
                    console.log('   ❌ Parent element not found');
                }
                return;
            }

            console.log(`   Source: ${icon.src}`);
            console.log(`   Alt: ${icon.alt}`);
            console.log(`   Loaded: ${icon.loaded ? '✅' : '❌'}`);
            console.log(`   Natural Size: ${icon.naturalWidth}x${icon.naturalHeight}`);
            console.log(`   Display Size: ${icon.displayWidth}x${icon.displayHeight}`);
            console.log(`   Visible: ${icon.visible ? '✅' : '❌'}`);
        });

        // Take screenshot of navigation area
        const navElement = await page.$('.bottom-nav-icons');
        if (navElement) {
            await navElement.screenshot({
                path: path.join(__dirname, 'backend', 'navigation-icons-current.png'),
                type: 'png'
            });
            console.log('\n📸 Navigation screenshot saved as: navigation-icons-current.png');
        }

        // Also take full dashboard screenshot
        const dashboardElement = await page.$('.customer-dashboard-section');
        if (dashboardElement) {
            await dashboardElement.screenshot({
                path: path.join(__dirname, 'backend', 'full-dashboard-current.png'),
                type: 'png'
            });
            console.log('📸 Full dashboard screenshot saved as: full-dashboard-current.png');
        }

    } catch (error) {
        console.error('❌ Error during navigation test:', error.message);
    } finally {
        await browser.close();
    }
}

testNavigationLinks();