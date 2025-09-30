import puppeteer from 'puppeteer';

(async () => {
  console.log('🔍 Launching detailed brands carousel investigation...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  // Capture console logs and errors
  const consoleMessages = [];
  const errors = [];

  page.on('console', msg => {
    consoleMessages.push(`${msg.type()}: ${msg.text()}`);
  });

  page.on('pageerror', error => {
    errors.push(error.message);
  });

  try {
    console.log('📡 Navigating to website...');
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    // Wait for content to load
    await new Promise(resolve => setTimeout(resolve, 5000));

    console.log('\n📊 === API DATA INVESTIGATION ===');

    // Check brands API response
    const brandsResponse = await page.evaluate(async () => {
      try {
        const response = await fetch('/api/brands');
        const data = await response.json();
        return {
          status: response.status,
          success: data.success,
          count: data.data ? data.data.length : 0,
          brands: data.data ? data.data.map(brand => ({
            id: brand.id,
            name: brand.name,
            logo_path: brand.logo_path,
            website: brand.website
          })) : []
        };
      } catch (error) {
        return { error: error.message };
      }
    });

    console.log('Brands API Response:', JSON.stringify(brandsResponse, null, 2));

    console.log('\n🖼️ === BRAND IMAGES INVESTIGATION ===');

    // Check brand image accessibility
    const imageChecks = await page.evaluate(async (brands) => {
      const results = [];

      for (const brand of brands) {
        if (brand.logo_path) {
          try {
            const response = await fetch(brand.logo_path);
            results.push({
              name: brand.name,
              logo_path: brand.logo_path,
              accessible: response.ok,
              status: response.status,
              contentType: response.headers.get('content-type')
            });
          } catch (error) {
            results.push({
              name: brand.name,
              logo_path: brand.logo_path,
              accessible: false,
              error: error.message
            });
          }
        } else {
          results.push({
            name: brand.name,
            logo_path: 'null/empty',
            accessible: false,
            error: 'No logo path provided'
          });
        }
      }

      return results;
    }, brandsResponse.brands || []);

    console.log('Brand Image Accessibility:');
    imageChecks.forEach(check => {
      console.log(`  ${check.name}: ${check.accessible ? '✅' : '❌'} ${check.logo_path} (${check.status || check.error})`);
    });

    console.log('\n🎠 === CAROUSEL INVESTIGATION ===');

    // Check carousel elements and styling
    const carouselInfo = await page.evaluate(() => {
      const carousel = document.querySelector('.brands-carousel-container');
      const wrapper = document.querySelector('.brands-carousel-container .swiper-wrapper');
      const nextArrow = document.querySelector('.brands-carousel-container .swiper-button-next');
      const prevArrow = document.querySelector('.brands-carousel-container .swiper-button-prev');
      const brandSlides = document.querySelectorAll('.brand-slide');

      return {
        carouselExists: !!carousel,
        wrapperExists: !!wrapper,
        nextArrowExists: !!nextArrow,
        prevArrowExists: !!prevArrow,
        brandSlidesCount: brandSlides.length,
        carouselStyles: carousel ? {
          display: getComputedStyle(carousel).display,
          position: getComputedStyle(carousel).position,
          overflow: getComputedStyle(carousel).overflow,
          width: getComputedStyle(carousel).width,
          height: getComputedStyle(carousel).height
        } : null,
        nextArrowStyles: nextArrow ? {
          display: getComputedStyle(nextArrow).display,
          visibility: getComputedStyle(nextArrow).visibility,
          opacity: getComputedStyle(nextArrow).opacity,
          color: getComputedStyle(nextArrow).color,
          backgroundColor: getComputedStyle(nextArrow).backgroundColor,
          zIndex: getComputedStyle(nextArrow).zIndex,
          position: getComputedStyle(nextArrow).position,
          right: getComputedStyle(nextArrow).right,
          fontSize: getComputedStyle(nextArrow).fontSize
        } : null,
        prevArrowStyles: prevArrow ? {
          display: getComputedStyle(prevArrow).display,
          visibility: getComputedStyle(prevArrow).visibility,
          opacity: getComputedStyle(prevArrow).opacity,
          color: getComputedStyle(prevArrow).color,
          backgroundColor: getComputedStyle(prevArrow).backgroundColor,
          zIndex: getComputedStyle(prevArrow).zIndex,
          position: getComputedStyle(prevArrow).position,
          left: getComputedStyle(prevArrow).left,
          fontSize: getComputedStyle(prevArrow).fontSize
        } : null,
        brandSlideContent: Array.from(brandSlides).slice(0, 3).map(slide => {
          const img = slide.querySelector('img');
          const text = slide.textContent.trim();
          return {
            hasImage: !!img,
            imageSrc: img ? img.src : null,
            imageAlt: img ? img.alt : null,
            textContent: text,
            innerHTML: slide.innerHTML
          };
        })
      };
    });

    console.log('Carousel Investigation Results:');
    console.log('  Carousel exists:', carouselInfo.carouselExists);
    console.log('  Wrapper exists:', carouselInfo.wrapperExists);
    console.log('  Next arrow exists:', carouselInfo.nextArrowExists);
    console.log('  Prev arrow exists:', carouselInfo.prevArrowExists);
    console.log('  Brand slides count:', carouselInfo.brandSlidesCount);

    if (carouselInfo.nextArrowStyles) {
      console.log('\n🏹 Next Arrow Styles:');
      console.log('  Display:', carouselInfo.nextArrowStyles.display);
      console.log('  Visibility:', carouselInfo.nextArrowStyles.visibility);
      console.log('  Opacity:', carouselInfo.nextArrowStyles.opacity);
      console.log('  Color:', carouselInfo.nextArrowStyles.color);
      console.log('  Background:', carouselInfo.nextArrowStyles.backgroundColor);
      console.log('  Z-Index:', carouselInfo.nextArrowStyles.zIndex);
      console.log('  Position:', carouselInfo.nextArrowStyles.position);
      console.log('  Right:', carouselInfo.nextArrowStyles.right);
      console.log('  Font Size:', carouselInfo.nextArrowStyles.fontSize);
    }

    if (carouselInfo.prevArrowStyles) {
      console.log('\n🏹 Prev Arrow Styles:');
      console.log('  Display:', carouselInfo.prevArrowStyles.display);
      console.log('  Visibility:', carouselInfo.prevArrowStyles.visibility);
      console.log('  Opacity:', carouselInfo.prevArrowStyles.opacity);
      console.log('  Color:', carouselInfo.prevArrowStyles.color);
      console.log('  Background:', carouselInfo.prevArrowStyles.backgroundColor);
      console.log('  Z-Index:', carouselInfo.prevArrowStyles.zIndex);
      console.log('  Position:', carouselInfo.prevArrowStyles.position);
      console.log('  Left:', carouselInfo.prevArrowStyles.left);
      console.log('  Font Size:', carouselInfo.prevArrowStyles.fontSize);
    }

    console.log('\n🖼️ Brand Slide Content Analysis:');
    carouselInfo.brandSlideContent.forEach((slide, index) => {
      console.log(`  Slide ${index + 1}:`);
      console.log(`    Has Image: ${slide.hasImage}`);
      console.log(`    Image Src: ${slide.imageSrc}`);
      console.log(`    Image Alt: ${slide.imageAlt}`);
      console.log(`    Text Content: ${slide.textContent}`);
      console.log(`    HTML: ${slide.innerHTML.substring(0, 100)}...`);
    });

    console.log('\n📝 === CONSOLE MESSAGES ===');
    consoleMessages.forEach(msg => console.log(`  ${msg}`));

    console.log('\n❌ === ERRORS ===');
    errors.forEach(error => console.log(`  ${error}`));

    // Take screenshot focused on brands section
    console.log('\n📸 Taking focused screenshot of brands section...');
    await page.screenshot({
      path: 'brands-debug-screenshot.png',
      fullPage: false,
      clip: {
        x: 0,
        y: 200,
        width: 1280,
        height: 400
      }
    });

  } catch (error) {
    console.error('Investigation error:', error);
  }

  await browser.close();
  console.log('\n✅ Investigation complete!');
})();