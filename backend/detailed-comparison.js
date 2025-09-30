import puppeteer from 'puppeteer';

(async () => {
  console.log('Starting detailed design comparison...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 800, height: 600 }); // Smaller viewport to match mockup better

  try {
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Get detailed measurements
    const measurements = await page.evaluate(() => {
      const mobileInput = document.getElementById('mobile-input');
      const phoneWrapper = document.querySelector('.phone-input-wrapper');
      const continueBtn = document.getElementById('mobile-continue');
      const signupBtn = document.getElementById('signup-link');
      const authTitle = document.querySelector('.auth-title');
      const authSubtitle = document.querySelector('.auth-subtitle');

      return {
        phoneWrapper: phoneWrapper ? {
          width: phoneWrapper.offsetWidth,
          height: phoneWrapper.offsetHeight,
          styles: window.getComputedStyle(phoneWrapper)
        } : null,
        continueBtn: continueBtn ? {
          width: continueBtn.offsetWidth,
          height: continueBtn.offsetHeight,
          styles: window.getComputedStyle(continueBtn)
        } : null,
        signupBtn: signupBtn ? {
          width: signupBtn.offsetWidth,
          height: signupBtn.offsetHeight,
          styles: window.getComputedStyle(signupBtn)
        } : null,
        title: authTitle ? {
          fontSize: window.getComputedStyle(authTitle).fontSize,
          marginBottom: window.getComputedStyle(authTitle).marginBottom
        } : null,
        subtitle: authSubtitle ? {
          fontSize: window.getComputedStyle(authSubtitle).fontSize,
          marginBottom: window.getComputedStyle(authSubtitle).marginBottom
        } : null
      };
    });

    console.log('Current Implementation Measurements:', JSON.stringify(measurements, null, 2));

    // Take focused screenshot of just the main content area
    const contentArea = await page.$('.auth-form-wrapper');
    if (contentArea) {
      await contentArea.screenshot({
        path: 'current-main-content.png'
      });
      console.log('Main content screenshot saved as current-main-content.png');
    }

    // Take full page screenshot for comparison
    await page.screenshot({
      path: 'current-full-comparison.png',
      fullPage: false
    });

    console.log('Detailed comparison screenshots captured!');

  } catch (error) {
    console.error('Error during detailed analysis:', error);
  }

  await browser.close();
})();