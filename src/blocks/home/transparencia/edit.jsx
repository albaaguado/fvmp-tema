/**
 * Block editor: doo/transparencia.
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export default function DooTransparenciaEdit( { attributes, setAttributes } ) {
	const { eyebrow, title, description, link1Title, link1Desc, link1Url, link2Title, link2Desc, link2Url } =
		attributes;
	const blockProps = useBlockProps( { className: 'doo-transparencia' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Enlace 1', 'dw-tema' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Título', 'dw-tema' ) }
						value={ link1Title }
						onChange={ ( val ) => setAttributes( { link1Title: val } ) }
					/>
					<TextControl
						label={ __( 'Descripción', 'dw-tema' ) }
						value={ link1Desc }
						onChange={ ( val ) => setAttributes( { link1Desc: val } ) }
					/>
					<TextControl
						label={ __( 'URL', 'dw-tema' ) }
						value={ link1Url }
						onChange={ ( val ) => setAttributes( { link1Url: val } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Enlace 2', 'dw-tema' ) }>
					<TextControl
						label={ __( 'Título', 'dw-tema' ) }
						value={ link2Title }
						onChange={ ( val ) => setAttributes( { link2Title: val } ) }
					/>
					<TextControl
						label={ __( 'Descripción', 'dw-tema' ) }
						value={ link2Desc }
						onChange={ ( val ) => setAttributes( { link2Desc: val } ) }
					/>
					<TextControl
						label={ __( 'URL', 'dw-tema' ) }
						value={ link2Url }
						onChange={ ( val ) => setAttributes( { link2Url: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-transparencia__container">
					<div className="doo-transparencia__inner">
						<div className="doo-transparencia__left">
							<RichText
								tagName="p"
								className="doo-transparencia__eyebrow"
								value={ eyebrow }
								onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
								placeholder={ __( 'Subtítulo…', 'dw-tema' ) }
							/>
							<RichText
								tagName="h2"
								className="doo-transparencia__title"
								value={ title }
								onChange={ ( val ) => setAttributes( { title: val } ) }
								placeholder={ __( 'Título…', 'dw-tema' ) }
							/>
							<RichText
								tagName="p"
								className="doo-transparencia__desc"
								value={ description }
								onChange={ ( val ) => setAttributes( { description: val } ) }
								placeholder={ __( 'Texto…', 'dw-tema' ) }
								allowedFormats={ [ 'core/bold', 'core/italic' ] }
							/>
						</div>
						<ServerSideRender
							block="doo/transparencia"
							attributes={ { ...attributes, renderOnlyLinks: true } }
						/>
					</div>
				</div>
			</section>
		</>
	);
}
