/* ─────────────────────────────────────────────────────────────────────────────
   Site interactions — vanilla, dependency-free.
   Every behaviour guards for element existence so it is safe on every page.
   ──────────────────────────────────────────────────────────────────────────── */

const $ = (sel, root = document) => root.querySelector(sel);
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

/* ── Theme toggle (circular View Transition wipe from the button) ──────────── */
function initTheme() {
    const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
    $$('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            const apply = () => {
                const dark = document.documentElement.classList.toggle('dark');
                try { localStorage.theme = dark ? 'dark' : 'light'; } catch (err) {}
            };
            if (!document.startViewTransition || reduce) { apply(); return; }

            const x = e.clientX || (innerWidth - 40);
            const y = e.clientY || 40;
            const end = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));

            const vt = document.startViewTransition(apply);
            vt.ready.then(() => {
                document.documentElement.animate(
                    { clipPath: [`circle(0px at ${x}px ${y}px)`, `circle(${end}px at ${x}px ${y}px)`] },
                    { duration: 480, easing: 'cubic-bezier(0.23,1,0.32,1)', pseudoElement: '::view-transition-new(root)' }
                );
            });
        });
    });
}

/* ── Header condense on scroll ─────────────────────────────────────────────── */
function initHeader() {
    const header = $('[data-header]');
    const bg = $('[data-header-bg]');
    if (!header) return;

    let ticking = false;
    const update = () => {
        const scrolled = window.scrollY > 8;
        header.classList.toggle('border-line', scrolled);
        if (bg) bg.classList.toggle('opacity-100', scrolled);
        ticking = false;
    };
    update();
    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
}

/* ── Mobile menu ───────────────────────────────────────────────────────────── */
function initMenu() {
    const toggle = $('[data-menu-toggle]');
    const panel = $('[data-menu-panel]');
    if (!toggle || !panel) return;
    toggle.addEventListener('click', () => {
        const open = panel.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', String(!open));
    });
}

/* ── Back to top ───────────────────────────────────────────────────────────── */
function initBackToTop() {
    const btn = $('[data-back-to-top]');
    if (!btn) return;
    let ticking = false;
    const update = () => {
        const show = window.scrollY > 700;
        btn.classList.toggle('opacity-0', !show);
        btn.classList.toggle('translate-y-4', !show);
        btn.classList.toggle('pointer-events-none', !show);
        ticking = false;
    };
    update();
    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

/* ── Reading progress ──────────────────────────────────────────────────────── */
function initReadingProgress() {
    const bar = $('#reading-progress');
    if (!bar) return;
    let ticking = false;
    const update = () => {
        const doc = document.documentElement;
        const max = doc.scrollHeight - doc.clientHeight;
        const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
        bar.style.width = pct + '%';
        ticking = false;
    };
    update();
    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    window.addEventListener('resize', update, { passive: true });
}

/* ── Copy code buttons ─────────────────────────────────────────────────────── */
function initCopyCode() {
    $$('.prose pre').forEach((pre) => {
        if (pre.querySelector('.code-copy')) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'code-copy';
        btn.textContent = 'Copy';
        btn.addEventListener('click', async () => {
            const code = pre.querySelector('code');
            const text = (code || pre).innerText;
            try {
                await navigator.clipboard.writeText(text);
                btn.textContent = 'Copied';
                btn.classList.add('copied');
                setTimeout(() => { btn.textContent = 'Copy'; btn.classList.remove('copied'); }, 1600);
            } catch (e) {}
        });
        pre.appendChild(btn);
    });
}

/* ── Table of contents + scrollspy ─────────────────────────────────────────── */
function slugify(text) {
    return text.toLowerCase().trim()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

function initTOC() {
    const article = $('[data-article]');
    if (!article) return;

    const headings = $$('h2, h3, h4', article);
    if (!headings.length) return;

    // Ensure stable ids, capture titles, and inject copy-link anchors on every heading.
    const used = new Set();
    headings.forEach((h) => {
        h.dataset.tocTitle = h.textContent.trim();
        if (!h.id) {
            let id = slugify(h.dataset.tocTitle) || 'section';
            let unique = id, n = 1;
            while (used.has(unique) || document.getElementById(unique)) unique = `${id}-${n++}`;
            h.id = unique;
        }
        used.add(h.id);

        if (!h.querySelector('.heading-anchor')) {
            const anchor = document.createElement('a');
            anchor.href = `#${h.id}`;
            anchor.className = 'heading-anchor';
            anchor.textContent = '#';
            anchor.setAttribute('aria-label', 'Copy link to this section');
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                history.replaceState(null, '', `#${h.id}`);
                document.getElementById(h.id).scrollIntoView({ behavior: 'smooth', block: 'start' });
                try {
                    navigator.clipboard.writeText(location.origin + location.pathname + '#' + h.id);
                    anchor.classList.add('copied');
                    setTimeout(() => anchor.classList.remove('copied'), 1200);
                } catch (err) {}
            });
            h.prepend(anchor);
        }
    });

    // Build the sidebar TOC only when there are enough H2/H3 sections.
    const toc = $('[data-toc]');
    if (!toc) return;
    const tocHeadings = headings.filter((h) => h.tagName === 'H2' || h.tagName === 'H3');
    if (tocHeadings.length < 2) {
        const wrap = toc.closest('[data-toc-wrap]');
        if (wrap) wrap.classList.add('hidden');
        return;
    }

    const list = document.createElement('ul');
    list.className = 'space-y-1';
    tocHeadings.forEach((h) => {
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = `#${h.id}`;
        a.textContent = h.dataset.tocTitle;
        a.dataset.tocLink = h.id;
        a.className =
            'block border-l-2 border-transparent py-1 text-sm text-muted transition-colors duration-200 hover:text-ink ' +
            (h.tagName === 'H3' ? 'pl-6' : 'pl-3');
        a.addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById(h.id).scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.replaceState(null, '', `#${h.id}`);
        });
        li.appendChild(a);
        list.appendChild(li);
    });
    toc.innerHTML = '';
    toc.appendChild(list);

    const links = $$('[data-toc-link]', toc);
    const setActive = (id) => {
        links.forEach((l) => {
            const on = l.dataset.tocLink === id;
            l.classList.toggle('!text-ink', on);
            l.classList.toggle('!border-accent', on);
            l.classList.toggle('font-medium', on);
        });
    };
    const observer = new IntersectionObserver((entries) => {
        const visible = entries.filter((e) => e.isIntersecting)
            .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);
        if (visible[0]) setActive(visible[0].target.id);
    }, { rootMargin: '-80px 0px -70% 0px', threshold: 0 });
    tocHeadings.forEach((h) => observer.observe(h));
}

