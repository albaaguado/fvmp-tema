<?php
/**
 * Jornadas Hero Block Render Template.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label       = isset( $attributes['label'] ) ? $attributes['label'] : 'OFERTA FORMATIVA';
$title       = isset( $attributes['title'] ) ? $attributes['title'] : 'Jornadas';
$description = isset( $attributes['description'] ) ? $attributes['description'] : '';
$stat1_value = isset( $attributes['stat1Value'] ) ? $attributes['stat1Value'] : '10+';
$stat1_label = isset( $attributes['stat1Label'] ) ? $attributes['stat1Label'] : 'jornadas realizadas';
$stat2_value = isset( $attributes['stat2Value'] ) ? $attributes['stat2Value'] : '3.000+';
$stat2_label = isset( $attributes['stat2Label'] ) ? $attributes['stat2Label'] : 'asistentes totales';
$stat3_value = isset( $attributes['stat3Value'] ) ? $attributes['stat3Value'] : '2025';
$stat3_label = isset( $attributes['stat3Label'] ) ? $attributes['stat3Label'] : 'año en curso';
?>

<section class="doo-jornadas-hero">
	<div class="doo-jornadas-hero__container">
		<div class="doo-jornadas-hero__content">
			<span class="doo-jornadas-hero__label"><?php echo esc_html( $label ); ?></span>
			<h1 class="doo-jornadas-hero__title"><?php echo esc_html( $title ); ?></h1>
			<p class="doo-jornadas-hero__description"><?php echo esc_html( $description ); ?></p>
			
			<div class="doo-jornadas-hero__stats">
				<div class="doo-jornadas-hero__stat">
					<span class="doo-jornadas-hero__stat-value"><?php echo esc_html( $stat1_value ); ?></span>
					<span class="doo-jornadas-hero__stat-label"><?php echo esc_html( $stat1_label ); ?></span>
				</div>
				<div class="doo-jornadas-hero__stat">
					<span class="doo-jornadas-hero__stat-value"><?php echo esc_html( $stat2_value ); ?></span>
					<span class="doo-jornadas-hero__stat-label"><?php echo esc_html( $stat2_label ); ?></span>
				</div>
				<div class="doo-jornadas-hero__stat">
					<span class="doo-jornadas-hero__stat-value"><?php echo esc_html( $stat3_value ); ?></span>
					<span class="doo-jornadas-hero__stat-label"><?php echo esc_html( $stat3_label ); ?></span>
				</div>
			</div>
		</div>
	</div>
</section>
