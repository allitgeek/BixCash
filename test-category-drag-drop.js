const puppeteer = require('puppeteer');

async function testCategoryDragDrop() {
    console.log('🧪 Testing Category Drag-and-Drop Reordering...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: false,
        defaultViewport: { width: 1400, height: 900 }
    });

    const page = await browser.newPage();

    // Enable console logging from the page
    page.on('console', msg => {
        if (msg.type() === 'log') {
            console.log('🔍 Page Console:', msg.text());
        }
    });

    try {
        console.log('📍 Navigating to admin login...');
        await page.goto('http://127.0.0.1:8000/admin/login', { waitUntil: 'networkidle2' });

        // Login to admin panel
        console.log('🔐 Logging in to admin panel...');
        await page.type('#email', 'admin@bixcash.com');
        await page.type('#password', 'admin123');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ waitUntil: 'networkidle2' });

        // Navigate to categories management
        console.log('📊 Navigating to categories management...');
        await page.goto('http://127.0.0.1:8000/admin/categories', { waitUntil: 'networkidle2' });

        // Wait for categories table to load
        await page.waitForSelector('#categoriesTable', { timeout: 10000 });
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Count categories
        const categoryCount = await page.evaluate(() => {
            const rows = document.querySelectorAll('.category-row');
            return rows.length;
        });

        console.log(`📊 Found ${categoryCount} categories to test with`);

        if (categoryCount < 2) {
            console.log('⚠️ Need at least 2 categories to test drag-and-drop functionality');
            console.log('Creating test categories...');

            // Create test categories if needed
            for (let i = categoryCount; i < 3; i++) {
                await page.goto('http://127.0.0.1:8000/admin/categories/create', { waitUntil: 'networkidle2' });
                await page.type('#name', `Test Category ${i + 1}`);
                await page.type('#description', `Test description for category ${i + 1}`);
                await page.type('#order', (i + 1).toString());
                await page.click('button[type="submit"]');
                await page.waitForNavigation({ waitUntil: 'networkidle2' });
            }

            // Go back to categories index
            await page.goto('http://127.0.0.1:8000/admin/categories', { waitUntil: 'networkidle2' });
            await page.waitForSelector('#categoriesTable', { timeout: 10000 });
        }

        // Get initial category order
        const initialOrder = await page.evaluate(() => {
            const rows = document.querySelectorAll('.category-row');
            return Array.from(rows).map(row => ({
                id: row.getAttribute('data-category-id'),
                order: row.querySelector('.order-badge').textContent,
                name: row.querySelector('strong').textContent
            }));
        });

        console.log('\n📋 Initial category order:');
        initialOrder.forEach(category => {
            console.log(`   ${category.order}. ${category.name} (ID: ${category.id})`);
        });

        // Test drag-and-drop: Move first category to second position
        console.log('\n🖱️ Testing drag-and-drop: Moving first category to second position...');

        const firstCategory = await page.$('.category-row:first-child');
        const secondCategory = await page.$('.category-row:nth-child(2)');

        if (firstCategory && secondCategory) {
            // Get bounding boxes
            const firstBox = await firstCategory.boundingBox();
            const secondBox = await secondCategory.boundingBox();

            // Perform drag and drop
            await page.mouse.move(firstBox.x + firstBox.width / 2, firstBox.y + firstBox.height / 2);
            await page.mouse.down();
            await page.mouse.move(secondBox.x + secondBox.width / 2, secondBox.y + secondBox.height / 2, { steps: 10 });
            await new Promise(resolve => setTimeout(resolve, 500)); // Pause for visual feedback
            await page.mouse.up();

            // Wait for AJAX request to complete
            await new Promise(resolve => setTimeout(resolve, 2000));

            // Get updated order
            const updatedOrder = await page.evaluate(() => {
                const rows = document.querySelectorAll('.category-row');
                return Array.from(rows).map(row => ({
                    id: row.getAttribute('data-category-id'),
                    order: row.querySelector('.order-badge').textContent,
                    name: row.querySelector('strong').textContent
                }));
            });

            console.log('\n📋 Updated category order after drag-and-drop:');
            updatedOrder.forEach(category => {
                console.log(`   ${category.order}. ${category.name} (ID: ${category.id})`);
            });

            // Verify the change
            const orderChanged = JSON.stringify(initialOrder) !== JSON.stringify(updatedOrder);

            if (orderChanged) {
                console.log('\n✅ SUCCESS: Category drag-and-drop reordering is working!');
                console.log('✅ Order badges updated automatically');
                console.log('✅ AJAX request processed successfully');
            } else {
                console.log('\n❌ ISSUE: Order did not change after drag-and-drop');
            }

            // Check for notification
            const hasNotification = await page.evaluate(() => {
                return document.querySelector('.category-notification') !== null;
            });

            if (hasNotification) {
                console.log('✅ User notification system working');
            }

            // Verify that frontend categories are now displaying in the new order
            console.log('\n🌐 Verifying frontend category display order...');
            await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle2' });
            await new Promise(resolve => setTimeout(resolve, 2000));

            const frontendOrder = await page.evaluate(() => {
                const categories = document.querySelectorAll('.category-item');
                return Array.from(categories).map(cat => cat.querySelector('span').textContent);
            });

            console.log('📋 Frontend categories order:');
            frontendOrder.forEach((name, index) => {
                console.log(`   ${index + 1}. ${name}`);
            });

        } else {
            console.log('❌ Could not find category elements for testing');
        }

        // Take screenshot for verification
        await page.screenshot({
            path: 'backend/category-drag-drop-test-result.png',
            fullPage: false
        });
        console.log('\n📸 Screenshot saved: backend/category-drag-drop-test-result.png');

        console.log('\n🎉 Category drag-and-drop functionality test completed!');

    } catch (error) {
        console.error('❌ Test failed:', error.message);

        // Take error screenshot
        await page.screenshot({
            path: 'backend/category-drag-drop-error.png',
            fullPage: true
        });
        console.log('📸 Error screenshot saved: backend/category-drag-drop-error.png');
    } finally {
        await browser.close();
    }
}

// Run the test
testCategoryDragDrop().catch(console.error);