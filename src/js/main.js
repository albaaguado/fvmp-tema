/**
 * FVMP Theme — Main JavaScript Entry Point
 *
 * Imports SCSS for Vite processing and initializes theme scripts.
 */

// Import SCSS — Vite processes this into CSS
import '../scss/main.scss';

// Theme initialization
document.addEventListener( 'DOMContentLoaded', () => {
	console.log( '[FVMP] Theme loaded.' );
	setActiveNavItem();
	initStickyHeader();
	initPresentacionToggle();
	initTestimoniosSlider();
	initFcFilters();
	initHomeScrollAnimations();
} );

/**
 * Easing for entrance/counter animations.
 * Fast at the beginning, slower near the end.
 *
 * @param {number} t Progress (0..1).
 * @return {number}
 */
function dooEaseOutCubic( t ) {
	return 1 - Math.pow( 1 - t, 3 );
}

/**
 * Animate an integer counter while preserving prefix/suffix formatting.
 * Examples: 54.000+, +1.200 alumnas/os.
 *
 * @param {HTMLElement} el Number element.
 * @param {number} duration Animation duration in ms.
 */
function animateImpactNumber( el, duration = 1600 ) {
	const rawText = ( el.textContent || '' ).trim();
	const match = rawText.match( /(\d[\d.,\s]*)/ );

	if ( ! match ) return;

	const numericPart = match[ 1 ];
	const digitsOnly = numericPart.replace( /\D/g, '' );
	const target = Number.parseInt( digitsOnly, 10 );

	if ( Number.isNaN( target ) ) return;

	const prefix = rawText.slice( 0, match.index );
	const suffix = rawText.slice( ( match.index || 0 ) + numericPart.length );
	const formatter = new Intl.NumberFormat( 'es-ES' );
	const start = performance.now();

	function tick( now ) {
		const progress = Math.min( 1, ( now - start ) / duration );
		const eased = dooEaseOutCubic( progress );
		const value = Math.round( target * eased );

		el.textContent = `${ prefix }${ formatter.format( value ) }${ suffix }`;

		if ( progress < 1 ) {
			requestAnimationFrame( tick );
		}
	}

	requestAnimationFrame( tick );
}

/**
 * Animate progress bar width and percentage text in a modalidades card.
 *
 * @param {HTMLElement} card Modalidades card.
 * @param {number} delay Delay in ms.
 */
function animateModalidadesCard( card, delay = 0 ) {
	const fill = card.querySelector( '.doo-modal-card__fill' );
	const pctEl = card.querySelector( '.doo-modal-card__pct' );
	if ( ! fill || ! pctEl ) return;

	const pctText = ( pctEl.textContent || '' ).replace( /\D/g, '' );
	let target = Number.parseInt( pctText, 10 );

	if ( Number.isNaN( target ) ) {
		target = Number.parseInt( ( fill.style.width || '' ).replace( /\D/g, '' ), 10 );
	}

	if ( Number.isNaN( target ) ) return;

	target = Math.max( 0, Math.min( 100, target ) );
	fill.style.width = '0%';
	pctEl.textContent = '0%';

	window.setTimeout( () => {
		const duration = 1200;
		const start = performance.now();

		function tick( now ) {
			const progress = Math.min( 1, ( now - start ) / duration );
			const eased = dooEaseOutCubic( progress );
			const value = Math.round( target * eased );

			fill.style.width = `${ value }%`;
			pctEl.textContent = `${ value }%`;

			if ( progress < 1 ) {
				requestAnimationFrame( tick );
			}
		}

		requestAnimationFrame( tick );
	}, delay );
}

/**
 * Trigger impact and modalidades animations when each section is visible.
 */
