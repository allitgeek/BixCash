import puppeteer from 'puppeteer';

(async () => {
  console.log('Final design verification after CSS fixes...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 800 });

  try {
    // Wait for server and CSS compilation
    await new Promise(resolve => setTimeout(resolve, 5000));

    console.log('Testing login page with fixed header/footer...');
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Take full page screenshot
    await page.screenshot({
      path: 'login-fixed-design.png',
      fullPage: true
    });

    // Take viewport screenshot for comparison
    await page.screenshot({
      path: 'login-fixed-viewport.png',
      fullPage: false
    });

    // Check header styling
    const headerAnalysis = await page.evaluate(() => {
      const header = document.querySelector('.main-header');
      return header ? {
        present: true,
        styles: {
          position: window.getComputedStyle(header).position,
          backgroundColor: window.getComputedStyle(header).backgroundColor,
          padding: window.getComputedStyle(header).padding,
          display: window.getComputedStyle(header).display,
          justifyContent: window.getComputedStyle(header).justifyContent,
          alignItems: window.getComputedStyle(header).alignItems,
          boxShadow: window.getComputedStyle(header).boxShadow
        },
        logo: {
          present: !!document.querySelector('.main-header .logo img'),
          height: document.querySelector('.main-header .logo img')?.offsetHeight
        },
        nav: {
          present: !!document.querySelector('.main-header nav'),
          links: Array.from(document.querySelectorAll('.main-header nav a')).map(a => a.textContent.trim())
        }
      } : { present: false };
    });

    // Check footer styling
    const footerAnalysis = await page.evaluate(() => {
      const footer = document.querySelector('.footer-section');
      return footer ? {
        present: true,
        styles: {
          backgroundColor: window.getComputedStyle(footer).backgroundColor,
          padding: window.getComputedStyle(footer).padding,
          color: window.getComputedStyle(footer).color
        },
        logoPresent: !!document.querySelector('.footer-logo-img'),
        socialLinksPresent: !!document.querySelector('.footer-social'),
        columnsPresent: !!document.querySelector('.footer-links')
      } : { present: false };
    });

    console.log('Header Analysis:', JSON.stringify(headerAnalysis, null, 2));
    console.log('Footer Analysis:', JSON.stringify(footerAnalysis, null, 2));

    // Test authentication functionality
    console.log('Testing authentication functionality...');
    await page.type('#mobile-input', '3001234567');

    const buttonState = await page.evaluate(() => {
      const btn = document.getElementById('mobile-continue');
      return {
        disabled: btn?.disabled,
        backgroundColor: window.getComputedStyle(btn).backgroundColor
      };
    });

    console.log('Continue button state:', buttonState);

    console.log('✅ Final verification complete!');
    console.log('Screenshots saved:');
    console.log('- login-fixed-design.png (full page)');
    console.log('- login-fixed-viewport.png (viewport)');

  } catch (error) {
    console.error('Error during final verification:', error);
  }

  await browser.close();
})();