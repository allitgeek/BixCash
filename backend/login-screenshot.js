import puppeteer from 'puppeteer';

(async () => {
  console.log('Launching browser for login page screenshot...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();

  // Set viewport size to match desktop
  await page.setViewport({ width: 1280, height: 720 });

  console.log('Navigating to login page...');

  try {
    // Navigate to the login page
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    console.log('Login page loaded successfully');

    // Wait a bit for any CSS/JS to finish loading
    await new Promise(resolve => setTimeout(resolve, 2000));

    // Take screenshot
    console.log('Taking login page screenshot...');
    await page.screenshot({
      path: 'current-login-page.png',
      fullPage: true
    });

    console.log('Screenshot saved as current-login-page.png');

    // Get page info
    const title = await page.title();
    console.log('Page title:', title);

    // Check form elements
    const formInfo = await page.evaluate(() => {
      const form = document.querySelector('form');
      const inputs = Array.from(document.querySelectorAll('input'));
      const buttons = Array.from(document.querySelectorAll('button'));
      const links = Array.from(document.querySelectorAll('a'));

      return {
        hasForm: !!form,
        inputCount: inputs.length,
        inputs: inputs.map(input => ({
          type: input.type,
          name: input.name,
          placeholder: input.placeholder,
          required: input.required
        })),
        buttons: buttons.map(btn => ({
          text: btn.textContent?.trim(),
          type: btn.type
        })),
        links: links.map(link => ({
          text: link.textContent?.trim(),
          href: link.href
        }))
      };
    });

    console.log('Form Analysis:', JSON.stringify(formInfo, null, 2));

  } catch (error) {
    console.error('Error capturing login page:', error);
  }

  await browser.close();
  console.log('Done!');
})();