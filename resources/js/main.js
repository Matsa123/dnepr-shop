import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', () => {
  const quotes = [
    'Неймовірне відео! Надихає на нові звершення.',
    'Підіймайся вище і досягай своїх цілей з впевненістю.'
  ];
  const texts = document.querySelectorAll('.slide-text h2, .slide-text p, .slide-text a, #video-quote');
  texts.forEach((el, i) => {
    el.style.animationDelay = `${i * 0.3}s`;
    el.style.opacity = 1;
  });
  const quoteElement = document.getElementById('video-quote');

  const swiper = new Swiper('.video-swiper', {
    loop: true,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    on: {
      init() {
        updateQuote(this.activeIndex);
        this.slides.forEach(slide => {
          const video = slide.querySelector('video');
          if (video) {
            video.pause();
            video.currentTime = 0;
            video.onended = null;
          }
        });
        const activeVideo = this.slides[this.activeIndex].querySelector('video');
        if (activeVideo) {
          activeVideo.play().catch(() => { });
          activeVideo.onended = () => this.slideNext();
        }
      },
      slideChangeTransitionEnd() {
        updateQuote(this.realIndex);
        this.slides.forEach(slide => {
          const video = slide.querySelector('video');
          if (video) {
            video.pause();
            video.currentTime = 0;
            video.onended = null;
          }
        });
        const activeVideo = this.slides[this.activeIndex].querySelector('video');
        if (activeVideo) {
          activeVideo.play().catch(() => { });
          activeVideo.onended = () => this.slideNext();
        }
      }
    }
  });

  function updateQuote(index) {
    quoteElement.classList.remove('visible');  // скрыть (opacity 0)
    setTimeout(() => {
      const quoteIndex = index % quotes.length;
      quoteElement.textContent = quotes[quoteIndex];
      quoteElement.classList.add('visible');  // показать (opacity 1)
    }, 400); // время чуть меньше, чем в CSS transition для плавности
  }
});