<?php
/**
 * Block render: doo/fap-hero.
 *
 * Hero for FAP section with orange theme.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

$eyebrow          = $attributes['eyebrow']                   ?? '';
$title            = $attributes['title']                     ?? '';
$description      = $attributes['description']               ?? '';
$stat2_num        = $attributes['stat2Num']                  ?? '2.500+';
$stat2_label      = $attributes['stat2Label']                ?? 'plazas disponibles';
$stat3_num        = $attributes['stat3Num']                  ?? '8';
$stat3_label      = $attributes['stat3Label']                ?? 'áreas temáticas';
$card_title       = $attributes['cardTitle']                 ?? '';
$card_description = $attributes['cardDescription']           ?? '';
$card_btn_text    = $attributes['cardButtonText']            ?? '';
$card_btn_url     = $attributes['cardButtonUrl']             ?? '#';
$card_reg_text    = $attributes['cardRegisterText']          ?? '';
$card_reg_url     = $attributes['cardRegisterUrl']           ?? '#';

$total = wp_count_posts( 'doo_accion_fap' );
$count = isset( $total->publish ) ? (int) $total->publish : 0;
?>
<section class="doo-fap-hero">
	<div class="doo-af-hero__inner">

		<div class="doo-af-hero__left">
			<p class="doo-af-hero__eyebrow"><?php echo wp_kses_post( $eyebrow ); ?></p>
			<h1 class="doo-af-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
			<p class="doo-af-hero__desc"><?php echo wp_kses_post( $description ); ?></p>

			<div class="doo-af-hero__stats">
				<div class="doo-af-hero__stat">
					<span class="doo-af-hero__stat-num"><?php echo esc_html( $count ); ?>+</span>
					<span class="doo-af-hero__stat-label">acciones formativas</span>
				</div>
				<?php if ( $stat2_num ) : ?>
				<div class="doo-af-hero__sep"></div>
				<div class="doo-af-hero__stat">
					<span class="doo-af-hero__stat-num"><?php echo wp_kses_post( $stat2_num ); ?></span>
					<span class="doo-af-hero__stat-label"><?php echo wp_kses_post( $stat2_label ); ?></span>
				</div>
				<?php endif; ?>
				<?php if ( $stat3_num ) : ?>
				<div class="doo-af-hero__sep"></div>
				<div class="doo-af-hero__stat">
					<span class="doo-af-hero__stat-num"><?php echo wp_kses_post( $stat3_num ); ?></span>
					<span class="doo-af-hero__stat-label"><?php echo wp_kses_post( $stat3_label ); ?></span>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="doo-af-hero__card">
			<div class="doo-af-hero__card-stripe"></div>
			<div class="doo-af-hero__card-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
			</div>
			<p class="doo-af-hero__card-title"><?php echo wp_kses_post( $card_title ); ?></p>
			<p class="doo-af-hero__card-desc"><?php echo wp_kses_post( $card_description ); ?></p>
			<a class="doo-af-hero__card-btn" href="<?php echo esc_url( $card_btn_url ); ?>"><?php echo wp_kses_post( $card_btn_text ); ?></a>
			<p class="doo-af-hero__card-register"><a href="<?php echo esc_url( $card_reg_url ); ?>"><?php echo wp_kses_post( $card_reg_text ); ?></a></p>
		</div>

	</div>
</section>
