/**
 * Block edit: doo/user-bar.
 *
 * Shows a placeholder avatar + editable subtitle.
 * The real user name is rendered server-side.
 */

import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function DooUserBarEdit( { attributes, setAttributes } ) {
	const { subtitle } = attributes;

	return (
		<div { ...useBlockProps( { className: 'doo-user-bar' } ) }>
			<div className="doo-user-bar__avatar" aria-hidden="true">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
					<circle cx="12" cy="8" r="5"/>
					<path d="M3 21a9 9 0 0 1 18 0"/>
				</svg>
			</div>
			<div className="doo-user-bar__info">
				<span className="doo-user-bar__name">
					{ __( '(Nombre del usuario)', 'dw-tema' ) }
				</span>
				<RichText
					tagName="a"
					className="doo-user-bar__subtitle"
					value={ subtitle }
					onChange={ ( val ) => setAttributes( { subtitle: val } ) }
					placeholder={ __( 'Mi área personal', 'dw-tema' ) }
					allowedFormats={ [] }
				/>
			</div>
		</div>
	);
}
