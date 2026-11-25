<?php
/**
 * Customizer options.
 *
 * Everything an editor might reasonably want to change without touching code:
 * the cover tagline, social links, the subscribe destination, the ticker, and
 * which homepage blocks are on.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function angbantayog_customize_register( $wp_customize ) {

	/* ------------------------------------------------------------- Identity */
	$wp_customize->add_section( 'ab_identity', array(
		'title'    => __( 'Ang Bantayog: Pagkakakilanlan', 'ang-bantayog' ),
		'priority' => 25,
	) );

	$wp_customize->add_setting( 'ab_tagline', array(
		'default'           => __( 'Lampas sa headline, lampas sa ingay — kung saan nagtatagpo ang lalim, katotohanan, at matapang na pagkukuwento.', 'ang-bantayog' ),
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'ab_tagline', array(
		'label'       => __( 'Tagline sa cover', 'ang-bantayog' ),
		'section'     => 'ab_identity',
		'type'        => 'textarea',
		'description' => __( 'Lalabas sa ilalim ng pamagat sa hero cover.', 'ang-bantayog' ),
	) );

	$wp_customize->add_setting( 'ab_masthead_line', array(
		'default'           => __( 'Opisyal na Pampaaralang Pahayagan ng Libertad National High School', 'ang-bantayog' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ab_masthead_line', array(
		'label'   => __( 'Linya sa topbar', 'ang-bantayog' ),
		'section' => 'ab_identity',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'ab_cover_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ab_cover_image', array(
		'label'   => __( 'Larawan sa cover', 'ang-bantayog' ),
		'section' => 'ab_identity',
	) ) );

	/* --------------------------------------------------------------- Social */
	$wp_customize->add_section( 'ab_social', array(
		'title'    => __( 'Ang Bantayog: Social at Kontak', 'ang-bantayog' ),
		'priority' => 26,
	) );

	$socials = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'tiktok'    => 'TikTok',
		'x'         => 'X (Twitter)',
	);
	foreach ( $socials as $key => $label ) {
		$wp_customize->add_setting( 'ab_social_' . $key, array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'ab_social_' . $key, array(
			'label'   => $label,
			'section' => 'ab_social',
			'type'    => 'url',
		) );
	}

	$wp_customize->add_setting( 'ab_contact_email', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'ab_contact_email', array(
		'label'   => __( 'Email ng newsroom', 'ang-bantayog' ),
		'section' => 'ab_social',
		'type'    => 'email',
	) );

	/* ---------------------------------------------------------- Newsletter */
	$wp_customize->add_section( 'ab_newsletter', array(
		'title'    => __( 'Ang Bantayog: Subscribe', 'ang-bantayog' ),
		'priority' => 27,
	) );

	$wp_customize->add_setting( 'ab_subscribe_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'ab_subscribe_url', array(
		'label'       => __( 'URL ng subscribe form', 'ang-bantayog' ),
		'description' => __( 'Google Form, Mailchimp, o anumang panlabas na form. Kung walang laman, itatago ang subscribe na buton.', 'ang-bantayog' ),
		'section'     => 'ab_newsletter',
		'type'        => 'url',
	) );

	$wp_customize->add_setting( 'ab_newsletter_blurb', array(
		'default'           => __( 'Makakuha ng pinakabagong balita ng Ang Bantayog diretso sa inyong inbox.', 'ang-bantayog' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ab_newsletter_blurb', array(
		'label'   => __( 'Teksto sa newsletter', 'ang-bantayog' ),
		'section' => 'ab_newsletter',
		'type'    => 'textarea',
	) );

	/* ------------------------------------------------------------- Homepage */
	$wp_customize->add_section( 'ab_homepage', array(
		'title'    => __( 'Ang Bantayog: Homepage', 'ang-bantayog' ),
		'priority' => 28,
	) );

	$toggles = array(
		'ab_show_ticker'     => array( __( 'Ipakita ang announcement ticker', 'ang-bantayog' ), true ),
		'ab_show_carousel'   => array( __( 'Ipakita ang hero carousel', 'ang-bantayog' ), true ),
		'ab_show_cover'      => array( __( 'Ipakita ang cover at tagline', 'ang-bantayog' ), true ),
		'ab_show_timeline'   => array( __( 'Ipakita ang Timeline (live feed)', 'ang-bantayog' ), true ),
		'ab_show_trending'   => array( __( 'Ipakita ang Trending na paksa', 'ang-bantayog' ), true ),
		'ab_show_picks'      => array( __( "Ipakita ang Editors' Pick", 'ang-bantayog' ), true ),
		'ab_show_factcheck'  => array( __( 'Ipakita ang Fact Check', 'ang-bantayog' ), true ),
		'ab_show_documents'  => array( __( 'Ipakita ang Mga Dokumento', 'ang-bantayog' ), true ),
		'ab_show_partners'   => array( __( 'Ipakita ang Mga Kasosyo', 'ang-bantayog' ), true ),
	);
	foreach ( $toggles as $id => $conf ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $conf[1],
			'sanitize_callback' => 'angbantayog_sanitize_checkbox',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $conf[0],
			'section' => 'ab_homepage',
			'type'    => 'checkbox',
		) );
	}

	$wp_customize->add_setting( 'ab_extra_sections', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'ab_extra_sections', array(
		'label'       => __( 'Karagdagang seksyon (category slugs)', 'ang-bantayog' ),
		'description' => __( 'Hiwalayin ng kuwit, hal.: agham-teknolohiya, literatura, kolum', 'ang-bantayog' ),
		'section'     => 'ab_homepage',
		'type'        => 'text',
	) );

	$wp_customize->add_setting( 'ab_timeline_count', array(
		'default'           => 8,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'ab_timeline_count', array(
		'label'       => __( 'Bilang ng item sa Timeline', 'ang-bantayog' ),
		'section'     => 'ab_homepage',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 3, 'max' => 20 ),
	) );
}
add_action( 'customize_register', 'angbantayog_customize_register' );

function angbantayog_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked );
}

/** Shorthand used throughout the templates. */
function angbantayog_show( $key, $default = true ) {
	return (bool) get_theme_mod( $key, $default );
}
