const puppeteer = require('puppeteer');

async function debugCategoryDrag() {
    console.log('🔍 Debug Category Drag-and-Drop Issues...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: true,
        defaultViewport: { width: 1400, height: 900 }
    });

    const page = await browser.newPage();

    // Enable console logging from the page
    page.on('console', msg => {
        console.log('🔍 Browser Console:', msg.text());
    });

    // Log network requests
    page.on('request', request => {
        if (request.url().includes('categories/reorder')) {
            console.log('📤 AJAX Request:', request.method(), request.url());
            console.log('📤 Request Data:', request.postData());
        }
    });

    page.on('response', response => {
        if (response.url().includes('categories/reorder')) {
            console.log('📥 AJAX Response:', response.status(), response.url());
        }
    });

    try {
        console.log('📍 Navigating to admin login...');
        await page.goto('http://127.0.0.1:8000/admin/login', { waitUntil: 'networkidle2' });

        // Login
        await page.type('#email', 'admin@bixcash.com');
        await page.type('#password', 'admin123');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ waitUntil: 'networkidle2' });

        // Navigate to categories
        await page.goto('http://127.0.0.1:8000/admin/categories', { waitUntil: 'networkidle2' });
        await page.waitForSelector('#categoriesTable', { timeout: 10000 });
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Check if drag handles exist
        const dragHandles = await page.evaluate(() => {
            const handles = document.querySelectorAll('.drag-handle');
            return {
                count: handles.length,
                visible: handles.length > 0 ? window.getComputedStyle(handles[0]).display !== 'none' : false,
                draggable: document.querySelectorAll('.category-row[draggable="true"]').length
            };
        });

        console.log('\n🔍 Drag Handle Analysis:');
        console.log(`   Found ${dragHandles.count} drag handles`);
        console.log(`   Handles visible: ${dragHandles.visible}`);
        console.log(`   Draggable rows: ${dragHandles.draggable}`);

        // Check if event listeners are attached
        const eventListeners = await page.evaluate(() => {
            const tbody = document.getElementById('sortableCategories');
            const rows = tbody ? tbody.querySelectorAll('.category-row') : [];

            let hasEventListeners = false;
            rows.forEach(row => {
                // Try to trigger a drag event to see if it's handled
                try {
                    const event = new DragEvent('dragstart', { bubbles: true });
                    hasEventListeners = row.dispatchEvent(event);
                } catch (e) {
                    // Event listeners might not be properly attached
                }
            });

            return {
                tbody: !!tbody,
                rowCount: rows.length,
                hasEventListeners
            };
        });

        console.log('\n🔍 Event Listener Analysis:');
        console.log(`   tbody exists: ${eventListeners.tbody}`);
        console.log(`   Row count: ${eventListeners.rowCount}`);
        console.log(`   Has event listeners: ${eventListeners.hasEventListeners}`);

        // Test manual drag using CDP (Chrome DevTools Protocol)
        console.log('\n🖱️ Testing manual drag with CDP...');

        const rows = await page.$$('.category-row');
        if (rows.length >= 2) {
            const firstRowBox = await rows[0].boundingBox();
            const secondRowBox = await rows[1].boundingBox();

            console.log('📍 First row position:', firstRowBox);
            console.log('📍 Second row position:', secondRowBox);

            // Simulate drag and drop using mouse events
            await page.mouse.move(firstRowBox.x + firstRowBox.width / 2, firstRowBox.y + firstRowBox.height / 2);
            console.log('🖱️ Mouse moved to first row');

            await page.mouse.down();
            console.log('🖱️ Mouse down on first row');

            await new Promise(resolve => setTimeout(resolve, 500));

            await page.mouse.move(secondRowBox.x + secondRowBox.width / 2, secondRowBox.y + secondRowBox.height / 2, { steps: 10 });
            console.log('🖱️ Mouse moved to second row');

            await new Promise(resolve => setTimeout(resolve, 500));

            await page.mouse.up();
            console.log('🖱️ Mouse up - drop completed');

            // Wait for any AJAX request
            await new Promise(resolve => setTimeout(resolve, 3000));
        }

        // Check if order changed
        const finalOrder = await page.evaluate(() => {
            const rows = document.querySelectorAll('.category-row');
            return Array.from(rows).map(row => ({
                id: row.getAttribute('data-category-id'),
                order: row.querySelector('.order-badge').textContent,
                name: row.querySelector('strong').textContent
            }));
        });

        console.log('\n📋 Final order after debug test:');
        finalOrder.forEach(category => {
            console.log(`   ${category.order}. ${category.name} (ID: ${category.id})`);
        });

        // Check for JavaScript errors
        const errors = await page.evaluate(() => {
            return window.console ? 'Console available' : 'Console not available';
        });
        console.log('\n🔍 JavaScript Environment:', errors);

        console.log('\n⏸️ Browser paused for manual inspection...');
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

debugCategoryDrag().catch(console.error);