const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function quickMobileDebug() {
    console.log('🔍 Quick mobile debug - investigating specific issues...');

    let browser;
    try {
        browser = await puppeteer.launch({
            headless: true,
            args: ['--no-sandbox', '--disable-setuid-sandbox']
        });

        const page = await browser.newPage();
        const screenshotsDir = path.join(__dirname, 'mobile-debug-results');

        if (!fs.existsSync(screenshotsDir)) {
            fs.mkdirSync(screenshotsDir);
        }

        // Test iPhone SE viewport (375x667) - most common issue viewport
        await page.setViewport({ width: 375, height: 667 });

        console.log('📱 Loading website on iPhone SE viewport...');
        await page.goto('http://127.0.0.1:8000', {
            waitUntil: 'domcontentloaded',
            timeout: 10000
        });

        // Wait for JavaScript and styles to load
        await new Promise(resolve => setTimeout(resolve, 3000));

        // Comprehensive analysis
        const analysis = await page.evaluate(() => {
            // Helper function to get computed style properties
            const getStyleInfo = (element, properties) => {
                if (!element) return null;
                const style = window.getComputedStyle(element);
                const result = {};
                properties.forEach(prop => {
                    result[prop] = style[prop];
                });
                result.actualWidth = element.offsetWidth;
                result.actualHeight = element.offsetHeight;
                result.scrollHeight = element.scrollHeight;
                return result;
            };

            // Elements to analyze
            const body = document.body;
            const hero = document.querySelector('.hero-slider');
            const heroContainer = document.querySelector('.hero-slider .swiper-container');
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            const mobileOverlay = document.querySelector('.mobile-nav-overlay');
            const mobileContent = document.querySelector('.mobile-nav-content');
            const header = document.querySelector('.main-header');

            return {
                viewport: {
                    width: window.innerWidth,
                    height: window.innerHeight,
                    devicePixelRatio: window.devicePixelRatio
                },

                // Body analysis for mobile setup
                body: getStyleInfo(body, [
                    'paddingTop', 'margin', 'overflow', 'position', 'width'
                ]),

                // Header analysis
                header: getStyleInfo(header, [
                    'position', 'top', 'left', 'width', 'height', 'zIndex', 'display'
                ]),

                // Hero section analysis - main issue
                hero: hero ? {
                    ...getStyleInfo(hero, [
                        'height', 'minHeight', 'paddingTop', 'marginTop',
                        'position', 'display', 'boxSizing', 'overflow'
                    ]),
                    isVisible: hero.offsetHeight > 0,
                    computedHeight: window.getComputedStyle(hero).height,
                    computedMinHeight: window.getComputedStyle(hero).minHeight
                } : { error: 'Hero element not found' },

                // Hero container analysis
                heroContainer: heroContainer ? {
                    ...getStyleInfo(heroContainer, [
                        'height', 'width', 'position', 'display'
                    ])
                } : { error: 'Hero container not found' },

                // Mobile button analysis - hamburger menu issue
                mobileButton: mobileBtn ? {
                    ...getStyleInfo(mobileBtn, [
                        'display', 'visibility', 'opacity', 'position', 'zIndex',
                        'width', 'height', 'top', 'right', 'transform'
                    ]),
                    isVisible: mobileBtn.offsetWidth > 0 && mobileBtn.offsetHeight > 0,
                    hasActiveClass: mobileBtn.classList.contains('active'),
                    classList: Array.from(mobileBtn.classList)
                } : { error: 'Mobile button not found' },

                // Mobile overlay analysis
                mobileOverlay: mobileOverlay ? {
                    ...getStyleInfo(mobileOverlay, [
                        'display', 'visibility', 'opacity', 'position', 'zIndex',
                        'transform', 'transition', 'top', 'left', 'width', 'height'
                    ]),
                    hasActiveClass: mobileOverlay.classList.contains('active'),
                    classList: Array.from(mobileOverlay.classList)
                } : { error: 'Mobile overlay not found' },

                // Mobile content analysis - "popping out" issue
                mobileContent: mobileContent ? {
                    ...getStyleInfo(mobileContent, [
                        'transform', 'transition', 'position', 'width', 'maxWidth',
                        'height', 'maxHeight', 'borderRadius', 'boxShadow'
                    ]),
                    parentTransform: mobileContent.parentElement ?
                        window.getComputedStyle(mobileContent.parentElement).transform : 'none'
                } : { error: 'Mobile content not found' },

                // Check for conflicting CSS rules
                cssIssues: {
                    heroCalcHeight: hero ? window.getComputedStyle(hero).height.includes('calc') : false,
                    heroViewportHeight: hero ? window.getComputedStyle(hero).height.includes('vh') : false,
                    mobileButtonImportant: mobileBtn ?
                        window.getComputedStyle(mobileBtn).display.includes('!important') : false
                }
            };
        });

        console.log('\n📊 ANALYSIS RESULTS:');
        console.log('==================');
        console.log(`Viewport: ${analysis.viewport.width}x${analysis.viewport.height}`);

        // Hero section analysis
        console.log('\n🎬 HERO SECTION ANALYSIS:');
        if (analysis.hero.error) {
            console.log('❌ Hero section not found');
        } else {
            console.log(`Height: ${analysis.hero.height}`);
            console.log(`Min-height: ${analysis.hero.minHeight}`);
            console.log(`Actual height: ${analysis.hero.actualHeight}px`);
            console.log(`Padding-top: ${analysis.hero.paddingTop}`);
            console.log(`Margin-top: ${analysis.hero.marginTop}`);
            console.log(`Position: ${analysis.hero.position}`);
            console.log(`Is visible: ${analysis.hero.isVisible}`);

            // Identify hero issues
            if (analysis.hero.actualHeight < 300) {
                console.log('⚠️  ISSUE: Hero height seems too small for mobile');
            }
            if (analysis.hero.height.includes('calc') && analysis.hero.paddingTop !== '0px') {
                console.log('⚠️  POTENTIAL ISSUE: Calc height with padding may cause overflow');
            }
        }

        // Mobile button analysis
        console.log('\n🍔 HAMBURGER MENU ANALYSIS:');
        if (analysis.mobileButton.error) {
            console.log('❌ Mobile button not found');
        } else {
            console.log(`Display: ${analysis.mobileButton.display}`);
            console.log(`Visibility: ${analysis.mobileButton.visibility}`);
            console.log(`Is visible: ${analysis.mobileButton.isVisible}`);
            console.log(`Size: ${analysis.mobileButton.actualWidth}x${analysis.mobileButton.actualHeight}px`);
            console.log(`Position: ${analysis.mobileButton.position}`);
            console.log(`Z-index: ${analysis.mobileButton.zIndex}`);

            if (!analysis.mobileButton.isVisible) {
                console.log('❌ ISSUE: Mobile button is not visible');
            }
            if (analysis.mobileButton.actualWidth < 44 || analysis.mobileButton.actualHeight < 44) {
                console.log('⚠️  ISSUE: Button smaller than recommended touch target (44px)');
            }
        }

        // Mobile overlay analysis
        console.log('\n📱 MOBILE OVERLAY ANALYSIS:');
        if (analysis.mobileOverlay.error) {
            console.log('❌ Mobile overlay not found');
        } else {
            console.log(`Display: ${analysis.mobileOverlay.display}`);
            console.log(`Position: ${analysis.mobileOverlay.position}`);
            console.log(`Transform: ${analysis.mobileOverlay.transform}`);
            console.log(`Transition: ${analysis.mobileOverlay.transition}`);

            // Check for "popping out" issues
            if (analysis.mobileContent && analysis.mobileContent.transform.includes('scale')) {
                console.log('⚠️  POTENTIAL ISSUE: Content has scale transform - may cause "popping"');
            }
            if (analysis.mobileOverlay.transform.includes('translateY')) {
                console.log('⚠️  POTENTIAL ISSUE: Overlay has translateY - may cause "popping"');
            }
        }

        // Take screenshots
        await page.screenshot({
            path: path.join(screenshotsDir, 'mobile-state-analysis.png'),
            fullPage: true
        });

        // Test hamburger click to see "popping" behavior
        console.log('\n🔄 TESTING HAMBURGER CLICK BEHAVIOR:');
        try {
            // Get initial state
            const beforeClick = await page.evaluate(() => {
                const overlay = document.querySelector('.mobile-nav-overlay');
                const content = document.querySelector('.mobile-nav-content');
                return {
                    overlayTransform: overlay ? window.getComputedStyle(overlay).transform : 'none',
                    contentTransform: content ? window.getComputedStyle(content).transform : 'none',
                    overlayActive: overlay ? overlay.classList.contains('active') : false
                };
            });

            console.log('Before click:', beforeClick);

            // Click hamburger
            await page.click('.mobile-menu-btn');

            // Wait a short time to catch the animation
            await new Promise(resolve => setTimeout(resolve, 100));

            const duringAnimation = await page.evaluate(() => {
                const overlay = document.querySelector('.mobile-nav-overlay');
                const content = document.querySelector('.mobile-nav-content');
                return {
                    overlayTransform: overlay ? window.getComputedStyle(overlay).transform : 'none',
                    contentTransform: content ? window.getComputedStyle(content).transform : 'none',
                    overlayActive: overlay ? overlay.classList.contains('active') : false,
                    overlayOpacity: overlay ? window.getComputedStyle(overlay).opacity : '0'
                };
            });

            console.log('During animation:', duringAnimation);

            // Wait for animation to complete
            await new Promise(resolve => setTimeout(resolve, 1000));

            const afterClick = await page.evaluate(() => {
                const overlay = document.querySelector('.mobile-nav-overlay');
                const content = document.querySelector('.mobile-nav-content');
                return {
                    overlayTransform: overlay ? window.getComputedStyle(overlay).transform : 'none',
                    contentTransform: content ? window.getComputedStyle(content).transform : 'none',
                    overlayActive: overlay ? overlay.classList.contains('active') : false,
                    overlayOpacity: overlay ? window.getComputedStyle(overlay).opacity : '0'
                };
            });

            console.log('After click:', afterClick);

            // Take screenshot of opened menu
            await page.screenshot({
                path: path.join(screenshotsDir, 'menu-opened.png'),
                fullPage: false
            });

            // Check if the "popping" is in the animation
            if (duringAnimation.contentTransform.includes('scale(0.8)') &&
                afterClick.contentTransform.includes('scale(1)')) {
                console.log('✅ Scale animation working as designed');
            } else if (duringAnimation.overlayTransform.includes('translateY') &&
                      afterClick.overlayTransform === 'none') {
                console.log('⚠️  POTENTIAL "POPPING": Overlay translateY animation may be too abrupt');
            }

        } catch (error) {
            console.log('❌ Error testing click:', error.message);
        }

        // Save detailed report
        const report = {
            timestamp: new Date().toISOString(),
            analysis,
            issues: {
                hero: analysis.hero.error || analysis.hero.actualHeight < 300,
                mobileButton: analysis.mobileButton.error || !analysis.mobileButton.isVisible,
                overlay: analysis.mobileOverlay.error
            }
        };

        fs.writeFileSync(
            path.join(screenshotsDir, 'debug-report.json'),
            JSON.stringify(report, null, 2)
        );

        console.log(`\n📁 Debug results saved to: ${screenshotsDir}/`);

        return report;

    } catch (error) {
        console.error('❌ Debug error:', error.message);
        return null;
    } finally {
        if (browser) {
            await browser.close();
        }
    }
}

// Run the debug
if (require.main === module) {
    quickMobileDebug()
        .then(result => {
            if (result) {
                console.log('\n🎯 SUMMARY:');
                console.log(`Hero issues: ${result.issues.hero ? '❌ YES' : '✅ NO'}`);
                console.log(`Mobile button issues: ${result.issues.mobileButton ? '❌ YES' : '✅ NO'}`);
                console.log(`Overlay issues: ${result.issues.overlay ? '❌ YES' : '✅ NO'}`);
            }
        })
        .catch(console.error);
}

module.exports = quickMobileDebug;