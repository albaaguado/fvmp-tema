/**
 * Block editor: doo/oferta — intro editable + tarjetas FC / FAP / PIM (inspector).
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

const PLAN_FIELDS = [
	{ prefix: 'fc', title: __( 'Tarjeta FC — Formación Continua', 'dw-tema' ) },
	{ prefix: 'fap', title: __( 'Tarjeta FAP — Atención Primaria', 'dw-tema' ) },
	{ prefix: 'pim', title: __( 'Tarjeta PIM — Innovación Municipal', 'dw-tema' ) },
];

export default function DooOfertaEdit( { attributes, setAttributes } ) {
	const { eyebrow, title, description } = attributes;
	const blockProps = useBlockProps( { className: 'doo-oferta' } );

	const setPlan = ( prefix, key, val ) => setAttributes( { [ prefix + key ]: val } );

	return (
		<>
			<InspectorControls>
				{ PLAN_FIELDS.map( ( { prefix, title: panelTitle } ) => (
					<PanelBody title={ panelTitle } key={ prefix } initialOpen={ prefix === 'fc' }>
						<TextControl
							label={ __( 'Etiqueta (tag)', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Tag` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Tag', v ) }
						/>
						<TextControl
							label={ __( 'Nombre del plan', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Name` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Name', v ) }
						/>
						<TextControl
							label={ __( 'Cifra 1', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Stat1Num` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Stat1Num', v ) }
						/>
						<TextControl
							label={ __( 'Etiqueta cifra 1', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Stat1Label` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Stat1Label', v ) }
						/>
						<TextControl
							label={ __( 'Cifra 2', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Stat2Num` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Stat2Num', v ) }
						/>
						<TextControl
							label={ __( 'Etiqueta cifra 2', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Stat2Label` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Stat2Label', v ) }
						/>
						<TextControl
							label={ __( 'Cifra 3', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Stat3Num` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Stat3Num', v ) }
						/>
						<TextControl
							label={ __( 'Etiqueta cifra 3', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Stat3Label` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Stat3Label', v ) }
						/>
						<TextControl
							label={ __( 'Característica 1', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Feat1` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Feat1', v ) }
						/>
						<TextControl
							label={ __( 'Característica 2', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Feat2` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Feat2', v ) }
						/>
						<TextControl
							label={ __( 'Característica 3', 'dw-tema' ) }
							value={ attributes[ `${ prefix }Feat3` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'Feat3', v ) }
						/>
						<TextControl
							label={ __( 'Texto del enlace', 'dw-tema' ) }
							value={ attributes[ `${ prefix }CtaText` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'CtaText', v ) }
						/>
						<TextControl
							label={ __( 'URL del enlace', 'dw-tema' ) }
							value={ attributes[ `${ prefix }CtaUrl` ] || '' }
							onChange={ ( v ) => setPlan( prefix, 'CtaUrl', v ) }
						/>
					</PanelBody>
				) ) }
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-oferta__container">
					<RichText
						tagName="p"
						className="doo-oferta__eyebrow"
						value={ eyebrow }
						onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
						placeholder={ __( 'Subtítulo…', 'dw-tema' ) }
					/>
					<RichText
						tagName="h2"
						className="doo-oferta__title"
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
						placeholder={ __( 'Título…', 'dw-tema' ) }
					/>
					<RichText
						tagName="p"
						className="doo-oferta__desc"
						value={ description }
						onChange={ ( val ) => setAttributes( { description: val } ) }
						placeholder={ __( 'Descripción…', 'dw-tema' ) }
						allowedFormats={ [ 'core/bold', 'core/italic' ] }
					/>
					<ServerSideRender
						block="doo/oferta"
						attributes={ { ...attributes, renderOnlyCards: true } }
					/>
				</div>
			</section>
		</>
	);
}
