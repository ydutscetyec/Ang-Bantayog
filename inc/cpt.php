<?php
/**
 * Custom post types.
 *
 * Each one exists because a feature on the reference sites needs a content
 * shape that plain posts cannot carry:
 *
 *   factcheck    - Rappler-style Fact Check, which needs a verdict + the claim
 *                  being checked, not just a body.
 *   document     - DOST-style transparency / downloads library, which needs a
 *                  file, a reference number and a document date.
 *   announcement - DOST-style ticker and advisory board, short-lived notices
 *                  that must not pollute the article feed.
 *   partner      - DOST-style affiliate logo strip; InsightPH's "part of
 *                  JuanClick" block is the same idea.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function angbantayog_register_post_types() {

	/* ---------------------------------------------------------------- Fact Check */
	register_post_type( 'ab_factcheck', array(
		'labels' => array(
			'name'               => __( 'Fact Check', 'ang-bantayog' ),
			'singular_name'      => __( 'Fact Check', 'ang-bantayog' ),
			'add_new_item'       => __( 'Magdagdag ng Fact Check', 'ang-bantayog' ),
			'edit_item'          => __( 'Baguhin ang Fact Check', 'ang-bantayog' ),
			'menu_name'          => __( 'Fact Check', 'ang-bantayog' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'fact-check', 'with_front' => false ),
		'menu_icon'     => 'dashicons-yes-alt',
		'menu_position' => 21,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'revisions' ),
		'taxonomies'    => array( 'ab_topic' ),
		'show_in_rest'  => true,
	) );

	/* ----------------------------------------------------------------- Documents */
	register_post_type( 'ab_document', array(
		'labels' => array(
			'name'          => __( 'Mga Dokumento', 'ang-bantayog' ),
			'singular_name' => __( 'Dokumento', 'ang-bantayog' ),
			'add_new_item'  => __( 'Magdagdag ng Dokumento', 'ang-bantayog' ),
			'edit_item'     => __( 'Baguhin ang Dokumento', 'ang-bantayog' ),
			'menu_name'     => __( 'Mga Dokumento', 'ang-bantayog' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'dokumento', 'with_front' => false ),
		'menu_icon'     => 'dashicons-media-document',
		'menu_position' => 22,
		'supports'      => array( 'title', 'editor', 'excerpt', 'revisions' ),
		'taxonomies'    => array( 'ab_doc_type' ),
		'show_in_rest'  => true,
	) );

	/* ------------------------------------------------------------- Announcements */
	register_post_type( 'ab_announcement', array(
		'labels' => array(
			'name'          => __( 'Mga Anunsyo', 'ang-bantayog' ),
			'singular_name' => __( 'Anunsyo', 'ang-bantayog' ),
			'add_new_item'  => __( 'Magdagdag ng Anunsyo', 'ang-bantayog' ),
			'edit_item'     => __( 'Baguhin ang Anunsyo', 'ang-bantayog' ),
			'menu_name'     => __( 'Mga Anunsyo', 'ang-bantayog' ),
		),
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'anunsyo', 'with_front' => false ),
		'menu_icon'     => 'dashicons-megaphone',
		'menu_position' => 23,
		'supports'      => array( 'title', 'editor', 'excerpt' ),
		'show_in_rest'  => true,
	) );

	/* ------------------------------------------------------------------ Partners */
	register_post_type( 'ab_partner', array(
		'labels' => array(
			'name'          => __( 'Mga Kasosyo', 'ang-bantayog' ),
			'singular_name' => __( 'Kasosyo', 'ang-bantayog' ),
			'add_new_item'  => __( 'Magdagdag ng Kasosyo', 'ang-bantayog' ),
			'menu_name'     => __( 'Mga Kasosyo', 'ang-bantayog' ),
		),
		'public'        => false,
		'show_ui'       => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-groups',
		'menu_position' => 24,
		'supports'      => array( 'title', 'thumbnail' ),
		'show_in_rest'  => true,
	) );
}
add_action( 'init', 'angbantayog_register_post_types' );

/* =========================================================================
   Meta boxes
   Hand-rolled rather than pulled from a plugin, so the theme installs clean
   on Hostinger with no dependencies.
   ========================================================================= */

