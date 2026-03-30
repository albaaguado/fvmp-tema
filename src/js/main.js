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
} );

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