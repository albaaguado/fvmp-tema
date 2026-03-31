/**
 * FVMP Theme — Gutenberg Block Registration.
 *
 * Single entry point compiled by Vite (IIFE).
 * Imports individual edit components for each block.
 */

import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';

import DooHeroEdit from './home/hero/edit.jsx';
import DooPresentacionEdit from './home/presentacion/edit.jsx';
import DooProfesoradoEdit from './home/profesorado/edit.jsx';
import DooOfertaEdit from './home/oferta/edit.jsx';
import DooImpactoEdit from './home/impacto/edit.jsx';
import DooTestimoniosEdit from './home/testimonios/edit.jsx';
import DooModalidadesEdit from './home/modalidades/edit.jsx';
import DooTransparenciaEdit from './home/transparencia/edit.jsx';
import DooPlanesEdit from './home/planes/edit.jsx';
import DooFcHeroEdit from './fc/hero/edit.jsx';

registerBlockType( 'doo/hero', { edit: DooHeroEdit } );
registerBlockType( 'doo/presentacion', { edit: DooPresentacionEdit } );
registerBlockType( 'doo/profesorado', { edit: DooProfesoradoEdit } );
registerBlockType( 'doo/fc-hero', { edit: DooFcHeroEdit } );

registerBlockType( 'doo/fc-courses', {
	edit: function DooFcCoursesEdit() {
		return (
			<div { ...useBlockProps() }>
				<ServerSideRender block="doo/fc-courses" />
			</div>
		);
	},
} );

/**
 * Static Home blocks — ServerSideRender preview only.
 */
function makeSsrEdit( blockName ) {
	return function SsrEdit() {
		return (
			<div { ...useBlockProps() }>
				<ServerSideRender block={ blockName } />
			</div>
		);
	};
}

registerBlockType( 'doo/trust-bar', { edit: makeSsrEdit( 'doo/trust-bar' ) } );
registerBlockType( 'doo/oferta', { edit: DooOfertaEdit } );
registerBlockType( 'doo/modalidades', { edit: DooModalidadesEdit } );
registerBlockType( 'doo/impacto', { edit: DooImpactoEdit } );
registerBlockType( 'doo/testimonios', { edit: DooTestimoniosEdit } );
registerBlockType( 'doo/transparencia', { edit: DooTransparenciaEdit } );
registerBlockType( 'doo/planes', { edit: DooPlanesEdit } );
