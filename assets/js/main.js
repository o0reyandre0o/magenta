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

  /* ------------------------------------------------------------- Doodles
     Each path is measured so the dash animation covers exactly its own
     length - a shared guess would leave short strokes finished early and long
     ones still drawing. Paths within one doodle are staggered so a two-stroke
     underline or a three-stroke arrow lands in sequence rather than at once. */
  function initDoodles() {
    var doodles = Array.prototype.slice.call(document.querySelectorAll('.doodle--draw'));
    if (!doodles.length) return;

    doodles.forEach(function (svg) {
      Array.prototype.forEach.call(svg.querySelectorAll('path'), function (path, i) {
        var length = 0;
        try {
          length = path.getTotalLength();
        } catch (e) {
          // getTotalLength throws on a detached or zero-length path.
        }
        if (!length) return;
        path.style.setProperty('--len', Math.ceil(length));
        path.style.setProperty('--d', i);
      });
    });

    if (reduced || !('IntersectionObserver' in window)) {
      doodles.forEach(function (svg) { svg.classList.add('is-in'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-in');
        observer.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.2 });

    doodles.forEach(function (svg) { observer.observe(svg); });
  }

  /* ----------------------------------------------------------- Ink trail
     Process-colour dots laid down behind the pointer. The dot pool is fixed
     and recycled - creating and destroying nodes on mousemove is what makes
     effects like this stutter. */
  function initInkTrail() {
    if (reduced || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    var COUNT = 20;
    var plates = ['c', 'm', 'y', 'k'];

    var layer = document.createElement('div');
    layer.className = 'ink-trail';
    layer.setAttribute('aria-hidden', 'true');

    var dots = [];
    for (var i = 0; i < COUNT; i++) {
      var dot = document.createElement('span');
      dot.className = 'ink-dot ink-dot--' + plates[i % plates.length];
      layer.appendChild(dot);
      dots.push(dot);
    }
    document.body.appendChild(layer);

    var next = 0;
    var lastX = 0;
    var lastY = 0;
    var since = 0;

    document.addEventListener('mousemove', function (event) {
      var dx = event.clientX - lastX;
      var dy = event.clientY - lastY;
      var moved = Math.sqrt(dx * dx + dy * dy);

      lastX = event.clientX;
      lastY = event.clientY;

      // Space the dots by distance, not by time, so the trail stays even
      // whether the pointer is dawdling or flying.
      since += moved;
      if (since < 26) return;
      since = 0;

      var dot = dots[next];
      next = (next + 1) % COUNT;

      // Restart the animation on a recycled node.
      dot.style.animation = 'none';
      // Reading offsetWidth forces the style flush that makes the restart take.
      void dot.offsetWidth;

      var size = 10 + Math.min(moved, 40) * 0.5;
      dot.style.width = size + 'px';
      dot.style.height = size + 'px';
      dot.style.margin = (-size / 2) + 'px 0 0 ' + (-size / 2) + 'px';
      dot.style.transform = 'translate3d(' + event.clientX + 'px,' + event.clientY + 'px,0)';
      dot.style.animation = 'ink-dry 900ms var(--ease-out) forwards';
    }, { passive: true });
  }

  /* --------------------------------------------------------- Cursor ring
     A registration mark trailing the pointer, opening up over anything
     clickable. Follows on an easing so it lags slightly behind. */
  function initCursorRing() {
    if (reduced || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    var ring = document.createElement('div');
    ring.className = 'cursor-ring';
    ring.setAttribute('aria-hidden', 'true');
    document.body.appendChild(ring);

    var targetX = 0, targetY = 0, x = 0, y = 0, live = false;

    document.addEventListener('mousemove', function (event) {
      targetX = event.clientX;
      targetY = event.clientY;

      if (!live) {
        x = targetX;
        y = targetY;
        live = true;
        ring.classList.add('is-live');
      }

      var hot = event.target.closest('a, button, [role="button"], input, select, textarea, .work-card, .reel');
      ring.classList.toggle('is-hot', !!hot);
    }, { passive: true });

    document.addEventListener('mouseleave', function () {
      ring.classList.remove('is-live');
      live = false;
    });

    (function follow() {
      x += (targetX - x) * 0.18;
      y += (targetY - y) * 0.18;
      ring.style.transform = 'translate3d(' + x.toFixed(1) + 'px,' + y.toFixed(1) + 'px,0)';
      window.requestAnimationFrame(follow);
    })();
  }

  /* --------------------------------------------------------------- Reels
     Decorative clips: they play only while on screen, so a page left open in
     a background tab is not burning battery decoding three videos. Under
     reduced motion nothing plays and the poster frame stands in. */
  function initReels() {
    var reels = Array.prototype.slice.call(document.querySelectorAll('[data-reel]'));
    if (!reels.length) return;

    if (reduced || !('IntersectionObserver' in window)) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        var video = entry.target;

        if (entry.isIntersecting) {
          // preload="none" means there is nothing buffered until now.
          if (!video.dataset.loaded) {
            video.load();
            video.dataset.loaded = '1';
          }
          var played = video.play();
          if (played && played.catch) {
            // Autoplay can still be refused; the poster remains, which is fine.
            played.catch(function () {});
          }
        } else if (!video.paused) {
          video.pause();
        }
      });
    }, { threshold: 0.35 });

    reels.forEach(function (video) { observer.observe(video); });
  }

  /* ---------------------------------------------------------------- Rail
     Fills the process rail as the block travels the viewport, so the line
     advances with the reader the way a sheet advances through the press.
     Without JS the rail simply stays empty and the stations still read. */
  function initRail() {
    var rails = Array.prototype.slice.call(document.querySelectorAll('[data-rail]'));
    if (!rails.length || reduced) return;

    var ticking = false;

    function update() {
      ticking = false;
      var vh = window.innerHeight;

      rails.forEach(function (rail) {
        var rect = rail.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > vh) return;

        // Measured against the middle of the viewport: the rail is full when
        // the end of the list has reached the reader's eye line.
        var travelled = vh * 0.5 - rect.top;
        var pass = Math.min(1, Math.max(0, travelled / rect.height));

        rail.style.setProperty('--pass', pass.toFixed(3));
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
    initDoodles();
    initInkTrail();
    initCursorRing();
    initScrollEnergy();
    initReels();
    initRail();
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