/* ── Scroll reveal ─────────────────────────────────────────────────────────── */
function initReveal() {
    if (!document.documentElement.classList.contains('js-reveal')) return;
    const els = $$('.reveal');
    if (!els.length) return;
    const io = new IntersectionObserver((entries) => {
        entries.forEach((e) => {
            if (e.isIntersecting) { e.target.classList.add('reveal-in'); io.unobserve(e.target); }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.04 });
    els.forEach((el) => io.observe(el));
}

/* ── Image lightbox (article images) ───────────────────────────────────────── */
function initLightbox() {
    const imgs = $$('.prose img');
    if (!imgs.length) return;
    let box = null;

    const close = () => {
        if (!box) return;
        const b = box; box = null;
        b.classList.remove('open');
        document.body.style.overflow = '';
        setTimeout(() => b.remove(), 220);
    };
    const open = (src, alt) => {
        box = document.createElement('div');
        box.className = 'lightbox';
        const img = document.createElement('img');
        img.src = src; img.alt = alt || '';
        box.appendChild(img);
        box.addEventListener('click', close);
        document.body.appendChild(box);
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(() => box.classList.add('open'));
    };

    imgs.forEach((img) => img.addEventListener('click', () => open(img.currentSrc || img.src, img.alt)));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
}

/* ── External links inside prose ───────────────────────────────────────────── */
function initExternalLinks() {
    $$('.prose a[href]').forEach((a) => {
        const href = a.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:')) return;
        try {
            const url = new URL(href, location.href);
            if (url.host !== location.host) {
                a.target = '_blank';
                a.rel = 'noopener noreferrer';
                a.setAttribute('data-external', '');
            }
        } catch (e) {}
    });
}

/* ── Like button (supports multiple synced instances) ──────────────────────── */
function initLike() {
    const buttons = $$('[data-like]');
    if (!buttons.length) return;

    let busy = false;

    const applyState = (liked, count, animate) => {
        buttons.forEach((btn) => {
            btn.dataset.liked = liked ? '1' : '0';
            btn.classList.toggle('is-liked', liked);
            const icon = $('[data-like-icon]', btn);
            if (icon) {
                icon.classList.toggle('fill-current', liked);
                if (liked && animate) {
                    icon.classList.remove('animate-heart');
                    void icon.offsetWidth;
                    icon.classList.add('animate-heart');
                }
            }
            const label = $('[data-like-label]', btn);
            if (label) label.textContent = liked ? 'Liked' : 'Like';
        });
        $$('[data-like-count]').forEach((el) => (el.textContent = count));
    };

    const toggle = async (url) => {
        if (busy) return;
        busy = true;
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
            if (res.status === 429) {
                if (window.showRateLimitPopup) window.showRateLimitPopup();
                busy = false;
                return;
            }
            const data = await res.json();
            applyState(!!data.liked, data.count, true);
        } catch (e) {
            console.error(e);
        }
        busy = false;
    };

    buttons.forEach((btn) => btn.addEventListener('click', () => toggle(btn.dataset.likeUrl)));
}

/* ── Copy-to-clipboard (share link) ────────────────────────────────────────── */
function initShare() {
    $$('[data-copy-link]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const url = btn.dataset.copyLink || window.location.href;
            try {
                await navigator.clipboard.writeText(url);
                const label = $('[data-copy-label]', btn);
                const original = label ? label.textContent : null;
                btn.classList.add('is-copied');
                if (label) label.textContent = 'Copied';
                setTimeout(() => {
                    btn.classList.remove('is-copied');
                    if (label && original !== null) label.textContent = original;
                }, 1600);
            } catch (e) {}
        });
    });
}

