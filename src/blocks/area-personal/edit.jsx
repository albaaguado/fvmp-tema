import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

export default function DooAreaPersonalEdit() {
	return (
		<div { ...useBlockProps() }>
			<ServerSideRender block="doo/area-personal" />
		</div>
	);
}
