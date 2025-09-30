import puppeteer from 'puppeteer';

(async () => {
  console.log('Launching browser...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();

  // Set viewport size
  await page.setViewport({ width: 1280, height: 720 });

  console.log('Navigating to http://127.0.0.1:8000...');

  try {
    // Navigate to the website
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    console.log('Page loaded successfully');

    // Wait a bit more for any dynamic content
    await new Promise(resolve => setTimeout(resolve, 3000));

    // Take screenshot
    console.log('Taking screenshot...');
    await page.screenshot({
      path: 'website-screenshot.png',
      fullPage: true
    });

    console.log('Screenshot saved as website-screenshot.png');

    // Check for console errors
    page.on('console', msg => console.log('PAGE LOG:', msg.text()));
    page.on('pageerror', error => console.log('PAGE ERROR:', error.message));

    // Get some basic info about the page
    const title = await page.title();
    console.log('Page title:', title);

    // Check if CSS is loaded and applied
    const cssLoaded = await page.evaluate(() => {
      const links = Array.from(document.querySelectorAll('link[rel="stylesheet"]'));

      // Check if custom CSS variables are available
      const bodyStyles = window.getComputedStyle(document.body);
      const bixGreen = bodyStyles.getPropertyValue('--bix-green');
      const bixNavy = bodyStyles.getPropertyValue('--bix-navy');

      // Check if main classes have styles applied
      const header = document.querySelector('.main-header');
      const headerStyles = header ? window.getComputedStyle(header) : null;

      return {
        links: links.map(link => ({
          href: link.href,
          loaded: link.sheet !== null
        })),
        customProperties: {
          bixGreen: bixGreen.trim(),
          bixNavy: bixNavy.trim()
        },
        headerPosition: headerStyles ? headerStyles.position : 'not found',
        headerBackground: headerStyles ? headerStyles.backgroundColor : 'not found',
        bodyFontFamily: bodyStyles.fontFamily
      };
    });

    console.log('CSS Links:', cssLoaded);

    // Check for any JavaScript errors
    const jsErrors = await page.evaluate(() => {
      return window.jsErrors || [];
    });

    if (jsErrors.length > 0) {
      console.log('JavaScript errors:', jsErrors);
    }

  } catch (error) {
    console.error('Error loading page:', error);
  }

  await browser.close();
  console.log('Done!');
})();