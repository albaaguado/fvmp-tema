/**
 * Block editor: doo/impacto — editable header + CTA, SSR stats strip.
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

export default function DooImpactoEdit( { attributes, setAttributes } ) {
	const {
		eyebrow,
		title,
		description,
		ctaEyebrow,
		ctaTitle,
		ctaDesc,
		ctaButtonText,
		ctaButtonUrl,
	} = attributes;

	const blockProps = useBlockProps( { className: 'doo-impacto' } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Cifras (Nuestro impacto)', 'dw-tema' ) } initialOpen={ true }>
					{ [ 1, 2, 3, 4 ].map( ( i ) => (
						<div key={ i } style={ { marginBottom: '12px' } }>
							<TextControl
								label={ __( 'Cifra', 'dw-tema' ) + ' ' + i }
								value={ attributes[ `stat${ i }Num` ] }
								onChange={ ( val ) => setAttributes( { [ `stat${ i }Num` ]: val } ) }
							/>
							<TextControl
								label={ __( 'Etiqueta', 'dw-tema' ) + ' ' + i }
								value={ attributes[ `stat${ i }Label` ] }
								onChange={ ( val ) => setAttributes( { [ `stat${ i }Label` ]: val } ) }
							/>
						</div>
					) ) }
				</PanelBody>
				<PanelBody title={ __( 'Botón CTA', 'dw-tema' ) }>
					<TextControl
						label={ __( 'URL del botón', 'dw-tema' ) }
						value={ ctaButtonUrl }
						onChange={ ( val ) => setAttributes( { ctaButtonUrl: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-impacto__container">
					<div className="doo-impacto__header">
						<RichText
							tagName="p"
							className="doo-impacto__eyebrow"
							value={ eyebrow }
							onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
							placeholder={ __( 'Subtítulo…', 'dw-tema' ) }
						/>
						<RichText
							tagName="h2"
							className="doo-impacto__title"
							value={ title }
							onChange={ ( val ) => setAttributes( { title: val } ) }
							placeholder={ __( 'Título…', 'dw-tema' ) }
						/>
						<RichText
							tagName="p"
							className="doo-impacto__desc"
							value={ description }
							onChange={ ( val ) => setAttributes( { description: val } ) }
							placeholder={ __( 'Descripción…', 'dw-tema' ) }
						/>
					</div>
					<ServerSideRender
						block="doo/impacto"
						attributes={ { ...attributes, renderOnlyStats: true } }
					/>
					<div className="doo-impacto__divider"></div>
				</div>

				<div className="doo-cta">
					<RichText
						tagName="p"
						className="doo-cta__eyebrow"
						value={ ctaEyebrow }
						onChange={ ( val ) => setAttributes( { ctaEyebrow: val } ) }
						placeholder={ __( 'Subtítulo CTA…', 'dw-tema' ) }
					/>
					<RichText
						tagName="h2"
						className="doo-cta__title"
						value={ ctaTitle }
						onChange={ ( val ) => setAttributes( { ctaTitle: val } ) }
						placeholder={ __( 'Título CTA…', 'dw-tema' ) }
					/>
					<RichText
						tagName="p"
						className="doo-cta__desc"
						value={ ctaDesc }
						onChange={ ( val ) => setAttributes( { ctaDesc: val } ) }
						placeholder={ __( 'Texto CTA…', 'dw-tema' ) }
					/>
					<a className="doo-cta__btn" href={ ctaButtonUrl || '#' } onClick={ ( e ) => e.preventDefault() }>
						<RichText
							tagName="span"
							value={ ctaButtonText }
							onChange={ ( val ) => setAttributes( { ctaButtonText: val } ) }
							placeholder={ __( 'Texto del botón…', 'dw-tema' ) }
						/>
					</a>
				</div>
			</section>
		</>
	);
}
