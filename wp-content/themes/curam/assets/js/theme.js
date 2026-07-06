/* Curam theme — front-end interactions
   1) Mobile nav toggle
   2) Active nav link highlight
   3) Filtered collection gallery
*/
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {

    /* ---- Mobile nav ---- */
    var burger = document.querySelector('.cu-burger');
    var nav = document.querySelector('.cu-nav');
    if (burger && nav) {
      burger.addEventListener('click', function () {
        nav.classList.toggle('is-open');
        burger.setAttribute('aria-expanded', nav.classList.contains('is-open'));
      });
    }

    /* ---- Active nav highlight ---- */
    var path = window.location.pathname;
    document.querySelectorAll('.cu-nav a').forEach(function (link) {
      var href = link.getAttribute('href');
      // Skip anchor-only links (e.g. /#craft)
      if (!href || href.charAt(0) === '#') return;
      var linkPath = href.split('#')[0];
      if (linkPath && linkPath !== '/' && path.indexOf(linkPath) === 0) {
        link.classList.add('is-active');
      }
    });

    /* ---- Filtered gallery ---- */
    document.querySelectorAll('.cu-gallery').forEach(function (gallery) {
      var chips = gallery.querySelectorAll('.cu-chip');
      var items = gallery.querySelectorAll('.cu-gitem');
      chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
          var filter = chip.getAttribute('data-filter');
          chips.forEach(function (c) { c.classList.remove('is-active'); });
          chip.classList.add('is-active');
          items.forEach(function (item) {
            var cats = item.getAttribute('data-cats') || '';
            var show = (filter === '*') || cats.split(' ').indexOf(filter) !== -1;
            item.classList.toggle('is-hidden', !show);
          });
        });
      });
    });

  });
})();
