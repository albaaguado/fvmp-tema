<?php
/**
 * Block render: doo/presentacion.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

$d = doo_presentacion_block_defaults();

$eyebrow   = isset( $attributes['eyebrow'] ) && '' !== $attributes['eyebrow'] ? $attributes['eyebrow'] : $d['eyebrow'];
$title     = isset( $attributes['title'] ) && '' !== $attributes['title'] ? $attributes['title'] : $d['title'];
$name      = isset( $attributes['name'] ) && '' !== $attributes['name'] ? $attributes['name'] : $d['name'];
$role      = isset( $attributes['role'] ) && '' !== $attributes['role'] ? $attributes['role'] : $d['role'];
$body      = isset( $attributes['body'] ) && '' !== $attributes['body'] ? $attributes['body'] : $d['body'];
$full_text = isset( $attributes['fullText'] ) && '' !== $attributes['fullText'] ? $attributes['fullText'] : $d['fullText'];
$link_text = isset( $attributes['linkText'] ) && '' !== $attributes['linkText'] ? $attributes['linkText'] : $d['linkText'];
$image_id  = isset( $attributes['imageId'] ) && (int) $attributes['imageId'] > 0 ? (int) $attributes['imageId'] : (int) $d['imageId'];
$image_url = isset( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : '';

if ( $image_id && ! $image_url ) {
	$image_url = wp_get_attachment_image_url( $image_id, 'medium' );
}

if ( ! $image_url ) {
	$image_url = DOO_THEME_URI . '/assets/images/miguel.png';
}

$image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
if ( ! $image_alt ) {
	$image_alt = $name;
}
?>
<section class="doo-presentacion">
	<div class="doo-presentacion__container">
		<div class="doo-presentacion__inner">

			<div class="doo-presentacion__left">
				<div class="doo-presentacion__photo">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" />
				</div>
				<p class="doo-presentacion__name"><?php echo esc_html( $name ); ?></p>
				<p class="doo-presentacion__role"><?php echo esc_html( $role ); ?></p>
			</div>

			<div class="doo-presentacion__right">
				<p class="doo-presentacion__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<h2 class="doo-presentacion__title"><?php echo esc_html( $title ); ?></h2>
				<p class="doo-presentacion__body"><?php echo wp_kses_post( $body ); ?></p>

				<?php if ( $full_text ) : ?>
					<div class="doo-presentacion__full" aria-hidden="true">
						<?php echo wp_kses_post( $full_text ); ?>
					</div>
					<a class="doo-presentacion__link" href="#" aria-expanded="false"><?php echo esc_html( $link_text ); ?></a>
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
