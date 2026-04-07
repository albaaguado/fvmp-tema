<?php
/**
 * Block render: doo/area-personal.
 *
 * Renders the personal area with tabbed sections for account data,
 * work data, and password change. Tab switching is handled via URL hash.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

// =============================================================================
// Current user data
// =============================================================================

$current_user = wp_get_current_user();
$username     = is_object( $current_user ) ? esc_attr( $current_user->user_login ) : '';
$first_name   = is_object( $current_user ) ? esc_attr( $current_user->first_name ) : '';
$last_name    = is_object( $current_user ) ? esc_attr( $current_user->last_name ) : '';
$display_name = is_object( $current_user ) ? esc_html( $current_user->display_name ) : '';

// =============================================================================
// Select options — Datos laborales
// =============================================================================

$opts_perfil_laboral = array(
	'cargo-electo'               => 'Cargo electo',
	'equipos-atencion-primaria'  => 'Equipos de Atención Primaria',
	'otros'                      => 'Otros',
);

/**
 * Categories per labour profile.
 * Key: profile value. Value: associative array of category value => label.
 * Used by JS to filter options when the profile changes.
 */
$categorias_por_perfil = array(
	'equipos-atencion-primaria' => array(
		'administrativo'              => 'Administrativo/a',
		'asesor-juridico'             => 'Asesor/a jurídico/a',
		'direccion-administrativa'    => 'Dirección administrativa',
		'agente-igualdad'             => 'Agente de igualdad',
		'educador-social'             => 'Educador/a social',
		'promotor-igualdad-basica'    => 'Promotor/a de igualdad (A.P.Básica)',
		'psicologo-basica'            => 'Psicólogo/a - pedagogo/a (A.P.Básica)',
		'tecnico-integracion'         => 'Técnico/a Integración Social',
		'tis-tasoc-barrios'           => 'TIS/TASOC Barrios Inclusivos',
		'trabajador-social'           => 'Trabajador/a social',
		'auxiliar-enfermeria'         => 'Auxiliar de enfermería',
		'auxiliar-enfermeria-dep'     => 'Auxiliar de enfermería (att. personas en situación de dependencia)',
		'enfermero'                   => 'Enfermero/a',
		'fisioterapeuta'              => 'Fisioterapeuta',
		'logopeda'                    => 'Logopeda',
		'medico'                      => 'Médico/a',
		'monitor-actividades'         => 'Monitor/a de actividades',
		'monitor-taller'              => 'Monitor/a de taller',
		'profesional-servicio'        => 'Profesional de servicio: comedor, vigilancia',
		'promotor-igualdad-especifica' => 'Promotor/a de igualdad (A.P.Específica)',
		'psicologo-especifica'        => 'Psicólogo/a - pedagogo/a (A.P.Específica)',
		'tasoc-superior'              => 'TASOC / Técnico/a superior de servicios socioculturales y a la comunidad',
		'tecnico-actividades-fisicas' => 'Técnico/a de actividades físicas y deportivas',
		'tecnico-educacion-infantil'  => 'Técnico/a superior de educación infantil',
		'terapeuta-ocupacional'       => 'Terapeuta ocupacional',
	),
);

// Flat list of all categories (shown when no profile selected or for profiles without specific list).
$opts_categoria = array_merge( ...array_values( $categorias_por_perfil ) );

$opts_provincia = array(
	'alicante'  => 'Alicante',
	'castellon' => 'Castellón',
	'valencia'  => 'Valencia',
);

$opts_titulacion = array(
	'universitaria-2-ciclos' => 'Estudios universitarios de 2 ciclos o equivalentes',
	'universitaria-1-ciclo'  => 'Estudios universitarios de 1 ciclo o equivalentes',
	'bachillerato-fp'        => 'Bachillerato, Enseñanzas profesionales FP I- FP II o equivalentes',
	'eso-egb'                => 'Enseñanza general secundaria (ESO, EGB, ...)',
	'primarios'              => 'Estudios primarios',
);

$opts_grupo_trabajo = array(
	'a1'              => 'A1',
	'a2'              => 'A2',
	'c1'              => 'C1',
	'c2'              => 'C2',
	'otras-ag-prof'   => 'Otras Ag.Prof.',
);

$opts_relacion_juridica = array(
	'funcionario-carrera'   => 'Funcionario de carrera',
	'funcionario-interino'  => 'Funcionario interino',
	'laboral-fijo'          => 'Laboral fijo',
	'laboral-eventual'      => 'Laboral eventual',
);

// =============================================================================
// SVG icon helpers
// =============================================================================

/**
 * Return inline SVG for the user-round (account) icon.
 *
 * @return string SVG markup.
 */
if ( ! function_exists( 'doo_ap_icon_user' ) ) :
function doo_ap_icon_user() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>';
}
endif;