function initHomeScrollAnimations() {
	const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	const impactoSection = document.querySelector( '.doo-impacto' );
	const modalidadesSection = document.querySelector( '.doo-modalidades' );
	const ofertaSection = document.querySelector( '.doo-oferta' );

	if ( impactoSection ) {
		const impactoObserver = new IntersectionObserver(
			( entries, observer ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) return;

					entry.target.querySelectorAll( '.doo-stat-card__num' ).forEach( ( el, i ) => {
						if ( reduceMotion ) {
							animateImpactNumber( el, 1 );
							return;
						}
						window.setTimeout( () => animateImpactNumber( el ), i * 110 );
					} );

					observer.unobserve( entry.target );
				} );
			},
			{
				threshold: 0.25,
				rootMargin: '0px 0px -10% 0px',
			}
		);

		impactoObserver.observe( impactoSection );
	}

	if ( modalidadesSection ) {
		const modalidadesObserver = new IntersectionObserver(
			( entries, observer ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) return;

					entry.target.querySelectorAll( '.doo-modal-card' ).forEach( ( card, i ) => {
						if ( reduceMotion ) {
							animateModalidadesCard( card, 0 );
							return;
						}
						animateModalidadesCard( card, i * 120 );
					} );

					observer.unobserve( entry.target );
				} );
			},
			{
				threshold: 0.25,
				rootMargin: '0px 0px -10% 0px',
			}
		);

		modalidadesObserver.observe( modalidadesSection );
	}

	if ( ofertaSection ) {
		const ofertaObserver = new IntersectionObserver(
			( entries, observer ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) return;

					entry.target.querySelectorAll( '.doo-plan-card__stat-num' ).forEach( ( el, i ) => {
						if ( reduceMotion ) {
							animateImpactNumber( el, 1 );
							return;
						}
						window.setTimeout( () => animateImpactNumber( el, 1200 ), i * 90 );
					} );

					observer.unobserve( entry.target );
				} );
			},
			{
				threshold: 0.25,
				rootMargin: '0px 0px -10% 0px',
			}
		);

		ofertaObserver.observe( ofertaSection );
	}
}

/**
 * Toggle the full presentation text when clicking "Leer mensaje completo".
 */
function initPresentacionToggle() {
	const link = document.querySelector( '.doo-presentacion__link' );
	const full  = document.querySelector( '.doo-presentacion__full' );

	if ( ! link || ! full ) return;

	const right = link.closest( '.doo-presentacion__right' );

	link.addEventListener( 'click', ( e ) => {
		e.preventDefault();

		const isOpen = full.classList.toggle( 'is-open' );
		if ( right ) right.classList.toggle( 'is-expanded', isOpen );

		full.setAttribute( 'aria-hidden', String( ! isOpen ) );
		link.setAttribute( 'aria-expanded', String( isOpen ) );
		link.textContent = isOpen ? 'Cerrar mensaje ↑' : 'Leer mensaje completo →';
	} );
}

/**
 * Testimonios slider.
 *
 * Desktop: Flex layout — 3 cards side by side per page, slides by card width.
 * Mobile:  CSS Grid layout — 3 cards stacked per page, slides by container width.
 *          JS sets --col-width so each grid column = overflow container width.
 */
function initTestimoniosSlider() {
	const track   = document.querySelector( '.doo-testimonios__track' );
	const prevBtn = document.querySelector( '.doo-testimonios__nav--prev' );
	const nextBtn = document.querySelector( '.doo-testimonios__nav--next' );
	const dots    = document.querySelectorAll( '.doo-testimonios__dot' );

	if ( ! track || ! prevBtn || ! nextBtn ) return;

	const cards = Array.from( track.querySelectorAll( '.doo-testi-card' ) );
	const total = cards.length;
	let current = 0;
	let hasInitialized = false;
	let slideFxTimeout;

	const MOBILE_MQ = window.matchMedia( '(max-width: 767px)' );

	function isMobile() { return MOBILE_MQ.matches; }

	/** Cards per "page": always 3, but navigation mode differs. */
	const VISIBLE = 3;

	/** Last valid starting index. */
	function getMaxIndex() {
		return ( Math.ceil( total / VISIBLE ) - 1 ) * VISIBLE;
	}

	/** Dot index for the current position. */
	function activeDotIndex() {
		return Math.min( Math.floor( current / VISIBLE ), dots.length - 1 );
	}

	/**
	 * On mobile, set --col-width = overflow container width so the CSS Grid
	 * knows how wide each "page column" should be.
	 */
	function syncColumnWidth() {
		if ( isMobile() ) {
			const w = track.parentElement.offsetWidth;
			track.style.setProperty( '--col-width', w + 'px' );
		}
	}

	/** Move the track and refresh controls / dots. */
	function update() {
		const maxIndex = getMaxIndex();
		current = Math.min( current, maxIndex );

		if ( hasInitialized ) {
			track.classList.add( 'is-switching' );
			window.clearTimeout( slideFxTimeout );
			slideFxTimeout = window.setTimeout( () => {
				track.classList.remove( 'is-switching' );
			}, 340 );
		} else {
			hasInitialized = true;
		}

		if ( isMobile() ) {
			// Grid layout: translate by full container width × page index
			const page           = Math.floor( current / VISIBLE );
			const containerWidth = track.parentElement.offsetWidth;
			track.style.transform = `translateX(-${ page * containerWidth }px)`;
		} else {
			// Flex layout: translate by individual card width × current index
			const gap       = 24;
			const cardWidth = cards[ 0 ].offsetWidth + gap;
			track.style.transform = `translateX(-${ current * cardWidth }px)`;
		}

		prevBtn.disabled = current === 0;
		nextBtn.disabled = current >= maxIndex;

		const activeIndex = activeDotIndex();
		dots.forEach( ( dot, i ) => {
			dot.classList.toggle( 'doo-testimonios__dot--inactive', i !== activeIndex );
		} );
	}

	prevBtn.addEventListener( 'click', () => {
		current = Math.max( 0, current - VISIBLE );
		update();
	} );

	nextBtn.addEventListener( 'click', () => {
		current = Math.min( getMaxIndex(), current + VISIBLE );
		update();
	} );

	dots.forEach( ( dot, i ) => {
		dot.addEventListener( 'click', () => {
			current = Math.min( i * VISIBLE, getMaxIndex() );
			update();
		} );
	} );

	window.addEventListener( 'resize', () => {
		syncColumnWidth();
		update();
	} );

	syncColumnWidth();
	update();
}

