<?php
/**
 * Block render: doo/transparencia.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! function_exists( 'doo_transparencia_render_links' ) ) {
	/**
	 * @param array<string, string> $a Parsed attributes.
	 * @return void
	 */
	function doo_transparencia_render_links( $a ) {
		$svg1 = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
		$svg2 = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';
		?>
			<div class="doo-transparencia__right">

				<a class="doo-trans-link" href="<?php echo esc_url( $a['link1Url'] ); ?>">
					<div class="doo-trans-link__icon">
						<?php echo $svg1; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div>
						<p class="doo-trans-link__title"><?php echo esc_html( $a['link1Title'] ); ?></p>
						<p class="doo-trans-link__desc"><?php echo esc_html( $a['link1Desc'] ); ?></p>
					</div>
				</a>

				<a class="doo-trans-link" href="<?php echo esc_url( $a['link2Url'] ); ?>">
					<div class="doo-trans-link__icon">
						<?php echo $svg2; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div>
						<p class="doo-trans-link__title"><?php echo esc_html( $a['link2Title'] ); ?></p>
						<p class="doo-trans-link__desc"><?php echo esc_html( $a['link2Desc'] ); ?></p>
					</div>
				</a>

			</div>
		<?php
	}
}

$a = wp_parse_args(
	isset( $attributes ) && is_array( $attributes ) ? $attributes : array(),
	array(
		'eyebrow'     => 'TRANSPARENCIA',
		'title'       => 'Gestión transparente de la formación pública',
		'description' => 'En la FVMP estamos comprometidos con la transparencia en la gestión de todas las acciones formativas que desarrollamos para nuestros usuarios.',
		'link1Title'  => 'Criterios de selección del alumnado',
		'link1Desc'   => 'Consulta los criterios objetivos que rigen el acceso a los diferentes planes de formación.',
		'link1Url'    => '#',
		'link2Title'  => 'Memoria de evaluación 2024',
		'link2Desc'   => 'Resultados, valoraciones y estadísticas del plan formativo del año anterior.',
		'link2Url'    => '#',
	)
);

if ( ! empty( $attributes['renderOnlyLinks'] ) && ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
	doo_transparencia_render_links( $a );
	return;
}
?>
<section class="doo-transparencia">
	<div class="doo-transparencia__container">
		<div class="doo-transparencia__inner">

			<div class="doo-transparencia__left">
				<p class="doo-transparencia__eyebrow"><?php echo esc_html( $a['eyebrow'] ); ?></p>
				<h2 class="doo-transparencia__title"><?php echo esc_html( $a['title'] ); ?></h2>
				<p class="doo-transparencia__desc"><?php echo wp_kses_post( $a['description'] ); ?></p>
			</div>

			<?php doo_transparencia_render_links( $a ); ?>

		</div>
	</div>
</section>
