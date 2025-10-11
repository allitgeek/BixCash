import puppeteer from 'puppeteer';

(async () => {
  console.log('🔍 Deep investigating carousel autoplay functionality...');
  const browser = await puppeteer.launch({
    headless: false, // Show browser for visual verification
    args: ['--no-sandbox', '--disable-setuid-sandbox'],
    devtools: false
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  // Capture all console messages and errors
  const consoleMessages = [];
  const errors = [];

  page.on('console', msg => {
    consoleMessages.push(`${msg.type()}: ${msg.text()}`);
    console.log(`BROWSER: ${msg.type()}: ${msg.text()}`);
  });

  page.on('pageerror', error => {
    errors.push(error.message);
    console.log(`PAGE ERROR: ${error.message}`);
  });

  try {
    console.log('📡 Navigating to website...');
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Wait for initial content to load
    console.log('⏳ Waiting for carousel to initialize...');
    await new Promise(resolve => setTimeout(resolve, 8000));

    console.log('\n🎠 === CAROUSEL AUTOPLAY INVESTIGATION ===');

    // Check if Swiper is initialized and autoplay is working
    const carouselStatus = await page.evaluate(() => {
      const carousel = document.querySelector('.brands-carousel-container');
      const wrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');
      const slides = document.querySelectorAll('.brand-slide');

      // Check if carousel has Swiper instance
      const swiperInstance = carousel ? carousel.swiper : null;

      if (!swiperInstance) {
        return {
          error: 'No Swiper instance found',
          carouselExists: !!carousel,
          wrapperExists: !!wrapper,
          slidesCount: slides.length
        };
      }

      return {
        swiperExists: true,
        autoplayStatus: {
          enabled: swiperInstance.autoplay ? swiperInstance.autoplay.running : false,
          delay: swiperInstance.params.autoplay ? swiperInstance.params.autoplay.delay : null,
          disableOnInteraction: swiperInstance.params.autoplay ? swiperInstance.params.autoplay.disableOnInteraction : null
        },
        loopStatus: {
          enabled: swiperInstance.params.loop,
          loopedSlides: swiperInstance.loopedSlides
        },
        slidesInfo: {
          total: slides.length,
          perView: swiperInstance.params.slidesPerView,
          activeIndex: swiperInstance.activeIndex,
          realIndex: swiperInstance.realIndex
        },
        wrapperTransform: wrapper ? wrapper.style.transform : 'none'
      };
    });

    console.log('Carousel Status:', JSON.stringify(carouselStatus, null, 2));

    if (carouselStatus.swiperExists) {
      console.log('\n⏰ Testing autoplay by watching for 15 seconds...');

      // Record slide positions over time
      const slidePositions = [];

      for (let i = 0; i < 5; i++) {
        await new Promise(resolve => setTimeout(resolve, 3000));

        const position = await page.evaluate(() => {
          const carousel = document.querySelector('.brands-carousel-container');
          const wrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');
          const swiperInstance = carousel ? carousel.swiper : null;

          return {
            time: Date.now(),
            activeIndex: swiperInstance ? swiperInstance.activeIndex : null,
            realIndex: swiperInstance ? swiperInstance.realIndex : null,
            transform: wrapper ? wrapper.style.transform : 'none',
            autoplayRunning: swiperInstance && swiperInstance.autoplay ? swiperInstance.autoplay.running : false
          };
        });

        slidePositions.push(position);
        console.log(`  Check ${i + 1}: Index ${position.activeIndex}/${position.realIndex}, Autoplay: ${position.autoplayRunning}, Transform: ${position.transform}`);
      }

      // Check if slides moved
      const uniqueIndices = [...new Set(slidePositions.map(p => p.activeIndex))];
      const uniqueTransforms = [...new Set(slidePositions.map(p => p.transform))];

      console.log('\n📊 Autoplay Analysis:');
      console.log(`  Unique active indices: ${uniqueIndices.length} (${uniqueIndices.join(', ')})`);
      console.log(`  Unique transforms: ${uniqueTransforms.length}`);
      console.log(`  Movement detected: ${uniqueIndices.length > 1 || uniqueTransforms.length > 1 ? '✅ YES' : '❌ NO'}`);

      if (uniqueIndices.length <= 1 && uniqueTransforms.length <= 1) {
        console.log('\n🚨 AUTOPLAY NOT WORKING - INVESTIGATING REASONS:');

        // Check for potential issues
        const debugInfo = await page.evaluate(() => {
          const carousel = document.querySelector('.brands-carousel-container');
          const swiperInstance = carousel ? carousel.swiper : null;

          if (!swiperInstance) return { error: 'No swiper instance' };

          return {
            autoplayConfig: swiperInstance.params.autoplay,
            loopConfig: swiperInstance.params.loop,
            slidesPerView: swiperInstance.params.slidesPerView,
            totalSlides: swiperInstance.slides.length,
            allowSlideNext: swiperInstance.allowSlideNext,
            allowSlidePrev: swiperInstance.allowSlidePrev,
            autoplayObject: swiperInstance.autoplay ? {
              running: swiperInstance.autoplay.running,
              paused: swiperInstance.autoplay.paused
            } : null,
            errors: window.swiperErrors || []
          };
        });

        console.log('Debug Info:', JSON.stringify(debugInfo, null, 2));
      }
    }

    // Take screenshot of brands section
    console.log('\n📸 Taking screenshot of brands carousel...');
    await page.screenshot({
      path: 'autoplay-debug-screenshot.png',
      fullPage: false,
      clip: {
        x: 0,
        y: 250,
        width: 1280,
        height: 300
      }
    });

    console.log('\n📝 Console Messages During Test:');
    consoleMessages.forEach(msg => console.log(`  ${msg}`));

    if (errors.length > 0) {
      console.log('\n❌ Errors During Test:');
      errors.forEach(error => console.log(`  ${error}`));
    }

  } catch (error) {
    console.error('Investigation error:', error);
  }

  await browser.close();
  console.log('\n✅ Autoplay investigation complete!');
})();