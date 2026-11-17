/**
 * AMUMA · animaciones e interacción.
 * GSAP + ScrollTrigger + SplitText + Lenis (todo incluido en el tema).
 */
(function () {
	'use strict';

	var root = document.documentElement;
	var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var hasGsap = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
	var i18n = (window.AMUMA && window.AMUMA.i18n) || {};

	window.AMUMA_READY = true;

	if (!hasGsap || reduced) {
		root.classList.add('anim-off');
		if (!hasGsap) root.classList.add('no-gsap');
	}

	var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
	var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

	/* ------------------------------------------------------------------
	 * Funciones que no dependen de las animaciones
	 * ------------------------------------------------------------------ */
	initMenu();
	initHeader();
	initLightbox();
	initLoadMore();
	initCopy();

	if (!hasGsap || reduced) {
		$$('.loader').forEach(function (l) { l.classList.add('is-done'); });
		return;
	}

	/* ------------------------------------------------------------------
	 * Animaciones
	 * ------------------------------------------------------------------ */
	var gsap = window.gsap;
	var ScrollTrigger = window.ScrollTrigger;
	var SplitText = window.SplitText;
	gsap.registerPlugin(ScrollTrigger);
	if (SplitText) gsap.registerPlugin(SplitText);

	gsap.defaults({ ease: 'expo.out', duration: 1.2 });

	// Scroll suave
	var lenis = null;
	if (typeof window.Lenis !== 'undefined') {
		lenis = new window.Lenis({ lerp: 0.1, wheelMultiplier: 1, anchors: { offset: -80 } });
		lenis.on('scroll', ScrollTrigger.update);
		gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
		gsap.ticker.lagSmoothing(0);
		window.amumaLenis = lenis;
	}

	document.fonts && document.fonts.ready ? document.fonts.ready.then(start) : window.addEventListener('load', start);

	function start() {
		var loader = $('.loader');
		var seen = false;
		try { seen = sessionStorage.getItem('amuma-intro') === '1'; } catch (e) {}

		if (loader && !seen) {
			if (lenis) lenis.stop();
			playLoader(loader).then(function () {
				try { sessionStorage.setItem('amuma-intro', '1'); } catch (e) {}
				if (lenis) lenis.start();
				intro();
			});
		} else {
			if (loader) loader.classList.add('is-done');
			intro();
		}
		initScroll();
		initMarquee();
		initReel();
		initMagnetic();
		initTilt();
		initCursor();
	}

	/* ---------- Trazo de un lazo ---------- */
	function drawRibbon(svg, opts) {
		opts = opts || {};
		var tl = gsap.timeline({ delay: opts.delay || 0 });
		var back = svg.querySelector('.ribbon__back');
		var front = svg.querySelector('.ribbon__front');
		var shadow = svg.querySelector('.ribbon__shadow');
		var shine = svg.querySelectorAll('.ribbon__shine');
		[back, front].forEach(function (p) {
			if (!p) return;
			var len = p.getTotalLength();
			gsap.set(p, { strokeDasharray: len, strokeDashoffset: len, autoAlpha: 1 });
		});
		gsap.set([shadow].concat(Array.prototype.slice.call(shine)), { autoAlpha: 0 });
		tl.to(back, { strokeDashoffset: 0, duration: opts.duration || 1.4, ease: 'power3.inOut' })
			.to(front, { strokeDashoffset: 0, duration: opts.duration || 1.4, ease: 'power3.inOut' }, '-=' + (opts.overlap || 0.9))
			.to(shadow, { autoAlpha: 1, duration: 0.6 }, '-=0.5')
			.to(shine, { autoAlpha: 1, duration: 0.8, stagger: 0.15 }, '-=0.4');
		return tl;
	}

	/* ---------- Loader ---------- */
	function playLoader(loader) {
		return new Promise(function (resolve) {
			var ribbon = loader.querySelector('svg');
			var word = loader.querySelector('.loader__word');
			var split = SplitText ? new SplitText(word, { type: 'chars' }) : null;
			var tl = gsap.timeline({ onComplete: function () { loader.classList.add('is-done'); resolve(); } });
			tl.add(drawRibbon(ribbon, { duration: 0.9, overlap: 0.55 }))
				.from(split ? split.chars : word, { yPercent: 100, autoAlpha: 0, stagger: 0.05, duration: 0.8 }, 0.5)
				.to(loader.querySelector('.loader__inner'), { scale: 0.9, autoAlpha: 0, duration: 0.6, ease: 'power2.in' }, '+=0.15')
				.to(loader, { clipPath: 'inset(0 0 100% 0)', duration: 1, ease: 'expo.inOut' }, '-=0.2');
			// Pulsación para saltar la intro
			loader.addEventListener('click', function () { tl.progress(1); });
		});
	}

	/* ---------- Entrada de la primera pantalla ---------- */
	function intro() {
		var titles = $$('[data-hero-title]');
		titles.forEach(function (t, i) {
			if (SplitText) {
				var s = new SplitText(t, { type: 'lines,words,chars', linesClass: 'split-line', wordsClass: 'word', mask: 'lines' });
				gsap.set(t, { autoAlpha: 1 });
				gsap.from(s.chars, { yPercent: 115, rotate: 6, duration: 1.3, stagger: 0.025, delay: 0.1 + i * 0.1 });
			} else {
				gsap.to(t, { autoAlpha: 1 });
			}
		});

		// Elementos de la primera pantalla que aparecen suavemente
		var firstFades = $$('[data-fade]').filter(function (el) { return el.getBoundingClientRect().top < window.innerHeight; });
		gsap.fromTo(firstFades, { autoAlpha: 0, y: 30 }, { autoAlpha: 1, y: 0, stagger: 0.1, delay: 0.45 });
		firstFades.forEach(function (el) { el.dataset.done = '1'; });

		// Lazo grande de portada
		var heroRibbon = $('.ribbon--hero');
		if (heroRibbon) {
			drawRibbon(heroRibbon, { delay: 0.1, duration: 1.2, overlap: 0.7 });
			gsap.from('.hero__orbit', { autoAlpha: 0, scale: 0.85, duration: 2, delay: 0.8 });
			gsap.from('.hero__halo', { scale: 0.6, autoAlpha: 0, duration: 2, delay: 0.2 });
			// Flota suavemente
			gsap.to(heroRibbon, { y: -14, rotate: 2.5, duration: 3.2, ease: 'sine.inOut', yoyo: true, repeat: -1, delay: 2 });
			heroMouse();
		}

		// Fotos reales que flotan alrededor del lazo
		var bubbles = $$('[data-bubble]');
		if (bubbles.length) {
			gsap.from(bubbles, { scale: 0, autoAlpha: 0, duration: 1.4, stagger: 0.15, delay: 0.9, ease: 'back.out(1.6)' });
			bubbles.forEach(function (b, i) {
				gsap.to(b, { y: i % 2 ? 12 : -12, x: i % 2 ? -6 : 6, duration: 3 + i * 0.6, ease: 'sine.inOut', yoyo: true, repeat: -1, delay: 2 + i * 0.3 });
			});
			if (window.matchMedia('(pointer: fine)').matches) {
				var tos = bubbles.map(function (b) { return { x: gsap.quickTo(b, 'xPercent', { duration: 1.4, ease: 'power3.out' }), y: gsap.quickTo(b, 'yPercent', { duration: 1.4, ease: 'power3.out' }) }; });
				window.addEventListener('mousemove', function (e) {
					var nx = e.clientX / window.innerWidth - 0.5;
					var ny = e.clientY / window.innerHeight - 0.5;
					tos.forEach(function (t, i) { var d = (i + 1) * 14; t.x(nx * d); t.y(ny * d); });
				});
			}
		}

		// Lazos decorativos de páginas interiores
		$$('.ribbon--page').forEach(function (svg) {
			drawRibbon(svg, { delay: 0.3, duration: 1.4 });
		});
	}

	function heroMouse() {
		var visual = $('[data-hero-visual]');
		if (!visual || !window.matchMedia('(pointer: fine)').matches) return;
		var xTo = gsap.quickTo(visual, 'x', { duration: 1.2, ease: 'power3.out' });
		var yTo = gsap.quickTo(visual, 'y', { duration: 1.2, ease: 'power3.out' });
		var rTo = gsap.quickTo(visual, 'rotationY', { duration: 1.2, ease: 'power3.out' });
		window.addEventListener('mousemove', function (e) {
			var nx = e.clientX / window.innerWidth - 0.5;
			var ny = e.clientY / window.innerHeight - 0.5;
			xTo(nx * 30);
			yTo(ny * 24);
			rTo(nx * 14);
		});
		gsap.set(visual, { transformPerspective: 900 });
	}

	/* ---------- Animaciones al hacer scroll ---------- */
	function initScroll() {
		// Textos que aparecen
		$$('[data-fade]').forEach(function (el) {
			if (el.dataset.done) return;
			gsap.fromTo(el, { autoAlpha: 0, y: 30 }, {
				autoAlpha: 1, y: 0,
				scrollTrigger: { trigger: el, start: 'top 88%', once: true }
			});
		});

		// Titulares por líneas
		$$('[data-split]').forEach(function (el) {
			if (!SplitText) { gsap.set(el, { autoAlpha: 1 }); return; }
			var s = new SplitText(el, { type: 'lines,words', linesClass: 'split-line', mask: 'lines' });
			gsap.set(el, { autoAlpha: 1 });
			gsap.from(s.words, {
				yPercent: 110, duration: 1.2, stagger: 0.06,
				scrollTrigger: { trigger: el, start: 'top 85%', once: true }
			});
		});

		// Bloques que suben al entrar
		ScrollTrigger.batch('[data-reveal]', {
			start: 'top 90%',
			once: true,
			onEnter: function (batch) {
				gsap.fromTo(batch, { autoAlpha: 0, y: 70 }, { autoAlpha: 1, y: 0, stagger: 0.12, duration: 1.3, overwrite: true });
			}
		});

		// Texto que se "enciende" palabra a palabra
		$$('[data-scrub-text]').forEach(function (el) {
			if (!SplitText) return;
			var s = new SplitText(el, { type: 'words', wordsClass: 'word' });
			gsap.set(s.words, { opacity: 0.14 });
			gsap.to(s.words, {
				opacity: 1, ease: 'none', stagger: 0.1,
				scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 45%', scrub: true }
			});
			// Las palabras clave en rosa
			s.words.forEach(function (w) {
				if (/^(compartir|informar|acompañamos|escuchamos|informamos|reivindicar|sensibilizar|sola)/i.test(w.textContent.trim())) w.style.color = 'var(--pink)';
			});
		});

		// Contadores
		$$('[data-count]').forEach(function (el) {
			var end = parseFloat(el.getAttribute('data-count'));
			if (isNaN(end)) return;
			var obj = { v: end > 1000 ? end - 40 : 0 };
			el.textContent = String(obj.v);
			gsap.to(obj, {
				v: end, duration: 2.2, ease: 'power2.out',
				scrollTrigger: { trigger: el, start: 'top 90%', once: true },
				onUpdate: function () { el.textContent = Math.round(obj.v).toLocaleString('es-ES'); }
			});
		});

		// Parallax de imágenes en tarjetas (fuera del carrusel horizontal)
		$$('[data-parallax-img]').forEach(function (img) {
			if (img.closest('[data-reel]')) return;
			gsap.fromTo(img, { yPercent: -6 }, {
				yPercent: 6, ease: 'none',
				scrollTrigger: { trigger: img.parentNode, start: 'top bottom', end: 'bottom top', scrub: true }
			});
		});

		// Foto de portada de una noticia: se "abre" y se acerca
		$$('[data-cover]').forEach(function (fig) {
			var inner = fig.querySelector('.single__cover-inner');
			var img = fig.querySelector('img');
			gsap.fromTo(inner, { clipPath: 'inset(12% 8% 12% 8% round 28px)' }, { clipPath: 'inset(0% 0% 0% 0% round 28px)', duration: 1.6, ease: 'expo.inOut', delay: 0.3 });
			if (img) {
				gsap.fromTo(img, { scale: 1.25 }, { scale: 1, duration: 2, ease: 'expo.out', delay: 0.3 });
				gsap.to(img, { yPercent: 8, ease: 'none', scrollTrigger: { trigger: fig, start: 'top top', end: 'bottom top', scrub: true } });
			}
		});

		// Al bajar, la portada se aleja
		var hero = $('[data-hero]');
		if (hero) {
			gsap.to('.hero__copy', { yPercent: -18, autoAlpha: 0.2, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
			gsap.to('.hero__visual .ribbon--hero', { rotate: 18, scale: 1.25, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
			gsap.to('.hero__blobs', { yPercent: 25, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
		}

		// Lazo del pie que gira y gran palabra AMUMA
		var footRibbon = $('.ribbon--footer');
		if (footRibbon) {
			gsap.fromTo(footRibbon, { rotate: -30, y: 80 }, { rotate: 10, y: 0, ease: 'none', scrollTrigger: { trigger: '.site-footer', start: 'top bottom', end: 'bottom bottom', scrub: true } });
		}
		var word = $('.site-footer__word');
		if (word && SplitText) {
			var ws = new SplitText(word, { type: 'chars' });
			gsap.from(ws.chars, { yPercent: 60, autoAlpha: 0, stagger: 0.06, duration: 1.4, scrollTrigger: { trigger: word, start: 'top 95%', once: true } });
		}

		window.addEventListener('load', function () { ScrollTrigger.refresh(); });
	}

	/* ---------- Cinta de palabras que reacciona al scroll ---------- */
	function initMarquee() {
		$$('[data-marquee]').forEach(function (m) {
			var track = m.querySelector('.marquee__track');
			var loop = gsap.to(track, { xPercent: -50, duration: 28, ease: 'none', repeat: -1 });
			var dir = 1;
			ScrollTrigger.create({
				trigger: m, start: 'top bottom', end: 'bottom top',
				onUpdate: function (self) {
					var v = self.getVelocity();
					dir = self.direction;
					gsap.to(loop, { timeScale: dir * (1 + Math.min(Math.abs(v) / 250, 6)), duration: 0.2, overwrite: true });
					gsap.to(loop, { timeScale: dir, duration: 1.2, delay: 0.25, overwrite: false });
				}
			});
			gsap.fromTo(m, { rotate: -4 }, { rotate: 1, ease: 'none', scrollTrigger: { trigger: m, start: 'top bottom', end: 'bottom top', scrub: true } });
		});
	}

	/* ---------- Carrusel horizontal de Actualidad ---------- */
	function initReel() {
		var section = $('[data-reel]');
		if (!section) return;
		var track = section.querySelector('[data-reel-track]');
		var bar = section.querySelector('[data-reel-progress]');
		var mm = gsap.matchMedia();
		mm.add('(min-width: 900px)', function () {
			section.classList.add('is-pinned');
			var dist = function () { return Math.max(0, track.scrollWidth - window.innerWidth); };
			var tween = gsap.to(track, {
				x: function () { return -dist(); },
				ease: 'none',
				scrollTrigger: {
					trigger: section.querySelector('.news-reel__pin'),
					start: 'top top',
					end: function () { return '+=' + dist(); },
					pin: true,
					scrub: 1,
					invalidateOnRefresh: true,
					anticipatePin: 1
				}
			});
			if (bar) {
				gsap.to(bar, { scaleX: 1, ease: 'none', scrollTrigger: { trigger: section.querySelector('.news-reel__pin'), start: 'top top', end: function () { return '+=' + dist(); }, scrub: true } });
			}
			// Las fotos se mueven un poco dentro de su marco mientras avanzan
			section.querySelectorAll('.card__img').forEach(function (img) {
				gsap.fromTo(img, { xPercent: 6, scale: 1.12 }, {
					xPercent: -6, ease: 'none',
					scrollTrigger: { trigger: img.parentNode, containerAnimation: tween, start: 'left right', end: 'right left', scrub: true }
				});
			});
			return function () { section.classList.remove('is-pinned'); };
		});
	}

	/* ---------- Botones "magnéticos" ---------- */
	function initMagnetic() {
		if (!window.matchMedia('(pointer: fine)').matches) return;
		$$('[data-magnetic]').forEach(function (btn) {
			var xTo = gsap.quickTo(btn, 'x', { duration: 0.6, ease: 'power3.out' });
			var yTo = gsap.quickTo(btn, 'y', { duration: 0.6, ease: 'power3.out' });
			btn.addEventListener('mousemove', function (e) {
				var r = btn.getBoundingClientRect();
				xTo((e.clientX - r.left - r.width / 2) * 0.3);
				yTo((e.clientY - r.top - r.height / 2) * 0.4);
			});
			btn.addEventListener('mouseleave', function () { xTo(0); yTo(0); });
		});
	}

	/* ---------- Tarjetas de servicios con inclinación y brillo ---------- */
	function initTilt() {
		if (!window.matchMedia('(pointer: fine)').matches) return;
		$$('[data-tilt]').forEach(function (card) {
			var rx = gsap.quickTo(card, 'rotationX', { duration: 0.8, ease: 'power3.out' });
			var ry = gsap.quickTo(card, 'rotationY', { duration: 0.8, ease: 'power3.out' });
			gsap.set(card, { transformPerspective: 900 });
			card.addEventListener('mousemove', function (e) {
				var r = card.getBoundingClientRect();
				var px = (e.clientX - r.left) / r.width;
				var py = (e.clientY - r.top) / r.height;
				card.style.setProperty('--mx', px * 100 + '%');
				card.style.setProperty('--my', py * 100 + '%');
				ry((px - 0.5) * 10);
				rx((0.5 - py) * 10);
			});
			card.addEventListener('mouseleave', function () { rx(0); ry(0); });
		});
	}

	/* ---------- Cursor ---------- */
	function initCursor() {
		var dot = $('.cursor-ribbon');
		if (!dot || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
		root.classList.add('has-cursor');
		var xTo = gsap.quickTo(dot, 'x', { duration: 0.35, ease: 'power3.out' });
		var yTo = gsap.quickTo(dot, 'y', { duration: 0.35, ease: 'power3.out' });
		window.addEventListener('mousemove', function (e) { xTo(e.clientX); yTo(e.clientY); });
		document.addEventListener('mouseover', function (e) {
			var label = e.target.closest('[data-cursor]');
			var link = e.target.closest('a, button');
			dot.classList.toggle('is-label', !!label);
			dot.textContent = label ? label.getAttribute('data-cursor') : '';
			dot.classList.toggle('is-link', !label && !!link);
		});
		document.addEventListener('mouseleave', function () { dot.classList.add('is-hidden'); });
		document.addEventListener('mouseenter', function () { dot.classList.remove('is-hidden'); });
	}

	/* ==================================================================
	 * Sin dependencias de GSAP
	 * ================================================================== */

	function initMenu() {
		var toggle = $('[data-menu-toggle]');
		var nav = $('#site-nav');
		if (!toggle || !nav) return;
		var setOpen = function (open) {
			document.body.classList.toggle('menu-open', open);
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (window.amumaLenis) open ? window.amumaLenis.stop() : window.amumaLenis.start();
			if (open && hasGsap && !reduced) {
				window.gsap.fromTo(nav.querySelectorAll('.menu > li > a'), { yPercent: 110 }, { yPercent: 0, stagger: 0.06, duration: 1, delay: 0.2, ease: 'expo.out' });
				window.gsap.fromTo(nav.querySelector('.nav__footer'), { autoAlpha: 0 }, { autoAlpha: 0.85, delay: 0.5 });
			}
		};
		toggle.addEventListener('click', function () { setOpen(!document.body.classList.contains('menu-open')); });
		nav.addEventListener('click', function (e) { if (e.target.closest('a')) setOpen(false); });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
	}

	function initHeader() {
		var header = $('[data-header]');
		if (!header) return;
		var last = window.scrollY;
		var ticking = false;
		var update = function () {
			var y = window.scrollY;
			header.classList.toggle('is-scrolled', y > 30);
			header.classList.toggle('is-hidden', y > 300 && y > last + 2 && !document.body.classList.contains('menu-open'));
			if (y < last - 2) header.classList.remove('is-hidden');
			last = y;
			ticking = false;
		};
		window.addEventListener('scroll', function () {
			if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
		}, { passive: true });
		update();
		var top = $('[data-to-top]');
		if (top) top.addEventListener('click', function (e) {
			e.preventDefault();
			if (window.amumaLenis) window.amumaLenis.scrollTo(0, { duration: 1.6 }); else window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	/* ---------- Visor de fotos ---------- */
	function initLightbox() {
		var scopes = $$('[data-lightbox-scope]');
		var cover = $('[data-cover] img');
		if (!scopes.length && !cover) return;

		var items = [];
		var addImg = function (img) {
			if (img.closest('a') && !isImageLink(img.closest('a'))) return; // si enlaza a otra página, se respeta
			var link = img.closest('a');
			var full = (link && isImageLink(link)) ? link.href : (img.getAttribute('data-lightbox-src') || largestFromSrcset(img) || img.src);
			var fig = img.closest('figure');
			var cap = fig && fig.querySelector('figcaption') ? fig.querySelector('figcaption').textContent : (img.alt || '');
			var idx = items.length;
			items.push({ src: full, caption: cap, alt: img.alt || '' });
			var target = link || img;
			target.addEventListener('click', function (e) { e.preventDefault(); open(idx); });
			img.style.cursor = 'zoom-in';
		};
		if (cover) addImg(cover);
		scopes.forEach(function (s) { $$('img', s).forEach(addImg); });
		if (!items.length) return;

		var box = document.createElement('div');
		box.className = 'lightbox';
		box.setAttribute('role', 'dialog');
		box.setAttribute('aria-modal', 'true');
		box.setAttribute('aria-label', 'Fotos');
		box.innerHTML =
			'<div class="lightbox__bar"><span class="lightbox__count"></span>' +
			'<button type="button" class="lightbox__close" aria-label="' + (i18n.close || 'Cerrar') + '"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18"/></svg></button></div>' +
			'<div class="lightbox__stage"><img class="lightbox__img" alt="">' +
			'<button type="button" class="lightbox__prev" aria-label="' + (i18n.prev || 'Anterior') + '"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="m15 5-7 7 7 7"/></svg></button>' +
			'<button type="button" class="lightbox__next" aria-label="' + (i18n.next || 'Siguiente') + '"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="m9 5 7 7-7 7"/></svg></button></div>' +
			'<div class="lightbox__caption"></div>';
		document.body.appendChild(box);

		var imgEl = $('.lightbox__img', box);
		var count = $('.lightbox__count', box);
		var caption = $('.lightbox__caption', box);
		var current = 0;
		var lastFocus = null;

		var show = function (i, dir) {
			current = (i + items.length) % items.length;
			var it = items[current];
			var apply = function () {
				imgEl.src = it.src;
				imgEl.alt = it.alt;
				caption.textContent = it.caption;
				count.textContent = (current + 1) + ' / ' + items.length;
			};
			if (hasGsap && !reduced && dir) {
				window.gsap.to(imgEl, { autoAlpha: 0, x: -40 * dir, duration: 0.2, ease: 'power2.in', onComplete: function () {
					apply();
					window.gsap.fromTo(imgEl, { autoAlpha: 0, x: 40 * dir }, { autoAlpha: 1, x: 0, duration: 0.5, ease: 'expo.out' });
				} });
			} else {
				apply();
			}
		};
		var open = function (i) {
			lastFocus = document.activeElement;
			show(i);
			box.classList.add('is-open');
			if (window.amumaLenis) window.amumaLenis.stop();
			document.body.style.overflow = 'hidden';
			$('.lightbox__close', box).focus();
			if (hasGsap && !reduced) window.gsap.fromTo(imgEl, { scale: 0.92, autoAlpha: 0 }, { scale: 1, autoAlpha: 1, duration: 0.7, ease: 'expo.out' });
		};
		var close = function () {
			box.classList.remove('is-open');
			if (window.amumaLenis) window.amumaLenis.start();
			document.body.style.overflow = '';
			if (lastFocus) lastFocus.focus();
		};
		var single = items.length < 2;
		$('.lightbox__prev', box).hidden = single;
		$('.lightbox__next', box).hidden = single;

		$('.lightbox__close', box).addEventListener('click', close);
		$('.lightbox__prev', box).addEventListener('click', function () { show(current - 1, -1); });
		$('.lightbox__next', box).addEventListener('click', function () { show(current + 1, 1); });
		box.addEventListener('click', function (e) { if (e.target === box || e.target.classList.contains('lightbox__stage')) close(); });
		document.addEventListener('keydown', function (e) {
			if (!box.classList.contains('is-open')) return;
			if (e.key === 'Escape') close();
			if (e.key === 'ArrowLeft' && !single) show(current - 1, -1);
			if (e.key === 'ArrowRight' && !single) show(current + 1, 1);
		});
		// Deslizar con el dedo
		var sx = null;
		box.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
		box.addEventListener('touchend', function (e) {
			if (sx === null || single) return;
			var dx = e.changedTouches[0].clientX - sx;
			if (Math.abs(dx) > 50) show(current + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1);
			sx = null;
		});
	}

	function isImageLink(a) {
		return /\.(jpe?g|png|gif|webp|avif)(\?.*)?$/i.test(a.getAttribute('href') || '');
	}

	function largestFromSrcset(img) {
		var set = img.getAttribute('srcset');
		if (!set) return null;
		var best = null;
		var bestW = 0;
		set.split(',').forEach(function (part) {
			var p = part.trim().split(/\s+/);
			var w = parseInt(p[1], 10) || 0;
			if (w > bestW) { bestW = w; best = p[0]; }
		});
		return best;
	}

	/* ---------- "Ver más" noticias sin recargar ---------- */
	function initLoadMore() {
		var btn = $('[data-load-more]');
		var target = $('[data-load-target]');
		var pag = $('[data-pagination]');
		if (!btn || !target || !window.fetch || !window.DOMParser) return;
		if (pag) pag.classList.add('is-enhanced');
		var label = btn.querySelector('span');
		var original = label ? label.textContent : '';
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			if (btn.classList.contains('is-loading')) return;
			btn.classList.add('is-loading');
			if (label) label.textContent = i18n.loading || 'Cargando…';
			fetch(btn.href, { credentials: 'same-origin' })
				.then(function (r) { return r.text(); })
				.then(function (html) {
					var doc = new DOMParser().parseFromString(html, 'text/html');
					var cards = Array.prototype.slice.call(doc.querySelectorAll('[data-load-target] > .card'));
					cards.forEach(function (c) { target.appendChild(c); });
					if (hasGsap && !reduced) {
						window.gsap.fromTo(cards, { autoAlpha: 0, y: 70 }, { autoAlpha: 1, y: 0, stagger: 0.1, duration: 1.2, ease: 'expo.out' });
						window.ScrollTrigger.refresh();
					} else {
						cards.forEach(function (c) { c.style.visibility = 'visible'; });
					}
					var next = doc.querySelector('[data-load-more]');
					if (next) {
						btn.href = next.href;
						btn.classList.remove('is-loading');
						if (label) label.textContent = original;
					} else {
						btn.parentNode.remove();
					}
				})
				.catch(function () { window.location.href = btn.href; });
		});
	}

	function initCopy() {
		$$('[data-copy]').forEach(function (b) {
			b.addEventListener('click', function () {
				var url = b.getAttribute('data-copy');
				var done = function () {
					var old = b.getAttribute('aria-label');
					b.setAttribute('aria-label', i18n.copied || 'Copiado');
					b.style.background = 'var(--pink)';
					b.style.color = '#fff';
					setTimeout(function () { b.setAttribute('aria-label', old); b.style.background = ''; b.style.color = ''; }, 1600);
				};
				if (navigator.clipboard) navigator.clipboard.writeText(url).then(done); else window.prompt('', url);
			});
		});
	}
})();
