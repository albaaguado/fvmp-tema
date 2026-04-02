<?php
/**
 * Block render: doo/registro.
 *
 * Renders the registration form using the field configuration stored in block
 * attributes. Falls back to sensible defaults when no attributes are saved yet.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

// =============================================================================
// Default field definitions (mirrors edit.jsx defaults)
// =============================================================================

$default_fields_personal = array(
	array( 'id' => 'nombre',     'label' => 'Nombre',               'placeholder' => 'Nombre',                        'type' => 'text',     'required' => true,  'fullWidth' => false ),
	array( 'id' => 'dni',        'label' => 'DNI - NIE',             'placeholder' => 'DNI - NIE',                     'type' => 'text',     'required' => true,  'fullWidth' => false ),
	array( 'id' => 'apellidos',  'label' => 'Apellidos',             'placeholder' => 'Apellidos',                     'type' => 'text',     'required' => true,  'fullWidth' => false ),
	array( 'id' => 'nacimiento', 'label' => 'Fecha de nacimiento',   'placeholder' => 'dd/mm/aaaa',                    'type' => 'text',     'required' => true,  'fullWidth' => false ),
	array( 'id' => 'email',      'label' => 'Correo electrónico',    'placeholder' => 'Correo electrónico',            'type' => 'email',    'required' => true,  'fullWidth' => false ),
	array( 'id' => 'telefono',   'label' => 'Teléfono',              'placeholder' => 'Número de teléfono',            'type' => 'tel',      'required' => true,  'fullWidth' => false ),
	array( 'id' => 'password',   'label' => 'Contraseña',            'placeholder' => 'Introduce contraseña',          'type' => 'password', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'sexo',       'label' => 'Sexo',                  'placeholder' => '',                              'type' => 'radio',    'required' => true,  'fullWidth' => false ),
);

$default_fields_laboral = array(
	array( 'id' => 'perfil',     'label' => 'Perfil laboral',          'placeholder' => 'Perfil laboral',                    'type' => 'select', 'required' => true,  'fullWidth' => true  ),
	array( 'id' => 'categoria',  'label' => 'Categoría profesional',   'placeholder' => 'Seleccione categoría profesional',  'type' => 'select', 'required' => true,  'fullWidth' => true  ),
	array( 'id' => 'puesto',     'label' => 'Puesto de trabajo',       'placeholder' => 'Introduzca su puesto de trabajo',   'type' => 'text',   'required' => true,  'fullWidth' => true  ),
	array( 'id' => 'centro',     'label' => 'Centro de trabajo',       'placeholder' => 'Centro de trabajo',                 'type' => 'text',   'required' => true,  'fullWidth' => true  ),
	array( 'id' => 'direccion',  'label' => 'Dirección',               'placeholder' => 'Dirección',                         'type' => 'text',   'required' => true,  'fullWidth' => true  ),
	array( 'id' => 'provincia',  'label' => 'Provincia',               'placeholder' => 'Provincia',                         'type' => 'select', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'extension',  'label' => 'Extensión',               'placeholder' => '',                                  'type' => 'text',   'required' => false, 'fullWidth' => false ),
	array( 'id' => 'comarca',    'label' => 'Comarca',                 'placeholder' => 'Seleccione o busque una comarca',   'type' => 'select', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'titulacion', 'label' => 'Titulación',              'placeholder' => 'Seleccione titulación',             'type' => 'select', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'municipio',  'label' => 'Municipio',               'placeholder' => 'Seleccione o busque un municipio', 'type' => 'select', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'grupo',      'label' => 'Grupo de trabajo',        'placeholder' => 'Seleccione grupo de trabajo',       'type' => 'select', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'cp',         'label' => 'CP',                      'placeholder' => '',                                  'type' => 'text',   'required' => true,  'fullWidth' => false ),
	array( 'id' => 'relacion',   'label' => 'Relación Jurídica',       'placeholder' => 'Seleccione relación jurídica',      'type' => 'select', 'required' => true,  'fullWidth' => false ),
	array( 'id' => 'tel_trabajo', 'label' => 'Teléfono de trabajo',    'placeholder' => '',                                  'type' => 'tel',    'required' => false, 'fullWidth' => true  ),
);

// =============================================================================
// Resolve attributes
// =============================================================================

$section_personal_title = isset( $attributes['sectionPersonalTitle'] ) && '' !== $attributes['sectionPersonalTitle']
	? $attributes['sectionPersonalTitle']
	: 'Datos personales';

$section_laboral_title = isset( $attributes['sectionLaboralTitle'] ) && '' !== $attributes['sectionLaboralTitle']
	? $attributes['sectionLaboralTitle']
	: 'Datos laborales';

$fields_personal = ( ! empty( $attributes['fieldsPersonal'] ) && is_array( $attributes['fieldsPersonal'] ) )
	? $attributes['fieldsPersonal']
	: $default_fields_personal;

$fields_laboral = ( ! empty( $attributes['fieldsLaboral'] ) && is_array( $attributes['fieldsLaboral'] ) )
	? $attributes['fieldsLaboral']
	: $default_fields_laboral;

$ajax_url  = esc_url( admin_url( 'admin-ajax.php' ) );
$nonce     = wp_create_nonce( 'doo_registro_nonce' );
$login_url = esc_url( wp_login_url() );

// =============================================================================
// Helper: group consecutive non-fullWidth fields in pairs
// =============================================================================

/**
 * Group fields for 2-column layout.
 *
 * @param array $fields List of field arrays.
 * @return array Array of rows, each row containing 1 or 2 field arrays.
 */
