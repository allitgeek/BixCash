const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function testFixes() {
    console.log('🔧 Testing Mobile Responsiveness Fixes...');
    console.log('1. Hero section simplified height calculation');
    console.log('2. Hamburger menu smooth animation (no popping)');

    let browser;
    try {
        browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });

        const page = await browser.newPage();
        const screenshotsDir = path.join(__dirname, 'fix-test-results');

        if (!fs.existsSync(screenshotsDir)) {
            fs.mkdirSync(screenshotsDir);
        }

        console.log('\n📱 Testing fixes across multiple mobile viewports...');

        const testViewports = [
            { name: 'iPhone SE', width: 375, height: 667 },
            { name: 'Small Mobile', width: 320, height: 568 },
            { name: 'Medium Mobile', width: 390, height: 844 },
            { name: 'Large Mobile', width: 480, height: 800 }
        ];

        let allTestsPassed = true;
        const results = [];

        for (const viewport of testViewports) {
            console.log(`\n🔍 Testing ${viewport.name} (${viewport.width}x${viewport.height})`);

            await page.setViewport(viewport);
            await page.goto('http://127.0.0.1:8000', {
                waitUntil: 'domcontentloaded',
                timeout: 10000
            });

            // Wait for content to load
            await new Promise(resolve => setTimeout(resolve, 2500));

            // Test 1: Hero section responsiveness
            const heroTest = await page.evaluate(() => {
                const hero = document.querySelector('.hero-slider');
                if (!hero) return { error: 'Hero section not found' };

                const style = window.getComputedStyle(hero);
                const actualHeight = hero.offsetHeight;

                return {
                    height: style.height,
                    minHeight: style.minHeight,
                    actualHeight,
                    marginTop: style.marginTop,
                    paddingTop: style.paddingTop,
                    position: style.position,
                    width: style.width,
                    overflow: style.overflow,
                    // Test if height is reasonable for mobile
                    isReasonableHeight: actualHeight >= 400 && actualHeight <= window.innerHeight,
                    heightInVh: Math.round((actualHeight / window.innerHeight) * 100),
                    viewport: {
                        width: window.innerWidth,
                        height: window.innerHeight
                    }
                };
            });

            console.log(`   Hero height: ${heroTest.actualHeight}px (${heroTest.heightInVh}vh)`);
            console.log(`   Height reasonable: ${heroTest.isReasonableHeight ? '✅' : '❌'}`);

            // Test 2: Mobile button functionality
            const buttonTest = await page.evaluate(() => {
                const btn = document.querySelector('.mobile-menu-btn');
                if (!btn) return { error: 'Mobile button not found' };

                const style = window.getComputedStyle(btn);
                return {
                    isVisible: btn.offsetWidth > 0 && btn.offsetHeight > 0,
                    display: style.display,
                    size: `${btn.offsetWidth}x${btn.offsetHeight}`,
                    zIndex: style.zIndex,
                    position: style.position
                };
            });

            console.log(`   Mobile button visible: ${buttonTest.isVisible ? '✅' : '❌'}`);
            console.log(`   Button size: ${buttonTest.size}px`);

            // Test 3: Smooth menu animation (test the improved animation)
            let animationTest = { error: 'Animation test failed' };
            try {
                // Get initial overlay state
                const beforeClick = await page.evaluate(() => {
                    const overlay = document.querySelector('.mobile-nav-overlay');
                    const content = document.querySelector('.mobile-nav-content');
                    return {
                        overlayTransition: overlay ? window.getComputedStyle(overlay).transition : 'none',
                        contentTransition: content ? window.getComputedStyle(content).transition : 'none',
                        overlayTransform: overlay ? window.getComputedStyle(overlay).transform : 'none',
                        contentTransform: content ? window.getComputedStyle(content).transform : 'none'
                    };
                });

                // Click hamburger menu
                await page.click('.mobile-menu-btn');
                await new Promise(resolve => setTimeout(resolve, 100));

                // Check animation state
                const duringAnimation = await page.evaluate(() => {
                    const overlay = document.querySelector('.mobile-nav-overlay');
                    const content = document.querySelector('.mobile-nav-content');
                    return {
                        overlayOpacity: overlay ? window.getComputedStyle(overlay).opacity : '0',
                        contentTransform: content ? window.getComputedStyle(content).transform : 'none',
                        overlayActive: overlay ? overlay.classList.contains('active') : false
                    };
                });

                // Wait for animation to complete
                await new Promise(resolve => setTimeout(resolve, 350));

                const afterAnimation = await page.evaluate(() => {
                    const overlay = document.querySelector('.mobile-nav-overlay');
                    const content = document.querySelector('.mobile-nav-content');
                    return {
                        overlayOpacity: overlay ? window.getComputedStyle(overlay).opacity : '0',
                        contentTransform: content ? window.getComputedStyle(content).transform : 'none',
                        overlayActive: overlay ? overlay.classList.contains('active') : false
                    };
                });

                animationTest = {
                    before: beforeClick,
                    during: duringAnimation,
                    after: afterAnimation,
                    // Check if animation is smooth (no translateY on overlay)
                    noTranslateY: !beforeClick.overlayTransition.includes('translateY'),
                    smoothScale: beforeClick.contentTransform.includes('scale(0.95)'),
                    animationWorking: afterAnimation.overlayOpacity === '1' && afterAnimation.overlayActive
                };

                // Close menu for next test
                await page.click('.mobile-nav-close');
                await new Promise(resolve => setTimeout(resolve, 300));

            } catch (error) {
                animationTest = { error: error.message };
            }

            const isAnimationSmooth = !animationTest.error &&
                                    animationTest.noTranslateY &&
                                    animationTest.smoothScale &&
                                    animationTest.animationWorking;

            console.log(`   Smooth animation: ${isAnimationSmooth ? '✅' : '❌'}`);

            // Take screenshot
            await page.screenshot({
                path: path.join(screenshotsDir, `${viewport.name.toLowerCase().replace(/\s+/g, '-')}-fixed.png`),
                fullPage: true
            });

            // Evaluate viewport test
            const viewportPassed = heroTest.isReasonableHeight &&
                                  buttonTest.isVisible &&
                                  isAnimationSmooth;

            if (!viewportPassed) allTestsPassed = false;

            results.push({
                viewport: viewport.name,
                hero: heroTest,
                button: buttonTest,
                animation: animationTest,
                passed: viewportPassed
            });

            console.log(`   Overall: ${viewportPassed ? '✅ PASS' : '❌ FAIL'}`);
        }

        // Generate summary report
        console.log('\n📊 FIXES TEST SUMMARY:');
        console.log('======================');

        results.forEach(result => {
            console.log(`${result.viewport}: ${result.passed ? '✅ PASS' : '❌ FAIL'}`);
            if (!result.passed) {
                if (result.hero.error || !result.hero.isReasonableHeight) {
                    console.log(`  - Hero issue: Height ${result.hero.actualHeight}px not reasonable`);
                }
                if (result.button.error || !result.button.isVisible) {
                    console.log(`  - Button issue: Not visible or missing`);
                }
                if (result.animation.error || !result.animation.animationWorking) {
                    console.log(`  - Animation issue: ${result.animation.error || 'Not working properly'}`);
                }
            }
        });

        console.log(`\n🎯 OVERALL RESULT: ${allTestsPassed ? '✅ ALL FIXES SUCCESSFUL' : '❌ SOME ISSUES REMAIN'}`);

        // Specific improvements summary
        console.log('\n🔧 IMPROVEMENTS APPLIED:');
        console.log('- Hero section: Removed complex calc() heights, simplified to viewport-based');
        console.log('- Mobile menu: Removed translateY animation, reduced scale change (0.95 vs 0.8)');
        console.log('- Animation timing: Faster, smoother easing curve');
        console.log('- Header heights: Fixed values instead of dynamic calculations');

        // Save detailed report
        const report = {
            timestamp: new Date().toISOString(),
            allTestsPassed,
            results,
            improvements: [
                'Simplified hero section height calculations',
                'Removed popping translateY animation from mobile overlay',
                'Improved mobile menu scale animation',
                'Fixed header heights for consistent spacing'
            ]
        };

        fs.writeFileSync(
            path.join(screenshotsDir, 'fixes-test-report.json'),
            JSON.stringify(report, null, 2)
        );

        console.log(`\n📁 Test results saved to: ${screenshotsDir}/`);

        return allTestsPassed;

    } catch (error) {
        console.error('❌ Test error:', error.message);
        return false;
    } finally {
        if (browser) {
            await browser.close();
        }
    }
}

// Run the test
if (require.main === module) {
    testFixes()
        .then(success => {
            console.log(`\n📋 Test completed: ${success ? 'SUCCESS - Fixes working properly!' : 'ISSUES DETECTED - May need further adjustment'}`);
            process.exit(success ? 0 : 1);
        })
        .catch(console.error);
}

module.exports = testFixes;