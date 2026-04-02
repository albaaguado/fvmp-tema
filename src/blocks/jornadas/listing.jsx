/**
 * Jornadas Listing Block - Editor Component.
 *
 * @package DooFvmpTheme
 */

import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function DooJornadasListingEdit({ attributes, setAttributes }) {
	const { sectionTitle, postsPerPage } = attributes;

	const blockProps = useBlockProps({ className: 'doo-jornadas-listing' });

	// Fetch jornadas from REST API
	const { jornadas, isLoading } = useSelect((select) => {
		const { getEntityRecords, isResolving } = select('core');
		
		return {
			jornadas: getEntityRecords('postType', 'doo_jornada', {
				per_page: postsPerPage,
				_embed: true
			}),
			isLoading: isResolving('getEntityRecords', ['postType', 'doo_jornada', { per_page: postsPerPage, _embed: true }])
		};
	}, [postsPerPage]);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Configuración', 'dw-tema')} initialOpen={true}>
					<RangeControl
						label={__('Número de jornadas', 'dw-tema')}
						value={postsPerPage}
						onChange={(value) => setAttributes({ postsPerPage: value })}
						min={1}
						max={12}
					/>
				</PanelBody>
			</InspectorControls>

			<section {...blockProps}>
				<div className="doo-jornadas-listing__container">
					<RichText
						tagName="h2"
						className="doo-jornadas-listing__title"
						value={sectionTitle}
						onChange={(value) => setAttributes({ sectionTitle: value })}
						placeholder={__('Próximas Jornadas', 'dw-tema')}
					/>

					{isLoading ? (
						<div className="doo-jornadas-listing__loading">
							<Spinner />
							<span>{__('Cargando jornadas...', 'dw-tema')}</span>
						</div>
					) : jornadas && jornadas.length > 0 ? (
						<div className="doo-jornadas-listing__grid">
							{jornadas.map((jornada) => {
								const image = jornada._embedded?.['wp:featuredmedia']?.[0]?.source_url;
								const date = jornada.meta?.doo_jornada_date || '';
								const description = jornada.meta?.doo_jornada_description || '';

								return (
									<article key={jornada.id} className="doo-jornada-card">
										{image ? (
											<div className="doo-jornada-card__image">
												<img src={image} alt={jornada.title.rendered} />
											</div>
										) : (
											<div className="doo-jornada-card__image doo-jornada-card__image--placeholder">
												<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
													<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
													<line x1="16" y1="2" x2="16" y2="6"></line>
													<line x1="8" y1="2" x2="8" y2="6"></line>
													<line x1="3" y1="10" x2="21" y2="10"></line>
												</svg>
											</div>
										)}
										
										<div className="doo-jornada-card__content">
											<span className="doo-jornada-card__badge">{__('Jornada', 'dw-tema')}</span>
											
											<h3 className="doo-jornada-card__title">
												<span dangerouslySetInnerHTML={{ __html: jornada.title.rendered }} />
											</h3>
											
											{date && (
												<div className="doo-jornada-card__meta">
													<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
														<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
														<line x1="16" y1="2" x2="16" y2="6"></line>
														<line x1="8" y1="2" x2="8" y2="6"></line>
														<line x1="3" y1="10" x2="21" y2="10"></line>
													</svg>
													<span>{date}</span>
												</div>
											)}
											
											{description && (
												<p className="doo-jornada-card__description">{description}</p>
											)}
										</div>
									</article>
								);
							})}
						</div>
					) : (
						<p className="doo-jornadas-listing__empty">
							{__('No hay jornadas programadas. Crea una desde Jornadas → Añadir jornada.', 'dw-tema')}
						</p>
					)}
				</div>
			</section>
		</>
	);
}
