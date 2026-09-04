/* =========================================================
   SysbiTechies — main.js
   Interactions: preloader, navbar, AOS, Typed, Swiper,
   CountUp counters, project filter, ripple, back-to-top
   ========================================================= */
(function () {
    'use strict';

    /* -------- Preloader -------- */
    window.addEventListener('load', function () {
        var pre = document.getElementById('preloader');
        if (pre) { pre.classList.add('loaded'); }
    });

    document.addEventListener('DOMContentLoaded', function () {

        /* -------- AOS -------- */
        if (window.AOS) {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 80,
                disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
            });
        }

        /* -------- Navbar scroll state + scroll progress -------- */
        var navbar = document.getElementById('siteNavbar');
        var progress = document.getElementById('scrollProgress');
        var backTop = document.getElementById('backToTop');

        function onScroll() {
            var y = window.scrollY || window.pageYOffset;

            if (navbar) { navbar.classList.toggle('scrolled', y > 40); }
            if (backTop) { backTop.classList.toggle('show', y > 500); }

            if (progress) {
                var h = document.documentElement.scrollHeight - window.innerHeight;
                progress.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        /* -------- Back to top -------- */
        if (backTop) {
            backTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        /* -------- Active nav link on scroll (scroll-spy) -------- */
        var sections = document.querySelectorAll('main section[id]');
        var navLinks = document.querySelectorAll('.nav-links .nav-link');
        if (sections.length && navLinks.length) {
            var spy = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var id = entry.target.getAttribute('id');
                        navLinks.forEach(function (link) {
                            link.classList.toggle('active', link.getAttribute('href') === '#' + id);
                        });
                    }
                });
            }, { rootMargin: '-45% 0px -50% 0px' });
            sections.forEach(function (s) { spy.observe(s); });
        }

        /* -------- Legal page TOC scroll-spy (Privacy / Terms) -------- */
        var legalToc = document.querySelectorAll('.legal-toc__link');
        var legalSections = document.querySelectorAll('.legal-section[id]');
        if (legalToc.length && legalSections.length) {
            var legalSpy = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var id = entry.target.getAttribute('id');
                        legalToc.forEach(function (link) {
                            link.classList.toggle('active', link.getAttribute('href') === '#' + id);
                        });
                    }
                });
            }, { rootMargin: '-15% 0px -70% 0px' });
            legalSections.forEach(function (s) { legalSpy.observe(s); });
        }

        /* -------- Typed.js (hero) -------- */
        var typedEl = document.getElementById('typed-words');
        if (typedEl && window.Typed) {
            // Word list is admin-editable (Home Page CMS); window.SYSBI_TYPED_WORDS
            // is set inline by the page before this script loads. Falls back to a
            // sensible default if the page didn't provide one.
            var typedWords = (window.SYSBI_TYPED_WORDS && window.SYSBI_TYPED_WORDS.length)
                ? window.SYSBI_TYPED_WORDS
                : ['Web Applications.', 'Mobile Apps.', 'Desktop Software.', 'ERP Systems.', 'CRM Solutions.', 'AI Solutions.'];

            new Typed('#typed-words', {
                strings: typedWords,
                typeSpeed: 65,
                backSpeed: 35,
                backDelay: 1600,
                startDelay: 400,
                loop: true,
                smartBackspace: true
            });
        }

        /* -------- CountUp counters -------- */
        var CountUpLib = (window.countUp && window.countUp.CountUp) || window.CountUp;
        var counters = document.querySelectorAll('[data-count]');
        if (counters.length) {
            var counted = new WeakSet();
            var countObserver = new IntersectionObserver(function (entries, obs) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || counted.has(entry.target)) { return; }
                    counted.add(entry.target);
                    var el = entry.target;
                    var end = parseFloat(el.getAttribute('data-count')) || 0;
                    var suffix = el.getAttribute('data-suffix') || '';

                    if (CountUpLib) {
                        var cu = new CountUpLib(el, end, {
                            duration: 2.2,
                            suffix: suffix,
                            useEasing: true,
                            separator: ','
                        });
                        if (!cu.error) { cu.start(); }
                        else { el.textContent = end + suffix; }
                    } else {
                        el.textContent = end + suffix;
                    }
                    obs.unobserve(el);
                });
            }, { threshold: 0.4 });
            counters.forEach(function (c) { countObserver.observe(c); });
        }

        /* -------- Testimonials Swiper -------- */
        if (window.Swiper && document.querySelector('.testimonials__swiper')) {
            new Swiper('.testimonials__swiper', {
                slidesPerView: 1,
                spaceBetween: 26,
                grabCursor: true,
                loop: true,
                autoplay: { delay: 4500, disableOnInteraction: false },
                pagination: { el: '.testimonials__pagination', clickable: true },
                breakpoints: {
                    768: { slidesPerView: 2 },
                    1100: { slidesPerView: 3 }
                }
            });
        }

        /* -------- Project filter -------- */
        var filterBtns = document.querySelectorAll('.filter-btn');
        var projectItems = document.querySelectorAll('.project-item');
        if (filterBtns.length && projectItems.length) {
            filterBtns.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(function (b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    var filter = btn.getAttribute('data-filter');

                    projectItems.forEach(function (item) {
                        var cats = item.getAttribute('data-category') || '';
                        var show = filter === 'all' || cats.indexOf(filter) !== -1;
                        item.classList.toggle('hide', !show);
                    });

                    if (window.AOS) { AOS.refreshHard(); }
                });
            });
        }

        /* -------- Button ripple -------- */
        document.querySelectorAll('.btn-ripple').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                var circle = document.createElement('span');
                var d = Math.max(btn.clientWidth, btn.clientHeight);
                var rect = btn.getBoundingClientRect();
                circle.className = 'ripple';
                circle.style.width = circle.style.height = d + 'px';
                circle.style.left = (e.clientX - rect.left - d / 2) + 'px';
                circle.style.top = (e.clientY - rect.top - d / 2) + 'px';
                btn.appendChild(circle);
                setTimeout(function () { circle.remove(); }, 600);
            });
        });

        /* -------- Hero parallax (pointer) -------- */
        var parallax = document.querySelector('[data-parallax]');
        if (parallax && !window.matchMedia('(prefers-reduced-motion: reduce)').matches && window.matchMedia('(min-width: 992px)').matches) {
            var hero = document.getElementById('home');
            hero.addEventListener('mousemove', function (e) {
                var cx = window.innerWidth / 2, cy = window.innerHeight / 2;
                var dx = (e.clientX - cx) / cx, dy = (e.clientY - cy) / cy;
                parallax.style.transform = 'translate(' + (dx * 12) + 'px,' + (dy * 12) + 'px)';
            });
            hero.addEventListener('mouseleave', function () {
                parallax.style.transform = 'translate(0,0)';
            });
        }

        /* -------- Cursor spotlight on service cards (premium micro-interaction) -------- */
        if (window.matchMedia('(hover: hover) and (min-width: 992px)').matches) {
            document.querySelectorAll('.service-card').forEach(function (card) {
                card.addEventListener('pointermove', function (e) {
                    var r = card.getBoundingClientRect();
                    card.style.setProperty('--mx', (e.clientX - r.left) + 'px');
                    card.style.setProperty('--my', (e.clientY - r.top) + 'px');
                });
            });
        }

        /* -------- Smooth-scroll for in-page anchors (offset aware) -------- */
        document.querySelectorAll('a[href^="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var href = link.getAttribute('href');
                if (href === '#' || href.length < 2) { return; }
                var target = document.querySelector(href);
                if (!target) { return; }
                e.preventDefault();
                var top = target.getBoundingClientRect().top + window.pageYOffset - 74;
                window.scrollTo({ top: top, behavior: 'smooth' });
            });
        });

    });
})();
