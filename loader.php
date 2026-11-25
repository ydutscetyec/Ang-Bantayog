<?php
/**
 * Ang Bantayog — DOST Style Light Sweep Loader
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="ab-load" id="abLoad" role="status" aria-live="polite" aria-label="Naglo-load ang Ang Bantayog">

  <div class="ab-load__bg" aria-hidden="true"></div>

  <button type="button" class="ab-load__skip" id="abLoadSkip">Laktawan</button>

  <div class="ab-load__stage">
    <div class="ab-load__content">
      <!-- The text that gets highlighted -->
      <span class="ab-load__word">SDOSC Updates</span>
      
      <div class="ab-load__rule" aria-hidden="true"></div>
      
      <!-- Subtle progress bar -->
      <div class="ab-load__bar" aria-hidden="true"><i id="abLoadBar"></i></div>
    </div>
  </div>

</div>

<style id="ab-load-css">
/* Base Overlay */
.ab-load {
  position: fixed; 
  inset: 0; 
  z-index: 99999;
  background: #050a14; /* Very dark, almost black for better contrast */
  overflow: hidden;
  transition: opacity .8s ease, visibility .8s ease;
}
.ab-load.is-done { opacity: 0; visibility: hidden; pointer-events: none; }

html.ab-load-lock, body.ab-load-lock { overflow: hidden; }

.ab-load__bg { 
  position: absolute; 
  inset: 0; 
  z-index: 1;
  background: radial-gradient(circle at center, #0d1b2a 0%, #000000 100%);
}

/* Layout */
.ab-load__stage {
  position: absolute; 
  inset: 0;
  display: flex; 
  justify-content: center;
  align-items: center;
  padding: 24px; 
  text-align: center;
  z-index: 4;
}

.ab-load__content {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

/* "Ang" Kicker */
.ab-load__kicker {
  display: block;
  font-family: "Figtree", "Segoe UI", system-ui, sans-serif;
  font-size: clamp(.8rem, 2vw, 1rem);
  font-weight: 800; 
  letter-spacing: .8em; 
  text-transform: uppercase;
  color: #e1b64a; 
  margin-bottom: 10px;
  opacity: 0;
  animation: fadeUp .8s ease .2s forwards;
}

/* ========================================== */
/* DOST LIGHT SWEEP EFFECT                    */
/* ========================================== */
.ab-load__word {
  display: block;
  font-family: "Figtree", "Segoe UI", system-ui, sans-serif;
  font-size: clamp(2.5rem, 10vw, 6rem);
  font-weight: 900;
  line-height: 1; 
  letter-spacing: -0.02em;
  
  /* Base dim text */
  color: rgba(255, 255, 255, 0.08); 
  
  /* The Light Gradient */
  background: linear-gradient(
    120deg, 
    rgba(255, 255, 255, 0.08) 0%, 
    rgba(255, 255, 255, 0.08) 35%, 
    #ffffff 50%, /* Bright white center of the light */
    rgba(255, 255, 255, 0.08) 65%, 
    rgba(255, 255, 255, 0.08) 100%
  );
  background-size: 200% 100%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  
  /* Animation */
  animation: dost-light-sweep 4s infinite ease-in-out;
  filter: drop-shadow(0 0 20px rgba(255,255,255,0.1));
}

/* The actual sweeping motion */
@keyframes dost-light-sweep {
  0% { 
    background-position: 150% 0; 
    filter: drop-shadow(0 0 0px rgba(255,255,255,0));
  }
  50% { 
    filter: drop-shadow(0 0 30px rgba(255,255,255,0.6)); /* Glow when light is in center */
  }
  100% { 
    background-position: -150% 0; 
    filter: drop-shadow(0 0 0px rgba(255,255,255,0));
  }
}
/* ========================================== */

/* Gold Rule Line */
.ab-load__rule {
  width: min(300px, 60vw); 
  height: 1px; 
  margin: 30px auto 0;
  background: linear-gradient(90deg, transparent, #c89434, transparent);
  opacity: 0;
  animation: fadeUp .8s ease .4s forwards;
}

/* Progress Bar */
.ab-load__bar {
  width: min(250px, 50vw); 
  height: 2px; 
  margin: 20px auto 0;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 999px; 
  overflow: hidden;
  opacity: 0;
  animation: fadeUp .8s ease .5s forwards;
}

.ab-load__bar i {
  display: block; 
  height: 100%; 
  width: 0%;
  background: #e1b64a;
  border-radius: 999px;
  box-shadow: 0 0 10px #e1b64a;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* Skip Button */
.ab-load__skip {
  position: absolute; 
  right: 20px; 
  top: 20px; 
  z-index: 5;
  padding: 8px 18px; 
  border-radius: 999px;
  border: 1px solid rgba(200, 148, 52, .3);
  background: rgba(0, 0, 0, .5);
  color: rgba(255, 255, 255, .6);
  font-family: "Figtree", sans-serif;
  font-size: .6rem; 
  font-weight: 800;
  letter-spacing: .16em; 
  text-transform: uppercase;
  cursor: pointer;
  backdrop-filter: blur(8px);
  transition: all .3s ease;
}

.ab-load__skip:hover { 
  background: rgba(200, 148, 52, .2); 
  color: #fff;
  border-color: #c89434;
}

.admin-bar .ab-load__skip { top: 52px; }

@media (prefers-reduced-motion: reduce) {
  .ab-load { transition: opacity .3s ease; }
  .ab-load__word { animation: none; background-position: center; color: #fff; -webkit-text-fill-color: #fff; }
  .ab-load__kicker, .ab-load__rule, .ab-load__bar { animation: none; opacity: 1; }
}
</style>

<script id="ab-load-js">
(function () {
  'use strict';

  var LOAD_DURATION    = 3500; // How long the loader stays (ms)
  var ONCE_PER_SESSION = false; // Set to true if you only want it to show once per tab session

  var root   = document.getElementById('abLoad');
  var bar    = document.getElementById('abLoadBar');
  var skip   = document.getElementById('abLoadSkip');
  var docEl  = document.documentElement;

  var SEEN_KEY = 'ab-load-seen';

  function seen() {
    if (!ONCE_PER_SESSION) return false;
    try { return sessionStorage.getItem(SEEN_KEY) === '1'; } catch (e) { return false; }
  }
  function markSeen() {
    try { sessionStorage.setItem(SEEN_KEY, '1'); } catch (e) {}
  }

  if (seen()) {
    root.parentNode && root.parentNode.removeChild(root);
    return;
  }

  docEl.classList.add('ab-load-lock');
  document.body && document.body.classList.add('ab-load-lock');

  var started  = performance.now();
  var finished = false;

  function frame(now) {
    var elapsed = now - started;
    var p = Math.min(elapsed / LOAD_DURATION, 1);

    // Smooth progress bar
    if (bar) bar.style.width = (p * 100).toFixed(1) + '%';

    if (p < 1) {
      requestAnimationFrame(frame);
    } else {
      dismiss();
    }
  }

  function dismiss() {
    if (finished) return;
    finished = true;
    markSeen();
    root.classList.add('is-done');
    docEl.classList.remove('ab-load-lock');
    document.body && document.body.classList.remove('ab-load-lock');
    setTimeout(function () {
      root.parentNode && root.parentNode.removeChild(root);
    }, 800);
  }

  skip.addEventListener('click', dismiss);
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') dismiss();
  });

  // Start animation
  requestAnimationFrame(frame);
})();
</script>