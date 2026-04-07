<?php
/**
 * Block render: doo/user-bar.
 *
 * Compact identity bar for the logged-in user.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
$display_name = ! empty( $current_user->display_name )
	? $current_user->display_name
	: esc_html__( 'Usuario', 'dw-tema' );

$subtitle = ! empty( $attributes['subtitle'] )
	? $attributes['subtitle']
	: esc_html__( 'Mi área personal', 'dw-tema' );
?>

<div class="doo-user-bar">
	<div class="doo-user-bar__avatar" aria-hidden="true">
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<circle cx="12" cy="8" r="5"/>
			<path d="M3 21a9 9 0 0 1 18 0"/>
		</svg>
	</div>
	<div class="doo-user-bar__info">
		<span class="doo-user-bar__name"><?php echo esc_html( $display_name ); ?></span>
		<a class="doo-user-bar__subtitle" href="<?php echo esc_url( home_url( '/area-personal/' ) ); ?>">
			<?php echo esc_html( $subtitle ); ?>
		</a>
	</div>
</div>