/**
 * Return inline SVG for the briefcase (work data) icon.
 *
 * @return string SVG markup.
 */
if ( ! function_exists( 'doo_ap_icon_briefcase' ) ) :
function doo_ap_icon_briefcase() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="17"/><line x1="9" y1="14.5" x2="15" y2="14.5"/></svg>';
}
endif;

/**
 * Return inline SVG for the settings (password) icon.
 *
 * @return string SVG markup.
 */
if ( ! function_exists( 'doo_ap_icon_settings' ) ) :
function doo_ap_icon_settings() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>';
}
endif;

/**
 * Return inline SVG for the chevron-right (sidebar nav) icon.
 *
 * @return string SVG markup.
 */
if ( ! function_exists( 'doo_ap_icon_chevron_right' ) ) :
function doo_ap_icon_chevron_right() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 18l6-6-6-6"/></svg>';
}
endif;

/**
 * Return inline SVG for the chevron-down (select fields) icon.
 *
 * @return string SVG markup.
 */
if ( ! function_exists( 'doo_ap_icon_chevron_down' ) ) :
function doo_ap_icon_chevron_down() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M6 9l6 6 6-6"/></svg>';
}
endif;

// =============================================================================
// Helper: render a text / tel / email / date / password input field
// =============================================================================

/**
 * Render a standard input field (text, tel, date, password, etc.).
 *
 * @param string $id       Field ID (without prefix).
 * @param string $label    Field label.
 * @param string $type     Input type attribute.
 * @param string $name     Input name attribute.
 * @param string $value    Pre-filled value.
 * @param bool   $required Whether the field is required.
 */
if ( ! function_exists( 'doo_ap_input_field' ) ) :
function doo_ap_input_field( $id, $label, $type, $name, $value = '', $required = false ) {
	$field_id = 'doo-ap-' . esc_attr( $id );
	?>
	<div class="doo-area-personal__field">
		<label class="doo-area-personal__label" for="<?php echo $field_id; ?>">
			<?php echo esc_html( $label ); ?>
			<?php if ( $required ) : ?>
				<span class="doo-area-personal__required" aria-hidden="true">*</span>
			<?php endif; ?>
		</label>
		<input
			class="doo-area-personal__input"
			type="<?php echo esc_attr( $type ); ?>"
			id="<?php echo $field_id; ?>"
			name="<?php echo esc_attr( $name ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo $required ? 'required' : ''; ?>
		>
	</div>
	<?php
}
endif;

// =============================================================================
// Helper: render a select field
// =============================================================================

/**
 * Render a select field wrapped in the chevron-down container.
 *
 * @param string $id          Field ID (without prefix).
 * @param string $label       Field label. Pass empty string to omit the label element.
 * @param string $name        Input name attribute.
 * @param string $placeholder Placeholder option text.
 * @param array  $options     Associative array of value => label option pairs.
 * @param bool   $required    Whether the field is required.
 * @param array  $attrs       Extra HTML attributes for the <select> element.
 */
