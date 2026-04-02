/**
 * Jornadas Hero Block - Editor Component.
 *
 * @package DooFvmpTheme
 */

import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function DooJornadasHeroEdit({ attributes, setAttributes }) {
	const {
		label,
		title,
		description,
		stat1Value,
		stat1Label,
		stat2Value,
		stat2Label,
		stat3Value,
		stat3Label
	} = attributes;

	const blockProps = useBlockProps({ className: 'doo-jornadas-hero' });

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Estadísticas', 'dw-tema')} initialOpen={true}>
					<TextControl
						label={__('Valor estadística 1', 'dw-tema')}
						value={stat1Value}
						onChange={(value) => setAttributes({ stat1Value: value })}
					/>
					<TextControl
						label={__('Etiqueta estadística 1', 'dw-tema')}
						value={stat1Label}
						onChange={(value) => setAttributes({ stat1Label: value })}
					/>
					<hr />
					<TextControl
						label={__('Valor estadística 2', 'dw-tema')}
						value={stat2Value}
						onChange={(value) => setAttributes({ stat2Value: value })}
					/>
					<TextControl
						label={__('Etiqueta estadística 2', 'dw-tema')}
						value={stat2Label}
						onChange={(value) => setAttributes({ stat2Label: value })}
					/>
					<hr />
					<TextControl
						label={__('Valor estadística 3', 'dw-tema')}
						value={stat3Value}
						onChange={(value) => setAttributes({ stat3Value: value })}
					/>
					<TextControl
						label={__('Etiqueta estadística 3', 'dw-tema')}
						value={stat3Label}
						onChange={(value) => setAttributes({ stat3Label: value })}
					/>
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="doo-jornadas-hero__container">
					<div className="doo-jornadas-hero__content">
						<RichText
							tagName="span"
							className="doo-jornadas-hero__label"
							value={label}
							onChange={(value) => setAttributes({ label: value })}
							placeholder={__('OFERTA FORMATIVA', 'dw-tema')}
						/>
						<RichText
							tagName="h1"
							className="doo-jornadas-hero__title"
							value={title}
							onChange={(value) => setAttributes({ title: value })}
							placeholder={__('Jornadas', 'dw-tema')}
						/>
						<RichText
							tagName="p"
							className="doo-jornadas-hero__description"
							value={description}
							onChange={(value) => setAttributes({ description: value })}
							placeholder={__('Descripción de la sección...', 'dw-tema')}
						/>
						
						<div className="doo-jornadas-hero__stats">
							<div className="doo-jornadas-hero__stat">
								<span className="doo-jornadas-hero__stat-value">{stat1Value}</span>
								<span className="doo-jornadas-hero__stat-label">{stat1Label}</span>
							</div>
							<div className="doo-jornadas-hero__stat">
								<span className="doo-jornadas-hero__stat-value">{stat2Value}</span>
								<span className="doo-jornadas-hero__stat-label">{stat2Label}</span>
							</div>
							<div className="doo-jornadas-hero__stat">
								<span className="doo-jornadas-hero__stat-value">{stat3Value}</span>
								<span className="doo-jornadas-hero__stat-label">{stat3Label}</span>
							</div>
						</div>
					</div>
				</div>
			</section>
		</>
	);
}
