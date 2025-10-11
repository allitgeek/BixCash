import puppeteer from 'puppeteer';

(async () => {
  console.log('Starting design comparison analysis...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  try {
    // Navigate to login page
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    console.log('Page loaded, analyzing current design...');

    // Take screenshot of initial state
    await page.screenshot({
      path: 'current-step1-mobile.png',
      fullPage: false,
      clip: { x: 0, y: 0, width: 1280, height: 800 }
    });

    // Analyze current design elements
    const designAnalysis = await page.evaluate(() => {
      const mobileInput = document.getElementById('mobile-input');
      const continueBtn = document.getElementById('mobile-continue');
      const signupLink = document.getElementById('signup-link');
      const phoneWrapper = document.querySelector('.phone-input-wrapper');
      const phonePrefix = document.querySelector('.phone-prefix');

      return {
        mobileInput: {
          present: !!mobileInput,
          placeholder: mobileInput?.placeholder,
          styling: mobileInput ? window.getComputedStyle(mobileInput) : null
        },
        continueButton: {
          present: !!continueBtn,
          text: continueBtn?.textContent?.trim(),
          disabled: continueBtn?.disabled,
          backgroundColor: continueBtn ? window.getComputedStyle(continueBtn).backgroundColor : null
        },
        signupLink: {
          present: !!signupLink,
          text: signupLink?.textContent?.trim(),
          backgroundColor: signupLink ? window.getComputedStyle(signupLink).backgroundColor : null
        },
        phoneWrapper: {
          present: !!phoneWrapper,
          hasPrefixSeparation: !!phonePrefix
        }
      };
    });

    console.log('Current Design Analysis:', JSON.stringify(designAnalysis, null, 2));

    // Test interaction - enter a valid phone number
    await page.type('#mobile-input', '3001234567');

    // Wait a moment for button state to update
    await new Promise(resolve => setTimeout(resolve, 500));

    // Take screenshot after input
    await page.screenshot({
      path: 'current-step1-with-input.png',
      fullPage: false,
      clip: { x: 0, y: 0, width: 1280, height: 800 }
    });

    // Check button state after input
    const buttonStateAfterInput = await page.evaluate(() => {
      const continueBtn = document.getElementById('mobile-continue');
      return {
        disabled: continueBtn?.disabled,
        backgroundColor: continueBtn ? window.getComputedStyle(continueBtn).backgroundColor : null
      };
    });

    console.log('Button state after input:', buttonStateAfterInput);

    // Try to click continue to see next step
    await page.click('#mobile-continue');
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Take screenshot of T-Pin step
    await page.screenshot({
      path: 'current-step2-tpin.png',
      fullPage: false,
      clip: { x: 0, y: 0, width: 1280, height: 800 }
    });

    // Now test the signup flow - click signup link
    await page.reload();
    await new Promise(resolve => setTimeout(resolve, 1000));

    await page.click('#signup-link');
    await new Promise(resolve => setTimeout(resolve, 500));

    // Take screenshot of signup step
    await page.screenshot({
      path: 'current-step-signup.png',
      fullPage: false,
      clip: { x: 0, y: 0, width: 1280, height: 800 }
    });

    console.log('Screenshots captured successfully!');
    console.log('Files created:');
    console.log('- current-step1-mobile.png (initial state)');
    console.log('- current-step1-with-input.png (with phone input)');
    console.log('- current-step2-tpin.png (T-Pin step)');
    console.log('- current-step-signup.png (signup step)');

  } catch (error) {
    console.error('Error during analysis:', error);
  }

  await browser.close();
  console.log('Analysis complete!');
})();