const puppeteer = require('puppeteer');

async function debugCategoryDimensions() {
    console.log('🔍 Debugging Category Dimensions...\n');

    const browser = await puppeteer.launch({
        headless: false,
        devtools: true,
        defaultViewport: { width: 1200, height: 800 }
    });

    const page = await browser.newPage();

    try {
        console.log('📍 Loading homepage...');
        await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle2' });
        await page.waitForSelector('.category-item', { timeout: 10000 });
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Get detailed dimensions
        const dimensionInfo = await page.evaluate(() => {
            const categories = document.querySelectorAll('.category-item');
            const results = [];

            categories.forEach((cat, index) => {
                const computedStyle = window.getComputedStyle(cat);
                const rect = cat.getBoundingClientRect();

                results.push({
                    index,
                    // CSS values
                    cssWidth: computedStyle.width,
                    cssHeight: computedStyle.height,
                    cssPadding: computedStyle.padding,
                    cssBorderRadius: computedStyle.borderRadius,
                    // Actual rendered size
                    actualWidth: Math.round(rect.width),
                    actualHeight: Math.round(rect.height),
                    // Image inside
                    imageHeight: cat.querySelector('img') ? window.getComputedStyle(cat.querySelector('img')).height : 'N/A'
                });
            });

            return results;
        });

        console.log('\n📏 Category Dimensions Analysis:');
        dimensionInfo.forEach(info => {
            console.log(`\n--- Category ${info.index + 1} ---`);
            console.log(`CSS Width: ${info.cssWidth} (should be 120px)`);
            console.log(`CSS Height: ${info.cssHeight} (should be 160px)`);
            console.log(`CSS Padding: ${info.cssPadding} (should be 1.2rem = ~19.2px)`);
            console.log(`CSS Border Radius: ${info.cssBorderRadius} (should be 8px)`);
            console.log(`Actual Rendered: ${info.actualWidth}x${info.actualHeight}px`);
            console.log(`Image Height: ${info.imageHeight} (should be 90px)`);
        });

        // Check what the original CSS is doing
        const originalCSSCat = await page.evaluate(() => {
            // Create a div with original CSS class only
            const testDiv = document.createElement('div');
            testDiv.className = 'category-item';
            testDiv.style.visibility = 'hidden';
            testDiv.style.position = 'absolute';
            testDiv.innerHTML = '<img style="height: 90px; margin-bottom: 0.5rem;"><span>Test</span>';

            document.body.appendChild(testDiv);

            const computedStyle = window.getComputedStyle(testDiv);
            const rect = testDiv.getBoundingClientRect();

            const result = {
                cssWidth: computedStyle.width,
                cssHeight: computedStyle.height,
                actualWidth: Math.round(rect.width),
                actualHeight: Math.round(rect.height),
                padding: computedStyle.padding
            };

            document.body.removeChild(testDiv);
            return result;
        });

        console.log('\n🎯 Original CSS Class Test:');
        console.log(`CSS Width: ${originalCSSCat.cssWidth}`);
        console.log(`CSS Height: ${originalCSSCat.cssHeight}`);
        console.log(`Actual Size: ${originalCSSCat.actualWidth}x${originalCSSCat.actualHeight}px`);
        console.log(`Padding: ${originalCSSCat.padding}`);

        await page.screenshot({
            path: 'backend/category-dimensions-debug.png',
            fullPage: false
        });
        console.log('\n📸 Debug screenshot saved: backend/category-dimensions-debug.png');

    } catch (error) {
        console.error('❌ Debug failed:', error.message);
    } finally {
        await browser.close();
    }
}

debugCategoryDimensions().catch(console.error);