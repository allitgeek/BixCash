import puppeteer from 'puppeteer';

(async () => {
  console.log('🔍 Deep investigating brands alignment and arrow styling...');
  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 720 });

  try {
    await page.goto('http://127.0.0.1:8000', {
      waitUntil: 'networkidle2',
      timeout: 30000
    });

    await new Promise(resolve => setTimeout(resolve, 5000));

    console.log('\n📐 === ALIGNMENT INVESTIGATION ===');

    // Check alignment of various sections
    const alignmentData = await page.evaluate(() => {
      // Get key sections for alignment comparison
      const sections = {
        categoriesContainer: document.querySelector('.category-container'),
        brandsContainer: document.querySelector('.brands-container'),
        brandsCarousel: document.querySelector('.brands-carousel-container'),
        brandsWrapper: document.querySelector('.brands-carousel-container .swiper-wrapper'),
        brandSlides: Array.from(document.querySelectorAll('.brand-slide')),
        howItWorksSection: document.querySelector('.how-it-works-section'),
        promotionsSection: document.querySelector('.promotions-section'),
        promotionsGrid: document.querySelector('.promotions-grid')
      };

      const getElementInfo = (element, name) => {
        if (!element) return { name, exists: false };

        const rect = element.getBoundingClientRect();
        const styles = window.getComputedStyle(element);

        return {
          name,
          exists: true,
          rect: {
            left: rect.left,
            width: rect.width,
            centerX: rect.left + rect.width / 2
          },
          styles: {
            maxWidth: styles.maxWidth,
            margin: styles.margin,
            marginLeft: styles.marginLeft,
            marginRight: styles.marginRight,
            textAlign: styles.textAlign,
            display: styles.display,
            justifyContent: styles.justifyContent,
            alignItems: styles.alignItems,
            padding: styles.padding,
            paddingLeft: styles.paddingLeft,
            paddingRight: styles.paddingRight
          }
        };
      };

      const results = {};

      // Check each section
      Object.keys(sections).forEach(key => {
        if (key === 'brandSlides') {
          results[key] = sections[key].slice(0, 3).map((slide, index) =>
            getElementInfo(slide, `brand-slide-${index + 1}`)
          );
        } else {
          results[key] = getElementInfo(sections[key], key);
        }
      });

      // Check carousel arrows positioning
      const nextArrow = document.querySelector('.brands-carousel-container .swiper-button-next');
      const prevArrow = document.querySelector('.brands-carousel-container .swiper-button-prev');

      results.arrows = {
        next: getElementInfo(nextArrow, 'next-arrow'),
        prev: getElementInfo(prevArrow, 'prev-arrow')
      };

      // Check viewport and page width
      results.viewport = {
        width: window.innerWidth,
        scrollWidth: document.documentElement.scrollWidth
      };

      return results;
    });

    console.log('📊 Section Alignment Analysis:');

    // Analyze center alignment
    const centerPoints = {};
    Object.keys(alignmentData).forEach(key => {
      if (key === 'brandSlides' || key === 'arrows' || key === 'viewport') return;

      const section = alignmentData[key];
      if (section.exists) {
        centerPoints[key] = section.rect.centerX;
        console.log(`  ${key}: Center at ${section.rect.centerX.toFixed(1)}px, Width: ${section.rect.width.toFixed(1)}px, Max-width: ${section.styles.maxWidth}`);
      }
    });

    console.log('\n🎯 Center Point Comparison:');
    const avgCenter = Object.values(centerPoints);
    const mainCenter = avgCenter.reduce((a, b) => a + b, 0) / avgCenter.length;
    console.log(`  Average center point: ${mainCenter.toFixed(1)}px`);

    Object.keys(centerPoints).forEach(key => {
      const deviation = Math.abs(centerPoints[key] - mainCenter);
      const status = deviation < 5 ? '✅' : deviation < 20 ? '⚠️' : '❌';
      console.log(`  ${key}: ${status} ${deviation.toFixed(1)}px deviation`);
    });

    console.log('\n🏹 Arrow Styling Analysis:');
    const nextArrowStyles = await page.evaluate(() => {
      const arrow = document.querySelector('.brands-carousel-container .swiper-button-next');
      if (!arrow) return null;

      const styles = window.getComputedStyle(arrow);
      return {
        background: styles.backgroundColor,
        color: styles.color,
        borderRadius: styles.borderRadius,
        boxShadow: styles.boxShadow,
        width: styles.width,
        height: styles.height,
        border: styles.border,
        opacity: styles.opacity,
        fontSize: styles.fontSize,
        display: styles.display,
        alignItems: styles.alignItems,
        justifyContent: styles.justifyContent
      };
    });

    if (nextArrowStyles) {
      console.log('  Current Arrow Styles:');
      Object.keys(nextArrowStyles).forEach(prop => {
        console.log(`    ${prop}: ${nextArrowStyles[prop]}`);
      });
    }

    console.log('\n📱 Brand Slides Analysis:');
    alignmentData.brandSlides.forEach(slide => {
      if (slide.exists) {
        console.log(`  ${slide.name}: Left: ${slide.rect.left.toFixed(1)}, Width: ${slide.rect.width.toFixed(1)}, Center: ${slide.rect.centerX.toFixed(1)}`);
        console.log(`    Display: ${slide.styles.display}, Justify: ${slide.styles.justifyContent}, Align: ${slide.styles.alignItems}`);
      }
    });

    // Take a screenshot focused on the brands section
    console.log('\n📸 Taking detailed brands section screenshot...');
    await page.screenshot({
      path: 'brands-alignment-debug.png',
      fullPage: false,
      clip: {
        x: 0,
        y: 250,
        width: 1280,
        height: 300
      }
    });

    console.log('\n💡 RECOMMENDATIONS:');
    console.log('1. Remove green background from arrows - use subtle styling');
    console.log('2. Ensure brands carousel has same max-width and centering as other sections');
    console.log('3. Check if brand slides are properly contained within carousel boundaries');

  } catch (error) {
    console.error('Investigation error:', error);
  }

  await browser.close();
  console.log('\n✅ Deep investigation complete!');
})();