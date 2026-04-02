/**
 * Block editor: doo/registro
 *
 * Full inline editing of registration form fields.
 * Click a field to select it → edit label inline or via InspectorControls.
 * Use × to remove a field, "+ Añadir campo" to add new ones.
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl, ToggleControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

// =============================================================================
// Default field sets (used when no attributes are saved yet)
// =============================================================================

const DEFAULT_FIELDS_PERSONAL = [
	{ id: 'nombre',     label: 'Nombre',               placeholder: 'Nombre',                   type: 'text',     required: true,  fullWidth: false },
	{ id: 'dni',        label: 'DNI - NIE',             placeholder: 'DNI - NIE',                type: 'text',     required: true,  fullWidth: false },
	{ id: 'apellidos',  label: 'Apellidos',             placeholder: 'Apellidos',                type: 'text',     required: true,  fullWidth: false },
	{ id: 'nacimiento', label: 'Fecha de nacimiento',   placeholder: 'dd/mm/aaaa',               type: 'text',     required: true,  fullWidth: false },
	{ id: 'email',      label: 'Correo electrónico',    placeholder: 'Correo electrónico',       type: 'email',    required: true,  fullWidth: false },
	{ id: 'telefono',   label: 'Teléfono',              placeholder: 'Número de teléfono',       type: 'tel',      required: true,  fullWidth: false },
	{ id: 'password',   label: 'Contraseña',            placeholder: 'Introduce contraseña',     type: 'password', required: true,  fullWidth: false },
	{ id: 'sexo',       label: 'Sexo',                  placeholder: '',                         type: 'radio',    required: true,  fullWidth: false },
];

const DEFAULT_FIELDS_LABORAL = [
	{ id: 'perfil',     label: 'Perfil laboral',                  placeholder: 'Perfil laboral',                        type: 'select', required: true,  fullWidth: true  },
	{ id: 'categoria',  label: 'Categoría profesional',           placeholder: 'Seleccione categoría profesional',      type: 'select', required: true,  fullWidth: true  },
	{ id: 'puesto',     label: 'Puesto de trabajo',               placeholder: 'Introduzca su puesto de trabajo',       type: 'text',   required: true,  fullWidth: true  },
	{ id: 'centro',     label: 'Centro de trabajo',               placeholder: 'Centro de trabajo',                     type: 'text',   required: true,  fullWidth: true  },
	{ id: 'direccion',  label: 'Dirección',                       placeholder: 'Dirección',                             type: 'text',   required: true,  fullWidth: true  },
	{ id: 'provincia',  label: 'Provincia',                       placeholder: 'Provincia',                             type: 'select', required: true,  fullWidth: false },
	{ id: 'extension',  label: 'Extensión',                       placeholder: '',                                      type: 'text',   required: false, fullWidth: false },
	{ id: 'comarca',    label: 'Comarca',                         placeholder: 'Seleccione o busque una comarca',       type: 'select', required: true,  fullWidth: false },
	{ id: 'titulacion', label: 'Titulación',                      placeholder: 'Seleccione titulación',                 type: 'select', required: true,  fullWidth: false },
	{ id: 'municipio',  label: 'Municipio',                       placeholder: 'Seleccione o busque un municipio',      type: 'select', required: true,  fullWidth: false },
	{ id: 'grupo',      label: 'Grupo de trabajo',                placeholder: 'Seleccione grupo de trabajo',           type: 'select', required: true,  fullWidth: false },
	{ id: 'cp',         label: 'CP',                              placeholder: '',                                      type: 'text',   required: true,  fullWidth: false },
	{ id: 'relacion',   label: 'Relación Jurídica',               placeholder: 'Seleccione relación jurídica',          type: 'select', required: true,  fullWidth: false },
	{ id: 'tel_trabajo', label: 'Teléfono de trabajo',            placeholder: '',                                      type: 'tel',    required: false, fullWidth: true  },
];

const FIELD_TYPES = [
	{ label: 'Texto',               value: 'text'     },
	{ label: 'Email',               value: 'email'    },
	{ label: 'Teléfono',            value: 'tel'      },
	{ label: 'Contraseña',          value: 'password' },
	{ label: 'Fecha',               value: 'date'     },
	{ label: 'Desplegable',         value: 'select'   },
	{ label: 'Botones de opción',   value: 'radio'    },
	{ label: 'Área de texto',       value: 'textarea' },
];

// =============================================================================
// Layout helper — group consecutive non-fullWidth fields in pairs
// =============================================================================

function groupFields( fields ) {
	const rows = [];
	let i = 0;
	while ( i < fields.length ) {
		if ( fields[ i ].fullWidth ) {
			rows.push( [ fields[ i ] ] );
			i++;
		} else if ( i + 1 < fields.length && ! fields[ i + 1 ].fullWidth ) {
			rows.push( [ fields[ i ], fields[ i + 1 ] ] );
			i += 2;
		} else {
			rows.push( [ fields[ i ] ] );
			i++;
		}
	}
	return rows;
}

// =============================================================================
// Field input preview (non-interactive visual mock)
// =============================================================================

function FieldPreview( { field } ) {
	const placeholderStyle = { color: '#b0b9c6', fontSize: 14, overflow: 'hidden', whiteSpace: 'nowrap', textOverflow: 'ellipsis' };

	if ( 'radio' === field.type ) {
		return (
			<div className="doo-registro__radio-group">
				<label className="doo-registro__radio-label">
					<span className="doo-registro__radio-dot" />
					{ __( 'Hombre', 'dw-tema' ) }
				</label>
				<label className="doo-registro__radio-label">
					<span className="doo-registro__radio-dot" />
					{ __( 'Mujer', 'dw-tema' ) }
				</label>
			</div>
		);
	}

	if ( 'select' === field.type ) {
		return (
			<div className="doo-registro__select-wrap" style={ { pointerEvents: 'none' } }>
				<div className="doo-registro__input doo-registro__input--select" style={ { display: 'flex', alignItems: 'center', height: 48, boxSizing: 'border-box' } }>
					<span style={ placeholderStyle }>{ field.placeholder || field.label }</span>
				</div>
			</div>
		);
	}

	if ( 'textarea' === field.type ) {
		return (
			<div className="doo-registro__input" style={ { height: 96, display: 'flex', alignItems: 'flex-start', paddingTop: 12, boxSizing: 'border-box', pointerEvents: 'none' } }>
				<span style={ placeholderStyle }>{ field.placeholder || field.label }</span>
			</div>
		);
	}

	return (
		<div className="doo-registro__input" style={ { display: 'flex', alignItems: 'center', height: 48, boxSizing: 'border-box', pointerEvents: 'none' } }>
			<span style={ placeholderStyle }>{ field.placeholder || field.label }</span>
		</div>
	);
}

// =============================================================================
// Single editable field card
// =============================================================================

function FieldEditor( { field, isSelected, onSelect, onUpdateLabel, onDelete } ) {
	return (
		<div
			className={ `doo-registro__field${ field.fullWidth ? ' doo-registro__field--full' : '' }` }
			style={ {
				outline:       isSelected ? '2px solid #009e96' : '2px solid transparent',
				outlineOffset: 4,
				borderRadius:  8,
				position:      'relative',
				cursor:        'pointer',
				padding:       4,
			} }
			onClick={ ( e ) => { e.stopPropagation(); onSelect(); } }
		>
			{/* Delete button */}
			<button
				type="button"
				onClick={ ( e ) => { e.stopPropagation(); onDelete(); } }
				style={ {
					position:        'absolute',
					top:             -10,
					right:           -10,
					zIndex:          10,
					width:           22,
					height:          22,
					padding:         0,
					borderRadius:    '50%',
					background:      '#cc1818',
					border:          'none',
					color:           '#fff',
					fontSize:        14,
					lineHeight:      '22px',
					cursor:          'pointer',
					display:         'flex',
					alignItems:      'center',
					justifyContent:  'center',
				} }
				aria-label={ __( 'Eliminar campo', 'dw-tema' ) }
			>
				×
			</button>

			{/* Editable label */}
			<RichText
				tagName="p"
				className="doo-registro__label"
				value={ field.label }
				onChange={ onUpdateLabel }
				placeholder={ __( 'Etiqueta…', 'dw-tema' ) }
				allowedFormats={ [] }
			/>

			{/* Input preview */}
			<FieldPreview field={ field } />

			{ field.required && (
				<span style={ { fontSize: 11, color: '#009e96', marginTop: 2, display: 'block' } }>
					{ __( 'Obligatorio', 'dw-tema' ) }
				</span>
			) }
		</div>
	);
}

