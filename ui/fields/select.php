<?php
$attributes             = array();
$attributes['tabindex'] = 2;

$pick_limit = (int) pods_v( $form_field_type . '_limit', $options, 0 );
$multiple   = false;

if ( 'multi' == pods_v( $form_field_type . '_format_type', $options ) && 1 != $pick_limit ) {
	$name .= '[]';
	$attributes['multiple'] = 'multiple';
	$multiple               = true;
}

if ( ! is_array( $options['data'] ) && false !== $options['data'] && 0 < strlen( $options['data'] ) ) {
	$options['data'] = implode( ',', $options['data'] );
} else {
	$options['data'] = (array) pods_var_raw( 'data', $options, array(), null, true );
}

$attributes = Pods_Form::merge_attributes( $attributes, $name, $form_field_type, $options );

if ( pods_v( 'readonly', $options, false ) ) {
	$attributes['readonly'] = 'READONLY';

	$attributes['class'] .= ' pods-form-ui-read-only';
}

$selection_made = false;
?>
<select<?php Pods_Form::attributes( $attributes, $name, $form_field_type, $options ); ?>>
	<?php
	foreach ( $options['data'] as $option_value => $option_label ) {
		if ( is_array( $option_label ) && isset( $option_label['label'] ) ) {
			$option_label = $option_label['label'];
		}

		if ( is_array( $option_label ) && ! isset( $option_label['disabled'] ) ) {
			?>
			<optgroup label="<?php echo esc_attr( $option_value ); ?>">
				<?php
				foreach ( $option_label as $sub_option_value => $sub_option_label ) {
					if ( is_array( $sub_option_label ) ) {
						if ( isset( $sub_option_label['label'] ) ) {
							$sub_option_label = $sub_option_label['label'];
						} else {
							$sub_option_label = $sub_option_value;
						}
					}

					$sub_option_label = (string) $sub_option_label;

					$selected            = '';
					$options['selected'] = '';

					if ( ! $selection_made && ( ( ! is_array( $value ) && (string) $sub_option_value === (string) $value ) || ( is_array( $value ) && ( in_array( $sub_option_value, $value ) || in_array( (string) $sub_option_value, $value ) ) ) ) ) {
						$selected            = ' SELECTED';
						$options['selected'] = 'selected';

						if ( ! $multiple ) {
							$selection_made = true;
						}
					}

					if ( is_array( $sub_option_value ) ) {
						?>
						<option<?php Pods_Form::attributes( $sub_option_value, $name, $form_field_type . '_option', $options ); ?>><?php echo esc_html( $sub_option_label ); ?></option>
					<?php
					} else {
						?>
						<option value="<?php echo esc_attr( $sub_option_value ); ?>"<?php echo $selected; ?>><?php echo esc_html( $sub_option_label ); ?></option>
					<?php
					}
				}
				?>
			</optgroup>
		<?php
		} else {
			$selected = $options['selected'] = '';

			if ( is_array( $option_label ) && isset( $option_label['label'] ) ) {
				if ( isset( $option_label['disabled'] ) && $option_label['disabled'] ) {
					$selected .= ' DISABLED';
					$options['disabled'] = 'disabled';
				}

				$option_label = $option_label['label'];
			}

			$option_label = (string) $option_label;

			if ( ! $selection_made && ( ( ! is_array( $value ) && (string) $option_value === (string) $value ) || ( is_array( $value ) && ( in_array( $option_value, $value ) || in_array( (string) $option_value, $value ) ) ) ) ) {
				$selected .= ' SELECTED';
				$options['selected'] = 'selected';

				if ( ! $multiple ) {
					$selection_made = true;
				}
			}

			if ( is_array( $option_value ) ) {
				?>
				<option<?php Pods_Form::attributes( $option_value, $name, $form_field_type . '_option', $options ); ?>><?php echo esc_html( $option_label ); ?></option>
			<?php
			} else {
				?>
				<option value="<?php echo esc_attr( $option_value ); ?>"<?php echo $selected; ?>><?php echo esc_html( $option_label ); ?></option>
			<?php
			}
		}
	}
	?>
</select>