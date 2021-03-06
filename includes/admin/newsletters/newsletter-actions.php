<?php
/**
 * Newsletter Actions
 *
 * Used for adding fields to the add/edit newsletter page and processing actions.
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
 * Field: Subject
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_field_subject( $newsletter ) {
	?>
	<div class="nml-field">
		<label for="nml_newsletter_subject"><?php _e( 'Subject', 'naked-mailing-list' ); ?></label>
		<input type="text" id="nml_newsletter_subject" class="regular-text" name="nml_newsletter_subject" value="<?php echo esc_attr( $newsletter->subject ); ?>" required>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_info_fields', 'nml_newsletter_field_subject' );

/**
 * Field: Body
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_field_body( $newsletter ) {
	?>
	<div class="nml-field">
		<label for="nml_newsletter_body"><?php _e( 'Body', 'naked-mailing-list' ); ?></label>
		<?php
		wp_editor( $newsletter->body, 'nml_newsletter_body', array(
			'textarea_name' => 'nml_newsletter_body',
			'editor_class'  => 'large-text'
		) );
		?>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_info_fields', 'nml_newsletter_field_body' );

/**
 * Field: From Name
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_field_from_name( $newsletter ) {
	$from_name = ! empty( $newsletter->from_name ) ? $newsletter->from_name : nml_get_option( 'from_name' );
	?>
	<div class="nml-field">
		<label for="nml_newsletter_from_name"><?php _e( 'From Name', 'naked-mailing-list' ); ?></label>
		<input type="text" id="nml_newsletter_from_name" class="regular-text" name="nml_newsletter_from_name" value="<?php echo esc_attr( $from_name ); ?>" required>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_headers_fields', 'nml_newsletter_field_from_name' );

/**
 * Field: From Address
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_field_from_address( $newsletter ) {
	$from_address = ! empty( $newsletter->from_address ) ? $newsletter->from_address : nml_get_option( 'from_email' );
	?>
	<div class="nml-field">
		<label for="nml_newsletter_from_address"><?php _e( 'From Address', 'naked-mailing-list' ); ?></label>
		<input type="email" id="nml_newsletter_from_address" class="regular-text" name="nml_newsletter_from_address" value="<?php echo esc_attr( $from_address ); ?>" required>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_headers_fields', 'nml_newsletter_field_from_address' );

/**
 * Field: Reply-To Name
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_field_reply_to_name( $newsletter ) {
	$reply_to_name = ! empty( $newsletter->reply_to_name ) ? $newsletter->reply_to_name : nml_get_option( 'reply_to_name' );
	?>
	<div class="nml-field">
		<label for="nml_newsletter_reply_to_name"><?php _e( 'Reply-To Name', 'naked-mailing-list' ); ?></label>
		<input type="text" id="nml_newsletter_reply_to_name" class="regular-text" name="nml_newsletter_reply_to_name" value="<?php echo esc_attr( $reply_to_name ); ?>" required>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_headers_fields', 'nml_newsletter_field_reply_to_name' );

/**
 * Field: Reply-To Address
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_field_reply_to_address( $newsletter ) {
	$reply_to_address = ! empty( $newsletter->reply_to_address ) ? $newsletter->reply_to_address : nml_get_option( 'reply_to_email' );
	?>
	<div class="nml-field">
		<label for="nml_newsletter_reply_to_address"><?php _e( 'Reply-To Address', 'naked-mailing-list' ); ?></label>
		<input type="email" id="nml_newsletter_reply_to_address" class="regular-text" name="nml_newsletter_reply_to_address" value="<?php echo esc_attr( $reply_to_address ); ?>" required>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_headers_fields', 'nml_newsletter_field_reply_to_address' );

/**
 * Field: Lists
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_selected_lists_field( $newsletter ) {
	$newsletter_lists    = $newsletter->get_lists( 'list' );
	$selected_list_names = wp_list_pluck( $newsletter_lists, 'name' );

	$all_lists = nml_get_lists( array(
		'number'  => - 1,
		'type'    => 'list',
		'fields'  => 'names',
		'orderby' => 'name',
		'order'   => 'ASC'
	) );
	?>
	<div class="nml-field nml-lists-wrap">
		<div class="nml-multicheck-wrap" data-name="nml_newsletter_lists[]">
			<?php foreach ( $all_lists as $list_name ) :
				$checked = in_array( $list_name, $selected_list_names ) ? ' checked="checked"' : '';
				?>
				<label for="nml_newsletter_lists_<?php echo sanitize_html_class( $list_name ); ?>">
					<input type="checkbox" name="nml_newsletter_lists[]" id="nml_newsletter_lists_<?php echo sanitize_html_class( $list_name ); ?>" value="<?php echo esc_attr( $list_name ); ?>"<?php echo $checked; ?>>
					<?php echo esc_html( $list_name ); ?>
				</label>
			<?php endforeach; ?>
		</div>

		<div class="nml-add-new-list">
			<label for="nml-add-new-list" class="screen-reader-text"><?php esc_html__( 'Enter the name of the new list', 'naked-mailing-list' ); ?></label>
			<input type="text" id="nml-add-new-list" name="nml_new_list" class="regular-text nml-new-list-value">
			<input type="button" class="button" value="<?php esc_attr_e( 'Add', 'naked-mailing-list' ); ?>">
		</div>
	</div>
	<?php
}

add_action( 'nml_edit_newsletter_lists_box', 'nml_newsletter_selected_lists_field' );

/**
 * Field: Tags
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_tags_box( $newsletter ) {

	$newsletter_tags = $newsletter->get_tags();
	$names           = wp_list_pluck( $newsletter_tags, 'name' );
	?>
	<div class="nml-field nml-tags-wrap">
		<div class="jaxtag">
			<div class="nojs-tags hide-if-js">
				<label for="nml_tags"><?php _e( 'Tags', 'naked-mailing-list' ); ?></label>
				<textarea id="nml_tags" name="nml_newsletter_tags" rows="5" cols="50"><?php echo esc_textarea( implode( ', ', $names ) ); ?></textarea>
			</div>
		</div>
		<div class="nml-ajaxtag hide-if-no-js">
			<p>
				<label for="nml_new_tag" class="screen-reader-text"><?php _e( 'Add a new tag to the subscriber', 'naked-mailing-list' ); ?></label>
				<input type="text" id="nml_new_tag" name="nml_new_tag" class="form-input-tip regular-text nml-new-tag" size="16" autocomplete="off" value="">
				<input type="button" class="button" value="<?php esc_attr_e( 'Add', 'naked-mailing-list' ); ?>" tabindex="3">
			</p>
		</div>
		<p class="description"><?php _e( 'Separate tags with commas', 'naked-mailing-list' ); ?></p>
		<div class="nml-tags-checklist"></div>
	</div>
	<?php

}

add_action( 'nml_edit_newsletter_tags_box', 'nml_newsletter_tags_box' );

/**
 * List of template tags
 *
 * @param NML_Newsletter $newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_newsletter_template_tags_list( $newsletter ) {
	?>
	<p><?php _e( 'The following template tags may be used:', 'naked-mailing-list' ); ?></p>
	<ul>
		<?php if ( 'post_notification' == $newsletter->type || ( 'add' == $_GET['view'] && isset( $_GET['type'] ) && 'post_notification' == $_GET['type'] ) ) : ?>
			<li>
				<em>%latest_post%</em> - <?php _e( 'Display full contents of latest post.', 'naked-mailing-list' ); ?>
			</li>
		<?php endif; ?>
	</ul>
	<?php
}

add_action( 'nml_edit_newsletter_template_tags_box', 'nml_newsletter_template_tags_list' );

/**
 * Save Newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_save_newsletter() {

	$nonce = isset( $_POST['nml_save_newsletter_nonce'] ) ? $_POST['nml_save_newsletter_nonce'] : false;

	if ( ! $nonce ) {
		return;
	}

	if ( ! wp_verify_nonce( $nonce, 'nml_save_newsletter' ) ) {
		wp_die( __( 'Failed security check.', 'naked-mailing-list' ) );
	}

	if ( ! current_user_can( 'edit_posts' ) ) { // @todo maybe change
		wp_die( __( 'You don\'t have permission to edit newsletters.', 'naked-mailing-list' ) );
	}

	$newsletter_id = $_POST['newsletter_id'];

	$data = array();

	if ( ! empty( $newsletter_id ) ) {
		$data['ID'] = absint( $newsletter_id );
	}

	$fields = array(
		// @todo more
		'subject'          => 'nml_newsletter_subject',
		'body'             => 'nml_newsletter_body',
		'from_name'        => 'nml_newsletter_from_name',
		'from_address'     => 'nml_newsletter_from_address',
		'reply_to_name'    => 'nml_newsletter_reply_to_name',
		'reply_to_address' => 'nml_newsletter_reply_to_address'
	);

	foreach ( $fields as $data_field => $post_name ) {
		if ( isset( $_POST[ $post_name ] ) ) {
			$data[ $data_field ] = $_POST[ $post_name ];
		}
	}

	// Add lists.
	if ( ! empty( $_POST['nml_newsletter_lists'] ) ) {
		if ( is_array( $_POST['nml_newsletter_lists'] ) ) {
			$list_array = $_POST['nml_newsletter_lists'];
		} else {
			$list_array = $_POST['nml_newsletter_lists'] ? explode( ',', $_POST['nml_newsletter_lists'] ) : array();
		}

		$data['lists'] = array_map( 'trim', $list_array );
	}

	// Add tags.
	if ( ! empty( $_POST['nml_newsletter_tags'] ) ) {
		if ( is_array( $_POST['nml_newsletter_tags'] ) ) {
			$tag_array = $_POST['nml_newsletter_tags'];
		} else {
			$tag_array = $_POST['nml_newsletter_tags'] ? explode( ',', $_POST['nml_newsletter_tags'] ) : array();
		}
		$data['tags'] = array_map( 'trim', $tag_array );
	}

	// Send newsletter!
	if ( isset( $_POST['send_newsletter'] ) ) {
		$data['status'] = 'sending';
	}

	$newsletter = new NML_Newsletter( $newsletter_id );
	$new_id     = false;

	if ( ! empty( $newsletter_id ) ) {
		$result = $newsletter->update( $data );

		if ( $result ) {
			$new_id = $newsletter->ID;
		}
	} else {
		$new_id = $newsletter->create( $data );
	}

	if ( ! $new_id || is_wp_error( $new_id ) ) {
		wp_die( __( 'An error occurred while inserting the newsletter.', 'naked-mailing-list' ) );
	}

	// Maybe add lists.
	if ( empty( $_POST['nml_newsletter_lists'] ) ) {
		$newsletter->delete_lists( 'list' );
	}

	// Maybe delete tags.
	if ( empty( $_POST['nml_newsletter_tags'] ) ) {
		$newsletter->delete_lists( 'tag' );
	}

	$edit_url = add_query_arg( array(
		'nml-message' => 'newsletter-updated'
	), nml_get_admin_page_edit_newsletter( absint( $new_id ) ) );

	wp_safe_redirect( $edit_url );

	exit;

}

add_action( 'nml_action_save_newsletter', 'nml_save_newsletter' );

/**
 * Process delete newsletter
 *
 * @since 1.0
 * @return void
 */
function nml_process_delete_newsletter() {

	$nonce = isset( $_GET['nonce'] ) ? $_GET['nonce'] : false;

	if ( empty( $nonce ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $nonce, 'nml_delete_newsletter' ) ) {
		wp_die( __( 'Failed nonce security.', 'naked-mailing-list' ) );
	}

	if ( ! isset( $_GET['ID'] ) ) {
		wp_die( __( 'Invalid newsletter ID.', 'naked-mailing-list' ) );
	}

	$deleted = nml_delete_newsletter( absint( $_GET['ID'] ) );

	if ( is_wp_error( $deleted ) ) {
		wp_die( $deleted->get_error_message() );
	}

	$redirect_url = add_query_arg( array(
		'nml-message' => 'newsletter-deleted'
	), nml_get_admin_page_newsletters() );

	wp_safe_redirect( $redirect_url );

	exit;

}

add_action( 'nml_action_delete_newsletter', 'nml_process_delete_newsletter' );