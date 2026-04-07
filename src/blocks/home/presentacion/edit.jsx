/**
 * Block editor component: doo/presentacion.
 *
 * Inline editing for photo, name, role, eyebrow, title and body.
 * El mensaje largo se puede editar en el lienzo (desplegable, igual que en el front)
 * o en el panel lateral.
 */

import { useState } from '@wordpress/element';
import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function DooPresentacionEdit( { attributes, setAttributes } ) {
	const { imageId, imageUrl, name, role, eyebrow, title, body, fullText, linkText } = attributes;
	const blockProps = useBlockProps( { className: 'doo-presentacion' } );

	const themeUri = window.dooThemeEditor?.themeUri || '';
	const fallbackImg = themeUri ? `${ themeUri }/assets/images/miguel.png` : '';
	const fullDefault = window.dooThemeEditor?.presentacionFullDefault || '';

	const [ isFullOpen, setFullOpen ] = useState( false );
	const fullHtml = fullText || fullDefault;

	const onSelectImage = ( media ) => {
		setAttributes( { imageId: media.id, imageUrl: media.url } );
	};

	const onRemoveImage = () => {
		setAttributes( { imageId: 0, imageUrl: '' } );
	};

	const displayImage = imageUrl || fallbackImg;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Full Message (expandable)', 'dw-tema' ) } initialOpen={ false }>
					<TextareaControl
						label={ __( 'Full text (HTML allowed)', 'dw-tema' ) }
						help={ __( 'You can also open "Read full message" in the block and edit here. Use <p> tags for paragraphs.', 'dw-tema' ) }
						value={ fullText || fullDefault }
						onChange={ ( val ) => setAttributes( { fullText: val } ) }
						rows={ 12 }
					/>
					<TextControl
						label={ __( 'Link text', 'dw-tema' ) }
						value={ linkText }
						onChange={ ( val ) => setAttributes( { linkText: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<section { ...blockProps }>
				<div className="doo-presentacion__container">
					<div className="doo-presentacion__inner">

						<div className="doo-presentacion__left">
							<div className="doo-presentacion__photo">
								<MediaUploadCheck>
									<MediaUpload
										onSelect={ onSelectImage }
										allowedTypes={ [ 'image' ] }
										value={ imageId }
										render={ ( { open } ) =>
											displayImage ? (
												<div>
													<img
														src={ displayImage }
														alt=""
														onClick={ open }
														style={ { cursor: 'pointer', maxWidth: '100%', borderRadius: '50%' } }
													/>
													<Button onClick={ onRemoveImage } isDestructive isSmall>
														{ __( 'Remove', 'dw-tema' ) }
													</Button>
												</div>
											) : (
												<Button onClick={ open } variant="secondary">
													{ __( 'Select photo', 'dw-tema' ) }
												</Button>
											)
										}
									/>
								</MediaUploadCheck>
							</div>
							<RichText
								tagName="p"
								className="doo-presentacion__name"
								value={ name }
								onChange={ ( val ) => setAttributes( { name: val } ) }
								placeholder={ __( 'Name…', 'dw-tema' ) }
							/>
							<RichText
								tagName="p"
								className="doo-presentacion__role"
								value={ role }
								onChange={ ( val ) => setAttributes( { role: val } ) }
								placeholder={ __( 'Role…', 'dw-tema' ) }
							/>
						</div>

						<div
							className={ `doo-presentacion__right${ isFullOpen ? ' is-expanded' : '' }` }
						>
							<RichText
								tagName="p"
								className="doo-presentacion__eyebrow"
								value={ eyebrow }
								onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
								placeholder={ __( 'Eyebrow…', 'dw-tema' ) }
							/>
							<RichText
								tagName="h2"
								className="doo-presentacion__title"
								value={ title }
								onChange={ ( val ) => setAttributes( { title: val } ) }
								placeholder={ __( 'Title…', 'dw-tema' ) }
							/>
							<RichText
								tagName="p"
								className="doo-presentacion__body"
								value={ body }
								onChange={ ( val ) => setAttributes( { body: val } ) }
								placeholder={ __( 'Introductory paragraph…', 'dw-tema' ) }
							/>
							{ fullHtml ? (
								<>
									<div
										className={ `doo-presentacion__full${ isFullOpen ? ' is-open' : '' }` }
										aria-hidden={ ! isFullOpen }
									>
										{ isFullOpen && (
											<textarea
												className="doo-presentacion__full-editor"
												aria-label={ __( 'Full message (HTML)', 'dw-tema' ) }
												value={ fullHtml }
												spellCheck={ false }
												onChange={ ( e ) =>
													setAttributes( { fullText: e.target.value } )
												}
												style={ {
													display: 'block',
													width: '100%',
													minHeight: '240px',
													boxSizing: 'border-box',
													padding: 12,
													fontSize: 14,
													lineHeight: 1.5,
													border: '1px solid #c3c4c7',
													borderRadius: 4,
													fontFamily: 'ui-monospace, Consolas, monospace',
												} }
											/>
										) }
									</div>
									<a
										href="#"
										className="doo-presentacion__link"
										onClick={ ( e ) => {
											e.preventDefault();
											setFullOpen( ! isFullOpen );
										} }
									>
										{ isFullOpen
											? __( 'Cerrar mensaje ↑', 'dw-tema' )
											: linkText ||
											  __( 'Leer mensaje completo →', 'dw-tema' ) }
									</a>
								</>
							) : (
								<p className="doo-presentacion__link" style={ { opacity: 0.6 } }>
									{ __( 'Add full message HTML in the sidebar.', 'dw-tema' ) }
								</p>
							) }
						</div>

					</div>
				</div>
			</section>
		</>
	);
}
