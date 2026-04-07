/**
 * Block editor: doo/planes — encabezado en el lienzo; tarjetas + imágenes en el inspector.
 */

import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

function PlanImagePanel( { prefix, title, attributes, setAttributes } ) {
	const idKey = `${ prefix }ImageId`;
	const urlKey = `${ prefix }ImageUrl`;
	const altKey = `${ prefix }ImageAlt`;
	const titleKey = `${ prefix }Title`;
	const subKey = `${ prefix }Sub`;
	const linkTextKey = `${ prefix }LinkText`;
	const linkUrlKey = `${ prefix }LinkUrl`;

	const imageId = attributes[ idKey ];
	const imageUrl = attributes[ urlKey ];
	const themeUri = window.dooThemeEditor?.themeUri || '';
	const fallback = themeUri ? `${ themeUri }/assets/images/${ prefix }.png` : '';

	const onSelect = ( media ) => {
		setAttributes( { [ idKey ]: media.id, [ urlKey ]: media.url } );
	};
	const onRemove = () => {
		setAttributes( { [ idKey ]: 0, [ urlKey ]: '' } );
	};

	const preview = imageUrl || ( ! imageId && fallback ? fallback : '' );

	return (
		<PanelBody title={ title } initialOpen={ prefix === 'pim' }>
			<div style={ { marginBottom: 12 } }>
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ onSelect }
						allowedTypes={ [ 'image' ] }
						value={ imageId }
						render={ ( { open } ) =>
							preview ? (
								<div>
									<img
										src={ preview }
										alt=""
										style={ { maxWidth: '100%', height: 'auto', cursor: 'pointer' } }
										onClick={ open }
										onKeyDown={ ( e ) => e.key === 'Enter' && open() }
										role="presentation"
									/>
									<Button onClick={ onRemove } isDestructive isSmall>
										{ __( 'Quitar imagen', 'dw-tema' ) }
									</Button>
								</div>
							) : (
								<Button onClick={ open } variant="secondary">
									{ __( 'Seleccionar imagen', 'dw-tema' ) }
								</Button>
							)
						}
					/>
				</MediaUploadCheck>
			</div>
			<TextControl
				label={ __( 'Texto alternativo (alt)', 'dw-tema' ) }
				value={ attributes[ altKey ] }
				onChange={ ( val ) => setAttributes( { [ altKey ]: val } ) }
			/>
			<TextControl
				label={ __( 'Título', 'dw-tema' ) }
				value={ attributes[ titleKey ] }
				onChange={ ( val ) => setAttributes( { [ titleKey ]: val } ) }
			/>
			<TextControl
				label={ __( 'Subtítulo', 'dw-tema' ) }
				value={ attributes[ subKey ] }
				onChange={ ( val ) => setAttributes( { [ subKey ]: val } ) }
			/>
			<TextControl
				label={ __( 'Texto del enlace', 'dw-tema' ) }
				value={ attributes[ linkTextKey ] }
				onChange={ ( val ) => setAttributes( { [ linkTextKey ]: val } ) }
			/>
			<TextControl
				label={ __( 'URL del enlace', 'dw-tema' ) }
				value={ attributes[ linkUrlKey ] }
				onChange={ ( val ) => setAttributes( { [ linkUrlKey ]: val } ) }
			/>
		</PanelBody>
	);
}

export default function DooPlanesEdit( { attributes, setAttributes } ) {
	const { title, description } = attributes;
	const blockProps = useBlockProps( { className: 'doo-planes' } );

	return (
		<>
			<InspectorControls>
				<PlanImagePanel
					prefix="pim"
					title={ __( 'PIM — Programa Innovación Municipal', 'dw-tema' ) }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
				<PlanImagePanel
					prefix="fap"
					title={ __( 'FAP — Formación Atención Primaria', 'dw-tema' ) }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
				<PlanImagePanel
					prefix="fc"
					title={ __( 'FC — Formación Continua', 'dw-tema' ) }
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-planes__container">
					<RichText
						tagName="h2"
						className="doo-planes__title"
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
						placeholder={ __( 'Título…', 'dw-tema' ) }
					/>
					<RichText
						tagName="p"
						className="doo-planes__desc"
						value={ description }
						onChange={ ( val ) => setAttributes( { description: val } ) }
						placeholder={ __( 'Descripción…', 'dw-tema' ) }
						allowedFormats={ [ 'core/bold', 'core/italic' ] }
					/>
					<ServerSideRender
						block="doo/planes"
						attributes={ { ...attributes, renderOnlyCards: true } }
					/>
				</div>
			</section>
		</>
	);
}
