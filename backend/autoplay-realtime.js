import puppeteer from 'puppeteer';

(async () => {
  console.log('🔍 Real-time autoplay monitoring...');
  const browser = await puppeteer.launch({
    headless: false,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  page.on('console', msg => {
    console.log(`BROWSER: ${msg.text()}`);
  });

  try {
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    console.log('⏳ Waiting for carousel to fully load...');
    await new Promise(resolve => setTimeout(resolve, 5000));

    console.log('\n🎬 Monitoring carousel for 30 seconds in real-time...');

    // Monitor in real-time with shorter intervals
    for (let i = 0; i < 30; i++) {
      const status = await page.evaluate(() => {
        const carousel = document.querySelector('.brands-carousel-container');
        const wrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');
        const swiperInstance = carousel ? carousel.swiper : null;

        if (!swiperInstance) return { error: 'No swiper' };

        return {
          time: new Date().toLocaleTimeString(),
          activeIndex: swiperInstance.activeIndex,
          realIndex: swiperInstance.realIndex,
          transform: wrapper.style.transform,
          autoplayRunning: swiperInstance.autoplay.running,
          autoplayPaused: swiperInstance.autoplay.paused
        };
      });

      console.log(`[${i + 1}s] ${status.time} - Index: ${status.activeIndex}/${status.realIndex}, Running: ${status.autoplayRunning}, Paused: ${status.autoplayPaused}`);

      if (i > 0 && (status.activeIndex !== previousStatus?.activeIndex || status.transform !== previousStatus?.transform)) {
        console.log(`  🎯 MOVEMENT DETECTED! Changed from index ${previousStatus?.activeIndex} to ${status.activeIndex}`);
      }

      const previousStatus = status;
      await new Promise(resolve => setTimeout(resolve, 1000));
    }

  } catch (error) {
    console.error('Error:', error);
  }

  await browser.close();
  console.log('\n✅ Monitoring complete!');
})();