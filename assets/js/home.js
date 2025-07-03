import { Carousel } from 'bootstrap';
const myCarouselElement = document.querySelector('#carouselExample')
const carousel = new Carousel(myCarouselElement, {
    interval: 2000,
    touch: false
  })