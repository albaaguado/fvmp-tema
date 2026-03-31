<?php
/**
 * Block render: doo/trust-bar.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

?>
<div class="doo-trust-bar">

	<div class="doo-trust-bar__item">
		<img src="<?php echo esc_url( DOO_THEME_URI . '/assets/images/generalitat1.png' ); ?>" alt="<?php esc_attr_e( 'Generalitat Valenciana — Conselleria d\'Innovació, Indústria, Comerç i Turisme', 'dw-tema' ); ?>" />
	</div>

	<div class="doo-trust-bar__sep" aria-hidden="true"></div>

	<div class="doo-trust-bar__item doo-trust-bar__item--fvmp">
		<img src="<?php echo esc_url( DOO_THEME_URI . '/assets/images/logo-fvmp.png' ); ?>" alt="<?php esc_attr_e( 'FVMP — Federació Valenciana de Municipis i Províncies', 'dw-tema' ); ?>" />
	</div>

	<div class="doo-trust-bar__sep" aria-hidden="true"></div>

	<div class="doo-trust-bar__item doo-trust-bar__item--tall">
		<img src="<?php echo esc_url( DOO_THEME_URI . '/assets/images/generalitat2.png' ); ?>" alt="<?php esc_attr_e( 'Generalitat Valenciana', 'dw-tema' ); ?>" />
	</div>

</div>
