<?php

/**
 * IgniteWoo Integrations class
 *
 * Loads Integrations into WooCommerce.
 *
 * Based on WC Integrations class
 *
 */
if ( !defined( 'ABSPATH' ) ) 
	die( '404 - Not Found' );

class IGN_Integrations extends WC_Settings_API {

	/** 
	* @var array Array of integration classes 
	*/
	var $integrations = array();


	public function __construct() {

		do_action( 'ignitewoo_integrations_init' );

	}

	
	/**
	* Load integration classes
	*/
	function init() { 
	
		$load_integrations = apply_filters( 'ignitewoo_integrations', array() );

		foreach ( $load_integrations as $integration ) {

			$load_integration = new $integration();

			$this->integrations[ $load_integration->id ] = $load_integration;

		}
		
	}
	
	
	/**
	* Return loaded integrations.
	*
	* @return array
	*/
	public function get_integrations() {
	
		$this->init(); 
		
		return $this->integrations;
		
	}
	
	
	/**
	* Add a tab
	*/
	public function ignitewoo_integrations_tab( $current_tab ) { 
	
		$class = 'nav-tab';
		
		$label = 'IgniteWoo'; // Not translatable
	
		$class = 'nav-tab';
		
		if ( 'ignitewoo' == $current_tab ) 
			$class .= ' nav-tab-active';
		
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) 
		
			echo '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=ignitewoo' ) . '" class="' . $class . '">' . $label . '</a>';
		else 
			echo '<a href="' . admin_url( 'admin.php?page=woocommerce&tab=ignitewoo' ) . '" class="' . $class . '">' . $label . '</a>';

		return;
		
	}
	
	
	/** 
	* Put integration sections on the screen 
	*/
	public function ignitewoo_integrations_sections() { 
		
		$current_tab = ( empty( $_GET['tab'] ) ) ? 'general' : sanitize_text_field( urldecode( $_GET['tab'] ) );
		
		if ( empty( $current_tab ) || 'ignitewoo' != $current_tab  ) 
			return;
			
		$integrations = $this->get_integrations();

		$current_section = ( empty( $_REQUEST['section'] ) ) ? '' : sanitize_text_field( urldecode( $_REQUEST['section'] ) );

		$current_section = empty( $current_section ) ? key( $integrations ) : $current_section;

		$links = array();

		foreach ( $integrations as $key => $integration ) {

			$title = empty( $integration->method_title ) ? ucwords( $integration->id ) : ucwords( $integration->method_title );

			$current = ( $integration->id == $current_section ) ? 'class="current"' : '';

			$links[] = '<a href="' . add_query_arg( 'section', $integration->id, admin_url( 'admin.php?page=woocommerce&tab=ignitewoo' ) ) . '"' . $current . '>' . esc_html( $title ) . '</a>';
		}

		echo '<ul class="subsubsub"><li>' . implode( ' | </li><li>', $links ) . '</li></ul><br class="clear" />';

		if ( isset( $integrations[ $current_section ] ) )
			$integrations[ $current_section ]->admin_options();
			
	}

	
}

global $ignitewoo_integrations;

$ignitewoo_integrations = new IGN_Integrations();