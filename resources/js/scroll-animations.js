/**
 * scroll-animations.js
 *
 * Single source of truth for all scroll-triggered entrance animations.
 * Rules:
 *  - Opacity + small y offset (≤ 24px) only. No scale, rotation, or skew changes.
 *  - Play once (toggleActions: 'play none none none') — no reversing on scroll-up.
 *  - Safe null-guards before every querySelectorAll usage.
 *  - prefers-reduced-motion is handled globally by the CSS layer (app.css).
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/* ─── Shared defaults ─────────────────────────────────────────── */
const ENTER_FROM = { opacity: 0, y: 20 };
const ENTER_TO   = { opacity: 1, y: 0, ease: 'power2.out', duration: 0.8 };
const ONCE       = { toggleActions: 'play none none none' };

/**
 * Fade a NodeList in with a stagger, triggered when the first element
 * crosses the viewport threshold.
 */
function revealGroup(elements, { trigger, start = 'top 80%', duration = 0.8, stagger = 0.15, y = 20 } = {}) {
    if (!elements || !elements.length) return;

    gsap.fromTo(
        elements,
        { opacity: 0, y },
        {
            opacity: 1,
            y: 0,
            duration,
            stagger,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: trigger ?? elements[0],
                start,
                ...ONCE,
            },
        }
    );
}

/* ─── About section ───────────────────────────────────────────── */
function initAbout() {
    const section = document.querySelector('#about');
    if (!section) return;

    // Portrait frame — simple fade, no scale or rotation
    const portrait = section.querySelector('.portrait-frame');
    if (portrait) {
        revealGroup([portrait], { start: 'top 80%', duration: 1.0, stagger: 0, y: 24 });

        // Corner accents — staggered fade
        const corners = portrait.querySelectorAll('.portrait-corner');
        if (corners.length) {
            revealGroup(Array.from(corners), { trigger: portrait, start: 'top 75%', duration: 0.6, stagger: 0.08, y: 0 });
        }
    }

    // Text block
    const textBlock = section.querySelector('.about-text-block');
    if (textBlock) {
        const els = textBlock.querySelectorAll('.section-label, .section-title, .about-body p, p');
        revealGroup(Array.from(els), { trigger: textBlock, start: 'top 78%', stagger: 0.14 });
    }

    // Stats — counter animation fires once on enter
    section.querySelectorAll('.stat-num').forEach(stat => {
        const raw = stat.innerText.trim();
        const numeric = parseInt(raw.replace(/\D/g, ''), 10);
        const suffix  = raw.replace(/[\d,]/g, ''); // e.g. '+'

        if (!numeric) return;

        // Fade in
        gsap.fromTo(stat,
            { opacity: 0 },
            {
                opacity: 1,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: { trigger: stat, start: 'top 85%', ...ONCE },
            }
        );

        // Count up
        gsap.fromTo({ v: 0 }, { v: 0 }, {
            v: numeric,
            duration: 1.6,
            ease: 'power2.out',
            onUpdate() {
                stat.innerText = Math.floor(this.targets()[0].v).toLocaleString() + suffix;
            },
            scrollTrigger: { trigger: stat, start: 'top 85%', ...ONCE },
        });
    });
}

/* ─── Services section ────────────────────────────────────────── */
function initServices() {
    const section = document.querySelector('.services-full');
    if (!section) return;

    // Header
    revealGroup(
        Array.from(section.querySelectorAll('.section-label, .section-title')),
        { trigger: section, start: 'top 78%', stagger: 0.18 }
    );

    // Cards — staggered, play once
    const cards = Array.from(section.querySelectorAll('.service-card'));
    revealGroup(cards, { start: 'top 85%', stagger: 0.12 });
}

/* ─── Process section ─────────────────────────────────────────── */
function initProcess() {
    const section = document.querySelector('#process');
    if (!section) return;

    // Header
    revealGroup(
        Array.from(section.querySelectorAll('.section-label, .section-title')),
        { trigger: section, start: 'top 78%', stagger: 0.18 }
    );

    // Process step dots — fade in only
    const dots = Array.from(section.querySelectorAll('.process-dot'));
    revealGroup(dots, { start: 'top 82%', stagger: 0.16, y: 10 });

    // Step labels and descriptions
    const texts = Array.from(section.querySelectorAll('.process-name, .process-desc'));
    revealGroup(texts, { start: 'top 85%', stagger: 0.08 });

    // Connecting line (desktop) — expand width
    const line = section.querySelector('.hidden.lg\\:block');
    if (line) {
        gsap.fromTo(line,
            { scaleX: 0, transformOrigin: 'left center' },
            {
                scaleX: 1,
                duration: 1.0,
                ease: 'power2.inOut',
                scrollTrigger: { trigger: line, start: 'top 72%', ...ONCE },
            }
        );
    }
}

/* ─── Testimonials section ────────────────────────────────────── */
function initTestimonials() {
    const section = document.querySelector('.testimonials-full');
    if (!section) return;

    // Header
    revealGroup(
        Array.from(section.querySelectorAll('.section-label, .section-title')),
        { trigger: section, start: 'top 78%', stagger: 0.18 }
    );

    // Cards
    const cards = Array.from(section.querySelectorAll('.testimonial-card'));
    revealGroup(cards, { start: 'top 85%', stagger: 0.15 });
}

/* ─── Portfolio page ──────────────────────────────────────────── */
function initPortfolio() {
    const grid = document.querySelector('.grid.grid-cols-12');
    if (!grid) return;

    // Sub-page header elements
    const header = document.querySelector('.subpage-header-padding');
    if (header) {
        revealGroup(
            Array.from(header.querySelectorAll('.section-label, .section-title')),
            { trigger: header, start: 'top 78%', stagger: 0.18 }
        );

        // Category filter pills
        const filters = Array.from(header.querySelectorAll('a[href*="portfolio"]'));
        revealGroup(filters, { trigger: header, start: 'top 82%', stagger: 0.05, y: 10 });
    }

    // Grid items
    const items = Array.from(grid.querySelectorAll('a.group, div.group'));
    revealGroup(items, { trigger: grid, start: 'top 82%', stagger: 0.07 });
}

/* ─── Contact page ────────────────────────────────────────────── */
function initContact() {
    // Left info column
    const infoCol = document.querySelector('.contact-info-col, [data-contact-info]');
    if (infoCol) {
        revealGroup(
            Array.from(infoCol.querySelectorAll('.section-label, .section-title, p, .flex')),
            { trigger: infoCol, start: 'top 78%', stagger: 0.1 }
        );
    }

    // Form panel
    const form = document.querySelector('form');
    if (form) {
        const els = Array.from(form.querySelectorAll('label, input, select, textarea, button'));
        revealGroup(els, { trigger: form, start: 'top 80%', stagger: 0.04, y: 12 });
    }
}

/* ─── Investment tiers (packages section) ─────────────────────── */
function initPackages() {
    const section = document.querySelector('.packages-full, #packages');
    if (!section) return;

    revealGroup(
        Array.from(section.querySelectorAll('.section-label, .section-title')),
        { trigger: section, start: 'top 78%', stagger: 0.18 }
    );

    const cards = Array.from(section.querySelectorAll('.pkg-card'));
    revealGroup(cards, { start: 'top 85%', stagger: 0.14 });
}

/* ─── Bootstrap ───────────────────────────────────────────────── */
function init() {
    initAbout();
    initServices();
    initProcess();
    initTestimonials();
    initPortfolio();
    initContact();
    initPackages();

    // Recompute positions after all setup is done
    ScrollTrigger.refresh();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
