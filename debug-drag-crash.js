const puppeteer = require('puppeteer');

async function debugDragCrash() {
    console.log('🔍 Debugging Drag-and-Drop Crash Issue...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: true,
        defaultViewport: { width: 1400, height: 900 }
    });

    const page = await browser.newPage();

    // Comprehensive error logging
    page.on('console', msg => {
        const type = msg.type();
        console.log(`🔍 [${type.toUpperCase()}]:`, msg.text());
    });

    page.on('pageerror', error => {
        console.log('❌ PAGE ERROR:', error.message);
    });

    page.on('error', error => {
        console.log('❌ BROWSER ERROR:', error.message);
    });

    page.on('requestfailed', request => {
        console.log('❌ REQUEST FAILED:', request.url(), request.failure().errorText);
    });

    try {
        console.log('📍 Going to admin categories page...');
        await page.goto('http://127.0.0.1:8000/admin/login', { waitUntil: 'networkidle0' });

        console.log('🔐 Logging in...');
        await page.type('#email', 'admin@bixcash.com');
        await page.type('#password', 'admin123');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ waitUntil: 'networkidle0' });

        console.log('📊 Going to categories...');
        await page.goto('http://127.0.0.1:8000/admin/categories', { waitUntil: 'networkidle0' });

        // Wait for page to load
        await page.waitForSelector('#categoriesTable', { timeout: 10000 });
        await new Promise(resolve => setTimeout(resolve, 3000));

        // Check the current state
        const pageState = await page.evaluate(() => {
            try {
                const tbody = document.getElementById('sortableCategories');
                const rows = tbody ? tbody.querySelectorAll('.category-row') : [];
                const scripts = document.querySelectorAll('script');

                // Check for JavaScript errors
                let hasErrors = false;
                let errorMessage = '';

                try {
                    // Try to access drag-related variables
                    if (typeof window.dragState !== 'undefined') {
                        console.log('dragState exists');
                    }
                } catch (e) {
                    hasErrors = true;
                    errorMessage = e.message;
                }

                return {
                    tbody: !!tbody,
                    rowCount: rows.length,
                    scriptCount: scripts.length,
                    hasErrors: hasErrors,
                    errorMessage: errorMessage,
                    url: window.location.href,
                    title: document.title
                };
            } catch (error) {
                return {
                    error: error.message,
                    stack: error.stack
                };
            }
        });

        console.log('\n🔧 Page State Analysis:');
        console.log('   URL:', pageState.url);
        console.log('   Title:', pageState.title);
        console.log('   tbody exists:', pageState.tbody);
        console.log('   Row count:', pageState.rowCount);
        console.log('   Script count:', pageState.scriptCount);
        console.log('   Has errors:', pageState.hasErrors);
        if (pageState.errorMessage) {
            console.log('   Error:', pageState.errorMessage);
        }
        if (pageState.error) {
            console.log('   Critical Error:', pageState.error);
        }

        console.log('\n⏸️ Browser paused for manual inspection...');
        console.log('   Check browser console for errors');
        console.log('   Try dragging an item manually');
        console.log('   Press any key to continue...');

        // Keep browser open for manual inspection
        await new Promise(resolve => {
            process.stdin.once('data', resolve);
        });

    } catch (error) {
        console.error('❌ Debug failed:', error.message);
    } finally {
        await browser.close();
    }
}

debugDragCrash().catch(console.error);