if ( ! function_exists( 'doo_registro_group_fields' ) ) :
function doo_registro_group_fields( array $fields ) {
	$rows = array();
	$i    = 0;
	$len  = count( $fields );

	while ( $i < $len ) {
		$f = $fields[ $i ];
		if ( ! empty( $f['fullWidth'] ) ) {
			$rows[] = array( $f );
			$i++;
		} elseif ( ( $i + 1 ) < $len && empty( $fields[ $i + 1 ]['fullWidth'] ) ) {
			$rows[] = array( $f, $fields[ $i + 1 ] );
			$i      += 2;
		} else {
			$rows[] = array( $f );
			$i++;
		}
	}

	return $rows;
}
endif;

// =============================================================================
// Helper: render a single field's HTML
// =============================================================================

/**
 * Render the input element for a field.
 *
 * @param array $field Field configuration array.
 */
if ( ! function_exists( 'doo_registro_render_input' ) ) :
function doo_registro_render_input( array $field ) {
	$id          = 'doo-' . esc_attr( $field['id'] );
	$name        = 'doo_' . esc_attr( $field['id'] );
	$placeholder = isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '';
	$required    = ! empty( $field['required'] );
	$type        = isset( $field['type'] ) ? $field['type'] : 'text';

	switch ( $type ) {

		case 'radio':
			?>
			<div class="doo-registro__radio-group">
				<label class="doo-registro__radio-label">
					<input class="doo-registro__radio" type="radio" name="<?php echo esc_attr( $name ); ?>" value="H"<?php echo $required ? ' required' : ''; ?>>
					<span class="doo-registro__radio-dot"></span>
					<?php esc_html_e( 'Hombre', 'dw-tema' ); ?>
				</label>
				<label class="doo-registro__radio-label">
					<input class="doo-registro__radio" type="radio" name="<?php echo esc_attr( $name ); ?>" value="M">
					<span class="doo-registro__radio-dot"></span>
					<?php esc_html_e( 'Mujer', 'dw-tema' ); ?>
				</label>
			</div>
			<?php
			break;

		case 'select':
			?>
			<div class="doo-registro__select-wrap">
				<select
					class="doo-registro__input doo-registro__input--select"
					id="<?php echo $id; ?>"
					name="<?php echo esc_attr( $name ); ?>"
					<?php echo $required ? 'required' : ''; ?>
				>
					<option value="" disabled selected><?php echo esc_html( $field['placeholder'] ?? $field['label'] ); ?></option>
				</select>
			</div>
			<?php
			break;

		case 'textarea':
			?>
			<textarea
				class="doo-registro__input doo-registro__input--textarea"
				id="<?php echo $id; ?>"
				name="<?php echo esc_attr( $name ); ?>"
				placeholder="<?php echo $placeholder; ?>"
				<?php echo $required ? 'required' : ''; ?>
				rows="4"
			></textarea>
			<?php
			break;

		default:
			// text, email, tel, password, date.
			$input_type = in_array( $type, array( 'text', 'email', 'tel', 'password', 'date' ), true ) ? $type : 'text';
			?>
			<input
				class="doo-registro__input"
				type="<?php echo esc_attr( $input_type ); ?>"
				id="<?php echo $id; ?>"
				name="<?php echo esc_attr( $name ); ?>"
				placeholder="<?php echo $placeholder; ?>"
				<?php echo $required ? 'required' : ''; ?>
			>
			<?php
			break;
	}
}
endif;

// =============================================================================
// Helper: render all fields in a section
// =============================================================================

/**
 * Render all fields for a form section.
 *
 * @param array $fields List of field arrays for the section.
 */
