const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function investigateMobileIssues() {
    console.log('🔍 Investigating specific mobile issues...');
    console.log('1. Hero section responsiveness on mobile');
    console.log('2. Hamburger menu "popping out" behavior');

    let browser;
    try {
        browser = await puppeteer.launch({
            headless: false, // Run with visible browser for debugging
            args: ['--no-sandbox', '--disable-setuid-sandbox'],
            devtools: true // Open DevTools automatically
        });

        const page = await browser.newPage();
        const screenshotsDir = path.join(__dirname, 'mobile-issue-investigation');

        if (!fs.existsSync(screenshotsDir)) {
            fs.mkdirSync(screenshotsDir);
        }

        // Test on iPhone SE viewport (375x667)
        await page.setViewport({ width: 375, height: 667 });

        // Navigate to the website
        console.log('📱 Loading website on iPhone SE viewport...');
        await page.goto('http://127.0.0.1:8000', {
            waitUntil: 'networkidle0',
            timeout: 15000
        });

        // Wait for JavaScript to initialize
        await new Promise(resolve => setTimeout(resolve, 3000));

        console.log('🔍 Issue 1: Hero Section Responsiveness Analysis');

        // Analyze hero section
        const heroAnalysis = await page.evaluate(() => {
            const heroSection = document.querySelector('.hero-slider');
            const heroContainer = document.querySelector('.hero-slider .swiper-container');
            const heroSlides = document.querySelectorAll('.hero-slider .swiper-slide');

            if (!heroSection) return { error: 'Hero section not found' };

            const heroStyle = window.getComputedStyle(heroSection);
            const containerStyle = heroContainer ? window.getComputedStyle(heroContainer) : null;

            return {
                viewport: {
                    width: window.innerWidth,
                    height: window.innerHeight
                },
                heroSection: {
                    exists: !!heroSection,
                    height: heroStyle.height,
                    minHeight: heroStyle.minHeight,
                    paddingTop: heroStyle.paddingTop,
                    marginTop: heroStyle.marginTop,
                    position: heroStyle.position,
                    display: heroStyle.display,
                    boxSizing: heroStyle.boxSizing,
                    actualHeight: heroSection.offsetHeight,
                    actualWidth: heroSection.offsetWidth
                },
                container: containerStyle ? {
                    height: containerStyle.height,
                    width: containerStyle.width,
                    position: containerStyle.position,
                    actualHeight: heroContainer.offsetHeight,
                    actualWidth: heroContainer.offsetWidth
                } : null,
                slides: {
                    count: heroSlides.length,
                    firstSlideHeight: heroSlides[0] ? heroSlides[0].offsetHeight : 0,
                    firstSlideWidth: heroSlides[0] ? heroSlides[0].offsetWidth : 0
                }
            };
        });

        console.log('Hero Section Analysis:', JSON.stringify(heroAnalysis, null, 2));

        // Take screenshot of hero section
        await page.screenshot({
            path: path.join(screenshotsDir, 'hero-section-mobile.png'),
            fullPage: false
        });

        console.log('🔍 Issue 2: Hamburger Menu Behavior Analysis');

        // Analyze hamburger menu
        const menuAnalysis = await page.evaluate(() => {
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            const mobileNavOverlay = document.querySelector('.mobile-nav-overlay');
            const mobileNavContent = document.querySelector('.mobile-nav-content');
            const body = document.body;

            if (!mobileMenuBtn) return { error: 'Mobile menu button not found' };

            const btnStyle = window.getComputedStyle(mobileMenuBtn);
            const overlayStyle = mobileNavOverlay ? window.getComputedStyle(mobileNavOverlay) : null;
            const contentStyle = mobileNavContent ? window.getComputedStyle(mobileNavContent) : null;

            return {
                button: {
                    exists: !!mobileMenuBtn,
                    display: btnStyle.display,
                    visibility: btnStyle.visibility,
                    position: btnStyle.position,
                    zIndex: btnStyle.zIndex,
                    transform: btnStyle.transform,
                    transition: btnStyle.transition,
                    isVisible: btnStyle.display !== 'none' && btnStyle.visibility !== 'hidden',
                    hasActiveClass: mobileMenuBtn.classList.contains('active'),
                    actualWidth: mobileMenuBtn.offsetWidth,
                    actualHeight: mobileMenuBtn.offsetHeight
                },
                overlay: overlayStyle ? {
                    exists: !!mobileNavOverlay,
                    display: overlayStyle.display,
                    visibility: overlayStyle.visibility,
                    opacity: overlayStyle.opacity,
                    transform: overlayStyle.transform,
                    transition: overlayStyle.transition,
                    position: overlayStyle.position,
                    zIndex: overlayStyle.zIndex,
                    hasActiveClass: mobileNavOverlay.classList.contains('active')
                } : null,
                content: contentStyle ? {
                    transform: contentStyle.transform,
                    transition: contentStyle.transition,
                    position: contentStyle.position,
                    width: contentStyle.width,
                    maxWidth: contentStyle.maxWidth,
                    actualWidth: mobileNavContent.offsetWidth,
                    actualHeight: mobileNavContent.offsetHeight
                } : null,
                bodyClass: body.className
            };
        });

        console.log('Hamburger Menu Analysis:', JSON.stringify(menuAnalysis, null, 2));

        // Test hamburger menu click behavior
        console.log('🔍 Testing hamburger menu click behavior...');

        try {
            // Take screenshot before click
            await page.screenshot({
                path: path.join(screenshotsDir, 'before-menu-click.png'),
                fullPage: false
            });

            // Click the hamburger menu
            await page.click('.mobile-menu-btn');

            // Wait for animation
            await new Promise(resolve => setTimeout(resolve, 1000));

            // Check state after click
            const afterClickState = await page.evaluate(() => {
                const overlay = document.querySelector('.mobile-nav-overlay');
                const btn = document.querySelector('.mobile-menu-btn');
                const content = document.querySelector('.mobile-nav-content');
                const body = document.body;

                return {
                    overlayActive: overlay ? overlay.classList.contains('active') : false,
                    btnActive: btn ? btn.classList.contains('active') : false,
                    bodyClass: body.className,
                    overlayVisible: overlay ? window.getComputedStyle(overlay).visibility === 'visible' : false,
                    overlayOpacity: overlay ? window.getComputedStyle(overlay).opacity : '0',
                    contentTransform: content ? window.getComputedStyle(content).transform : 'none'
                };
            });

            console.log('After Click State:', JSON.stringify(afterClickState, null, 2));

            // Take screenshot after click
            await page.screenshot({
                path: path.join(screenshotsDir, 'after-menu-click.png'),
                fullPage: false
            });

            // Test close behavior
            console.log('🔍 Testing menu close behavior...');

            // Click close button
            await page.click('.mobile-nav-close');
            await new Promise(resolve => setTimeout(resolve, 1000));

            const afterCloseState = await page.evaluate(() => {
                const overlay = document.querySelector('.mobile-nav-overlay');
                const btn = document.querySelector('.mobile-menu-btn');
                const body = document.body;

                return {
                    overlayActive: overlay ? overlay.classList.contains('active') : false,
                    btnActive: btn ? btn.classList.contains('active') : false,
                    bodyClass: body.className,
                    overlayVisible: overlay ? window.getComputedStyle(overlay).visibility === 'visible' : false,
                    overlayOpacity: overlay ? window.getComputedStyle(overlay).opacity : '0'
                };
            });

            console.log('After Close State:', JSON.stringify(afterCloseState, null, 2));

            // Take screenshot after close
            await page.screenshot({
                path: path.join(screenshotsDir, 'after-menu-close.png'),
                fullPage: false
            });

        } catch (error) {
            console.error('❌ Error testing menu behavior:', error.message);
        }

        console.log('🔍 Testing different mobile viewports...');

        // Test different mobile sizes
        const viewports = [
            { name: 'iPhone SE', width: 375, height: 667 },
            { name: 'iPhone 12', width: 390, height: 844 },
            { name: 'Samsung Galaxy S21', width: 360, height: 800 },
            { name: 'Small Mobile', width: 320, height: 568 }
        ];

        for (const viewport of viewports) {
            console.log(`📱 Testing ${viewport.name} (${viewport.width}x${viewport.height})`);

            await page.setViewport(viewport);
            await new Promise(resolve => setTimeout(resolve, 500));

            const viewportAnalysis = await page.evaluate(() => {
                const hero = document.querySelector('.hero-slider');
                const btn = document.querySelector('.mobile-menu-btn');

                return {
                    viewport: {
                        width: window.innerWidth,
                        height: window.innerHeight
                    },
                    heroHeight: hero ? hero.offsetHeight : 0,
                    btnVisible: btn ? window.getComputedStyle(btn).display !== 'none' : false,
                    heroStyle: hero ? {
                        height: window.getComputedStyle(hero).height,
                        minHeight: window.getComputedStyle(hero).minHeight
                    } : null
                };
            });

            console.log(`${viewport.name} Analysis:`, JSON.stringify(viewportAnalysis, null, 2));

            await page.screenshot({
                path: path.join(screenshotsDir, `${viewport.name.toLowerCase().replace(/\s+/g, '-')}.png`),
                fullPage: false
            });
        }

        console.log(`\n📁 Screenshots and analysis saved to: ${screenshotsDir}/`);

        // Generate summary report
        const report = {
            timestamp: new Date().toISOString(),
            heroAnalysis,
            menuAnalysis,
            afterClickState: afterClickState || 'Error during testing',
            afterCloseState: afterCloseState || 'Error during testing',
            viewportTests: viewports.length
        };

        fs.writeFileSync(
            path.join(screenshotsDir, 'investigation-report.json'),
            JSON.stringify(report, null, 2)
        );

        console.log('\n🎯 INVESTIGATION SUMMARY:');
        console.log('=======================');
        console.log('Hero Section Issues:');
        if (heroAnalysis.error) {
            console.log('❌ Hero section not found');
        } else {
            console.log(`📏 Hero height: ${heroAnalysis.heroSection.height}`);
            console.log(`📏 Hero min-height: ${heroAnalysis.heroSection.minHeight}`);
            console.log(`📏 Actual height: ${heroAnalysis.heroSection.actualHeight}px`);
        }

        console.log('\nHamburger Menu Issues:');
        if (menuAnalysis.error) {
            console.log('❌ Mobile menu button not found');
        } else {
            console.log(`👁️ Button visible: ${menuAnalysis.button.isVisible}`);
            console.log(`📏 Button size: ${menuAnalysis.button.actualWidth}x${menuAnalysis.button.actualHeight}px`);
            console.log(`🔧 Menu functionality: ${afterClickState ? 'Tested' : 'Error'}`);
        }

        return true;

    } catch (error) {
        console.error('❌ Investigation error:', error.message);
        return false;
    } finally {
        if (browser) {
            // Keep browser open for manual inspection
            console.log('\n🔍 Browser kept open for manual inspection');
            console.log('Press Ctrl+C to close browser and exit');

            // Wait for user to close manually
            await new Promise(resolve => {
                process.on('SIGINT', () => {
                    browser.close();
                    resolve();
                });
            });
        }
    }
}

// Run the investigation
if (require.main === module) {
    investigateMobileIssues()
        .then(success => {
            console.log(`\n📋 Investigation completed: ${success ? 'SUCCESS' : 'FAILED'}`);
        })
        .catch(console.error);
}

module.exports = investigateMobileIssues;