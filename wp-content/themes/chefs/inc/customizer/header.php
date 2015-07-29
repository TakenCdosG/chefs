<?php
/**
 * Header theme options
 *
 * @package     Chef's WordPress theme
 * @subpackage  Customizer
 * @author      Adrián A. Morelos H.
 * @link        http://thinkcreativegroup.com
 * @since       2.0.0
 */

function wpex_customizer_general( $wp_customize ) {

	// General Section
	$wp_customize->add_section( 'wpex_header_section' , array(
		'title'      => __( 'Header', 'wpex' ),
		'priority'   => 210,
	) );

	// Logo Image
	$wp_customize->add_setting( 'wpex_logo', array(
		'type'	=> 'theme_mod'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'wpex_logo', array(
		'label'		=> __('Image Logo','wpex'),
		'section'	=> 'wpex_header_section',
		'settings'	=> 'wpex_logo',
	) ) );
		
}
add_action( 'customize_register', 'wpex_customizer_general' );