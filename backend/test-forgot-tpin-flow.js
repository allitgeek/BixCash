import puppeteer from 'puppeteer';

(async () => {
  console.log('Testing Forgot T-Pin flow...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 800 });

  try {
    // Wait for server to be ready
    await new Promise(resolve => setTimeout(resolve, 3000));

    console.log('Loading login page...');
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Step 1: Start normal flow to get to T-Pin step
    console.log('Step 1: Enter mobile number to get to T-Pin step...');
    await page.type('#mobile-input', '3001234567'); // Use existing demo user
    await page.click('#mobile-continue');
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Check if we're on T-Pin step
    const tpinStepVisible = await page.evaluate(() => {
      const tpinStep = document.getElementById('step-tpin');
      return tpinStep && tpinStep.classList.contains('active');
    });

    if (tpinStepVisible) {
      console.log('✅ T-Pin step reached successfully');

      // Step 2: Click "Forgot T-Pin?" link
      console.log('Step 2: Clicking "Forgot T-Pin?" link...');
      await page.click('.helper-link');
      await new Promise(resolve => setTimeout(resolve, 1000));

      // Check if we're back to mobile step with updated UI
      const mobileStepInfo = await page.evaluate(() => {
        const mobileStep = document.getElementById('step-mobile');
        const title = document.querySelector('#step-mobile .auth-title').textContent;
        const subtitle = document.querySelector('#step-mobile .auth-subtitle').textContent;

        return {
          isActive: mobileStep && mobileStep.classList.contains('active'),
          title: title,
          subtitle: subtitle
        };
      });

      console.log('Mobile step info:', mobileStepInfo);

      if (mobileStepInfo.isActive && mobileStepInfo.title === 'Reset T-Pin') {
        console.log('✅ Forgot T-Pin redirect works - back to mobile step with reset UI');

        // Step 3: Enter mobile number for verification
        console.log('Step 3: Re-entering mobile number for verification...');
        await page.click('#mobile-input', {clickCount: 3}); // Select all
        await page.type('#mobile-input', '3001234567');
        await page.click('#mobile-continue');
        await new Promise(resolve => setTimeout(resolve, 1500));

        // Check if we're on OTP step
        const otpStepVisible = await page.evaluate(() => {
          const otpStep = document.getElementById('step-otp');
          return otpStep && otpStep.classList.contains('active');
        });

        if (otpStepVisible) {
          console.log('✅ OTP step reached for identity verification');

          // Step 4: Enter OTP to verify identity
          console.log('Step 4: Entering OTP for verification...');
          const otpInputs = await page.$$('#otp-inputs .pin-input');
          for (let i = 0; i < 6; i++) {
            await otpInputs[i].type('1'); // Demo OTP
          }

          await page.click('#otp-continue');
          await new Promise(resolve => setTimeout(resolve, 1500));

          // Check if we're on reset T-Pin step
          const resetTpinStepVisible = await page.evaluate(() => {
            const resetStep = document.getElementById('step-reset-tpin');
            return resetStep && resetStep.classList.contains('active');
          });

          if (resetTpinStepVisible) {
            console.log('✅ Reset T-Pin step reached successfully');

            // Step 5: Set new T-Pin
            console.log('Step 5: Setting new T-Pin...');
            const newTpinInputs = await page.$$('#new-tpin-inputs .pin-input');
            for (let i = 0; i < 4; i++) {
              await newTpinInputs[i].type('9'); // New T-Pin: 9999
            }

            await page.click('#save-new-tpin');
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Check if we're on success step with reset message
            const successInfo = await page.evaluate(() => {
              const successStep = document.getElementById('step-success');
              const title = document.querySelector('#step-success .auth-title').textContent;
              const subtitle = document.querySelector('#step-success .auth-subtitle').textContent;

              return {
                isActive: successStep && successStep.classList.contains('active'),
                title: title,
                subtitle: subtitle
              };
            });

            console.log('Success step info:', successInfo);

            if (successInfo.isActive && successInfo.title === 'T-Pin Reset Successful!') {
              console.log('✅ T-Pin reset completed successfully!');

              // Step 6: Test that new T-Pin works
              console.log('Step 6: Testing new T-Pin works for login...');
              await page.reload();
              await new Promise(resolve => setTimeout(resolve, 2000));

              // Try login with new T-Pin
              await page.type('#mobile-input', '3001234567');
              await page.click('#mobile-continue');
              await new Promise(resolve => setTimeout(resolve, 1000));

              // Enter new T-Pin
              const loginTpinInputs = await page.$$('#tpin-inputs .pin-input');
              for (let i = 0; i < 4; i++) {
                await loginTpinInputs[i].type('9'); // Use new T-Pin
              }

              await page.click('#tpin-continue');
              await new Promise(resolve => setTimeout(resolve, 1500));

              const finalSuccess = await page.evaluate(() => {
                const successStep = document.getElementById('step-success');
                return successStep && successStep.classList.contains('active');
              });

              if (finalSuccess) {
                console.log('✅ New T-Pin login successful!');
                console.log('🎉 COMPLETE FORGOT T-PIN FLOW WORKING PERFECTLY!');
              } else {
                console.log('❌ New T-Pin login failed');
              }

            } else {
              console.log('❌ T-Pin reset success step failed');
            }
          } else {
            console.log('❌ Reset T-Pin step not reached');
          }
        } else {
          console.log('❌ OTP step not reached for verification');
        }
      } else {
        console.log('❌ Forgot T-Pin redirect failed');
      }
    } else {
      console.log('❌ Could not reach T-Pin step');
    }

    // Take final screenshot
    await page.screenshot({
      path: 'forgot-tpin-flow-final.png',
      fullPage: false
    });

  } catch (error) {
    console.error('Error during forgot T-Pin flow test:', error);
  }

  await browser.close();
})();