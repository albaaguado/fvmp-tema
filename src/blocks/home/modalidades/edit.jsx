/**
 * Block editor: doo/modalidades.
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

const MODALIDADES = [
	{ key: 'presencial', label: __( 'Presencial', 'dw-tema' ) },
	{ key: 'online', label: __( 'Online', 'dw-tema' ) },
	{ key: 'streaming', label: __( 'Streaming', 'dw-tema' ) },
	{ key: 'mix', label: __( 'Mix', 'dw-tema' ) },
];

export default function DooModalidadesEdit( { attributes, setAttributes } ) {
	const { eyebrow, title } = attributes;
	const blockProps = useBlockProps( { className: 'doo-modalidades' } );

	return (
		<>
			<InspectorControls>
				{ MODALIDADES.map( ( { key, label } ) => (
					<PanelBody title={ label } key={ key } initialOpen={ key === 'presencial' }>
						<TextControl
							label={ __( 'Nombre', 'dw-tema' ) }
							value={ attributes[ `${ key }Name` ] }
							onChange={ ( val ) => setAttributes( { [ `${ key }Name` ]: val } ) }
						/>
						<TextControl
							label={ __( 'Porcentaje (barra)', 'dw-tema' ) }
							help={ __( 'Número entre 0 y 100.', 'dw-tema' ) }
							value={ attributes[ `${ key }Pct` ] }
							onChange={ ( val ) => setAttributes( { [ `${ key }Pct` ]: val } ) }
						/>
						<TextControl
							label={ __( 'Descripción', 'dw-tema' ) }
							value={ attributes[ `${ key }Desc` ] }
							onChange={ ( val ) => setAttributes( { [ `${ key }Desc` ]: val } ) }
						/>
					</PanelBody>
				) ) }
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-modalidades__container">
					<RichText
						tagName="p"
						className="doo-modalidades__eyebrow"
						value={ eyebrow }
						onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
						placeholder={ __( 'Subtítulo…', 'dw-tema' ) }
					/>
					<RichText
						tagName="h2"
						className="doo-modalidades__title"
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
						placeholder={ __( 'Título…', 'dw-tema' ) }
					/>
					<ServerSideRender
						block="doo/modalidades"
						attributes={ { ...attributes, renderOnlyGrid: true } }
					/>
				</div>
			</section>
		</>
	);
}