if ( ! function_exists( 'doo_ap_select_field' ) ) :
function doo_ap_select_field( $id, $label, $name, $placeholder, $options = array(), $required = false, $attrs = array() ) {
	$field_id   = 'doo-ap-' . esc_attr( $id );
	$extra_attr = '';
	foreach ( $attrs as $attr_key => $attr_val ) {
		$extra_attr .= ' ' . esc_attr( $attr_key ) . '="' . esc_attr( $attr_val ) . '"';
	}
	?>
	<div class="doo-area-personal__field">
		<?php if ( '' !== $label ) : ?>
			<label class="doo-area-personal__label" for="<?php echo $field_id; ?>">
				<?php echo esc_html( $label ); ?>
				<?php if ( $required ) : ?>
					<span class="doo-area-personal__required" aria-hidden="true">*</span>
				<?php endif; ?>
			</label>
		<?php endif; ?>
		<div class="doo-area-personal__select-wrap">
			<select
				class="doo-area-personal__input doo-area-personal__input--select"
				id="<?php echo $field_id; ?>"
				name="<?php echo esc_attr( $name ); ?>"
				<?php echo $required ? 'required' : ''; ?>
				<?php echo $extra_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			>
				<option value="" disabled selected><?php echo esc_html( $placeholder ); ?></option>
				<?php foreach ( $options as $val => $lbl ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $lbl ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php echo doo_ap_icon_chevron_down(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
	<?php
}
endif;
?>

<section class="doo-area-personal">

	<!-- ===================================================================
		 Page header
	=================================================================== -->
	<div class="doo-area-personal__page-header">
		<h1 class="doo-area-personal__page-title"><?php esc_html_e( 'Área Personal', 'dw-tema' ); ?></h1>
	</div>

	<!-- ===================================================================
		 Two-column layout
	=================================================================== -->
	<div class="doo-area-personal__layout">

		<!-- Sidebar -->
		<aside class="doo-area-personal__sidebar">

			<div class="doo-area-personal__sb-user">
				<span class="doo-area-personal__sb-name"><?php echo $display_name; ?></span>
				<a class="doo-area-personal__sb-profile" href="<?php echo esc_url( get_edit_profile_url() ); ?>">
					<?php esc_html_e( 'Ver el perfil', 'dw-tema' ); ?>
				</a>
			</div>

			<div class="doo-area-personal__sb-divider"></div>

			<nav class="doo-area-personal__sb-nav" aria-label="<?php esc_attr_e( 'Área Personal', 'dw-tema' ); ?>">

				<button type="button" class="doo-area-personal__sb-item" data-panel="cuenta">
					<span class="doo-area-personal__sb-item-left">
						<?php echo doo_ap_icon_user(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'Cuenta', 'dw-tema' ); ?>
					</span>
					<?php echo doo_ap_icon_chevron_right(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>

				<button type="button" class="doo-area-personal__sb-item" data-panel="laboral">
					<span class="doo-area-personal__sb-item-left">
						<?php echo doo_ap_icon_briefcase(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'Datos Laborales', 'dw-tema' ); ?>
					</span>
					<?php echo doo_ap_icon_chevron_right(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>

				<button type="button" class="doo-area-personal__sb-item" data-panel="contrasena">
					<span class="doo-area-personal__sb-item-left">
						<?php echo doo_ap_icon_settings(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'Cambiar la contraseña', 'dw-tema' ); ?>
					</span>
					<?php echo doo_ap_icon_chevron_right(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>

			</nav>
		</aside>

		<!-- Right content area -->
		<div class="doo-area-personal__content">

			<!-- ===============================================================
				 Panel 1 — Cuenta
			=============================================================== -->
			<div class="doo-area-personal__panel" data-panel="cuenta">

				<div class="doo-area-personal__panel-header">
					<?php echo doo_ap_icon_user(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h2 class="doo-area-personal__panel-title"><?php esc_html_e( 'Cuenta', 'dw-tema' ); ?></h2>
				</div>

				<div class="doo-area-personal__panel-divider"></div>

				<form
					class="doo-area-personal__form"
					id="doo-ap-cuenta-form"
					method="post"
					novalidate
				>
					<?php wp_nonce_field( 'doo_area_personal_cuenta', 'doo_ap_cuenta_nonce' ); ?>

					<?php
					doo_ap_input_field( 'username', 'Nombre de usuario', 'text', 'username', $username, true );
					doo_ap_input_field( 'nombre', 'Nombre', 'text', 'nombre', $first_name, false );
					doo_ap_input_field( 'apellidos', 'Apellidos', 'text', 'apellidos', $last_name, false );
					doo_ap_input_field( 'telefono', 'Teléfono', 'tel', 'telefono', '', false );
					doo_ap_input_field( 'nacimiento', 'Fecha de nacimiento', 'date', 'nacimiento', '', false );
					?>

					<div class="doo-area-personal__field">
						<fieldset class="doo-area-personal__fieldset">
							<legend class="doo-area-personal__label"><?php esc_html_e( 'Sexo', 'dw-tema' ); ?></legend>
							<div class="doo-area-personal__radio-group">
								<label class="doo-area-personal__radio-item">
									<input type="radio" name="sexo" value="H">
									<?php esc_html_e( 'Hombre', 'dw-tema' ); ?>
								</label>
								<label class="doo-area-personal__radio-item">
									<input type="radio" name="sexo" value="M">
									<?php esc_html_e( 'Mujer', 'dw-tema' ); ?>
								</label>
							</div>
						</fieldset>
					</div>

					<div class="doo-area-personal__actions">
						<button type="submit" class="doo-area-personal__btn">
							<?php esc_html_e( 'Actualizar cuenta', 'dw-tema' ); ?>
						</button>
					</div>

				</form>
			</div>

			<!-- ===============================================================
				 Panel 2 — Datos laborales
			=============================================================== -->
			<div class="doo-area-personal__panel" data-panel="laboral">

				<div class="doo-area-personal__panel-header">
					<?php echo doo_ap_icon_briefcase(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h2 class="doo-area-personal__panel-title"><?php esc_html_e( 'Datos laborales', 'dw-tema' ); ?></h2>
				</div>

				<div class="doo-area-personal__panel-divider"></div>

				<form
					class="doo-area-personal__form"
					id="doo-ap-laboral-form"
					method="post"
					novalidate
				>
					<?php wp_nonce_field( 'doo_area_personal_laboral', 'doo_ap_laboral_nonce' ); ?>

					<?php
					doo_ap_select_field( 'perfil-laboral', 'Perfil laboral', 'perfil_laboral', 'Perfil laboral', $opts_perfil_laboral, true, array( 'data-perfil-select' => '1' ) );
					doo_ap_select_field( 'categoria', 'Categoría profesional', 'categoria_profesional', 'Seleccione categoría profesional', $opts_categoria, false, array( 'data-category-select' => '1' ) );
					doo_ap_input_field( 'puesto', 'Puesto de trabajo', 'text', 'puesto_trabajo', '', false );
					doo_ap_input_field( 'centro', 'Centro de trabajo', 'text', 'centro_trabajo', '', true );
					doo_ap_input_field( 'direccion', 'Dirección', 'text', 'direccion', '', true );
					doo_ap_select_field( 'provincia', 'Provincia', 'provincia', 'Seleccione provincia', $opts_provincia, true );
					doo_ap_select_field( 'comarca', 'Comarca', 'comarca', 'Seleccione comarca', array(), true );
					doo_ap_select_field( 'municipio', 'Municipio', 'municipio', 'Seleccione municipio', array(), true );
					doo_ap_input_field( 'cp', 'CP', 'text', 'cp', '', true );
					doo_ap_input_field( 'extension', 'Extensión', 'text', 'extension', '', false );
					doo_ap_input_field( 'telefono-laboral', 'Teléfono', 'tel', 'telefono_laboral', '', true );
					doo_ap_select_field( 'titulacion', 'Titulación', 'titulacion', 'Seleccione titulación', $opts_titulacion, true );
					doo_ap_select_field( 'grupo-trabajo', 'Grupo de trabajo', 'grupo_trabajo', 'Seleccione grupo de trabajo', $opts_grupo_trabajo, true );
					doo_ap_select_field( 'relacion-juridica', 'Relación Jurídica', 'relacion_juridica', 'Seleccione relación jurídica', $opts_relacion_juridica, true );
					?>

					<?php
					// Pass categories-per-profile map to JS as inline JSON.
					$categories_json = wp_json_encode( $categorias_por_perfil );
					?>
					<script>
					( function() {
						var cats    = <?php echo $categories_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
						var allCats = <?php echo wp_json_encode( $opts_categoria ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
						var perfilSel = document.getElementById( 'doo-ap-perfil-laboral' );
						var catSel    = document.getElementById( 'doo-ap-categoria' );
						if ( ! perfilSel || ! catSel ) return;

						function rebuildCategories( profileKey ) {
							while ( catSel.options.length > 1 ) catSel.remove( 1 );
							var opts = ( profileKey && cats[ profileKey ] ) ? cats[ profileKey ] : allCats;
							Object.keys( opts ).forEach( function( val ) {
								var opt = document.createElement( 'option' );
								opt.value = val;
								opt.textContent = opts[ val ];
								catSel.appendChild( opt );
							} );
							catSel.value = '';
							catSel.dispatchEvent( new Event( 'change', { bubbles: true } ) );
						}

						perfilSel.addEventListener( 'change', function() {
							rebuildCategories( this.value );
						} );
					} )();
					</script>

					<div class="doo-area-personal__actions">
						<button type="submit" class="doo-area-personal__btn">
							<?php esc_html_e( 'Actualizar cuenta', 'dw-tema' ); ?>
						</button>
					</div>

				</form>
			</div>

			<!-- ===============================================================
				 Panel 3 — Cambiar la contraseña
			=============================================================== -->
			<div class="doo-area-personal__panel" data-panel="contrasena">

				<div class="doo-area-personal__panel-header">
					<?php echo doo_ap_icon_settings(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h2 class="doo-area-personal__panel-title"><?php esc_html_e( 'Cambiar la contraseña', 'dw-tema' ); ?></h2>
				</div>

				<div class="doo-area-personal__panel-divider"></div>

				<form
					class="doo-area-personal__form"
					id="doo-ap-password-form"
					method="post"
					novalidate
				>
					<?php wp_nonce_field( 'doo_area_personal_password', 'doo_ap_password_nonce' ); ?>

					<?php
					doo_ap_input_field( 'current-password', 'Contraseña actual', 'password', 'current_password', '', true );
					doo_ap_input_field( 'new-password', 'Nueva contraseña', 'password', 'new_password', '', true );
					doo_ap_input_field( 'confirm-password', 'Confirmar nueva contraseña', 'password', 'confirm_password', '', true );
					?>

					<div class="doo-area-personal__actions">
						<button type="submit" class="doo-area-personal__btn">
							<?php esc_html_e( 'Actualizar contraseña', 'dw-tema' ); ?>
						</button>
					</div>

				</form>
			</div>

		</div><!-- .doo-area-personal__content -->
	</div><!-- .doo-area-personal__layout -->

</section><!-- .doo-area-personal -->
