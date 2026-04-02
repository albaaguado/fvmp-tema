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
import DooFapHeroEdit from './fap/hero/edit.jsx';
import DooPimHeroEdit from './pim/hero/edit.jsx';
import DooAfCoursesEdit from './af/listado/edit.jsx';
import DooAfCursoEdit from './af/curso/edit.jsx';
import DooRegistroEdit from './registro/edit.jsx';
import DooJornadasHeroEdit from './jornadas/hero.jsx';
import DooJornadasListingEdit from './jornadas/listing.jsx';
import DooJornadaDetailEdit from './jornadas/detail.jsx';

registerBlockType( 'doo/hero', { edit: DooHeroEdit } );
registerBlockType( 'doo/presentacion', { edit: DooPresentacionEdit } );
registerBlockType( 'doo/profesorado', { edit: DooProfesoradoEdit } );
registerBlockType( 'doo/fc-hero', { edit: DooFcHeroEdit } );
registerBlockType( 'doo/fap-hero', { edit: DooFapHeroEdit } );
registerBlockType( 'doo/pim-hero', { edit: DooPimHeroEdit } );

registerBlockType( 'doo/af-courses', { edit: DooAfCoursesEdit } );

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
registerBlockType( 'doo/user-nav',  { edit: makeSsrEdit( 'doo/user-nav' ) } );
registerBlockType( 'doo/af-curso',  { edit: DooAfCursoEdit } );
registerBlockType( 'doo/registro', { edit: DooRegistroEdit } );
registerBlockType( 'doo/oferta', { edit: DooOfertaEdit } );
registerBlockType( 'doo/modalidades', { edit: DooModalidadesEdit } );
registerBlockType( 'doo/impacto', { edit: DooImpactoEdit } );
registerBlockType( 'doo/testimonios', { edit: DooTestimoniosEdit } );
registerBlockType( 'doo/transparencia', { edit: DooTransparenciaEdit } );
registerBlockType( 'doo/planes', { edit: DooPlanesEdit } );

// Jornadas blocks
registerBlockType( 'doo/jornadas-hero', { edit: DooJornadasHeroEdit } );
registerBlockType( 'doo/jornadas-listing', { edit: DooJornadasListingEdit } );
registerBlockType( 'doo/jornada-detail', { edit: DooJornadaDetailEdit } );
