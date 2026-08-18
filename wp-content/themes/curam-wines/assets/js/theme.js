/* Curam Wines — front-end JS */
(function () {
  'use strict';

  function setChipState(chip, active) {
    chip.classList.toggle('is-active', active);
    chip.setAttribute('aria-pressed', active ? 'true' : 'false');
  }

  function announceLive(message) {
    var region = document.getElementById('cw-live-region');
    if (!region || !message) return;
    region.textContent = '';
    window.setTimeout(function () {
      region.textContent = message;
    }, 50);
  }

  function applyGalleryFilter(wrap) {
    var situationRow = wrap.querySelector('.cw-filter--situation');
    var seriesRow    = wrap.querySelector('.cw-filter--series');
    var hasDual      = situationRow && seriesRow;
    var hasSituation = situationRow && !seriesRow;
    var items        = wrap.querySelectorAll('[data-cats]');

    var situation = wrap.getAttribute('data-situation') || '*';
    var series    = wrap.getAttribute('data-series') || '*';
    var single    = wrap.getAttribute('data-filter') || '*';
    var visible   = 0;

    items.forEach(function (item) {
      var cats = (item.getAttribute('data-cats') || '').split(/\s+/).filter(Boolean);
      var show;

      if (hasDual) {
        var matchSituation = situation === '*' || cats.indexOf(situation) !== -1;
        var matchSeries    = series === '*' || cats.indexOf(series) !== -1;
        show = matchSituation && matchSeries;
      } else if (hasSituation) {
        show = situation === '*' || cats.indexOf(situation) !== -1;
      } else {
        show = single === '*' || cats.indexOf(single) !== -1;
      }

      item.classList.toggle('is-hidden', !show);
      item.setAttribute('aria-hidden', show ? 'false' : 'true');
      if (show) visible++;
    });

    wrap.setAttribute('data-visible-count', String(visible));
    return visible;
  }

  function initGalleryFilter(wrap) {
    var situationRow = wrap.querySelector('.cw-filter--situation');
    var seriesRow    = wrap.querySelector('.cw-filter--series');
    var hasDual      = situationRow && seriesRow;

    function activateInRow(row, filter) {
      if (!row) return;
      row.querySelectorAll('.cw-chip').forEach(function (chip) {
        setChipState(chip, chip.getAttribute('data-filter') === filter);
      });
    }

    wrap.querySelectorAll('.cw-chip').forEach(function (chip) {
      chip.addEventListener('click', function (e) {
        e.preventDefault();
        var filter = chip.getAttribute('data-filter');

        if (chip.closest('.cw-filter--situation')) {
          wrap.setAttribute('data-situation', filter);
          activateInRow(situationRow, filter);
        } else if (chip.closest('.cw-filter--series')) {
          wrap.setAttribute('data-series', filter);
          activateInRow(seriesRow, filter);
        } else {
          wrap.setAttribute('data-filter', filter);
          var row = chip.closest('.cw-filter');
          if (row) {
            row.querySelectorAll('.cw-chip').forEach(function (c) {
              setChipState(c, c.getAttribute('data-filter') === filter);
            });
          }
        }

        applyGalleryFilter(wrap);
        var count = wrap.getAttribute('data-visible-count') || '0';
        announceLive(chip.textContent.trim() + ' filter applied. ' + count + ' items shown.');
      });
    });

    var params = new URLSearchParams(window.location.search);

    if (hasDual) {
      wrap.setAttribute('data-situation', '*');
      wrap.setAttribute('data-series', '*');

      var situation = params.get('situation') || params.get('type') || '*';
      var series    = params.get('series') || '*';

      if (situationRow.querySelector('.cw-chip[data-filter="' + situation + '"]')) {
        wrap.setAttribute('data-situation', situation);
        activateInRow(situationRow, situation);
      }
      if (seriesRow.querySelector('.cw-chip[data-filter="' + series + '"]')) {
        wrap.setAttribute('data-series', series);
        activateInRow(seriesRow, series);
      }
    } else if (situationRow) {
      wrap.setAttribute('data-situation', '*');

      var activeSituation = params.get('situation') || params.get('type') || '*';
      if (situationRow.querySelector('.cw-chip[data-filter="' + activeSituation + '"]')) {
        wrap.setAttribute('data-situation', activeSituation);
        activateInRow(situationRow, activeSituation);
      }
    } else {
      wrap.setAttribute('data-filter', '*');
      var active = params.get('situation') || params.get('type') || params.get('series') || '*';
      var row    = wrap.querySelector('.cw-filter');
      if (row && row.querySelector('.cw-chip[data-filter="' + active + '"]')) {
        wrap.setAttribute('data-filter', active);
        activateInRow(row, active);
      }
    }

    applyGalleryFilter(wrap);
  }

  document.addEventListener('DOMContentLoaded', function () {

    /* ---- Sticky header ---- */
    var header = document.querySelector('.cw-header');
    if (header) {
      var onScroll = function () {
        header.classList.toggle('is-scrolled', window.scrollY > 40);
      };
      window.addEventListener('scroll', onScroll, { passive: true });
      onScroll();
    }

    /* ---- Mobile nav ---- */
    var burger = document.querySelector('.cw-burger');
    var nav    = document.querySelector('.cw-nav');
    if (burger && nav) {
      function closeNav() {
        nav.classList.remove('is-open');
        burger.setAttribute('aria-expanded', 'false');
        burger.setAttribute('aria-label', 'Open menu');
        document.body.classList.remove('cw-nav-open');
      }

      burger.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = nav.classList.toggle('is-open');
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
        burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        document.body.classList.toggle('cw-nav-open', open);
        if (open) {
          var firstLink = nav.querySelector('a');
          if (firstLink) firstLink.focus();
        }
      });

      nav.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeNav);
      });

      document.addEventListener('click', function (e) {
        if (!nav.classList.contains('is-open')) return;
        if (!nav.contains(e.target) && !burger.contains(e.target)) closeNav();
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNav();
      });
    }

    /* ---- Active nav highlight ---- */
    var path = window.location.pathname;
    document.querySelectorAll('.cw-nav a').forEach(function (link) {
      var href = link.getAttribute('href');
      if (!href || href.charAt(0) === '#') return;
      var linkPath = href.split('?')[0].split('#')[0];
      if (linkPath && linkPath !== '/' && path.indexOf(linkPath.replace(/\/$/, '')) === 0) {
        link.classList.add('is-active');
      }
    });

    /* ---- Filtered galleries ---- */
    document.querySelectorAll('.cw-gallery-wrap, .cw-cs-filter-wrap').forEach(initGalleryFilter);

    /* ---- Enquiry form detail toggle ---- */
    var formToggle = document.getElementById('cw-form-toggle');
    var formDetail = document.getElementById('cw-form-detail');
    var formMode   = document.getElementById('cw-enquiry-mode');
    if (formToggle && formDetail) {
      formToggle.addEventListener('click', function () {
        var open = formDetail.hasAttribute('hidden');
        if (open) {
          formDetail.removeAttribute('hidden');
          formToggle.setAttribute('aria-expanded', 'true');
          formToggle.textContent = 'Hide extra detail';
          if (formMode) formMode.value = 'detailed';
        } else {
          formDetail.setAttribute('hidden', '');
          formToggle.setAttribute('aria-expanded', 'false');
          formToggle.textContent = 'Add more detail (optional)';
          if (formMode) formMode.value = 'quick';
        }
      });
    }

    /* ---- Media lightbox (gallery page + post media) ---- */
    function initMediaLightbox() {
      var lightbox = document.getElementById('cw-gallery-lightbox');
      if (!lightbox) return;

      var lbImg     = document.getElementById('cw-gallery-lightbox-img');
      var lbVideo   = document.getElementById('cw-gallery-lightbox-video');
      var lbCounter = document.getElementById('cw-gallery-lightbox-counter');
      var lbType    = document.getElementById('cw-gallery-lightbox-type');
      var lbTitle   = document.getElementById('cw-gallery-lightbox-title');
      var lbLink    = document.getElementById('cw-gallery-lightbox-link');
      var closeBtn  = lightbox.querySelector('.cw-gallery-lightbox-close');
      var prevBtn   = lightbox.querySelector('.cw-gallery-lightbox-prev');
      var nextBtn   = lightbox.querySelector('.cw-gallery-lightbox-next');

      var activeGroup = null;
      var activeIndex = 0;
      var lastTrigger = null;

      function getFocusable(container) {
        return Array.prototype.slice.call(
          container.querySelectorAll('button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])')
        ).filter(function (el) {
          return el.offsetParent !== null || el === closeBtn;
        });
      }

      function trapFocus(e) {
        if (lightbox.hidden || e.key !== 'Tab') return;
        var focusable = getFocusable(lightbox.querySelector('.cw-gallery-lightbox-inner') || lightbox);
        if (!focusable.length) return;
        var first = focusable[0];
        var last  = focusable[focusable.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }

      function getGroupTriggers(groupEl) {
        return Array.prototype.slice.call(
          groupEl.querySelectorAll('.cw-lightbox-trigger:not(.is-hidden)')
        );
      }

      function readTriggerData(trigger) {
        return {
          media: trigger.getAttribute('data-media') || 'image',
          full: trigger.getAttribute('data-full') || '',
          video: trigger.getAttribute('data-video') || '',
          title: trigger.getAttribute('data-title') || '',
          typeLabel: trigger.getAttribute('data-type-label') || '',
          url: trigger.getAttribute('data-url') || ''
        };
      }

      function showSlide(triggers, index) {
        var trigger = triggers[index];
        if (!trigger) return;

        var data = readTriggerData(trigger);
        activeIndex = index;

        if (data.media === 'video' && data.video) {
          lbImg.hidden = true;
          lbVideo.hidden = false;
          lbVideo.src = data.video;
          if (data.full) lbVideo.poster = data.full;
          lbVideo.load();
          lbVideo.play();
        } else {
          lbVideo.pause();
          lbVideo.removeAttribute('src');
          lbVideo.load();
          lbVideo.hidden = true;
          lbImg.hidden = false;
          lbImg.src = data.full;
          lbImg.alt = data.title;
        }

        lbType.textContent = data.typeLabel;
        lbTitle.textContent = data.title;
        lbCounter.textContent = (index + 1) + ' / ' + triggers.length;

        if (data.url) {
          lbLink.href = data.url;
          lbLink.hidden = false;
        } else {
          lbLink.hidden = true;
        }

        if (prevBtn) prevBtn.disabled = index <= 0;
        if (nextBtn) nextBtn.disabled = index >= triggers.length - 1;

        announceLive('Showing ' + data.title + ', item ' + (index + 1) + ' of ' + triggers.length);
      }

      function openLightbox(groupEl, trigger) {
        activeGroup = groupEl;
        lastTrigger = trigger;
        var triggers = getGroupTriggers(groupEl);
        var index = triggers.indexOf(trigger);
        if (index < 0) return;

        showSlide(triggers, index);
        lightbox.hidden = false;
        lightbox.removeAttribute('aria-hidden');
        document.body.classList.add('cw-lightbox-open');
        if (closeBtn) closeBtn.focus();
      }

      function closeLightbox() {
        lightbox.hidden = true;
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('cw-lightbox-open');
        lbVideo.pause();
        lbVideo.removeAttribute('src');
        lbVideo.load();
        lbImg.removeAttribute('src');
        activeGroup = null;
        if (lastTrigger) {
          lastTrigger.focus();
          lastTrigger = null;
        }
      }

      function step(delta) {
        if (!activeGroup) return;
        var triggers = getGroupTriggers(activeGroup);
        var next = activeIndex + delta;
        if (next < 0 || next >= triggers.length) return;
        showSlide(triggers, next);
      }

      document.querySelectorAll('[data-lightbox-group]').forEach(function (groupEl) {
        groupEl.addEventListener('click', function (e) {
          var trigger = e.target.closest('.cw-lightbox-trigger');
          if (!trigger || !groupEl.contains(trigger)) return;
          e.preventDefault();
          openLightbox(groupEl, trigger);
        });
      });

      if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
      if (prevBtn) prevBtn.addEventListener('click', function () { step(-1); });
      if (nextBtn) nextBtn.addEventListener('click', function () { step(1); });

      lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) closeLightbox();
      });

      document.addEventListener('keydown', function (e) {
        if (lightbox.hidden) return;
        trapFocus(e);
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') step(-1);
        if (e.key === 'ArrowRight') step(1);
      });
    }

    initMediaLightbox();

    document.querySelectorAll('.cw-gallery-page-wrap').forEach(initGalleryFilter);

  });
})();
