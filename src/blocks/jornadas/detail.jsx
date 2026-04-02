/**
 * Jornada Detail Block - Editor Component.
 *
 * @package DooFvmpTheme
 */

import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function DooJornadaDetailEdit() {
	const blockProps = useBlockProps({ className: 'doo-jornada-detail-editor' });

	return (
		<div {...blockProps}>
			<Placeholder
				icon="calendar-alt"
				label={__('Detalle de Jornada', 'dw-tema')}
				instructions={__('Este bloque mostrará automáticamente los detalles de la jornada vinculada a esta página.', 'dw-tema')}
			/>
		</div>
	);
}
