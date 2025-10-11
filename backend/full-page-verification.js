import puppeteer from 'puppeteer';

(async () => {
  console.log('Full page design verification using Puppeteer...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 800 });

  try {
    // Wait for server to be ready
    await new Promise(resolve => setTimeout(resolve, 3000));

    // First, capture main website for reference
    console.log('Capturing main website...');
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    await page.screenshot({
      path: 'main-website-reference.png',
      fullPage: true
    });
    console.log('Main website reference captured');

    // Now capture login page full design
    console.log('Capturing login page full design...');
    await page.goto('http://127.0.0.1:8000/login', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Take full page screenshot
    await page.screenshot({
      path: 'login-page-full-design.png',
      fullPage: true
    });
    console.log('Login page full design captured');

    // Get detailed CSS analysis of header and footer
    const designAnalysis = await page.evaluate(() => {
      const header = document.querySelector('.main-header');
      const footer = document.querySelector('.footer-section');
      const authContainer = document.querySelector('.auth-container');
      const mainContent = document.querySelector('.main-content');

      return {
        header: header ? {
          present: true,
          height: header.offsetHeight,
          styles: {
            position: window.getComputedStyle(header).position,
            top: window.getComputedStyle(header).top,
            zIndex: window.getComputedStyle(header).zIndex,
            background: window.getComputedStyle(header).backgroundColor,
            padding: window.getComputedStyle(header).padding,
            display: window.getComputedStyle(header).display
          }
        } : { present: false },
        footer: footer ? {
          present: true,
          height: footer.offsetHeight,
          styles: {
            background: window.getComputedStyle(footer).backgroundColor,
            padding: window.getComputedStyle(footer).padding,
            position: window.getComputedStyle(footer).position
          }
        } : { present: false },
        authContainer: authContainer ? {
          present: true,
          paddingTop: window.getComputedStyle(authContainer).paddingTop,
          minHeight: window.getComputedStyle(authContainer).minHeight
        } : { present: false },
        mainContent: mainContent ? {
          present: true,
          paddingTop: window.getComputedStyle(mainContent).paddingTop,
          minHeight: window.getComputedStyle(mainContent).minHeight
        } : { present: false },
        documentHeight: document.documentElement.scrollHeight,
        viewportHeight: window.innerHeight
      };
    });

    console.log('Design Analysis:', JSON.stringify(designAnalysis, null, 2));

    // Check for CSS conflicts
    const cssConflicts = await page.evaluate(() => {
      const conflicts = [];

      // Check if there are multiple header styles
      const headerClasses = document.querySelectorAll('.main-header, .auth-header');
      if (headerClasses.length > 1) {
        conflicts.push('Multiple header classes found');
      }

      // Check if there are multiple footer styles
      const footerClasses = document.querySelectorAll('.footer-section, .auth-footer');
      if (footerClasses.length > 1) {
        conflicts.push('Multiple footer classes found');
      }

      // Check for overlapping content
      const header = document.querySelector('.main-header');
      const mainContent = document.querySelector('.main-content');

      if (header && mainContent) {
        const headerRect = header.getBoundingClientRect();
        const contentRect = mainContent.getBoundingClientRect();

        if (contentRect.top < headerRect.bottom) {
          conflicts.push('Content overlapping with header');
        }
      }

      return conflicts;
    });

    console.log('CSS Conflicts found:', cssConflicts);

    // Take viewport screenshot for comparison
    await page.screenshot({
      path: 'login-page-viewport.png',
      fullPage: false
    });

  } catch (error) {
    console.error('Error during verification:', error);
  }

  await browser.close();
  console.log('Full page verification complete. Check the generated images.');
})();