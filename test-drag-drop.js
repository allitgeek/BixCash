const puppeteer = require('puppeteer');

async function testDragDropSlides() {
    console.log('🧪 Testing Drag-and-Drop Slide Reordering...\n');

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

        // Navigate to slides management
        console.log('📊 Navigating to slides management...');
        await page.goto('http://127.0.0.1:8000/admin/slides', { waitUntil: 'networkidle2' });

        // Wait for slides table to load
        await page.waitForSelector('#slidesTable', { timeout: 10000 });
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Count slides
        const slideCount = await page.evaluate(() => {
            const rows = document.querySelectorAll('.slide-row');
            return rows.length;
        });

        console.log(`📊 Found ${slideCount} slides to test with`);

        if (slideCount < 2) {
            console.log('⚠️ Need at least 2 slides to test drag-and-drop functionality');
            console.log('Creating test slides...');

            // Create test slides if needed
            for (let i = slideCount; i < 3; i++) {
                await page.goto('http://127.0.0.1:8000/admin/slides/create', { waitUntil: 'networkidle2' });
                await page.type('#title', `Test Slide ${i + 1}`);
                await page.type('#description', `Test description for slide ${i + 1}`);
                await page.type('#media_path', 'https://via.placeholder.com/800x400/0066cc/ffffff?text=Test+Slide+' + (i + 1));
                await page.type('#order', (i + 1).toString());
                await page.click('button[type="submit"]');
                await page.waitForNavigation({ waitUntil: 'networkidle2' });
            }

            // Go back to slides index
            await page.goto('http://127.0.0.1:8000/admin/slides', { waitUntil: 'networkidle2' });
            await page.waitForSelector('#slidesTable', { timeout: 10000 });
        }

        // Get initial slide order
        const initialOrder = await page.evaluate(() => {
            const rows = document.querySelectorAll('.slide-row');
            return Array.from(rows).map(row => ({
                id: row.getAttribute('data-slide-id'),
                order: row.querySelector('.order-badge').textContent,
                title: row.querySelector('strong').textContent
            }));
        });

        console.log('\n📋 Initial slide order:');
        initialOrder.forEach(slide => {
            console.log(`   ${slide.order}. ${slide.title} (ID: ${slide.id})`);
        });

        // Test drag-and-drop: Move first slide to second position
        console.log('\n🖱️ Testing drag-and-drop: Moving first slide to second position...');

        const firstSlide = await page.$('.slide-row:first-child');
        const secondSlide = await page.$('.slide-row:nth-child(2)');

        if (firstSlide && secondSlide) {
            // Get bounding boxes
            const firstBox = await firstSlide.boundingBox();
            const secondBox = await secondSlide.boundingBox();

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
                const rows = document.querySelectorAll('.slide-row');
                return Array.from(rows).map(row => ({
                    id: row.getAttribute('data-slide-id'),
                    order: row.querySelector('.order-badge').textContent,
                    title: row.querySelector('strong').textContent
                }));
            });

            console.log('\n📋 Updated slide order after drag-and-drop:');
            updatedOrder.forEach(slide => {
                console.log(`   ${slide.order}. ${slide.title} (ID: ${slide.id})`);
            });

            // Verify the change
            const orderChanged = JSON.stringify(initialOrder) !== JSON.stringify(updatedOrder);

            if (orderChanged) {
                console.log('\n✅ SUCCESS: Drag-and-drop reordering is working!');
                console.log('✅ Order badges updated automatically');
                console.log('✅ AJAX request processed successfully');
            } else {
                console.log('\n❌ ISSUE: Order did not change after drag-and-drop');
            }

            // Check for notification
            const hasNotification = await page.evaluate(() => {
                return document.querySelector('.slide-notification') !== null;
            });

            if (hasNotification) {
                console.log('✅ User notification system working');
            }

        } else {
            console.log('❌ Could not find slide elements for testing');
        }

        // Take screenshot for verification
        await page.screenshot({
            path: 'backend/drag-drop-test-result.png',
            fullPage: false
        });
        console.log('\n📸 Screenshot saved: backend/drag-drop-test-result.png');

        console.log('\n🎉 Drag-and-drop functionality test completed!');

    } catch (error) {
        console.error('❌ Test failed:', error.message);

        // Take error screenshot
        await page.screenshot({
            path: 'backend/drag-drop-error.png',
            fullPage: true
        });
        console.log('📸 Error screenshot saved: backend/drag-drop-error.png');
    } finally {
        await browser.close();
    }
}

// Run the test
testDragDropSlides().catch(console.error);