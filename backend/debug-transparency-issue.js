import puppeteer from 'puppeteer';

(async () => {
  console.log('Debugging transparency issue with Puppeteer...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 800 });

  try {
    // Wait for server to be ready
    await new Promise(resolve => setTimeout(resolve, 2000));

    console.log('Loading main website...');
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Scroll to dashboard section to ensure it's visible
    await page.evaluate(() => {
      const dashboardSection = document.querySelector('.customer-dashboard-section');
      if (dashboardSection) {
        dashboardSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

    await new Promise(resolve => setTimeout(resolve, 2000));

    // Take screenshot of the dashboard section
    const dashboardSection = await page.$('.customer-dashboard-section');
    if (dashboardSection) {
      await dashboardSection.screenshot({
        path: 'dashboard-transparency-issue.png'
      });
      console.log('Dashboard section screenshot captured');
    }

    // Get detailed information about the grow money card styling
    const cardInfo = await page.evaluate(() => {
      const growMoneyCard = document.querySelector('.dashboard-card.grow-money');
      const cardImage = document.querySelector('.dashboard-card.grow-money .card-full-image');
      const dashboardRightContent = document.querySelector('.dashboard-right-content');

      return {
        growMoneyCard: growMoneyCard ? {
          present: true,
          styles: {
            backgroundColor: window.getComputedStyle(growMoneyCard).backgroundColor,
            background: window.getComputedStyle(growMoneyCard).background,
            borderRadius: window.getComputedStyle(growMoneyCard).borderRadius,
            boxShadow: window.getComputedStyle(growMoneyCard).boxShadow
          },
          classList: Array.from(growMoneyCard.classList)
        } : { present: false },
        cardImage: cardImage ? {
          present: true,
          src: cardImage.src,
          styles: {
            backgroundColor: window.getComputedStyle(cardImage).backgroundColor,
            background: window.getComputedStyle(cardImage).background,
            borderRadius: window.getComputedStyle(cardImage).borderRadius
          }
        } : { present: false },
        dashboardRightContent: dashboardRightContent ? {
          styles: {
            backgroundColor: window.getComputedStyle(dashboardRightContent).backgroundColor,
            background: window.getComputedStyle(dashboardRightContent).background
          }
        } : { present: false },
        allDashboardCards: Array.from(document.querySelectorAll('.dashboard-card')).map(card => ({
          classList: Array.from(card.classList),
          backgroundColor: window.getComputedStyle(card).backgroundColor,
          background: window.getComputedStyle(card).background
        }))
      };
    });

    console.log('Card styling analysis:', JSON.stringify(cardInfo, null, 2));

    // Check if the image actually loaded and what its natural properties are
    const imageAnalysis = await page.evaluate(() => {
      const img = document.querySelector('.dashboard-card.grow-money .card-full-image');
      if (img) {
        return {
          complete: img.complete,
          naturalWidth: img.naturalWidth,
          naturalHeight: img.naturalHeight,
          currentSrc: img.currentSrc,
          src: img.src
        };
      }
      return null;
    });

    console.log('Image analysis:', imageAnalysis);

    // Take a focused screenshot of just the grow money card
    const growMoneyCard = await page.$('.dashboard-card.grow-money');
    if (growMoneyCard) {
      await growMoneyCard.screenshot({
        path: 'grow-money-card-current.png'
      });
      console.log('Grow money card screenshot captured');
    }

  } catch (error) {
    console.error('Error during transparency debugging:', error);
  }

  await browser.close();
})();