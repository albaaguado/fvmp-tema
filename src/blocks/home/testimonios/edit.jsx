/**
 * Block editor: doo/testimonios — encabezado editable; citas desde CPT.
 */

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export default function DooTestimoniosEdit( { attributes, setAttributes } ) {
	const { eyebrow, title } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Encabezado de sección', 'dw-tema' ) }>
					<p style={ { marginTop: 0 } }>
						{ __(
							'Las citas se gestionan en el menú «Testimonios» del escritorio (no aquí).',
							'dw-tema'
						) }
					</p>
					<TextControl
						label={ __( 'Subtítulo (eyebrow)', 'dw-tema' ) }
						value={ eyebrow }
						onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
					/>
					<TextControl
						label={ __( 'Título', 'dw-tema' ) }
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block="doo/testimonios"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