// =============================================================================
// Main edit component
// =============================================================================

export default function DooRegistroEdit( { attributes, setAttributes } ) {
	const {
		sectionPersonalTitle = 'Datos personales',
		sectionLaboralTitle  = 'Datos laborales',
	} = attributes;

	const fieldsPersonal = attributes.fieldsPersonal?.length ? attributes.fieldsPersonal : DEFAULT_FIELDS_PERSONAL;
	const fieldsLaboral  = attributes.fieldsLaboral?.length  ? attributes.fieldsLaboral  : DEFAULT_FIELDS_LABORAL;

	const [ selectedId, setSelectedId ] = useState( null ); // "personal:id" | "laboral:id"

	const blockProps = useBlockProps( { className: 'doo-registro' } );

	// --- Helpers ---

	function updateField( section, id, key, value ) {
		const attrKey = 'personal' === section ? 'fieldsPersonal' : 'fieldsLaboral';
		const current = 'personal' === section ? fieldsPersonal : fieldsLaboral;
		setAttributes( {
			[ attrKey ]: current.map( ( f ) => ( f.id === id ? { ...f, [ key ]: value } : f ) ),
		} );
	}

	function deleteField( section, id ) {
		const attrKey = 'personal' === section ? 'fieldsPersonal' : 'fieldsLaboral';
		const current = 'personal' === section ? fieldsPersonal : fieldsLaboral;
		setAttributes( { [ attrKey ]: current.filter( ( f ) => f.id !== id ) } );
		setSelectedId( null );
	}

	function addField( section ) {
		const attrKey = 'personal' === section ? 'fieldsPersonal' : 'fieldsLaboral';
		const current = 'personal' === section ? fieldsPersonal : fieldsLaboral;
		const newField = {
			id:          `field_${ Date.now() }`,
			label:       __( 'Nuevo campo', 'dw-tema' ),
			placeholder: '',
			type:        'text',
			required:    false,
			fullWidth:   false,
		};
		setAttributes( { [ attrKey ]: [ ...current, newField ] } );
		setSelectedId( `${ section }:${ newField.id }` );
	}

	// --- Resolve selected field ---
	let selectedField   = null;
	let selectedSection = null;
	if ( selectedId ) {
		const colonIdx   = selectedId.indexOf( ':' );
		selectedSection  = selectedId.slice( 0, colonIdx );
		const fieldId    = selectedId.slice( colonIdx + 1 );
		const pool       = 'personal' === selectedSection ? fieldsPersonal : fieldsLaboral;
		selectedField    = pool.find( ( f ) => f.id === fieldId ) || null;
	}

	// --- Render a section's fields ---
	function renderSection( fields, section ) {
		return groupFields( fields ).map( ( row, rowIdx ) => {
			if ( 1 === row.length && row[ 0 ].fullWidth ) {
				const f = row[ 0 ];
				return (
					<FieldEditor
						key={ f.id }
						field={ f }
						isSelected={ selectedId === `${ section }:${ f.id }` }
						onSelect={ () => setSelectedId( `${ section }:${ f.id }` ) }
						onUpdateLabel={ ( val ) => updateField( section, f.id, 'label', val ) }
						onDelete={ () => deleteField( section, f.id ) }
					/>
				);
			}
			return (
				<div key={ rowIdx } className="doo-registro__row">
					{ row.map( ( f ) => (
						<FieldEditor
							key={ f.id }
							field={ f }
							isSelected={ selectedId === `${ section }:${ f.id }` }
							onSelect={ () => setSelectedId( `${ section }:${ f.id }` ) }
							onUpdateLabel={ ( val ) => updateField( section, f.id, 'label', val ) }
							onDelete={ () => deleteField( section, f.id ) }
						/>
					) ) }
				</div>
			);
		} );
	}

	return (
		<>
			{ selectedField && (
				<InspectorControls>
					<PanelBody title={ __( 'Campo seleccionado', 'dw-tema' ) } initialOpen={ true }>
						<TextControl
							label={ __( 'Etiqueta', 'dw-tema' ) }
							value={ selectedField.label }
							onChange={ ( val ) => updateField( selectedSection, selectedField.id, 'label', val ) }
						/>
						<TextControl
							label={ __( 'Placeholder', 'dw-tema' ) }
							value={ selectedField.placeholder }
							onChange={ ( val ) => updateField( selectedSection, selectedField.id, 'placeholder', val ) }
						/>
						<SelectControl
							label={ __( 'Tipo de campo', 'dw-tema' ) }
							value={ selectedField.type }
							options={ FIELD_TYPES }
							onChange={ ( val ) => updateField( selectedSection, selectedField.id, 'type', val ) }
						/>
						<ToggleControl
							label={ __( 'Obligatorio', 'dw-tema' ) }
							checked={ selectedField.required }
							onChange={ ( val ) => updateField( selectedSection, selectedField.id, 'required', val ) }
						/>
						<ToggleControl
							label={ __( 'Ancho completo', 'dw-tema' ) }
							checked={ selectedField.fullWidth }
							onChange={ ( val ) => updateField( selectedSection, selectedField.id, 'fullWidth', val ) }
						/>
					</PanelBody>
				</InspectorControls>
			) }

			<section { ...blockProps }>

				<div className="doo-registro__page-header">
					<h1 className="doo-registro__title">{ __( 'Registro', 'dw-tema' ) }</h1>
				</div>

				<div className="doo-registro__body">
					{/* eslint-disable-next-line jsx-a11y/click-events-have-key-events, jsx-a11y/no-noninteractive-element-interactions */}
					<form
						className="doo-registro__card"
						onSubmit={ ( e ) => e.preventDefault() }
						onClick={ ( e ) => { if ( e.target === e.currentTarget ) setSelectedId( null ); } }
					>

						{/* ── Datos personales ── */}
						<div className="doo-registro__section">
							<RichText
								tagName="h2"
								className="doo-registro__section-title"
								value={ sectionPersonalTitle }
								onChange={ ( val ) => setAttributes( { sectionPersonalTitle: val } ) }
								placeholder={ __( 'Título sección…', 'dw-tema' ) }
								allowedFormats={ [] }
							/>
							<div className="doo-registro__section-bar" />
							<div className="doo-registro__fields">
								{ renderSection( fieldsPersonal, 'personal' ) }
							</div>
							<Button
								variant="secondary"
								isSmall
								onClick={ () => addField( 'personal' ) }
								style={ { alignSelf: 'flex-start', marginTop: 8 } }
							>
								{ __( '+ Añadir campo', 'dw-tema' ) }
							</Button>
						</div>

						<hr className="doo-registro__divider" />

						{/* ── Datos laborales ── */}
						<div className="doo-registro__section">
							<RichText
								tagName="h2"
								className="doo-registro__section-title"
								value={ sectionLaboralTitle }
								onChange={ ( val ) => setAttributes( { sectionLaboralTitle: val } ) }
								placeholder={ __( 'Título sección…', 'dw-tema' ) }
								allowedFormats={ [] }
							/>
							<div className="doo-registro__section-bar" />
							<div className="doo-registro__fields">
								{ renderSection( fieldsLaboral, 'laboral' ) }
							</div>
							<Button
								variant="secondary"
								isSmall
								onClick={ () => addField( 'laboral' ) }
								style={ { alignSelf: 'flex-start', marginTop: 8 } }
							>
								{ __( '+ Añadir campo', 'dw-tema' ) }
							</Button>
						</div>

						<hr className="doo-registro__divider" />

						{/* ── Privacidad (preview) ── */}
						<div className="doo-registro__privacy">
							<span className="doo-registro__privacy-link">
								{ __( 'Mostrar la política de privacidad', 'dw-tema' ) }
							</span>
							<label className="doo-registro__check-label">
								<span className="doo-registro__check-box" />
								{ __( 'Acepto los términos de privacidad de la FVMP', 'dw-tema' ) }
							</label>
						</div>

						{/* ── Acciones (preview) ── */}
						<div className="doo-registro__actions">
							<button className="doo-registro__submit" type="button">
								{ __( 'Enviar', 'dw-tema' ) }
							</button>
							<div className="doo-registro__login-row">
								<span className="doo-registro__login-text">{ __( '¿Ya tienes cuenta?', 'dw-tema' ) }</span>
								<span className="doo-registro__login-link">{ __( 'Iniciar sesión', 'dw-tema' ) }</span>
							</div>
						</div>

					</form>
				</div>

			</section>
		</>
	);
}
