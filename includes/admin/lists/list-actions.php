<?php
/**
 * List Actions
 *
 * Used for adding fields to the add/edit list page and processing actions.
 *
 * @package   naked-mailing-list
 * @copyright Copyright (c) 2017, Ashley Gibson
 * @license   GPL2+
 * @since     1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Field: Name
 *
 * @param object $list Database object
 *
 * @since 1.0
 * @return void
 */
function nml_list_field_name( $list ) {
	?>
	<div class="nml-field">
		<label for="nml_list_name"><?php _e( 'Name', 'naked-mailing-list' ); ?></label>
		<input type="text" id="nml_list_name" class="regular-text" name="nml_list_name" value="<?php echo esc_attr( $list->name ); ?>" required>
	</div>
	<?php
}

add_action( 'nml_edit_list_fields', 'nml_list_field_name' );

/**
 * Field: Description
 *
 * @param object $list Database object
 *
 * @since 1.0
 * @return void
 */
function nml_list_field_description( $list ) {
	?>
	<div class="nml-field">
		<label for="nml_list_desc"><?php _e( 'Description', 'naked-mailing-list' ); ?></label>
		<textarea id="nml_list_desc" class="large-text" name="nml_list_desc" rows="5" cols="50"><?php echo esc_textarea( $list->description ); ?></textarea>
	</div>
	<?php
}

add_action( 'nml_edit_list_fields', 'nml_list_field_description' );

/**
 * Field: Type
 *
 * @param object $list Database object
 *
 * @since 1.0
 * @return void
 */
function nml_list_field_type( $list ) {
	?>
	<div class="nml-field">
		<label for="nml_list_type"><?php _e( 'Type', 'naked-mailing-list' ); ?></label>
		<select id="nml_list_type" name="nml_list_type">
			<option value="list" <?php selected( $list->type, 'list' ); ?>><?php esc_html_e( 'List', 'naked-mailing-list' ); ?></option>
			<option value="tag" <?php selected( $list->type, 'tag' ); ?>><?php esc_html_e( 'Tag', 'naked-mailing-list' ); ?></option>
		</select>
	</div>
	<?php
}

add_action( 'nml_edit_list_fields', 'nml_list_field_type' );

function nml_save_list() {

	$nonce = isset( $_POST['nml_save_list_nonce'] ) ? $_POST['nml_save_list_nonce'] : false;

	if ( ! $nonce ) {
		return;
	}

	if ( ! wp_verify_nonce( $nonce, 'nml_save_list' ) ) {
		wp_die( __( 'Failed security check.', 'naked-mailing-list' ) );
	}

	if ( ! current_user_can( 'edit_posts' ) ) { // @todo maybe change
		wp_die( __( 'You don\'t have permission to edit lists.', 'naked-mailing-list' ) );
	}

	$list_id = $_POST['list_id'];

	$data = array(
		'type' => 'list'
	);

	if ( isset( $_POST['list_id'] ) && ! empty( $_POST['list_id'] ) ) {
		$data['ID'] = intval( $_POST['list_id'] );
	}

	if ( isset( $_POST['nml_list_name'] ) ) {
		$data['name'] = sanitize_text_field( $_POST['nml_list_name'] );
	}

	if ( isset( $_POST['nml_list_desc'] ) ) {
		$data['description'] = wp_strip_all_tags( $_POST['nml_list_desc'] );
	}

	if ( isset( $_POST['nml_list_type'] ) && 'tag' == $_POST['nml_list_type'] ) {
		$data['type'] = 'tag';
	}

	$new_list_id = naked_mailing_list()->lists->add( $data );

	if ( ! $new_list_id || is_wp_error( $new_list_id ) ) {
		wp_die( __( 'An error occurred while inserting the list.', 'naked-mailing-list' ) );
	}

	$edit_url = add_query_arg( array(
		'nml-message' => 'list-updated'
	), nml_get_admin_page_edit_list( absint( $new_list_id ) ) );

	wp_safe_redirect( $edit_url );

	exit;

}

add_action( 'nml_save_list', 'nml_save_list' );