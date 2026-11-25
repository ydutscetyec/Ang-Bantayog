/*!
 * Ang Bantayog v3 — publishing platform behaviour.
 *
 * Everything here degrades to a working page if JS fails: the carousel shows
 * its first slide, the ticker becomes a static list, Load More buttons simply
 * do nothing (archives still have real pagination), and the accessibility
 * toolbar falls back to the browser's own zoom.
 *
 * No dependencies.
 */
(function () {
  'use strict';

  var root = document.documentElement;
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function $(sel, ctx) { return (ctx || document).querySelector(sel); }
  function $$(sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); }
  function store(key, value) { try { localStorage.setItem(key, value); } catch (e) {} }
  function read(key) { try { return localStorage.getItem(key); } catch (e) { return null; } }

  /* ====================================================== Accessibility bar */
  function initA11y() {
    var buttons = $$('.a11ybar__btn[data-textsize]');
    var current = read('angbantayog-textsize') || 'md';

    function apply(size) {
      root.setAttribute('data-textsize', size);
      store('angbantayog-textsize', size);
      buttons.forEach(function (b) {
        b.classList.toggle('is-active', b.getAttribute('data-textsize') === size);
      });
    }
    apply(current);

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        apply(btn.getAttribute('data-textsize'));
      });
    });

    var contrast = $('#contrastToggle');
    if (!contrast) return;

    function setContrast(on) {
      if (on) { root.setAttribute('data-contrast', 'on'); }
      else { root.removeAttribute('data-contrast'); }
      store('angbantayog-contrast', on ? 'on' : 'off');
      contrast.setAttribute('aria-pressed', on ? 'true' : 'false');
      contrast.classList.toggle('is-active', on);
    }
    setContrast(read('angbantayog-contrast') === 'on');

    contrast.addEventListener('click', function () {
      setContrast(contrast.getAttribute('aria-pressed') !== 'true');
    });
  }

  /* ================================================================ Ticker */
  function initTicker() {
    var track = $('#tickerTrack');
    var pause = $('#tickerPause');
    if (!track) return;

    // Duplicate the items so the -50% keyframe loops seamlessly.
    if (!reduceMotion && track.children.length) {
      var clone = track.cloneNode(true);
      while (clone.firstChild) { track.appendChild(clone.firstChild); }
    }

    if (!pause) return;
    pause.addEventListener('click', function () {
      var paused = track.classList.toggle('is-paused');
      pause.setAttribute('aria-pressed', paused ? 'true' : 'false');
      pause.innerHTML = paused ? '&#9654;' : '&#10073;&#10073;';
    });
  }

  /* ============================================================== Carousel */
  function initCarousel() {
    var viewport = $('#heroCarousel');
    if (!viewport) return;

    var slides = $$('.slide', viewport);
    if (slides.length < 2) return;

    var dots = $$('[data-carousel-dot]');
    var index = 0;
    var timer = null;
    var DELAY = 6500;

    function show(next) {
      index = (next + slides.length) % slides.length;
      slides.forEach(function (s, i) { s.classList.toggle('is-active', i === index); });
      dots.forEach(function (d, i) {
        d.classList.toggle('is-active', i === index);
        d.setAttribute('aria-selected', i === index ? 'true' : 'false');
      });
    }

    function start() {
      if (reduceMotion) return;
      stop();
      timer = setInterval(function () { show(index + 1); }, DELAY);
    }
    function stop() { if (timer) { clearInterval(timer); timer = null; } }

    var prev = $('[data-carousel-prev]');
    var next = $('[data-carousel-next]');
    if (prev) prev.addEventListener('click', function () { show(index - 1); start(); });
    if (next) next.addEventListener('click', function () { show(index + 1); start(); });

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        show(parseInt(dot.getAttribute('data-carousel-dot'), 10) || 0);
        start();
      });
    });

    // Pause while the reader is hovering or tabbing through, and while the
    // tab is in the background.
    viewport.addEventListener('mouseenter', stop);
    viewport.addEventListener('mouseleave', start);
    viewport.addEventListener('focusin', stop);
    viewport.addEventListener('focusout', start);
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) { stop(); } else { start(); }
    });

    viewport.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') { show(index - 1); start(); }
      if (e.key === 'ArrowRight') { show(index + 1); start(); }
    });

    show(0);
    start();
  }

  /* ============================================================= Load more */
  function initLoadMore() {
    if (typeof window.ABData === 'undefined') return;

    $$('.loadmore').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var target = $(btn.getAttribute('data-target'));
        if (!target || btn.disabled) return;

        var page = (parseInt(btn.getAttribute('data-page'), 10) || 1) + 1;
        var original = btn.textContent;

        btn.disabled = true;
        btn.textContent = window.ABData.i18n.loading;

        // Exclude what is already on screen, so nothing repeats when a new
        // article is published between page loads.
        var shown = $$('[id^="post-"]', target).map(function (el) {
          return el.id.replace('post-', '');
        });

        var body = new URLSearchParams();
        body.append('action', 'ab_load_more');
        body.append('nonce', window.ABData.nonce);
        body.append('page', page);
        body.append('mode', btn.getAttribute('data-loadmore') || 'timeline');
        body.append('per_page', btn.getAttribute('data-per-page') || '8');
        body.append('category', btn.getAttribute('data-category') || '');
        body.append('exclude', shown.join(','));

        fetch(window.ABData.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
          body: body.toString()
        })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (!res || !res.success || !res.data.html) {
              btn.textContent = window.ABData.i18n.noMore;
              return;
            }

            var holder = document.createElement('div');
            holder.innerHTML = res.data.html;

            var first = null;
            while (holder.firstElementChild) {
              var node = holder.firstElementChild;
              target.appendChild(node);
              if (!first) first = node;
            }

            btn.setAttribute('data-page', page);

            if (res.data.has_more) {
              btn.disabled = false;
              btn.textContent = original;
            } else {
              btn.textContent = window.ABData.i18n.noMore;
            }

            // Move focus to the first new item so keyboard and screen-reader
            // users are not left stranded at the bottom of the old list.
            if (first) {
              var link = first.querySelector('a[href]');
              if (link) {
                link.setAttribute('tabindex', '-1');
                link.focus({ preventScroll: true });
              }
            }
          })
          .catch(function () {
            btn.disabled = false;
            btn.textContent = window.ABData.i18n.error;
          });
      });
    });
  }

  /* ====================================================== Reading progress */
  function initProgress() {
    var bar = $('#readingProgress');
    if (!bar) return;
    var fill = bar.firstElementChild;
    var article = $('.article__body');
    if (!fill || !article) return;

    var ticking = false;

    function update() {
      var rect = article.getBoundingClientRect();
      var total = rect.height - window.innerHeight;
      var done = total > 0 ? (-rect.top) / total : 0;
      fill.style.width = Math.max(0, Math.min(1, done)) * 100 + '%';
      ticking = false;
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(update);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
  }

  /* =========================================================== Copy link */
  function initCopy() {
    $$('.share__copy').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var url = btn.getAttribute('data-url') || window.location.href;
        var done = function () {
          btn.classList.add('is-copied');
          setTimeout(function () { btn.classList.remove('is-copied'); }, 1600);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(url).then(done, function () {});
        } else {
          var tmp = document.createElement('input');
          tmp.value = url;
          document.body.appendChild(tmp);
          tmp.select();
          try { document.execCommand('copy'); done(); } catch (e) {}
          document.body.removeChild(tmp);
        }
      });
    });
  }

  /* ============================================================ Mega menu */
  function initMenu() {
    // Submenus open on hover via CSS; this adds keyboard and touch support.
    $$('.nav__menu .menu-item-has-children > a').forEach(function (link) {
      link.setAttribute('aria-expanded', 'false');
      link.addEventListener('click', function (e) {
        var parent = link.parentNode;
        var isOpen = parent.classList.contains('is-open');
        // Only hijack the click on touch/narrow screens, where there is no hover.
        if (window.matchMedia('(hover: hover)').matches) return;
        e.preventDefault();
        parent.classList.toggle('is-open', !isOpen);
        link.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
      });
    });
  }

  /* ================================================================= Init */
  function init() {
    initA11y();
    initTicker();
    initCarousel();
    initLoadMore();
    initProgress();
    initCopy();
    initMenu();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
