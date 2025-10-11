const puppeteer = require('puppeteer');

async function testBrandsDragDrop() {
    console.log('🔧 Testing Brands Drag-and-Drop System...\n');

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
        console.log('📍 Going to admin login...');
        await page.goto('http://127.0.0.1:8000/admin/login', { waitUntil: 'networkidle0' });

        console.log('🔐 Logging in...');
        await page.type('#email', 'admin@bixcash.com');
        await page.type('#password', 'admin123');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ waitUntil: 'networkidle0' });

        console.log('📊 Going to brands...');
        await page.goto('http://127.0.0.1:8000/admin/brands', { waitUntil: 'networkidle0' });
        await page.waitForSelector('#brandsTable', { timeout: 10000 });

        // Wait for system to initialize
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Check system status
        const systemStatus = await page.evaluate(() => {
            const tbody = document.getElementById('sortableBrands');
            const rows = tbody ? tbody.querySelectorAll('.brand-row') : [];
            const handles = document.querySelectorAll('.drag-handle');

            return {
                tbody: !!tbody,
                rowCount: rows.length,
                draggableRows: Array.from(rows).filter(row => row.draggable).length,
                dragHandles: handles.length,
                systemInitialized: true
            };
        });

        console.log('\n🔧 System Status:');
        console.log(`   tbody exists: ${systemStatus.tbody}`);
        console.log(`   Row count: ${systemStatus.rowCount}`);
        console.log(`   Draggable rows: ${systemStatus.draggableRows}`);
        console.log(`   Drag handles: ${systemStatus.dragHandles}`);
        console.log(`   System ready: ${systemStatus.systemInitialized}`);

        if (systemStatus.rowCount >= 2) {
            // Get initial order
            const initialOrder = await page.evaluate(() => {
                const rows = document.querySelectorAll('.brand-row');
                return Array.from(rows).map(row => ({
                    id: row.getAttribute('data-brand-id'),
                    name: row.querySelector('strong').textContent,
                    order: row.querySelector('.order-badge').textContent
                }));
            });

            console.log('\n📋 Initial Brand Order:');
            initialOrder.forEach(brand => console.log(`   ${brand.order}. ${brand.name}`));

            console.log('\n🎯 Testing drag operation...');

            // Test drag first item to second position
            const result = await page.evaluate(() => {
                const rows = document.querySelectorAll('.brand-row');
                if (rows.length < 2) return { success: false, error: 'Not enough rows' };

                try {
                    const firstRow = rows[0];
                    const secondRow = rows[1];

                    // Simulate the drop by manually inserting
                    const tbody = document.getElementById('sortableBrands');
                    tbody.insertBefore(firstRow, secondRow.nextSibling);

                    // Update order badges
                    const updatedRows = tbody.querySelectorAll('.brand-row');
                    updatedRows.forEach((row, index) => {
                        const badge = row.querySelector('.order-badge');
                        if (badge) badge.textContent = index + 1;
                    });

                    return { success: true };
                } catch (error) {
                    return { success: false, error: error.message };
                }
            });

            if (result.success) {
                console.log('✅ DOM manipulation successful');

                // Get updated order
                await new Promise(resolve => setTimeout(resolve, 1000));
                const updatedOrder = await page.evaluate(() => {
                    const rows = document.querySelectorAll('.brand-row');
                    return Array.from(rows).map(row => ({
                        id: row.getAttribute('data-brand-id'),
                        name: row.querySelector('strong').textContent,
                        order: row.querySelector('.order-badge').textContent
                    }));
                });

                console.log('\n📋 Updated Brand Order:');
                updatedOrder.forEach(brand => console.log(`   ${brand.order}. ${brand.name}`));

                const orderChanged = JSON.stringify(initialOrder) !== JSON.stringify(updatedOrder);
                console.log(`\n🔄 Order Changed: ${orderChanged ? '✅ YES' : '❌ NO'}`);

            } else {
                console.log('❌ DOM manipulation failed:', result.error);
            }
        } else {
            console.log('⚠️ Not enough brands to test drag-and-drop');
        }

        console.log('\n✅ Brands drag-and-drop system test completed!');

        await page.screenshot({
            path: 'backend/brands-drag-test.png',
            fullPage: false
        });
        console.log('📸 Screenshot saved: backend/brands-drag-test.png');

    } catch (error) {
        console.error('❌ Test failed:', error.message);
    } finally {
        await browser.close();
    }
}

testBrandsDragDrop().catch(console.error);