/** The fixed set of fact-check verdicts. Keys are stored; labels are shown. */
function angbantayog_verdicts() {
	return array(
		'tama'        => __( 'TAMA', 'ang-bantayog' ),
		'mali'        => __( 'MALI', 'ang-bantayog' ),
		'mapanlinlang'=> __( 'MAPANLINLANG', 'ang-bantayog' ),
		'walang-basbas'=> __( 'HINDI MAPAGTIBAY', 'ang-bantayog' ),
	);
}

function angbantayog_add_meta_boxes() {
	add_meta_box(
		'ab_factcheck_meta',
		__( 'Detalye ng Fact Check', 'ang-bantayog' ),
		'angbantayog_factcheck_box',
		'ab_factcheck',
		'normal',
		'high'
	);
	add_meta_box(
		'ab_document_meta',
		__( 'Detalye ng Dokumento', 'ang-bantayog' ),
		'angbantayog_document_box',
		'ab_document',
		'normal',
		'high'
	);
	add_meta_box(
		'ab_partner_meta',
		__( 'Link ng Kasosyo', 'ang-bantayog' ),
		'angbantayog_partner_box',
		'ab_partner',
		'side',
		'default'
	);
	add_meta_box(
		'ab_post_extras',
		__( 'Ang Bantayog: Karagdagang Detalye', 'ang-bantayog' ),
		'angbantayog_post_extras_box',
		array( 'post', 'ab_factcheck' ),
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'angbantayog_add_meta_boxes' );

function angbantayog_factcheck_box( $post ) {
	wp_nonce_field( 'ab_meta', 'ab_meta_nonce' );
	$verdict = get_post_meta( $post->ID, '_ab_verdict', true );
	$claim   = get_post_meta( $post->ID, '_ab_claim', true );
	$source  = get_post_meta( $post->ID, '_ab_claim_source', true );
	?>
	<p>
		<label for="ab_verdict"><strong><?php esc_html_e( 'Hatol', 'ang-bantayog' ); ?></strong></label><br />
		<select name="ab_verdict" id="ab_verdict">
			<option value=""><?php esc_html_e( '— pumili —', 'ang-bantayog' ); ?></option>
			<?php foreach ( angbantayog_verdicts() as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $verdict, $key ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="ab_claim"><strong><?php esc_html_e( 'Ang pahayag na sinuri', 'ang-bantayog' ); ?></strong></label><br />
		<textarea name="ab_claim" id="ab_claim" rows="3" style="width:100%"><?php echo esc_textarea( $claim ); ?></textarea>
	</p>
	<p>
		<label for="ab_claim_source"><strong><?php esc_html_e( 'Pinagmulan ng pahayag', 'ang-bantayog' ); ?></strong></label><br />
		<input type="text" name="ab_claim_source" id="ab_claim_source" value="<?php echo esc_attr( $source ); ?>" style="width:100%" />
	</p>
	<?php
}

function angbantayog_document_box( $post ) {
	wp_nonce_field( 'ab_meta', 'ab_meta_nonce' );
	$file = get_post_meta( $post->ID, '_ab_doc_file', true );
	$ref  = get_post_meta( $post->ID, '_ab_doc_ref', true );
	$date = get_post_meta( $post->ID, '_ab_doc_date', true );
	?>
	<p>
		<label for="ab_doc_file"><strong><?php esc_html_e( 'URL ng file (PDF/DOCX)', 'ang-bantayog' ); ?></strong></label><br />
		<input type="url" name="ab_doc_file" id="ab_doc_file" value="<?php echo esc_attr( $file ); ?>" style="width:100%" />
		<em><?php esc_html_e( 'I-upload sa Media Library, pagkatapos i-paste ang URL dito.', 'ang-bantayog' ); ?></em>
	</p>
	<p>
		<label for="ab_doc_ref"><strong><?php esc_html_e( 'Reference number', 'ang-bantayog' ); ?></strong></label><br />
		<input type="text" name="ab_doc_ref" id="ab_doc_ref" value="<?php echo esc_attr( $ref ); ?>" style="width:100%" />
	</p>
	<p>
		<label for="ab_doc_date"><strong><?php esc_html_e( 'Petsa ng dokumento', 'ang-bantayog' ); ?></strong></label><br />
		<input type="date" name="ab_doc_date" id="ab_doc_date" value="<?php echo esc_attr( $date ); ?>" />
	</p>
	<?php
}

function angbantayog_partner_box( $post ) {
	wp_nonce_field( 'ab_meta', 'ab_meta_nonce' );
	$url = get_post_meta( $post->ID, '_ab_partner_url', true );
	?>
	<p>
		<label for="ab_partner_url"><strong><?php esc_html_e( 'Website', 'ang-bantayog' ); ?></strong></label><br />
		<input type="url" name="ab_partner_url" id="ab_partner_url" value="<?php echo esc_attr( $url ); ?>" style="width:100%" />
	</p>
	<p><em><?php esc_html_e( 'Gamitin ang Featured Image para sa logo.', 'ang-bantayog' ); ?></em></p>
	<?php
}

function angbantayog_post_extras_box( $post ) {
	wp_nonce_field( 'ab_meta', 'ab_meta_nonce' );
	$pick   = get_post_meta( $post->ID, '_ab_editors_pick', true );
	$live   = get_post_meta( $post->ID, '_ab_live', true );
	$deck   = get_post_meta( $post->ID, '_ab_deck', true );
	$affil  = get_post_meta( $post->ID, '_ab_byline_affiliation', true );
	?>
	<p>
		<label><input type="checkbox" name="ab_editors_pick" value="1" <?php checked( $pick, '1' ); ?> />
		<?php esc_html_e( "Editors' Pick", 'ang-bantayog' ); ?></label>
	</p>
	<p>
		<label><input type="checkbox" name="ab_live" value="1" <?php checked( $live, '1' ); ?> />
		<?php esc_html_e( 'LIVE / umuusbong na balita', 'ang-bantayog' ); ?></label>
	</p>
	<p>
		<label for="ab_deck"><strong><?php esc_html_e( 'Deck (buod sa ilalim ng headline)', 'ang-bantayog' ); ?></strong></label><br />
		<textarea name="ab_deck" id="ab_deck" rows="3" style="width:100%"><?php echo esc_textarea( $deck ); ?></textarea>
	</p>
	<p>
		<label for="ab_byline_affiliation"><strong><?php esc_html_e( 'Afiliasyon ng awtor', 'ang-bantayog' ); ?></strong></label><br />
		<input type="text" name="ab_byline_affiliation" id="ab_byline_affiliation"
		       value="<?php echo esc_attr( $affil ); ?>" style="width:100%"
		       placeholder="<?php esc_attr_e( 'hal. Grade 11 — Engineering 1', 'ang-bantayog' ); ?>" />
	</p>
	<?php
}

/**
 * Save handler for every box above.
 * Text fields are sanitised by type; the file URL keeps its scheme via esc_url_raw.
 */
function angbantayog_save_meta( $post_id ) {
	if ( ! isset( $_POST['ab_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ab_meta_nonce'], 'ab_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$text_fields = array(
		'_ab_claim'                => 'ab_claim',
		'_ab_claim_source'         => 'ab_claim_source',
		'_ab_doc_ref'              => 'ab_doc_ref',
		'_ab_doc_date'             => 'ab_doc_date',
		'_ab_deck'                 => 'ab_deck',
		'_ab_byline_affiliation'   => 'ab_byline_affiliation',
	);
	foreach ( $text_fields as $meta => $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	if ( isset( $_POST['ab_verdict'] ) ) {
		$verdict = sanitize_key( $_POST['ab_verdict'] );
		if ( array_key_exists( $verdict, angbantayog_verdicts() ) ) {
			update_post_meta( $post_id, '_ab_verdict', $verdict );
		} else {
			delete_post_meta( $post_id, '_ab_verdict' );
		}
	}

	foreach ( array( '_ab_doc_file' => 'ab_doc_file', '_ab_partner_url' => 'ab_partner_url' ) as $meta => $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta, esc_url_raw( wp_unslash( $_POST[ $field ] ) ) );
		}
	}

	// Checkboxes: absent means off, so they are handled separately.
	foreach ( array( '_ab_editors_pick' => 'ab_editors_pick', '_ab_live' => 'ab_live' ) as $meta => $field ) {
		if ( ! empty( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $meta, '1' );
		} else {
			delete_post_meta( $post_id, $meta );
		}
	}
}
add_action( 'save_post', 'angbantayog_save_meta' );
