/**
 * doo/af-curso — Block editor component.
 */

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	PanelRow,
	TextControl,
	TextareaControl,
	Button,
	SelectControl,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const SECTION_COLORS = {
	fc: {
		label: 'Formación Continua',
		accentColor: '#009e96',
		accentBg: '#e0f5f4',
		navyColor: '#1e3a5f',
		borderColor: '#0d9488',
		objectivesBg: '#F0FBFA',
	},
	fap: {
		label: 'Formación para Equipos de Atención Primaria',
		accentColor: '#f5a623',
		accentBg: '#ffe8cc',
		navyColor: '#1e3a5f',
		borderColor: '#f5a623',
		objectivesBg: '#FFF4E5',
	},
	pim: {
		label: 'Programa de Innovación Municipal',
		accentColor: '#1a2b4a',
		accentBg: '#d4dce8',
		navyColor: '#1a2b4a',
		borderColor: '#1a2b4a',
		objectivesBg: '#EEF2F8',
		characteristicsBg: '#E2E6EE',
	},
};

export default function DooAfCursoEdit( { attributes, setAttributes } ) {
	const {
		section,
		accentColor,
		accentBg,
		navyColor,
		borderColor,
		objectivesBg,
		characteristicsBg,
		sectionLabel,
		sectionSlug,
		objectivesIntro,
		objectives,
		topics,
		destinatarios,
		horario,
	} = attributes;

	const blockProps = useBlockProps();

	// Initialize section to 'fc' if empty
	useEffect( () => {
		if ( ! section ) {
			setAttributes( { section: 'fc' } );
		}
	}, [] );

	// Auto-apply colors when section changes
	useEffect( () => {
		if ( section && SECTION_COLORS[ section ] ) {
			const colors = SECTION_COLORS[ section ];
			setAttributes( {
				sectionLabel: colors.label,
				accentColor: colors.accentColor,
				accentBg: colors.accentBg,
				navyColor: colors.navyColor,
				borderColor: colors.borderColor,
				objectivesBg: colors.objectivesBg,
				characteristicsBg: colors.characteristicsBg || '',
			} );
		}
	}, [ section ] );

	// ── Objectives helpers ──────────────────────────────────────────────────
	const updateObjective = ( index, text ) =>
		setAttributes( {
			objectives: objectives.map( ( o, i ) => ( i === index ? { ...o, text } : o ) ),
		} );

	const removeObjective = ( index ) =>
		setAttributes( { objectives: objectives.filter( ( _, i ) => i !== index ) } );

	const addObjective = () =>
		setAttributes( { objectives: [ ...objectives, { text: '' } ] } );

	// ── Topics helpers ──────────────────────────────────────────────────────
	const updateTopicTitle = ( index, title ) =>
		setAttributes( {
			topics: topics.map( ( t, i ) => ( i === index ? { ...t, title } : t ) ),
		} );

	const updateTopicItems = ( index, value ) =>
		setAttributes( {
			topics: topics.map( ( t, i ) =>
				i === index ? { ...t, items: value.split( '\n' ) } : t
			),
		} );

	const removeTopic = ( index ) =>
		setAttributes( { topics: topics.filter( ( _, i ) => i !== index ) } );

	const addTopic = () =>
		setAttributes( { topics: [ ...topics, { title: '', items: [] } ] } );

	return (
		<>
			<InspectorControls>

				{/* ── Sección y colores ────────────────────────────────────── */}
				<PanelBody title="Sección y colores del tema" initialOpen={ true }>
					<SelectControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Sección"
						value={ section }
						options={ [
							{ label: 'Formación Continua (FC)', value: 'fc' },
							{ label: 'FAP (Atención Primaria)', value: 'fap' },
							{ label: 'PIM (Innovación Municipal)', value: 'pim' },
						] }
						onChange={ ( val ) => setAttributes( { section: val } ) }
						help="Selecciona la sección para aplicar colores automáticamente"
					/>
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Etiqueta de sección (breadcrumb)"
						value={ sectionLabel }
						onChange={ ( val ) => setAttributes( { sectionLabel: val } ) }
					/>
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Slug de sección (URL)"
						value={ sectionSlug }
						onChange={ ( val ) => setAttributes( { sectionSlug: val } ) }
					/>
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Color de acento"
						value={ accentColor }
						onChange={ ( val ) => setAttributes( { accentColor: val } ) }
						help="Ej: #009e96"
					/>
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Color de fondo claro"
						value={ accentBg }
						onChange={ ( val ) => setAttributes( { accentBg: val } ) }
						help="Ej: #e0f5f4"
					/>
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Fondo de objetivos"
						value={ objectivesBg }
						onChange={ ( val ) => setAttributes( { objectivesBg: val } ) }
						help="Ej: #F0FBFA"
					/>
					{ characteristicsBg && (
						<TextControl
							__nextHasNoMarginBottom
							__next40pxDefaultSize
							label="Fondo de características"
							value={ characteristicsBg }
							onChange={ ( val ) => setAttributes( { characteristicsBg: val } ) }
							help="Ej: #E2E6EE"
						/>
					) }
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Color navy (badges)"
						value={ navyColor }
						onChange={ ( val ) => setAttributes( { navyColor: val } ) }
						help="Ej: #1e3a5f"
					/>
					<TextControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label="Color del borde lateral"
						value={ borderColor }
						onChange={ ( val ) => setAttributes( { borderColor: val } ) }
						help="Ej: #0d9488"
					/>
				</PanelBody>

			</InspectorControls>

			<div { ...blockProps }>
				<div className="doo-af-editor">

					<div className="doo-af-editor__grid">

						{/* Panel lateral */}
						<div className="doo-af-editor__card">
							<h3 className="doo-af-editor__card-title">Panel lateral</h3>
							<div className="doo-af-editor__hint">
								Título, código, fechas, plazas y modalidad se cargan automáticamente desde la Acción formativa.
							</div>
							<label className="doo-af-editor__label">Destinatarios</label>
							<TextareaControl
								__nextHasNoMarginBottom
								value={ destinatarios }
								onChange={ ( val ) => setAttributes( { destinatarios: val } ) }
								rows={ 3 }
							/>
							<label className="doo-af-editor__label">Horario</label>
							<TextControl
								__nextHasNoMarginBottom
								__next40pxDefaultSize
								value={ horario }
								onChange={ ( val ) => setAttributes( { horario: val } ) }
							/>
						</div>

						{/* Objetivos */}
						<div className="doo-af-editor__card">
							<h3 className="doo-af-editor__card-title">Objetivos</h3>
							<label className="doo-af-editor__label">Texto introductorio</label>
							<TextareaControl
								__nextHasNoMarginBottom
								value={ objectivesIntro }
								onChange={ ( val ) => setAttributes( { objectivesIntro: val } ) }
								rows={ 2 }
							/>
							<div className="doo-af-editor__obj-list">
								{ objectives.map( ( obj, i ) => (
									<PanelRow key={ i } className="doo-af-editor__obj-row">
										<TextControl
											__nextHasNoMarginBottom
											__next40pxDefaultSize
											label={ `${ i + 1 }.` }
											value={ obj.text }
											onChange={ ( val ) => updateObjective( i, val ) }
										/>
										<Button
											isDestructive
											variant="tertiary"
											size="small"
											onClick={ () => removeObjective( i ) }
										>✕</Button>
									</PanelRow>
								) ) }
							</div>
							<Button variant="secondary" onClick={ addObjective } className="doo-af-editor__add-btn">
								+ Añadir objetivo
							</Button>
						</div>

					</div>

					{/* Temas del contenido */}
					<div className="doo-af-editor__card doo-af-editor__card--full">
						<h3 className="doo-af-editor__card-title">Temas del contenido</h3>
						<div className="doo-af-editor__topics">
							{ topics.map( ( topic, i ) => (
								<div key={ i } className="doo-af-editor__topic">
									<div className="doo-af-editor__topic-header">
										<span className="doo-af-editor__topic-num">{ i + 1 }</span>
										<TextControl
											__nextHasNoMarginBottom
											__next40pxDefaultSize
											placeholder="Título del tema"
											value={ topic.title }
											onChange={ ( val ) => updateTopicTitle( i, val ) }
										/>
										<Button isDestructive variant="tertiary" size="small" onClick={ () => removeTopic( i ) }>✕</Button>
									</div>
									<TextareaControl
										__nextHasNoMarginBottom
										label="Puntos del temario (una línea por punto, ## para subtítulos)"
										value={ ( topic.items || [] ).join( '\n' ) }
										onChange={ ( val ) => updateTopicItems( i, val ) }
										rows={ 6 }
									/>
								</div>
							) ) }
						</div>
						<Button variant="secondary" onClick={ addTopic } className="doo-af-editor__add-btn">
							+ Añadir tema
						</Button>
					</div>

				</div>
			</div>
		</>
	);
}
