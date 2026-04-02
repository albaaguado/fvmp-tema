/**
 * Block editor component: doo/pim-hero.
 *
 * Inline RichText editing in the canvas.
 * Live course count from PIM CPT.
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export default function DooPimHeroEdit( { attributes, setAttributes } ) {
	const {
		eyebrow,
		title,
		description,
		stat2Num,
		stat2Label,
		stat3Num,
		stat3Label,
		cardTitle,
		cardDescription,
		cardButtonText,
		cardButtonUrl,
		cardRegisterText,
		cardRegisterUrl,
	} = attributes;

	const blockProps = useBlockProps( { className: 'doo-pim-hero' } );
	const [ accionesCount, setAccionesCount ] = useState( null );

	useEffect( () => {
		apiFetch( {
			path: '/wp/v2/doo_accion_pim?per_page=1&status=publish',
			parse: false,
		} )
			.then( ( response ) => {
				const total = response.headers.get( 'X-WP-Total' );
				setAccionesCount( total ? parseInt( total, 10 ) : 0 );
			} )
			.catch( () => {
				setAccionesCount( null );
			} );
	}, [] );

	const countDisplay = accionesCount === null ? '—' : `${ accionesCount }+`;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Enlaces (CTA)', 'dw-tema' ) }>
					<TextControl
						label={ __( 'URL botón principal', 'dw-tema' ) }
						value={ cardButtonUrl }
						onChange={ ( val ) => setAttributes( { cardButtonUrl: val } ) }
					/>
					<TextControl
						label={ __( 'URL registro', 'dw-tema' ) }
						value={ cardRegisterUrl }
						onChange={ ( val ) => setAttributes( { cardRegisterUrl: val } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Estadísticas', 'dw-tema' ) }>
					<TextControl
						label={ __( 'Stat 2 — cifra', 'dw-tema' ) }
						value={ stat2Num }
						onChange={ ( val ) => setAttributes( { stat2Num: val } ) }
					/>
					<TextControl
						label={ __( 'Stat 2 — etiqueta', 'dw-tema' ) }
						value={ stat2Label }
						onChange={ ( val ) => setAttributes( { stat2Label: val } ) }
					/>
					<TextControl
						label={ __( 'Stat 3 — cifra', 'dw-tema' ) }
						value={ stat3Num }
						onChange={ ( val ) => setAttributes( { stat3Num: val } ) }
					/>
					<TextControl
						label={ __( 'Stat 3 — etiqueta', 'dw-tema' ) }
						value={ stat3Label }
						onChange={ ( val ) => setAttributes( { stat3Label: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-af-hero__inner">

					<div className="doo-af-hero__left">
						<RichText
							tagName="p"
							className="doo-af-hero__eyebrow"
							value={ eyebrow }
							onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
							placeholder={ __( 'Subtítulo…', 'dw-tema' ) }
						/>
						<RichText
							tagName="h1"
							className="doo-af-hero__title"
							value={ title }
							onChange={ ( val ) => setAttributes( { title: val } ) }
							placeholder={ __( 'Título…', 'dw-tema' ) }
						/>
						<RichText
							tagName="p"
							className="doo-af-hero__desc"
							value={ description }
							onChange={ ( val ) => setAttributes( { description: val } ) }
							placeholder={ __( 'Descripción…', 'dw-tema' ) }
						/>

						<div className="doo-af-hero__stats">
							<div className="doo-af-hero__stat">
								<span className="doo-af-hero__stat-num">{ countDisplay }</span>
								<span className="doo-af-hero__stat-label">acciones formativas</span>
							</div>
							{ stat2Num && (
								<>
									<div className="doo-af-hero__sep" aria-hidden="true"></div>
									<div className="doo-af-hero__stat">
										<span className="doo-af-hero__stat-num">{ stat2Num }</span>
										<span className="doo-af-hero__stat-label">{ stat2Label }</span>
									</div>
								</>
							) }
							{ stat3Num && (
								<>
									<div className="doo-af-hero__sep" aria-hidden="true"></div>
									<div className="doo-af-hero__stat">
										<span className="doo-af-hero__stat-num">{ stat3Num }</span>
										<span className="doo-af-hero__stat-label">{ stat3Label }</span>
									</div>
								</>
							) }
						</div>
					</div>

					<div className="doo-af-hero__card">
						<div className="doo-af-hero__card-stripe"></div>
						<div className="doo-af-hero__card-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
						</div>
						<RichText
							tagName="p"
							className="doo-af-hero__card-title"
							value={ cardTitle }
							onChange={ ( val ) => setAttributes( { cardTitle: val } ) }
							placeholder={ __( 'Título de la tarjeta…', 'dw-tema' ) }
						/>
						<RichText
							tagName="p"
							className="doo-af-hero__card-desc"
							value={ cardDescription }
							onChange={ ( val ) => setAttributes( { cardDescription: val } ) }
							placeholder={ __( 'Texto de la tarjeta…', 'dw-tema' ) }
						/>
						<a className="doo-af-hero__card-btn" href={ cardButtonUrl || '#' } onClick={ ( e ) => e.preventDefault() }>
							<RichText
								tagName="span"
								value={ cardButtonText }
								onChange={ ( val ) => setAttributes( { cardButtonText: val } ) }
								placeholder={ __( 'Texto del botón…', 'dw-tema' ) }
							/>
						</a>
						<p className="doo-af-hero__card-register">
							<a href={ cardRegisterUrl || '#' } onClick={ ( e ) => e.preventDefault() }>
								<RichText
									tagName="span"
									value={ cardRegisterText }
									onChange={ ( val ) => setAttributes( { cardRegisterText: val } ) }
									placeholder={ __( 'Enlace a registro…', 'dw-tema' ) }
								/>
							</a>
						</p>
					</div>

				</div>
			</section>
		</>
	);
}


