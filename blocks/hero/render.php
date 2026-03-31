<?php
/**
 * Block render: doo/hero.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

$d = doo_hero_block_defaults();

$eyebrow     = isset( $attributes['eyebrow'] ) && '' !== $attributes['eyebrow'] ? $attributes['eyebrow'] : $d['eyebrow'];
$title       = isset( $attributes['title'] ) && '' !== $attributes['title'] ? $attributes['title'] : $d['title'];
$description = isset( $attributes['description'] ) && '' !== $attributes['description'] ? $attributes['description'] : $d['description'];
$button_text = isset( $attributes['buttonText'] ) && '' !== $attributes['buttonText'] ? $attributes['buttonText'] : $d['buttonText'];
$button_url  = isset( $attributes['buttonUrl'] ) && '' !== $attributes['buttonUrl'] ? $attributes['buttonUrl'] : $d['buttonUrl'];
$image_id    = isset( $attributes['imageId'] ) && (int) $attributes['imageId'] > 0 ? (int) $attributes['imageId'] : (int) $d['imageId'];
$image_url   = isset( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : '';

if ( $image_id && ! $image_url ) {
	$image_url = wp_get_attachment_image_url( $image_id, 'full' );
}

if ( ! $image_url ) {
	$image_url = DOO_THEME_URI . '/assets/images/hero-img.png';
}

$image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
?>
<div class="doo-hero-wrap">
	<div class="doo-hero">

	<div class="doo-hero__left">
		<p class="doo-hero__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<h1 class="doo-hero__title"><?php echo esc_html( $title ); ?></h1>
		<p class="doo-hero__desc"><?php echo esc_html( $description ); ?></p>
		<a class="doo-hero__btn" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_text ); ?></a>
	</div>

	<div class="doo-hero__photo">
		<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" aria-hidden="true" />
		<div class="doo-hero__gradient"></div>
	</div>

	</div>
</div>
