// Main frontend script for Agro Market
// Features: tiny DOM helpers, flash messages, password toggle,
// form handling (AJAX-friendly), data-confirm, data-action buttons,
// and sidebar/mobile toggles.

(function () {
    'use strict';

    // Simple selectors
    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

    // Create or return a flash container appended to document.body
    function getFlashContainer() {
        let c = document.getElementById('flash-container');
        if (c) return c;
        c = document.createElement('div');
        c.id = 'flash-container';
        c.setAttribute('aria-live', 'polite');
        document.body.appendChild(c);
        return c;
    }

    /* Network helper: apiFetch
     * Centralized fetch wrapper that:
     * - reads CSRF token from <meta name="csrf-token"> if present
     * - sends FormData or JSON payloads correctly
     * - parses JSON/text responses and throws on non-OK
     * - supports a small retry/backoff for idempotent requests
     */
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    async function apiFetch(url, opts = {}) {
        const { method = 'GET', body = null, json = true, retries = 1 } = opts;
        const cfg = { method: method.toUpperCase(), credentials: 'same-origin', headers: {} };

        // Attach CSRF for unsafe methods
        if (CSRF_TOKEN && ['POST', 'PUT', 'PATCH', 'DELETE'].includes(cfg.method)) {
            cfg.headers['X-CSRF-Token'] = CSRF_TOKEN;
        }

        if (body instanceof FormData) {
            cfg.body = body;
        } else if (body && typeof body === 'object' && json) {
            cfg.headers['Content-Type'] = 'application/json';
            cfg.body = JSON.stringify(body);
        } else if (body) {
            cfg.body = body;
        }

        // simple retry/backoff
        let attempt = 0;
        while (true) {
            try {
                const res = await fetch(url, cfg);
                const contentType = res.headers.get('content-type') || '';
                if (!res.ok) {
                    const text = await res.text().catch(() => '');
                    const err = new Error('Network response was not ok');
                    err.status = res.status;
                    err.body = contentType.includes('application/json') ? JSON.parse(text || '{}') : text;
                    throw err;
                }
                if (contentType.includes('application/json')) return res.json();
                return res.text();
            } catch (err) {
                attempt += 1;
                if (attempt > retries) throw err;
                // backoff (simple)
                await new Promise((r) => setTimeout(r, 300 * attempt));
            }
        }
    }

    // Show a transient flash message. type: 'success'|'error'|'info'
    function showFlash(message, type = 'info', timeout = 4000) {
        const container = getFlashContainer();
        const el = document.createElement('div');
        el.className = `flash flash--${type}`;
        el.textContent = message;
        el.role = 'status';
        el.tabIndex = 0;
        // allow manual dismiss
        el.addEventListener('click', () => el.remove());
        container.appendChild(el);
        setTimeout(() => {
            if (el.parentNode) el.remove();
        }, timeout);
    }

    // Add a show/hide toggle for password inputs (by id or all on page)
    function installPasswordToggles() {
        $$('input[type="password"]').forEach((input) => {
            // Skip if already has a toggle
            if (input.dataset.hasToggle) return;
            input.dataset.hasToggle = '1';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'pw-toggle';
            btn.title = 'Show password';
            btn.textContent = 'Show';
            btn.style.marginLeft = '6px';
            btn.addEventListener('click', () => {
                const isPwd = input.type === 'password';
                input.type = isPwd ? 'text' : 'password';
                btn.textContent = isPwd ? 'Hide' : 'Show';
                btn.title = isPwd ? 'Hide password' : 'Show password';
            });
            // Try to insert after input; if wrapped in label, append after label
            if (input.parentNode && input.parentNode.tagName.toLowerCase() === 'label') {
                input.parentNode.appendChild(btn);
            } else if (input.nextSibling) {
                input.parentNode.insertBefore(btn, input.nextSibling);
            } else {
                input.parentNode.appendChild(btn);
            }
        });
    }

    // Utility: debounce a function (useful for search inputs)
    function debounce(fn, wait = 250) {
        let t;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // Utility: throttle a function
    function throttle(fn, limit = 200) {
        let lastCall = 0;
        let timer = null;
        return function (...args) {
            const now = Date.now();
            if (!lastCall || now - lastCall >= limit) {
                lastCall = now;
                fn.apply(this, args);
            } else {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    lastCall = Date.now();
                    fn.apply(this, args);
                }, limit - (now - lastCall));
            }
        };
    }

    // Intercept forms that either have `data-ajax` or class `auth-form` and submit via fetch
    async function handleFormSubmit(e) {
        const form = e.target;
        if (!(form instanceof HTMLFormElement)) return;
        const wantsAjax = form.dataset.ajax !== undefined || form.classList.contains('auth-form');
        if (!wantsAjax) return; // allow normal submit

        e.preventDefault();
        const method = (form.method || 'POST').toUpperCase();
        const action = form.action || window.location.href;
        const fd = new FormData(form);

        // Basic client-side validation for auth forms
        if (form.classList.contains('auth-form')) {
            const email = fd.get('email');
            const password = fd.get('password');
            if (!email || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
                showFlash('Please enter a valid email address.', 'error');
                return;
            }
            if (!password || String(password).length < 4) {
                showFlash('Password is too short.', 'error');
                return;
            }
        }

        try {
            const json = await apiFetch(action, { method, body: fd, retries: 1 }).catch((r) => { throw r; });
            if (typeof json === 'string') {
                // server returned HTML/text — navigate if redirected
                // best-effort: reload
                window.location.reload();
                return;
            }
            if (json && json.success) {
                if (json.message) showFlash(json.message, 'success');
                if (json.redirect) return (window.location.href = json.redirect);
                if (json.reload) return window.location.reload();
            } else {
                showFlash(json?.message || 'Operation failed', 'error');
            }
        } catch (err) {
            console.error('Form submit error', err);
            showFlash(err?.body?.message || 'Network error. Please try again.', 'error');
        }
    }

    // Confirm handler for elements with `data-confirm="Message"`
    function handleConfirmClick(e) {
        const el = e.target.closest('[data-confirm]');
        if (!el) return;
        const msg = el.dataset.confirm || 'Are you sure?';
        if (!window.confirm(msg)) {
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    }

    // Generic handler for elements with data-action (e.g., add-to-cart)
    async function handleActionClick(e) {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const action = btn.dataset.action;
        const url = btn.dataset.url || btn.getAttribute('href');
        // If button is inside a form, let the form handle submission
        const parentForm = btn.closest('form');
        if (parentForm) return; // assume form will submit

        if (!url) {
            // No URL — maybe it's a UI-only action
            return;
        }

        e.preventDefault();
        try {
            // optimistic update for common actions (e.g., add-to-cart)
            if (action === 'add-to-cart') optimisticCartUpdate(btn, +1);
            const json = await apiFetch(url, { method: 'POST', retries: 1 });
            // if server returns a cart_count, update UI to match authoritative value
            if (json && typeof json.cart_count !== 'undefined') {
                const counter = document.querySelector('#cart-count');
                if (counter) counter.textContent = String(json.cart_count);
            }
            if (json && json.success) {
                showFlash(json.message || 'Done', 'success');
                if (json.reload) setTimeout(() => window.location.reload(), 600);
                if (json.redirect) window.location.href = json.redirect;
            } else {
                // rollback optimistic update if necessary
                if (action === 'add-to-cart') optimisticCartUpdate(btn, -1);
                showFlash(json?.message || 'Operation failed', 'error');
            }
        } catch (err) {
            console.error('Action error', err);
            if (action === 'add-to-cart') optimisticCartUpdate(btn, -1);
            showFlash(err?.body?.message || 'Network error. Try again.', 'error');
        }
    }

    // Optimistic UI: update a cart count element when user adds/removes item
    // btn: the element that triggered the action; delta: +1 or -1
    function optimisticCartUpdate(btn, delta) {
        // common selector for cart count
        const counter = document.querySelector('#cart-count');
        if (!counter) return;
        const current = parseInt(counter.textContent || '0', 10) || 0;
        const next = Math.max(0, current + delta);
        counter.textContent = String(next);
    }

    // Lazy-load images using IntersectionObserver fallback
    function lazyLoadImages() {
        const imgs = document.querySelectorAll('img[data-src]');
        if (!imgs.length) return;
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        obs.unobserve(img);
                    }
                });
            }, { rootMargin: '200px' });
            imgs.forEach((i) => io.observe(i));
        } else {
            imgs.forEach((i) => { i.src = i.dataset.src; i.removeAttribute('data-src'); });
        }
    }

    // Global error handlers to surface uncaught errors
    function installGlobalErrorHandlers() {
        window.addEventListener('error', (ev) => {
            console.error('Uncaught error:', ev.error || ev.message);
        });
        window.addEventListener('unhandledrejection', (ev) => {
            console.error('Unhandled promise rejection:', ev.reason);
        });
    }

    // Sidebar / mobile menu toggle: elements with data-toggle="sidebar" will toggle `open` class on target
    function handleToggles(e) {
        const t = e.target.closest('[data-toggle]');
        if (!t) return;
        const targetSel = t.dataset.target || '#sidebar';
        const target = document.querySelector(targetSel);
        if (!target) return;
        e.preventDefault();
        target.classList.toggle('open');
    }

    // Initialize behaviors
    function init() {
        installPasswordToggles();
        installGlobalErrorHandlers();
        lazyLoadImages();
        attachProductSearch();
        document.addEventListener('submit', handleFormSubmit, true);
        document.addEventListener('click', handleConfirmClick, true);
        document.addEventListener('click', handleActionClick, true);
        document.addEventListener('click', handleToggles, true);
    }

    // Attach a debounced product search (filters .product-card by .product-title)
    function attachProductSearch() {
        const input = document.getElementById('product-search');
        if (!input) return;
        const grid = document.getElementById('product-grid');
        const cards = () => Array.from(grid.querySelectorAll('.product-card'));
        const doFilter = (ev) => {
            const q = (ev.target.value || '').trim().toLowerCase();
            cards().forEach((card) => {
                const titleEl = card.querySelector('.product-title');
                const title = titleEl ? titleEl.textContent.trim().toLowerCase() : '';
                const matched = q === '' || title.indexOf(q) !== -1;
                card.style.display = matched ? '' : 'none';
            });
        };
        input.addEventListener('input', debounce(doFilter, 250));
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose a small API for debugging from console
    window.Agro = window.Agro || {};
    window.Agro.showFlash = showFlash;
    window.Agro.installPasswordToggles = installPasswordToggles;
    window.Agro.apiFetch = apiFetch;
    window.Agro.debounce = debounce;
    window.Agro.throttle = throttle;
    window.Agro.lazyLoadImages = lazyLoadImages;
    window.Agro.optimisticCartUpdate = optimisticCartUpdate;
})();





