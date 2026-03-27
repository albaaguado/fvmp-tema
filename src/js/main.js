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
 * Horizontal slider for the testimonios section.
 * Shows 3 cards at a time, slides 1 card per step.
 * Nav buttons and dots update to reflect current position.
 */
function initTestimoniosSlider() {
	const track   = document.querySelector( '.doo-testimonios__track' );
	const prevBtn = document.querySelector( '.doo-testimonios__nav--prev' );
	const nextBtn = document.querySelector( '.doo-testimonios__nav--next' );
	const dots    = document.querySelectorAll( '.doo-testimonios__dot' );

	if ( ! track || ! prevBtn || ! nextBtn ) return;

	const cards    = Array.from( track.querySelectorAll( '.doo-testi-card' ) );
	const visible  = 3;
	const total    = cards.length;
	const maxIndex = Math.max( 0, total - visible );
	const numDots  = dots.length; // ceil(total / visible)
	let current    = 0;

	/**
	 * Returns the dot index (page) that corresponds to the current card position.
	 *
	 * @return {number}
	 */
	function activeDotIndex() {
		return Math.min( Math.floor( current / visible ), numDots - 1 );
	}

	/**
	 * Apply slider position and update controls state.
	 */
	function update() {
		const gap       = 24;
		const cardWidth = cards[ 0 ].offsetWidth + gap;

		track.style.transform = `translateX(-${ current * cardWidth }px)`;

		prevBtn.disabled = current === 0;
		nextBtn.disabled = current >= maxIndex;

		const activeIndex = activeDotIndex();
		dots.forEach( ( dot, i ) => {
			dot.classList.toggle( 'doo-testimonios__dot--inactive', i !== activeIndex );
		} );
	}

	prevBtn.addEventListener( 'click', () => {
		if ( current > 0 ) {
			current = Math.max( 0, current - visible );
			update();
		}
	} );

	nextBtn.addEventListener( 'click', () => {
		if ( current < maxIndex ) {
			current = Math.min( maxIndex, current + visible );
			update();
		}
	} );

	dots.forEach( ( dot, i ) => {
		dot.addEventListener( 'click', () => {
			current = Math.min( i * visible, maxIndex );
			update();
		} );
	} );

	update();
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