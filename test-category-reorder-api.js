const puppeteer = require('puppeteer');

async function testCategoryReorderAPI() {
    console.log('🧪 Testing Category Reorder API Directly...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: false,
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
            response.text().then(text => {
                console.log('📥 Response Body:', text);
            });
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

        // Get initial order
        const initialOrder = await page.evaluate(() => {
            const rows = document.querySelectorAll('.category-row');
            return Array.from(rows).map(row => ({
                id: parseInt(row.getAttribute('data-category-id')),
                order: parseInt(row.querySelector('.order-badge').textContent),
                name: row.querySelector('strong').textContent
            }));
        });

        console.log('\n📋 Initial category order:');
        initialOrder.forEach(category => {
            console.log(`   ${category.order}. ${category.name} (ID: ${category.id})`);
        });

        // Manually reverse the order to test API
        const reversedOrder = initialOrder.map((category, index) => ({
            id: category.id,
            order: initialOrder.length - index
        }));

        console.log('\n🔄 Testing API with reversed order:');
        reversedOrder.forEach((category, index) => {
            console.log(`   ${category.order}. Category ID ${category.id}`);
        });

        // Test the API directly using JavaScript injection
        const apiTestResult = await page.evaluate(async (newOrder) => {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF Token:', csrfToken);

                const response = await fetch('/admin/categories/reorder', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({ categories: newOrder })
                });

                const result = await response.json();
                console.log('API Response:', result);

                return {
                    success: response.ok,
                    status: response.status,
                    data: result
                };
            } catch (error) {
                console.error('API Error:', error);
                return {
                    success: false,
                    error: error.message
                };
            }
        }, reversedOrder);

        console.log('\n📡 API Test Result:');
        console.log('   Success:', apiTestResult.success);
        console.log('   Status:', apiTestResult.status);
        console.log('   Data:', JSON.stringify(apiTestResult.data, null, 2));

        // Wait a moment then refresh to see if order changed
        await new Promise(resolve => setTimeout(resolve, 2000));
        await page.reload({ waitUntil: 'networkidle2' });
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Get updated order
        const updatedOrder = await page.evaluate(() => {
            const rows = document.querySelectorAll('.category-row');
            return Array.from(rows).map(row => ({
                id: parseInt(row.getAttribute('data-category-id')),
                order: parseInt(row.querySelector('.order-badge').textContent),
                name: row.querySelector('strong').textContent
            }));
        });

        console.log('\n📋 Updated category order after API call:');
        updatedOrder.forEach(category => {
            console.log(`   ${category.order}. ${category.name} (ID: ${category.id})`);
        });

        // Verify change
        const orderChanged = JSON.stringify(initialOrder) !== JSON.stringify(updatedOrder);

        if (orderChanged) {
            console.log('\n✅ SUCCESS: API reorder is working!');
            console.log('✅ Database updated successfully');
            console.log('✅ Page reflects new order');
        } else {
            console.log('\n❌ ISSUE: Order did not change after API call');
        }

        // Test if frontend reflects the changes
        console.log('\n🌐 Testing frontend category order...');
        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle2' });
        await new Promise(resolve => setTimeout(resolve, 2000));

        const frontendOrder = await page.evaluate(() => {
            const categories = document.querySelectorAll('.category-item');
            return Array.from(categories).map(cat => cat.querySelector('span').textContent);
        });

        console.log('\n📋 Frontend categories order:');
        frontendOrder.forEach((name, index) => {
            console.log(`   ${index + 1}. ${name}`);
        });

        console.log('\n🎉 Category reorder API test completed!');

    } catch (error) {
        console.error('❌ Test failed:', error.message);
    } finally {
        await browser.close();
    }
}

testCategoryReorderAPI().catch(console.error);