/**
 * Compact header on scroll.
 *
 * Adds `is-scrolled` to .doo-header when the user scrolls past a threshold.
 * Uses hysteresis (different add/remove thresholds) to avoid flickering.
 */
function initStickyHeader() {
	const header = document.querySelector( '.doo-header' );
	if ( ! header ) return;

	const SCROLL_DOWN = 80;
	const SCROLL_UP   = 40;
	let ticking       = false;

	function onScroll() {
		if ( ticking ) return;
		ticking = true;

		requestAnimationFrame( () => {
			const y = window.scrollY;

			if ( y > SCROLL_DOWN ) {
				header.classList.add( 'is-scrolled' );
			} else if ( y < SCROLL_UP ) {
				header.classList.remove( 'is-scrolled' );
			}

			ticking = false;
		} );
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	onScroll();
}

/**
 * Client-side filtering for the Formación Continua course list.
 *
 * Reads checked checkboxes from the sidebar (data-fc-sidebar),
 * hides/shows course cards (data-fc-list .doo-af-card) by matching
 * data-area and data-modality attributes, and updates the visible count.
 */
function initFcFilters() {
	const sidebar = document.querySelector( '[data-fc-sidebar]' );
	const list    = document.querySelector( '[data-fc-list]' );
	const counter = document.querySelector( '[data-fc-count]' );

	if ( ! sidebar || ! list ) return;

	const cards = Array.from( list.querySelectorAll( '.doo-af-card' ) );

	function applyFilters() {
		const checkedAreas = Array.from(
			sidebar.querySelectorAll( 'input[name="doo_area"]:checked' )
		).map( ( cb ) => cb.value );

		const checkedMods = Array.from(
			sidebar.querySelectorAll( 'input[name="doo_modality"]:checked' )
		).map( ( cb ) => cb.value );

		let visible = 0;

		cards.forEach( ( card ) => {
			const area     = card.dataset.area || '';
			const modality = card.dataset.modality || '';

			const matchArea = checkedAreas.length === 0 || checkedAreas.includes( area );
			const matchMod  = checkedMods.length === 0 || checkedMods.includes( modality );

			const show = matchArea && matchMod;
			card.style.display = show ? '' : 'none';
			if ( show ) visible++;
		} );

		if ( counter ) {
			counter.innerHTML = `Mostrando <strong>${ visible }</strong> cursos disponibles`;
		}
	}

	sidebar.addEventListener( 'change', applyFilters );
}

/**
 * Mark the nav item whose href matches the current URL as active.
 * Adds `current-menu-item` so the SCSS active styles apply.
 */
function setActiveNavItem() {
	const currentPath = window.location.pathname;

	document.querySelectorAll( '.doo-header__nav .wp-block-navigation-item' ).forEach( ( item ) => {
		const link = item.querySelector( '.wp-block-navigation-item__content' );
		if ( ! link ) return;

		const linkPath = new URL( link.href, window.location.origin ).pathname;

		if ( linkPath === currentPath ) {
			item.classList.add( 'current-menu-item' );
		}
	} );
}