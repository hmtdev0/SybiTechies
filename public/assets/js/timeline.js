/* =========================================================
   SysbiTechies — timeline.js
   Scroll-driven "Our Journey" timeline (About page):
     - a progress line that fills/empties as the user scrolls,
       lerped for smoothness and fully reversible
     - dots that activate/deactivate as the line reaches them
     - cards that reveal/hide in sync with their dot

   Scoped entirely to .timeline — does not touch AOS or any
   other section. No-ops silently if .timeline isn't on the page.
   ========================================================= */
(function () {
    'use strict';

    var timeline = document.querySelector('.timeline');
    if (!timeline) { return; }

    var items = Array.prototype.slice.call(timeline.querySelectorAll('.timeline__item'));
    if (!items.length) { return; }

    var dots = items.map(function (item) { return item.querySelector('.timeline__dot'); });

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduceMotion) {
        // No scroll-linked motion at all — everything shown in its final state.
        timeline.style.setProperty('--tl-progress', 1);
        items.forEach(function (item) { item.classList.add('is-visible'); });
        dots.forEach(function (dot) { if (dot) { dot.classList.add('is-active'); } });
        return;
    }

    var LERP_FACTOR = 0.1;      // smoothing factor for the line's follow speed
    var VIEWPORT_TRIGGER = 0.7; // section top/bottom cross this fraction of viewport height

    // ---- Cached geometry (document-relative). Only recomputed on resize. ----
    var timelineTop = 0;
    var timelineHeight = 0;
    var dotOffsets = [];        // each dot's position as a 0–1 fraction of timelineHeight

    function measure() {
        var rect = timeline.getBoundingClientRect();
        var scrollY = window.scrollY || window.pageYOffset;

        timelineTop = rect.top + scrollY;
        timelineHeight = timeline.offsetHeight || 1; // guard divide-by-zero

        dotOffsets = dots.map(function (dot) {
            if (!dot) { return 0; }
            var dotRect = dot.getBoundingClientRect();
            var dotCenter = dotRect.top + scrollY + dotRect.height / 2;
            return (dotCenter - timelineTop) / timelineHeight;
        });
    }

    // ---- Scroll progress for the section as a whole ----
    // progress = 0 when the section TOP crosses VIEWPORT_TRIGGER of the viewport,
    // progress = 1 when the section BOTTOM crosses that same line.
    function computeTargetProgress() {
        var scrollY = window.scrollY || window.pageYOffset;
        var triggerLine = window.innerHeight * VIEWPORT_TRIGGER;

        var p = (scrollY - (timelineTop - triggerLine)) / timelineHeight;
        return Math.max(0, Math.min(1, p));
    }

    var targetProgress = 0;
    var currentProgress = 0;
    var rafId = null;

    function applyProgress(p) {
        timeline.style.setProperty('--tl-progress', p.toFixed(4));

        for (var i = 0; i < items.length; i++) {
            var active = p >= dotOffsets[i];
            if (dots[i]) { dots[i].classList.toggle('is-active', active); }
            items[i].classList.toggle('is-visible', active);
        }
    }

    function tick() {
        var diff = targetProgress - currentProgress;

        if (Math.abs(diff) < 0.0008) {
            currentProgress = targetProgress;
            applyProgress(currentProgress);
            rafId = null; // converged — stop looping until the next scroll/resize
            return;
        }

        currentProgress += diff * LERP_FACTOR;
        applyProgress(currentProgress);
        rafId = requestAnimationFrame(tick);
    }

    function requestTick() {
        if (rafId === null) {
            rafId = requestAnimationFrame(tick);
        }
    }

    function onScroll() {
        targetProgress = computeTargetProgress();
        requestTick();
    }

    var resizeTimer = null;
    function onResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            measure();
            onScroll();
        }, 150);
    }

    measure();
    targetProgress = computeTargetProgress();
    currentProgress = targetProgress; // no lerp-in on initial paint/reload
    applyProgress(currentProgress);

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onResize);
})();
