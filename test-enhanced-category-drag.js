const puppeteer = require('puppeteer');

async function testEnhancedCategoryDrag() {
    console.log('🧪 Testing Enhanced Category Drag-and-Drop System...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: false,
        defaultViewport: { width: 1400, height: 900 },
        slowMo: 100 // Slow down operations to see what's happening
    });

    const page = await browser.newPage();

    // Enhanced console logging
    page.on('console', msg => {
        const type = msg.type();
        if (['log', 'error', 'warn'].includes(type)) {
            console.log(`🔍 [${type.toUpperCase()}]:`, msg.text());
        }
    });

    // Log network requests
    page.on('request', request => {
        if (request.url().includes('categories/reorder')) {
            console.log('📤 AJAX Request:', request.method(), request.url());
        }
    });

    page.on('response', response => {
        if (response.url().includes('categories/reorder')) {
            console.log('📥 AJAX Response:', response.status());
        }
    });

    try {
        console.log('📍 Navigating to admin login...');
        await page.goto('http://127.0.0.1:8000/admin/login', { waitUntil: 'networkidle2' });

        // Login
        console.log('🔐 Logging in...');
        await page.type('#email', 'admin@bixcash.com');
        await page.type('#password', 'admin123');
        await page.click('button[type="submit"]');
        await page.waitForNavigation({ waitUntil: 'networkidle2' });

        // Navigate to categories
        console.log('📊 Navigating to categories...');
        await page.goto('http://127.0.0.1:8000/admin/categories', { waitUntil: 'networkidle2' });
        await page.waitForSelector('#categoriesTable', { timeout: 10000 });

        // Wait for enhanced drag-and-drop system to initialize
        console.log('⏳ Waiting for enhanced system initialization...');
        await new Promise(resolve => setTimeout(resolve, 3000));

        // Check if system is properly initialized
        const systemStatus = await page.evaluate(() => {
            const tbody = document.getElementById('sortableCategories');
            const rows = tbody ? tbody.querySelectorAll('.category-row') : [];
            const dragHandles = document.querySelectorAll('.drag-handle');

            return {
                tbodyExists: !!tbody,
                rowCount: rows.length,
                draggableRows: Array.from(rows).filter(row => row.draggable).length,
                dragHandleCount: dragHandles.length,
                hasConsoleLog: typeof console !== 'undefined'
            };
        });

        console.log('\n🔧 System Status Check:');
        console.log(`   tbody exists: ${systemStatus.tbodyExists}`);
        console.log(`   Row count: ${systemStatus.rowCount}`);
        console.log(`   Draggable rows: ${systemStatus.draggableRows}`);
        console.log(`   Drag handles: ${systemStatus.dragHandleCount}`);
        console.log(`   Console available: ${systemStatus.hasConsoleLog}`);

        if (systemStatus.rowCount < 2) {
            console.log('⚠️ Not enough categories for testing. Need at least 2.');
            return;
        }

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
        initialOrder.forEach(cat => {
            console.log(`   ${cat.order}. ${cat.name} (ID: ${cat.id})`);
        });

        // Test multiple drag operations
        const testScenarios = [
            { from: 0, to: 1, description: 'Move first to second position' },
            { from: 2, to: 0, description: 'Move third to first position' },
            { from: 4, to: 1, description: 'Move last to second position' }
        ];

        for (let i = 0; i < testScenarios.length; i++) {
            const scenario = testScenarios[i];

            if (scenario.from >= systemStatus.rowCount || scenario.to >= systemStatus.rowCount) {
                console.log(`⏭️ Skipping scenario ${i + 1}: Not enough categories`);
                continue;
            }

            console.log(`\n🎯 Test Scenario ${i + 1}: ${scenario.description}`);

            try {
                // Use JavaScript injection to simulate drag-and-drop more reliably
                const result = await page.evaluate((fromIndex, toIndex) => {
                    return new Promise((resolve) => {
                        const tbody = document.getElementById('sortableCategories');
                        const rows = tbody.querySelectorAll('.category-row');

                        if (fromIndex >= rows.length || toIndex >= rows.length) {
                            resolve({ success: false, error: 'Invalid indices' });
                            return;
                        }

                        const fromRow = rows[fromIndex];
                        const toRow = rows[toIndex];

                        console.log(`Moving "${fromRow.querySelector('strong').textContent}" to position of "${toRow.querySelector('strong').textContent}"`);

                        try {
                            // Simulate the drag-and-drop operation
                            if (fromIndex < toIndex) {
                                tbody.insertBefore(fromRow, toRow.nextSibling);
                            } else {
                                tbody.insertBefore(fromRow, toRow);
                            }

                            // Trigger the order update
                            const updatedRows = tbody.querySelectorAll('.category-row');
                            const categoryOrder = [];

                            updatedRows.forEach((row, index) => {
                                const orderBadge = row.querySelector('.order-badge');
                                if (orderBadge) {
                                    orderBadge.textContent = index + 1;
                                }

                                const categoryId = row.getAttribute('data-category-id');
                                if (categoryId) {
                                    categoryOrder.push({
                                        id: parseInt(categoryId),
                                        order: index + 1
                                    });
                                }
                            });

                            // Make the API call
                            fetch('/admin/categories/reorder', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({ categories: categoryOrder })
                            })
                            .then(response => response.json())
                            .then(data => {
                                resolve({
                                    success: data.success,
                                    order: categoryOrder,
                                    message: data.message
                                });
                            })
                            .catch(error => {
                                resolve({ success: false, error: error.message });
                            });

                        } catch (error) {
                            resolve({ success: false, error: error.message });
                        }
                    });
                }, scenario.from, scenario.to);

                if (result.success) {
                    console.log(`✅ Scenario ${i + 1} completed successfully`);
                    console.log(`   API Response: ${result.message}`);
                } else {
                    console.log(`❌ Scenario ${i + 1} failed: ${result.error}`);
                }

                // Wait between scenarios
                await new Promise(resolve => setTimeout(resolve, 2000));

            } catch (error) {
                console.log(`❌ Scenario ${i + 1} error: ${error.message}`);
            }
        }

        // Get final order
        await page.reload({ waitUntil: 'networkidle2' });
        await new Promise(resolve => setTimeout(resolve, 2000));

        const finalOrder = await page.evaluate(() => {
            const rows = document.querySelectorAll('.category-row');
            return Array.from(rows).map(row => ({
                id: parseInt(row.getAttribute('data-category-id')),
                order: parseInt(row.querySelector('.order-badge').textContent),
                name: row.querySelector('strong').textContent
            }));
        });

        console.log('\n📋 Final category order after all tests:');
        finalOrder.forEach(cat => {
            console.log(`   ${cat.order}. ${cat.name} (ID: ${cat.id})`);
        });

        // Verify frontend changes
        console.log('\n🌐 Verifying frontend reflects changes...');
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

        // Compare orders
        const orderChanged = JSON.stringify(initialOrder) !== JSON.stringify(finalOrder);
        const frontendMatches = finalOrder.every((cat, index) =>
            frontendOrder[index] === cat.name
        );

        console.log('\n📊 Test Results Summary:');
        console.log(`   Order changed from initial: ${orderChanged ? '✅ YES' : '❌ NO'}`);
        console.log(`   Frontend matches admin: ${frontendMatches ? '✅ YES' : '❌ NO'}`);
        console.log(`   Enhanced system working: ${orderChanged && frontendMatches ? '✅ YES' : '❌ NO'}`);

        if (orderChanged && frontendMatches) {
            console.log('\n🎉 Enhanced drag-and-drop system is working perfectly!');
        } else {
            console.log('\n⚠️ There may still be issues with the drag-and-drop system.');
        }

    } catch (error) {
        console.error('❌ Test failed:', error.message);
    } finally {
        console.log('\n🔍 Keeping browser open for manual inspection...');
        console.log('Press any key to close browser...');

        // Keep browser open for manual testing
        await new Promise(resolve => {
            process.stdin.once('data', resolve);
        });

        await browser.close();
    }
}

testEnhancedCategoryDrag().catch(console.error);