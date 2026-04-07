/**
 * doo/af-courses — Block editor component.
 * Lets the admin choose which section's CPT this block should list.
 */

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

export default function DooAfCoursesEdit( { attributes, setAttributes } ) {
	const { postType } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title="Sección" initialOpen={ true }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Sección de cursos"
						value={ postType }
						options={ [
							{ label: 'FC — Formación Continua',            value: 'doo_accion_formativa' },
							{ label: 'FAP — Formación Atención Primaria',  value: 'doo_accion_fap' },
							{ label: 'PIM — Programa Innovación Municipal', value: 'doo_accion_pim' },
						] }
						onChange={ ( val ) => setAttributes( { postType: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<ServerSideRender block="doo/af-courses" attributes={ attributes } />
			</div>
		</>
	);
}
