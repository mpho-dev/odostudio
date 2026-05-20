/**
 * interactions.js
 *
 * Single source of truth for all hover, focus, and interactive animations.
 * Rules:
 *  - No continuous loops (repeat: -1) on page elements.
 *  - No fake form submission logic — let the server handle form state.
 *  - No floating label transforms — they cause layout shifts on custom forms.
 *  - 3D tilt capped at ±2.5° (divisor 80).
 *  - overwrite: 'auto' on all hover tweens to prevent queuing.
 */
import { gsap } from 'gsap';

/* ─── Button press feedback ───────────────────────────────────── */
function initButtons() {
    // Subtle press-down for all action buttons
    document.querySelectorAll('.pkg-btn, .form-submit, button[type="submit"], .nav-cta').forEach(btn => {
        btn.addEventListener('mousedown', () =>
            gsap.to(btn, { scale: 0.97, duration: 0.1, ease: 'power2.inOut', overwrite: 'auto' })
        );

        const resetScale = () =>
            gsap.to(btn, { scale: 1, duration: 0.18, ease: 'power2.out', overwrite: 'auto' });

        btn.addEventListener('mouseup', resetScale);
        btn.addEventListener('mouseleave', resetScale);

        // Ripple on click
        btn.addEventListener('click', e => createRipple(btn, e));
    });
}

/* ─── Ripple utility ──────────────────────────────────────────── */
function createRipple(el, e) {
    const rect = el.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x    = e.clientX - rect.left  - size / 2;
    const y    = e.clientY - rect.top   - size / 2;

    const ripple = document.createElement('span');
    ripple.style.cssText = `
        position:absolute;
        width:${size}px;height:${size}px;
        border-radius:50%;
        background:rgba(255,255,255,0.25);
        left:${x}px;top:${y}px;
        pointer-events:none;
        transform:scale(0);
        z-index:0;
    `;

    // Ensure the button can clip the ripple
    if (getComputedStyle(el).position === 'static') {
        el.style.position = 'relative';
    }
    el.style.overflow = 'hidden';
    el.appendChild(ripple);

    gsap.to(ripple, {
        scale: 3.5,
        opacity: 0,
        duration: 0.55,
        ease: 'power2.out',
        onComplete: () => ripple.remove(),
    });
}

/* ─── Form field focus glow ───────────────────────────────────── */
function initFormFields() {
    document.querySelectorAll('input:not([type="hidden"]), select, textarea').forEach(field => {
        field.addEventListener('focus', () =>
            gsap.to(field, {
                boxShadow: '0 0 0 2px rgba(201,168,76,0.35)',
                duration: 0.25,
                ease: 'power2.out',
                overwrite: 'auto',
            })
        );

        field.addEventListener('blur', () =>
            gsap.to(field, {
                boxShadow: 'none',
                duration: 0.25,
                ease: 'power2.out',
                overwrite: 'auto',
            })
        );
    });
}

/* ─── Card shadow depth on hover ──────────────────────────────── */
function initCardHover() {
    document.querySelectorAll('.service-card, .testimonial-card, .pkg-card').forEach(card => {
        card.addEventListener('mouseenter', () =>
            gsap.to(card, {
                boxShadow: '0 10px 30px rgba(0,0,0,0.28)',
                duration: 0.3,
                ease: 'power2.out',
                overwrite: 'auto',
            })
        );

        card.addEventListener('mouseleave', () =>
            gsap.to(card, {
                boxShadow: 'none',
                duration: 0.3,
                ease: 'power2.out',
                overwrite: 'auto',
            })
        );
    });
}

/* ─── Service card — subtle 3D tilt ──────────────────────────── */
function initServiceCardTilt() {
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const rotX = ((e.clientY - r.top)  - r.height / 2) / 80;
            const rotY = (r.width  / 2 - (e.clientX - r.left)) / 80;
            gsap.to(card, { rotationX: rotX, rotationY: rotY, duration: 0.5, ease: 'power2.out', overwrite: 'auto' });
        });

        card.addEventListener('mouseleave', () =>
            gsap.to(card, { rotationX: 0, rotationY: 0, duration: 0.6, ease: 'power2.out', overwrite: 'auto' })
        );

        // Service icon — gentle scale only
        const icon = card.querySelector('.service-icon');
        if (icon) {
            card.addEventListener('mouseenter', () =>
                gsap.to(icon, { scale: 1.1, duration: 0.3, ease: 'power2.out', overwrite: 'auto' })
            );
            card.addEventListener('mouseleave', () =>
                gsap.to(icon, { scale: 1, duration: 0.3, ease: 'power2.out', overwrite: 'auto' })
            );
        }

        // Feature list — opacity reveal on hover
        const features = Array.from(card.querySelectorAll('.service-features li'));
        if (features.length) {
            card.addEventListener('mouseenter', () =>
                gsap.fromTo(features, { opacity: 0.5 }, { opacity: 1, duration: 0.3, stagger: 0.05, ease: 'power1.out' })
            );
            card.addEventListener('mouseleave', () =>
                gsap.to(features, { opacity: 0.75, duration: 0.25, ease: 'power1.out' })
            );
        }
    });
}