/* ── Command palette ───────────────────────────────────────────────────────── */
function initCommandPalette() {
    const palette = $('[data-command-palette]');
    if (!palette) return;

    const backdrop = $('[data-command-backdrop]', palette);
    const card = $('[data-command-card]', palette);
    const input = $('[data-command-input]', palette);
    const results = $('[data-command-results]', palette);

    let open = false;
    let activeIndex = 0;
    let items = [];
    let debounce;
    let cache = new Map();

    const openPalette = () => {
        if (open) return;
        open = true;
        palette.classList.remove('hidden');
        requestAnimationFrame(() => {
            backdrop.classList.add('opacity-100');
            card.classList.remove('opacity-0', 'scale-95');
        });
        document.body.style.overflow = 'hidden';
        input.value = '';
        input.focus();
        search('');
    };

    const closePalette = () => {
        if (!open) return;
        open = false;
        backdrop.classList.remove('opacity-100');
        card.classList.add('opacity-0', 'scale-95');
        document.body.style.overflow = '';
        setTimeout(() => palette.classList.add('hidden'), 200);
    };

    const render = (data, q) => {
        items = data;
        if (!data.length) {
            results.innerHTML = `<p class="px-3 py-6 text-center text-sm text-faint">No posts found${q ? ` for “${escapeHtml(q)}”` : ''}.</p>`;
            return;
        }
        results.innerHTML = data.map((r, i) => `
            <a href="${r.url}" data-command-item data-index="${i}"
               class="flex items-start gap-3 rounded-xl px-3 py-2.5 transition-colors ${i === 0 ? 'bg-surface-2' : ''}">
                <span class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-surface-2 text-faint">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h11l5 5v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/></svg>
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-sm font-medium text-ink">${escapeHtml(r.title)}</span>
                    ${r.excerpt ? `<span class="mt-0.5 block truncate text-xs text-muted">${escapeHtml(r.excerpt)}</span>` : ''}
                </span>
                <span class="ml-auto self-center font-mono text-[0.6rem] text-faint">${r.date || ''}</span>
            </a>
        `).join('');
        activeIndex = 0;
        bindItems();
    };

    const bindItems = () => {
        $$('[data-command-item]', results).forEach((el) => {
            el.addEventListener('mousemove', () => setActive(parseInt(el.dataset.index, 10)));
        });
    };

    const setActive = (i) => {
        const nodes = $$('[data-command-item]', results);
        if (!nodes.length) return;
        activeIndex = (i + nodes.length) % nodes.length;
        nodes.forEach((n, idx) => n.classList.toggle('bg-surface-2', idx === activeIndex));
        nodes[activeIndex].scrollIntoView({ block: 'nearest' });
    };

    const search = (q) => {
        if (cache.has(q)) { render(cache.get(q), q); return; }
        fetch(`/api/search?q=${encodeURIComponent(q)}`, { headers: { Accept: 'application/json' } })
            .then((r) => r.json())
            .then((data) => { cache.set(q, data.results || []); render(data.results || [], q); })
            .catch(() => { results.innerHTML = '<p class="px-3 py-6 text-center text-sm text-faint">Search unavailable.</p>'; });
    };

    input.addEventListener('input', () => {
        const q = input.value.trim();
        clearTimeout(debounce);
        debounce = setTimeout(() => search(q), 140);
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown') { e.preventDefault(); setActive(activeIndex + 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(activeIndex - 1); }
        else if (e.key === 'Enter') {
            e.preventDefault();
            const nodes = $$('[data-command-item]', results);
            if (nodes[activeIndex]) window.location.href = nodes[activeIndex].href;
            else if (input.value.trim()) window.location.href = `/search?q=${encodeURIComponent(input.value.trim())}`;
        }
    });

    $$('[data-command-open]').forEach((b) => b.addEventListener('click', openPalette));
    backdrop.addEventListener('click', closePalette);
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); open ? closePalette() : openPalette(); }
        else if (e.key === 'Escape' && open) closePalette();
        else if (e.key === '/' && !open && !/input|textarea|select/i.test(document.activeElement.tagName)) { e.preventDefault(); openPalette(); }
    });
}

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

/* ── Boot ──────────────────────────────────────────────────────────────────── */
function boot() {
    initTheme();
    initHeader();
    initMenu();
    initBackToTop();
    initReadingProgress();
    initCopyCode();
    initTOC();
    initReveal();
    initLightbox();
    initExternalLinks();
    initLike();
    initShare();
    initCommandPalette();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
