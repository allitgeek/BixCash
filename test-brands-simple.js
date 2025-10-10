const puppeteer = require('puppeteer');

async function testBrandsSimple() {
    console.log('🔧 Testing Brands Drag-and-Drop Implementation...\\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: false,
        defaultViewport: { width: 1400, height: 900 }
    });

    const page = await browser.newPage();

    // Console logging
    page.on('console', msg => {
        console.log('🔍 Browser:', msg.text());
    });

    page.on('error', error => {
        console.log('❌ Page Error:', error.message);
    });

    try {
        console.log('📍 Going directly to admin brands page...');
        await page.goto('http://127.0.0.1:8000/admin/brands', {
            waitUntil: 'networkidle2',
            timeout: 10000
        });

        // Check if we're redirected to login
        const currentUrl = page.url();
        if (currentUrl.includes('/admin/login')) {
            console.log('🔐 Need to login first...');
            await page.type('#email', 'admin@bixcash.com');
            await page.type('#password', 'admin123');
            await page.click('button[type="submit"]');
            await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 });

            // Go to brands page after login
            await page.goto('http://127.0.0.1:8000/admin/brands', {
                waitUntil: 'networkidle2',
                timeout: 10000
            });
        }

        console.log('📊 Checking brands page elements...');

        // Wait for the page to load
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Check if the drag-and-drop elements exist
        const systemCheck = await page.evaluate(() => {
            const table = document.getElementById('brandsTable');
            const tbody = document.getElementById('sortableBrands');
            const rows = tbody ? tbody.querySelectorAll('.brand-row') : [];
            const dragHandles = document.querySelectorAll('.drag-handle');
            const orderBadges = document.querySelectorAll('.order-badge');

            const dragScript = document.querySelector('script');
            const hasJavaScript = document.body.innerHTML.includes('initializeDragAndDrop');

            return {
                tableExists: !!table,
                tbodyExists: !!tbody,
                rowCount: rows.length,
                dragHandleCount: dragHandles.length,
                orderBadgeCount: orderBadges.length,
                hasJavaScript: hasJavaScript,
                pageTitle: document.title,
                bodyContent: document.body.innerHTML.length
            };
        });

        console.log('\\n🔧 System Check Results:');
        console.log(`   Page Title: ${systemCheck.pageTitle}`);
        console.log(`   Table exists: ${systemCheck.tableExists}`);
        console.log(`   Tbody exists: ${systemCheck.tbodyExists}`);
        console.log(`   Brand rows: ${systemCheck.rowCount}`);
        console.log(`   Drag handles: ${systemCheck.dragHandleCount}`);
        console.log(`   Order badges: ${systemCheck.orderBadgeCount}`);
        console.log(`   JavaScript loaded: ${systemCheck.hasJavaScript}`);
        console.log(`   Page content length: ${systemCheck.bodyContent} characters`);

        if (systemCheck.rowCount > 0) {
            // Get brand information
            const brandInfo = await page.evaluate(() => {
                const rows = document.querySelectorAll('.brand-row');
                return Array.from(rows).map((row, index) => ({
                    id: row.getAttribute('data-brand-id'),
                    name: row.querySelector('strong')?.textContent || 'No name',
                    order: row.querySelector('.order-badge')?.textContent || index + 1,
                    hasDragHandle: !!row.querySelector('.drag-handle'),
                    isDraggable: row.draggable
                }));
            });

            console.log('\\n📋 Current Brands:');
            brandInfo.forEach(brand => {
                console.log(`   ${brand.order}. ${brand.name} (ID: ${brand.id}) - Draggable: ${brand.isDraggable}, Handle: ${brand.hasDragHandle}`);
            });

            // Test if drag-and-drop system is initialized
            const dragSystemTest = await page.evaluate(() => {
                const firstRow = document.querySelector('.brand-row');
                if (!firstRow) return { initialized: false, error: 'No rows found' };

                return {
                    initialized: true,
                    draggable: firstRow.draggable,
                    hasEventListeners: firstRow.ondragstart !== null || firstRow.getAttribute('draggable') === 'true',
                    dragHandleExists: !!firstRow.querySelector('.drag-handle'),
                    orderBadgeExists: !!firstRow.querySelector('.order-badge')
                };
            });

            console.log('\\n🎯 Drag System Status:');
            console.log(`   System initialized: ${dragSystemTest.initialized}`);
            console.log(`   Rows draggable: ${dragSystemTest.draggable}`);
            console.log(`   Event listeners: ${dragSystemTest.hasEventListeners}`);
            console.log(`   Drag handles: ${dragSystemTest.dragHandleExists}`);
            console.log(`   Order badges: ${dragSystemTest.orderBadgeExists}`);

            if (dragSystemTest.initialized && brandInfo.length >= 2) {
                console.log('\\n✅ Brands drag-and-drop system appears to be properly implemented!');
            } else if (brandInfo.length < 2) {
                console.log('\\n⚠️ Need at least 2 brands to test drag-and-drop functionality');
            } else {
                console.log('\\n❌ Drag-and-drop system not properly initialized');
            }
        } else {
            console.log('\\n⚠️ No brands found in the database');
        }

        await page.screenshot({
            path: 'backend/brands-implementation-check.png',
            fullPage: false
        });
        console.log('\\n📸 Screenshot saved: backend/brands-implementation-check.png');

    } catch (error) {
        console.error('❌ Test failed:', error.message);
    } finally {
        await browser.close();
    }
}

testBrandsSimple().catch(console.error);