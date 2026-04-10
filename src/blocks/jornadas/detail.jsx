/**
 * Jornada Detail Block — Gutenberg Editor Component.
 *
 * Provides InspectorControls for managing location, presentation documents,
 * and YouTube video links. The actual frontend is rendered server-side by
 * blocks/jornada-detail/render.php.
 *
 * @package DooFvmpTheme
 */

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button, Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Jornada Detail edit component.
 *
 * @param {Object} props               Block props.
 * @param {Object} props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 * @return {JSX.Element} Editor UI.
 */
export default function DooJornadaDetailEdit( { attributes, setAttributes } ) {
	const { location, presentations, videos } = attributes;
	const blockProps = useBlockProps( { className: 'doo-jornada-detail-editor' } );

	// ---- Presentations helpers ----

	const updatePresentation = ( index, key, value ) => {
		const updated = [ ...presentations ];
		updated[ index ] = { ...updated[ index ], [ key ]: value };
		setAttributes( { presentations: updated } );
	};

	const addPresentation = () => {
		setAttributes( {
			presentations: [ ...presentations, { title: '', fileType: 'PDF', url: '' } ],
		} );
	};

	const removePresentation = ( index ) => {
		setAttributes( { presentations: presentations.filter( ( _, i ) => i !== index ) } );
	};

	// ---- Videos helpers ----

	const updateVideo = ( index, key, value ) => {
		const updated = [ ...videos ];
		updated[ index ] = { ...updated[ index ], [ key ]: value };
		setAttributes( { videos: updated } );
	};

	const addVideo = () => {
		setAttributes( {
			videos: [ ...videos, { title: '', url: '' } ],
		} );
	};

	const removeVideo = ( index ) => {
		setAttributes( { videos: videos.filter( ( _, i ) => i !== index ) } );
	};

	const itemStyle = {
		borderBottom: '1px solid #e2e4e8',
		marginBottom: '12px',
		paddingBottom: '12px',
	};

	return (
		<>
			<InspectorControls>

				{/* ---- Jornada info ---- */}
				<PanelBody title={ __( 'Información de la Jornada', 'dw-tema' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Localización', 'dw-tema' ) }
						value={ location }
						onChange={ ( value ) => setAttributes( { location: value } ) }
					/>
				</PanelBody>

				{/* ---- Presentations ---- */}
				<PanelBody title={ __( 'Presentaciones', 'dw-tema' ) } initialOpen={ false }>
					{ presentations.map( ( pres, i ) => (
						<div key={ i } style={ itemStyle }>
							<TextControl
								label={ __( 'Título del documento', 'dw-tema' ) }
								value={ pres.title }
								onChange={ ( v ) => updatePresentation( i, 'title', v ) }
							/>
							<TextControl
								label={ __( 'Tipo de archivo (PDF, PPT…)', 'dw-tema' ) }
								value={ pres.fileType }
								onChange={ ( v ) => updatePresentation( i, 'fileType', v ) }
							/>
							<TextControl
								label={ __( 'URL de descarga', 'dw-tema' ) }
								value={ pres.url }
								onChange={ ( v ) => updatePresentation( i, 'url', v ) }
								type="url"
							/>
							<Button
								isDestructive
								variant="link"
								onClick={ () => removePresentation( i ) }
							>
								{ __( 'Eliminar presentación', 'dw-tema' ) }
							</Button>
						</div>
					) ) }
					<Button variant="secondary" onClick={ addPresentation }>
						{ __( '+ Añadir presentación', 'dw-tema' ) }
					</Button>
				</PanelBody>

				{/* ---- Videos ---- */}
				<PanelBody title={ __( 'Vídeos', 'dw-tema' ) } initialOpen={ false }>
					{ videos.map( ( video, i ) => (
						<div key={ i } style={ itemStyle }>
							<TextControl
								label={ __( 'Título del vídeo', 'dw-tema' ) }
								value={ video.title }
								onChange={ ( v ) => updateVideo( i, 'title', v ) }
							/>
							<TextControl
								label={ __( 'URL de YouTube', 'dw-tema' ) }
								help={ __( 'Ej: https://www.youtube.com/watch?v=xxxxx', 'dw-tema' ) }
								value={ video.url }
								onChange={ ( v ) => updateVideo( i, 'url', v ) }
								type="url"
							/>
							<Button
								isDestructive
								variant="link"
								onClick={ () => removeVideo( i ) }
							>
								{ __( 'Eliminar vídeo', 'dw-tema' ) }
							</Button>
						</div>
					) ) }
					<Button variant="secondary" onClick={ addVideo }>
						{ __( '+ Añadir vídeo', 'dw-tema' ) }
					</Button>
				</PanelBody>

			</InspectorControls>

			<div { ...blockProps }>
				<Placeholder
					icon="calendar-alt"
					label={ __( 'Detalle de Jornada', 'dw-tema' ) }
					instructions={ __(
						'Renderizado en el servidor. Usa el panel lateral para editar localización, presentaciones y vídeos.',
						'dw-tema'
					) }
				/>
			</div>
		</>
	);
}
