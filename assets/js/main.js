/* =========================================================================
   Magenta - front-end behaviour

   No dependencies and no build step: the theme is synced straight from git
   into wp-content/themes, so what is committed has to be what runs.

   Everything here is enhancement. The page is complete and readable with
   this file blocked, and every motion effect is skipped outright when the
   visitor has asked for reduced motion.
   ========================================================================= */

(function () {
  'use strict';

  var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ------------------------------------------------------------- Reveals */
  function initReveals() {
    var targets = document.querySelectorAll('[data-reveal]');
    if (!targets.length) return;

    if (reduced || !('IntersectionObserver' in window)) {
      targets.forEach(function (el) { el.classList.add('is-in'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        observer.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -12% 0px', threshold: 0.15 });

    targets.forEach(function (el) { observer.observe(el); });
  }

  /* ------------------------------------------------ Plate misregistration
     Each scoped section gets its own --reg, running 1 -> 0 as the section
     travels through the viewport. The plates start apart and pull into
     register: the brand idea, performed. */
  function initRegistration() {
    var scopes = Array.prototype.slice.call(document.querySelectorAll('[data-reg-scope]'));
    if (!scopes.length || reduced) return;

    // Scopes already on screen at load cannot be driven by scroll position —
    // their rect.top is at or above zero, which reads as "already in register"
    // and the effect would never play. Those get a timed entrance instead, and
    // are then left alone.
    var entrance = [];

    scopes.forEach(function (scope) {
      scope.style.setProperty('--reg', '1');
      if (scope.getBoundingClientRect().top < window.innerHeight * 0.6) {
        entrance.push(scope);
      }
    });

    var scrolled = scopes.filter(function (scope) {
      return entrance.indexOf(scope) === -1;
    });

    // Hold out of register just long enough to be seen, then pull in.
    // The 900ms travel comes from the transition on .cmyk__plate.
    if (entrance.length) {
      window.setTimeout(function () {
        entrance.forEach(function (scope) { scope.style.setProperty('--reg', '0'); });
      }, 260);
    }

    if (!scrolled.length) return;

    var ticking = false;

    function update() {
      ticking = false;
      var vh = window.innerHeight;

      scrolled.forEach(function (scope) {
        var rect = scope.getBoundingClientRect();

        // Off screen: leave it wherever it was, no work done.
        if (rect.bottom < -vh || rect.top > vh * 1.5) return;

        // 0 when the section top reaches the top of the viewport,
        // 1 when it is still a full viewport below.
        var reg = Math.min(1, Math.max(0, rect.top / vh));
        scope.style.setProperty('--reg', reg.toFixed(3));
      });
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(update);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  }

  /* ----------------------------------------------------------- Parallax
     Stickers and frames drift against the scroll at their own rate. Small
     numbers only - this is a nudge, not a ride. */
  function initParallax() {
    var layers = Array.prototype.slice.call(document.querySelectorAll('[data-parallax]'));
    if (!layers.length || reduced) return;

    var ticking = false;

    function update() {
      ticking = false;
      var vh = window.innerHeight;

      layers.forEach(function (layer) {
        var rect = layer.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > vh) return;

        var depth = parseFloat(layer.getAttribute('data-parallax')) || 0.05;
        var centre = rect.top + rect.height / 2 - vh / 2;
        layer.style.translate = '0 ' + (-centre * depth).toFixed(1) + 'px';
      });
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(update);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  }

  /* ------------------------------------------------------------- Marquee
     Duration is derived from track width so the speed stays constant no
     matter how many items are in the ticker. */
  function initMarquees() {
    document.querySelectorAll('[data-marquee]').forEach(function (marquee) {
      var track = marquee.querySelector('.marquee__track');
      if (!track) return;

      var pxPerSecond = 90;
      var duration = track.scrollWidth / pxPerSecond;

      marquee.querySelectorAll('.marquee__track').forEach(function (t) {
        t.style.animationDuration = duration.toFixed(2) + 's';
      });
    });
  }

  /* -------------------------------------------------------------- Header
     Hides on the way down, returns on the way up. Never hides while the
     mobile menu is open. */
  function initHeader() {
    var header = document.querySelector('[data-header]');
    if (!header) return;

    var last = window.scrollY;
    var ticking = false;

    function update() {
      ticking = false;
      var y = window.scrollY;

      if (document.body.classList.contains('nav-open')) {
        header.classList.remove('is-hidden');
        last = y;
        return;
      }

      if (y > last && y > 240) {
        header.classList.add('is-hidden');
      } else {
        header.classList.remove('is-hidden');
      }

      last = y;
    }

    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(update);
    }, { passive: true });
  }

  /* ---------------------------------------------------------- Mobile nav */
  function initNav() {
    var toggle = document.querySelector('[data-nav-toggle]');
    var nav = document.querySelector('[data-mobile-nav]');
    if (!toggle || !nav) return;

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', String(open));
      nav.hidden = !open;
      document.body.classList.toggle('nav-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    nav.addEventListener('click', function (event) {
      if (event.target.closest('a')) setOpen(false);
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && !nav.hidden) {
        setOpen(false);
        toggle.focus();
      }
    });
  }

  /* -------------------------------------------------------- Contact form */
  function initContactForm() {
    var form = document.querySelector('[data-contact-form]');
    if (!form || typeof window.magentaData === 'undefined') return;

    var status = form.querySelector('[data-form-status]');

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      var data = new FormData(form);
      data.append('action', window.magentaData.action);
      data.append('nonce', window.magentaData.nonce);

      form.classList.add('is-sending');
      status.className = 'print-form__status';
      status.textContent = window.magentaData.i18n.sending;

      fetch(window.magentaData.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          var ok = result && result.success;
          var message = (result && result.data && result.data.message) || window.magentaData.i18n.error;

          status.className = 'print-form__status ' + (ok ? 'is-ok' : 'is-error');
          status.textContent = message;

          if (ok) form.reset();
        })
        .catch(function () {
          status.className = 'print-form__status is-error';
          status.textContent = window.magentaData.i18n.error;
        })
        .finally(function () {
          form.classList.remove('is-sending');
        });
    });
  }

  function init() {
    initReveals();
    initRegistration();
    initParallax();
    initMarquees();
    initHeader();
    initNav();
    initContactForm();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
