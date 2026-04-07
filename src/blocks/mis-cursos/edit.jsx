/**
 * Block edit: doo/mis-cursos.
 *
 * All text fields are editable inline via RichText.
 * Course data rows are shown as read-only placeholders
 * (they will come from the backend in production).
 */

import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const TABLE_HEADERS = [ 'Orden', 'Código', 'Curso', 'Inicio', 'Fin', 'Estado', 'Certificado', 'Ordenar', 'Borrar' ];
const TABLE_HEADER_CELLS = [ '--order', '--code', '--name', '--start', '--end', '--status', '--cert', '--sort', '--delete' ];

function TableHeader() {
	return (
		<div className="doo-mis-cursos__row doo-mis-cursos__row--header">
			{ TABLE_HEADERS.map( ( label, i ) => (
				<div
					key={ i }
					className={ `doo-mis-cursos__cell doo-mis-cursos__cell${ TABLE_HEADER_CELLS[ i ] }` }
				>
					{ label }
				</div>
			) ) }
		</div>
	);
}

function PlaceholderRow( { order, code, name, start, end, status } ) {
	return (
		<div className="doo-mis-cursos__row doo-mis-cursos__row--data">
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--order">{ order }</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--code">{ code }</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--name">{ name }</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--start">{ start }</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--end">{ end }</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--status">{ status }</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--cert"></div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--sort">
				<span className="doo-mis-cursos__sort-btn">↑</span>
				<span className="doo-mis-cursos__sort-btn">↓</span>
			</div>
			<div className="doo-mis-cursos__cell doo-mis-cursos__cell--delete">
				<span className="doo-mis-cursos__delete-btn">✕</span>
			</div>
		</div>
	);
}

export default function DooMisCursosEdit( { attributes, setAttributes } ) {
	const {
		sectionTitle,
		infoText,
		warningText,
		program1Title,
		program2Title,
		btnVerMasLabel,
		btnEnviarLabel,
	} = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Botones', 'dw-tema' ) }>
					<TextControl
						label={ __( 'Botón "Ver más cursos"', 'dw-tema' ) }
						value={ btnVerMasLabel }
						onChange={ ( val ) => setAttributes( { btnVerMasLabel: val } ) }
					/>
					<TextControl
						label={ __( 'Botón "Enviar solicitud"', 'dw-tema' ) }
						value={ btnEnviarLabel }
						onChange={ ( val ) => setAttributes( { btnEnviarLabel: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps( { className: 'doo-mis-cursos' } ) }>
				<div className="doo-mis-cursos__content">

					<RichText
						tagName="h1"
						className="doo-mis-cursos__title"
						value={ sectionTitle }
						onChange={ ( val ) => setAttributes( { sectionTitle: val } ) }
						placeholder={ __( 'Título de sección', 'dw-tema' ) }
						allowedFormats={ [] }
					/>

					<RichText
						tagName="p"
						className="doo-mis-cursos__info"
						value={ infoText }
						onChange={ ( val ) => setAttributes( { infoText: val } ) }
						placeholder={ __( 'Texto informativo...', 'dw-tema' ) }
						allowedFormats={ [] }
					/>

					<RichText
						tagName="p"
						className="doo-mis-cursos__warning"
						value={ warningText }
						onChange={ ( val ) => setAttributes( { warningText: val } ) }
						placeholder={ __( 'Texto de aviso...', 'dw-tema' ) }
						allowedFormats={ [] }
					/>

					{ /* Program 1 */ }
					<RichText
						tagName="h2"
						className="doo-mis-cursos__program-title"
						value={ program1Title }
						onChange={ ( val ) => setAttributes( { program1Title: val } ) }
						placeholder={ __( 'Nombre del programa 1...', 'dw-tema' ) }
						allowedFormats={ [] }
					/>
					<div className="doo-mis-cursos__table-wrap">
						<TableHeader />
						<PlaceholderRow
							order="1"
							code="IM01.1"
							name="Microcredencial Universitària en Innovació Local"
							start="11/04/2025"
							end="27/06/2025"
							status="Pendiente"
						/>
					</div>

					{ /* Program 2 */ }
					<RichText
						tagName="h2"
						className="doo-mis-cursos__program-title"
						value={ program2Title }
						onChange={ ( val ) => setAttributes( { program2Title: val } ) }
						placeholder={ __( 'Nombre del programa 2...', 'dw-tema' ) }
						allowedFormats={ [] }
					/>
					<div className="doo-mis-cursos__table-wrap">
						<TableHeader />
						<PlaceholderRow
							order="1"
							code="AP01.1"
							name="Entrenament en benestar social per als professionals de serveis socials municipals"
							start="31/03/2025"
							end="08/04/2025"
							status="Pendiente"
						/>
					</div>

					<div className="doo-mis-cursos__actions">
						<span className="doo-mis-cursos__btn doo-mis-cursos__btn--outline">
							{ btnVerMasLabel }
						</span>
						<span className="doo-mis-cursos__btn doo-mis-cursos__btn--primary">
							{ btnEnviarLabel }
						</span>
					</div>

				</div>
			</div>
		</>
	);
}
