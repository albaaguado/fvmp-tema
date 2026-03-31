/**
 * Block editor component: doo/profesorado.
 *
 * Section headers are editable via InspectorControls (sidebar).
 * The teacher grid is dynamic (from doo_docente CPT) — rendered
 * via ServerSideRender so the editor shows a live preview.
 */

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export default function DooProfesoradoEdit( { attributes, setAttributes } ) {
	const { eyebrow, title, description } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Section Header', 'dw-tema' ) }>
					<p style={ { marginTop: 0 } }>
						{ __(
							'Las fichas de docentes se gestionan en el menú «Docentes» del escritorio (no aquí).',
							'dw-tema'
						) }
					</p>
					<TextControl
						label={ __( 'Eyebrow', 'dw-tema' ) }
						value={ eyebrow }
						onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
					/>
					<TextControl
						label={ __( 'Title', 'dw-tema' ) }
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
					/>
					<TextareaControl
						label={ __( 'Description', 'dw-tema' ) }
						value={ description }
						onChange={ ( val ) => setAttributes( { description: val } ) }
						rows={ 3 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block="doo/profesorado"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
