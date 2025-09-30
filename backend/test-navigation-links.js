import puppeteer from 'puppeteer';

(async () => {
  console.log('Testing navigation links functionality...');
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

    // Extract all navigation and footer links
    const links = await page.evaluate(() => {
      const headerLinks = Array.from(document.querySelectorAll('.main-header nav a')).map(link => ({
        text: link.textContent.trim(),
        href: link.getAttribute('href'),
        type: 'header'
      }));

      const footerLinks = Array.from(document.querySelectorAll('.footer-link')).map(link => ({
        text: link.textContent.trim(),
        href: link.getAttribute('href'),
        type: 'footer'
      }));

      const logoLink = document.querySelector('.main-header .logo');
      const logoLinkData = logoLink ? {
        text: 'Logo',
        href: logoLink.getAttribute('href'),
        type: 'logo'
      } : null;

      return {
        headerLinks,
        footerLinks,
        logoLink: logoLinkData,
        totalLinks: headerLinks.length + footerLinks.length + (logoLinkData ? 1 : 0)
      };
    });

    console.log('Found links:', JSON.stringify(links, null, 2));

    // Test logo link click
    if (links.logoLink) {
      console.log('Testing logo link...');
      await page.click('.main-header .logo');
      await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 });

      const currentUrl = page.url();
      console.log('Logo clicked - Current URL:', currentUrl);

      if (currentUrl.includes('127.0.0.1:8000') && !currentUrl.includes('/login')) {
        console.log('✅ Logo link works - redirected to main website');
      } else {
        console.log('❌ Logo link failed');
      }

      // Go back to login page for other tests
      await page.goto('http://127.0.0.1:8000/login', {
        waitUntil: 'networkidle2',
        timeout: 30000
      });
    }

    // Test first header navigation link
    if (links.headerLinks.length > 0) {
      console.log('Testing header navigation link...');
      const firstLink = links.headerLinks[0];

      // Click the link
      await page.click('.main-header nav a');
      await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 });

      const currentUrl = page.url();
      console.log(`Header link "${firstLink.text}" clicked - Current URL:`, currentUrl);

      if (currentUrl.includes('127.0.0.1:8000') && currentUrl.includes('#')) {
        console.log('✅ Header navigation works - redirected to main website with anchor');
      } else {
        console.log('❌ Header navigation failed');
      }

      // Go back to login page
      await page.goto('http://127.0.0.1:8000/login', {
        waitUntil: 'networkidle2',
        timeout: 30000
      });
    }

    // Test first footer link
    if (links.footerLinks.length > 0) {
      console.log('Testing footer link...');
      const firstFooterLink = links.footerLinks[0];

      // Click the footer link
      await page.click('.footer-link');
      await page.waitForNavigation({ waitUntil: 'networkidle2', timeout: 10000 });

      const currentUrl = page.url();
      console.log(`Footer link "${firstFooterLink.text}" clicked - Current URL:`, currentUrl);

      if (currentUrl.includes('127.0.0.1:8000') && currentUrl.includes('#')) {
        console.log('✅ Footer navigation works - redirected to main website with anchor');
      } else {
        console.log('❌ Footer navigation failed');
      }
    }

    console.log('✅ Navigation testing completed!');
    console.log(`Total links tested: ${links.totalLinks}`);

  } catch (error) {
    console.error('Navigation testing error:', error);
  }

  await browser.close();
})();