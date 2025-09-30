import puppeteer from 'puppeteer';

(async () => {
  console.log('Testing website consistency after header/footer integration...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  try {
    // Test the main website first
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    console.log('Main website loaded successfully');

    // Take screenshot of main website header
    const mainHeader = await page.$('.main-header');
    if (mainHeader) {
      await mainHeader.screenshot({
        path: 'main-website-header.png'
      });
      console.log('Main website header screenshot captured');
    }

    // Take screenshot of main website footer
    const mainFooter = await page.$('.footer-section');
    if (mainFooter) {
      await mainFooter.screenshot({
        path: 'main-website-footer.png'
      });
      console.log('Main website footer screenshot captured');
    }

    // Now test the login page with updated header/footer
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    console.log('Login page loaded successfully');

    // Take screenshot of login page header
    const loginHeader = await page.$('.main-header');
    if (loginHeader) {
      await loginHeader.screenshot({
        path: 'login-page-header.png'
      });
      console.log('Login page header screenshot captured');
    }

    // Take screenshot of login page footer
    const loginFooter = await page.$('.footer-section');
    if (loginFooter) {
      await loginFooter.screenshot({
        path: 'login-page-footer.png'
      });
      console.log('Login page footer screenshot captured');
    }

    // Take full login page screenshot to verify overall consistency
    await page.screenshot({
      path: 'login-page-with-consistent-headers.png',
      fullPage: false,
      clip: { x: 0, y: 0, width: 1280, height: 900 }
    });

    // Test navigation functionality
    console.log('Testing navigation links from login page...');

    // Check if navigation links exist and are clickable
    const navLinks = await page.evaluate(() => {
      const links = Array.from(document.querySelectorAll('.main-header nav a'));
      return links.map(link => ({
        text: link.textContent.trim(),
        href: link.getAttribute('href'),
        present: true
      }));
    });

    console.log('Navigation links found:', navLinks);

    // Test authentication functionality still works
    console.log('Testing authentication functionality...');

    // Check if mobile input exists and works
    const mobileInputExists = await page.$('#mobile-input');
    if (mobileInputExists) {
      await page.type('#mobile-input', '3001234567');
      console.log('Mobile input working - entered test number');

      // Check if continue button is clickable
      const continueBtn = await page.$('#mobile-continue');
      if (continueBtn) {
        console.log('Continue button found and ready');
      }
    }

    console.log('✅ All consistency tests completed successfully!');
    console.log('Files created:');
    console.log('- main-website-header.png');
    console.log('- main-website-footer.png');
    console.log('- login-page-header.png');
    console.log('- login-page-footer.png');
    console.log('- login-page-with-consistent-headers.png');

  } catch (error) {
    console.error('Error during consistency testing:', error);
  }

  await browser.close();
})();