/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
const $ = require('jquery');
require('bootstrap');

import './styles/app.scss';

//dismiss alerts automatically when not manually dismissable

setTimeout(() => {
    $('.alert-success').fadeOut(500);
  }, "5000");
// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
