<?php
/**
 * Admin class for WP Contributors plugin.
 *
 * This file contains the admin functionality for managing contributors in the
 * WordPress admin interface. It adds a metabox to the post editor for selecting
 * contributors and saves the selected contributors as post metadata.
 *
 * @package    WP_Contributors
 * @subpackage WP_Contributors/includes/classes/admin
 * @since      1.0.0
 * @author     Kajal Gohel
 */

/**
 * Class WP_Contributors_Admin
 *
 * Handles the admin functionality for the WP Contributors plugin.
 *
 * @package    WP_Contributors
 * @subpackage WP_Contributors/includes/classes/admin
 * @since      1.0.0
 */
class WP_Contributors_Admin {

	/**
	 * Initialize the admin hooks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function run() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ) );
	}

	/**
	 * Add a Contributors metabox to the post editor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_metabox() {
		add_meta_box(
			'wp_contributors_metabox',
			__( 'Contributors', 'wp-contributors' ),
			array( $this, 'render_metabox' ),
			'post',
			'side',
			'default'
		);
	}

	/**
	 * Render the Contributors metabox.
	 *
	 * @param WP_Post $post The current post object.
	 * @return void
	 * @since 1.0.0
	 */
	public function render_metabox( $post ) {
		// Get all users.
		$users = get_users();

		// Retrieve saved contributors.
		$saved_contributors = get_post_meta( $post->ID, '_wp_contributors', true );
		$saved_contributors = is_array( $saved_contributors ) ? $saved_contributors : array();

		// Output a nonce field for security.
		wp_nonce_field( 'wp_contributors_save_metabox', 'wp_contributors_nonce' );

		// Check if the current user has the required role to edit contributors.
		$current_user_can_edit = current_user_can( 'edit_others_posts' );
		?>
		<div class="wp-contributors-metabox">
			<?php if ( ! $current_user_can_edit ) : ?>
				<p><?php esc_html_e( 'You do not have permission to edit contributors.', 'wp-contributors' ); ?></p>
			<?php endif; ?>
			<ul class="wp-contributors-list">
				<?php foreach ( $users as $user ) : ?>
					<?php
					$checked = in_array( $user->ID, $saved_contributors, true ) ? 'checked' : '';
					?>
					<li>
						<label>
							<input
								type="checkbox"
								name="wp_contributors[]"
								value="<?php echo esc_attr( $user->ID ); ?>"
								<?php echo esc_attr( $checked ); ?>
								<?php echo $current_user_can_edit ? '' : 'disabled'; ?>
							>
							<?php echo esc_html( $user->display_name ); ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Save the contributors when the post is saved.
	 *
	 * @param int $post_id The ID of the current post.
	 * @return void
	 * @since 1.0.0
	 */
	public function save_metabox( $post_id ) {
		// Verify the nonce for security.
		if ( ! isset( $_POST['wp_contributors_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['wp_contributors_nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'wp_contributors_save_metabox' ) ) {
			return;
		}

		// Prevent saving during autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check if the user has permission to edit the post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Verify that the current user can manage contributors (Admin, Editor, Author).
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		// Sanitize and save the contributors.
		$contributors = isset( $_POST['wp_contributors'] ) ? array_map( 'intval', $_POST['wp_contributors'] ) : array();
		update_post_meta( $post_id, '_wp_contributors', $contributors );
	}
}