if ( ! function_exists( 'doo_registro_render_fields' ) ) :
function doo_registro_render_fields( array $fields ) {
	$rows = doo_registro_group_fields( $fields );

	foreach ( $rows as $row ) {
		$is_full = count( $row ) === 1 && ! empty( $row[0]['fullWidth'] );

		if ( $is_full ) {
			$f = $row[0];
			?>
			<div class="doo-registro__field doo-registro__field--full">
				<label class="doo-registro__label" for="doo-<?php echo esc_attr( $f['id'] ); ?>">
					<?php echo esc_html( $f['label'] ); ?>
					<?php if ( ! empty( $f['required'] ) ) : ?>
						<span class="doo-registro__required" aria-hidden="true">*</span>
					<?php endif; ?>
				</label>
				<?php doo_registro_render_input( $f ); ?>
			</div>
			<?php
		} else {
			?>
			<div class="doo-registro__row">
				<?php foreach ( $row as $f ) : ?>
					<div class="doo-registro__field">
						<?php if ( 'radio' !== $f['type'] ) : ?>
							<label class="doo-registro__label" for="doo-<?php echo esc_attr( $f['id'] ); ?>">
								<?php echo esc_html( $f['label'] ); ?>
								<?php if ( ! empty( $f['required'] ) ) : ?>
									<span class="doo-registro__required" aria-hidden="true">*</span>
								<?php endif; ?>
							</label>
						<?php else : ?>
							<fieldset class="doo-registro__fieldset">
								<legend class="doo-registro__label">
									<?php echo esc_html( $f['label'] ); ?>
									<?php if ( ! empty( $f['required'] ) ) : ?>
										<span class="doo-registro__required" aria-hidden="true">*</span>
									<?php endif; ?>
								</legend>
						<?php endif; ?>

						<?php doo_registro_render_input( $f ); ?>

						<?php if ( 'radio' === $f['type'] ) : ?>
							</fieldset>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}
}
endif;
?>

<section class="doo-registro">

	<div class="doo-registro__page-header">
		<h1 class="doo-registro__title"><?php esc_html_e( 'Registro', 'dw-tema' ); ?></h1>
	</div>

	<div class="doo-registro__body">
		<form
			class="doo-registro__card"
			id="doo-registro-form"
			method="post"
			action="<?php echo $ajax_url; ?>"
			novalidate
		>
			<input type="hidden" name="action" value="doo_registro_submit">
			<input type="hidden" name="doo_nonce" value="<?php echo esc_attr( $nonce ); ?>">

			<!-- Datos personales -->
			<div class="doo-registro__section">
				<h2 class="doo-registro__section-title"><?php echo esc_html( $section_personal_title ); ?></h2>
				<div class="doo-registro__section-bar"></div>
				<div class="doo-registro__fields">
					<?php doo_registro_render_fields( $fields_personal ); ?>
				</div>
			</div>

			<hr class="doo-registro__divider">

			<!-- Datos laborales -->
			<div class="doo-registro__section">
				<h2 class="doo-registro__section-title"><?php echo esc_html( $section_laboral_title ); ?></h2>
				<div class="doo-registro__section-bar"></div>
				<div class="doo-registro__fields">
					<?php doo_registro_render_fields( $fields_laboral ); ?>
				</div>
			</div>

			<hr class="doo-registro__divider">

			<!-- Privacidad -->
			<div class="doo-registro__privacy">
				<a class="doo-registro__privacy-link" href="#">
					<?php esc_html_e( 'Mostrar la política de privacidad', 'dw-tema' ); ?>
				</a>
				<label class="doo-registro__check-label">
					<input class="doo-registro__checkbox" type="checkbox" name="doo_privacidad" value="1" required>
					<span class="doo-registro__check-box"></span>
					<?php esc_html_e( 'Acepto los términos de privacidad de la FVMP', 'dw-tema' ); ?>
				</label>
			</div>

			<!-- Acciones -->
			<div class="doo-registro__actions">
				<button class="doo-registro__submit" type="submit">
					<?php esc_html_e( 'Enviar', 'dw-tema' ); ?>
				</button>
				<div class="doo-registro__login-row">
					<span class="doo-registro__login-text"><?php esc_html_e( '¿Ya tienes cuenta?', 'dw-tema' ); ?></span>
					<a class="doo-registro__login-link" href="<?php echo $login_url; ?>">
						<?php esc_html_e( 'Iniciar sesión', 'dw-tema' ); ?>
					</a>
				</div>
			</div>

		</form>
	</div>

</section>
