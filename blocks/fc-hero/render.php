<?php
/**
 * Block render: doo/fc-hero.
 *
 * Section texts and CTA card come from block attributes (editable).
 * Course count stats are dynamic from the doo_accion_formativa CPT.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

$eyebrow          = $attributes['eyebrow'];
$title            = $attributes['title'];
$description      = $attributes['description'];
$card_title       = $attributes['cardTitle'];
$card_description = $attributes['cardDescription'];
$card_btn_text    = $attributes['cardButtonText'];
$card_btn_url     = $attributes['cardButtonUrl'];
$card_reg_text    = $attributes['cardRegisterText'];
$card_reg_url     = $attributes['cardRegisterUrl'];

$total = wp_count_posts( 'doo_accion_formativa' );
$count = isset( $total->publish ) ? (int) $total->publish : 0;
?>
<section class="doo-fc-hero">
	<div class="doo-fc-hero__inner">

		<div class="doo-fc-hero__left">
			<p class="doo-fc-hero__eyebrow"><?php echo wp_kses_post( $eyebrow ); ?></p>
			<h1 class="doo-fc-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
			<p class="doo-fc-hero__desc"><?php echo wp_kses_post( $description ); ?></p>

			<div class="doo-fc-hero__stats">
				<div class="doo-fc-hero__stat">
					<span class="doo-fc-hero__stat-num"><?php echo esc_html( $count ); ?>+</span>
					<span class="doo-fc-hero__stat-label">acciones formativas</span>
				</div>
				<div class="doo-fc-hero__sep"></div>
				<div class="doo-fc-hero__stat">
					<span class="doo-fc-hero__stat-num">7000+</span>
					<span class="doo-fc-hero__stat-label">plazas disponibles</span>
				</div>
				<div class="doo-fc-hero__sep"></div>
				<div class="doo-fc-hero__stat">
					<span class="doo-fc-hero__stat-num">3</span>
					<span class="doo-fc-hero__stat-label">modalidades</span>
				</div>
			</div>
		</div>

		<div class="doo-fc-hero__card">
			<div class="doo-fc-hero__card-stripe"></div>
			<div class="doo-fc-hero__card-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
			</div>
			<p class="doo-fc-hero__card-title"><?php echo wp_kses_post( $card_title ); ?></p>
			<p class="doo-fc-hero__card-desc"><?php echo wp_kses_post( $card_description ); ?></p>
			<a class="doo-fc-hero__card-btn" href="<?php echo esc_url( $card_btn_url ); ?>"><?php echo wp_kses_post( $card_btn_text ); ?></a>
			<p class="doo-fc-hero__card-register"><a href="<?php echo esc_url( $card_reg_url ); ?>"><?php echo wp_kses_post( $card_reg_text ); ?></a></p>
		</div>

	</div>
</section>
