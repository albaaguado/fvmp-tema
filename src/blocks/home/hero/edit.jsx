/**
 * Block editor component: doo/hero.
 *
 * Full inline editing — all fields are editable directly in the editor.
 */

import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function DooHeroEdit( { attributes, setAttributes } ) {
	const { eyebrow, title, description, buttonText, buttonUrl, imageId, imageUrl } = attributes;
	const blockProps = useBlockProps( { className: 'doo-hero-wrap' } );

	const themeUri = window.dooThemeEditor?.themeUri || '';
	const fallbackImg = themeUri ? `${ themeUri }/assets/images/hero-img.png` : '';

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
				<PanelBody title={ __( 'Button Settings', 'dw-tema' ) }>
					<TextControl
						label={ __( 'Button URL', 'dw-tema' ) }
						value={ buttonUrl }
						onChange={ ( val ) => setAttributes( { buttonUrl: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div className="doo-hero">
				<div className="doo-hero__left">
					<RichText
						tagName="p"
						className="doo-hero__eyebrow"
						value={ eyebrow }
						onChange={ ( val ) => setAttributes( { eyebrow: val } ) }
						placeholder={ __( 'Eyebrow text…', 'dw-tema' ) }
					/>
					<RichText
						tagName="h1"
						className="doo-hero__title"
						value={ title }
						onChange={ ( val ) => setAttributes( { title: val } ) }
						placeholder={ __( 'Main title…', 'dw-tema' ) }
					/>
					<RichText
						tagName="p"
						className="doo-hero__desc"
						value={ description }
						onChange={ ( val ) => setAttributes( { description: val } ) }
						placeholder={ __( 'Description…', 'dw-tema' ) }
					/>
					<RichText
						tagName="span"
						className="doo-hero__btn"
						value={ buttonText }
						onChange={ ( val ) => setAttributes( { buttonText: val } ) }
						placeholder={ __( 'Button text…', 'dw-tema' ) }
					/>
				</div>

				<div className="doo-hero__photo">
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
											style={ { cursor: 'pointer', maxWidth: '100%' } }
										/>
										<Button onClick={ onRemoveImage } isDestructive isSmall>
											{ __( 'Remove image', 'dw-tema' ) }
										</Button>
									</div>
								) : (
									<Button onClick={ open } variant="secondary">
										{ __( 'Select hero image', 'dw-tema' ) }
									</Button>
								)
							}
						/>
					</MediaUploadCheck>
					<div className="doo-hero__gradient"></div>
				</div>
				</div>
			</div>
		</>
	);
}