/* ─── Investment tier cards ───────────────────────────────────── */
function initTierCards() {
    document.querySelectorAll('.pkg-card').forEach(card => {
        const isFeatured = card.classList.contains('featured');

        // Hover glow for featured card only
        if (isFeatured) {
            const glow = document.createElement('div');
            glow.style.cssText = `
                position:absolute;inset:-1px;
                background:linear-gradient(135deg,rgba(201,168,76,0.14),rgba(228,201,126,0.07),rgba(201,168,76,0.14));
                pointer-events:none;opacity:0;z-index:0;
            `;
            if (getComputedStyle(card).position === 'static') card.style.position = 'relative';
            card.insertBefore(glow, card.firstChild);

            card.addEventListener('mouseenter', () =>
                gsap.to(glow, { opacity: 1, duration: 0.35, ease: 'power2.out' })
            );
            card.addEventListener('mouseleave', () =>
                gsap.to(glow, { opacity: 0, duration: 0.35, ease: 'power2.out' })
            );
        }

        // Feature items — opacity reveal on hover
        const features = Array.from(card.querySelectorAll('.pkg-features li'));
        if (features.length) {
            card.addEventListener('mouseenter', () =>
                gsap.fromTo(features, { opacity: 0.6 }, { opacity: 1, duration: 0.28, stagger: 0.04, ease: 'power1.out' })
            );
        }

        // Price counter — fires once via IntersectionObserver
        const priceEl = card.querySelector('.pkg-price');
        if (priceEl) {
            const rawText  = priceEl.innerText;
            const numeric  = parseInt(rawText.replace(/\D/g, ''), 10);
            const suffix   = priceEl.querySelector('span') ? priceEl.querySelector('span').outerHTML : '';

            if (numeric) {
                const observer = new IntersectionObserver(entries => {
                    if (!entries[0].isIntersecting) return;
                    observer.unobserve(priceEl);

                    gsap.fromTo({ v: 0 }, { v: 0 }, {
                        v: numeric,
                        duration: 1.4,
                        ease: 'power2.out',
                        onUpdate() {
                            priceEl.innerHTML = `R&nbsp;${Math.floor(this.targets()[0].v).toLocaleString()} ${suffix}`;
                        },
                    });
                }, { threshold: 0.6 });

                observer.observe(priceEl);
            }
        }
    });
}

/* ─── Navigation link nudge ───────────────────────────────────── */
function initNavLinks() {
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', () =>
            gsap.to(link, { y: -2, duration: 0.18, ease: 'power2.out', overwrite: 'auto' })
        );
        link.addEventListener('mouseleave', () =>
            gsap.to(link, { y: 0, duration: 0.18, ease: 'power2.out', overwrite: 'auto' })
        );
    });
}

/* ─── Toast notifications (MutationObserver) ──────────────────── */
function initToasts() {
    const container = document.querySelector('.toast-container');
    if (!container) return;

    const observer = new MutationObserver(mutations => {
        mutations.forEach(m => {
            m.addedNodes.forEach(node => {
                if (!(node instanceof Element) || !node.classList.contains('toast')) return;

                gsap.fromTo(node,
                    { x: 110, opacity: 0 },
                    { x: 0, opacity: 1, duration: 0.28, ease: 'power2.out' }
                );

                // The toast's own CSS handles auto-dismiss; we handle the exit animation
                // by watching for the 'toast-out' class being added externally.
                const exitObserver = new MutationObserver(() => {
                    if (node.classList.contains('toast-out')) {
                        gsap.to(node, {
                            x: 110,
                            opacity: 0,
                            duration: 0.22,
                            ease: 'power2.in',
                            onComplete: () => node.remove(),
                        });
                        exitObserver.disconnect();
                    }
                });
                exitObserver.observe(node, { attributes: true, attributeFilter: ['class'] });
            });
        });
    });

    observer.observe(container, { childList: true });
}

/* ─── Modal open / close ──────────────────────────────────────── */
function initModals() {
    // Open
    document.querySelectorAll('[data-modal-trigger]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modal = document.querySelector(`#${trigger.dataset.modalTrigger}`);
            if (!modal) return;

            modal.style.display = 'flex';
            gsap.fromTo(modal,
                { opacity: 0, y: 16 },
                { opacity: 1, y: 0, duration: 0.28, ease: 'power2.out' }
            );
        });
    });

    // Close
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal, [role="dialog"]');
            if (!modal) return;

            gsap.to(modal, {
                opacity: 0,
                y: 12,
                duration: 0.22,
                ease: 'power2.in',
                onComplete: () => { modal.style.display = 'none'; },
            });
        });
    });
}

/* ─── Bootstrap ───────────────────────────────────────────────── */
function init() {
    initButtons();
    initFormFields();
    initCardHover();
    initServiceCardTilt();
    initTierCards();
    initNavLinks();
    initToasts();
    initModals();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
