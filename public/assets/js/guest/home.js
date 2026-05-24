/**
 * home.js — Lazy Loading untuk Home Page
 *
 * Scroll animations sekarang ditangani oleh AOS (Animate On Scroll).
 * File ini hanya bertugas untuk lazy load iframe Google Maps
 * via IntersectionObserver (native, tanpa CDN tambahan).
 *
 * Manfaat lazy iframe:
 *   - Google Maps touchstart/touchmove violation TIDAK muncul saat page load
 *   - Hemat bandwidth jika user tidak scroll ke section peta
 */

(function () {
    'use strict';

    /* ============================================================
       LAZY LOAD IFRAME (Google Maps)
       Iframe dengan [data-src] tidak dimuat sampai mendekati viewport.
       ============================================================ */
    function initLazyIframes() {
        var lazyIframes = document.querySelectorAll('iframe[data-src]');
        if (!lazyIframes.length) return;

        if (!('IntersectionObserver' in window)) {
            // Fallback: langsung load semua iframe
            lazyIframes.forEach(function (iframe) {
                iframe.src = iframe.getAttribute('data-src');
                iframe.removeAttribute('data-src');
            });
            return;
        }

        var iframeObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var iframe = entry.target;
                        iframe.src = iframe.getAttribute('data-src');
                        iframe.removeAttribute('data-src');
                        iframeObserver.unobserve(iframe);
                    }
                });
            },
            {
                // Mulai load 300px sebelum masuk viewport supaya sudah
                // siap ditampilkan saat user tiba di section peta
                rootMargin: '0px 0px 300px 0px',
                threshold: 0,
            }
        );

        lazyIframes.forEach(function (iframe) {
            iframeObserver.observe(iframe);
        });
    }

    /* ============================================================
       INIT
       ============================================================ */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLazyIframes);
    } else {
        initLazyIframes();
    }

})();
