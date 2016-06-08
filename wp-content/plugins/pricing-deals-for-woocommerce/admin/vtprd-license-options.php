<?php

/**
 *  Added v1.1.5
 *  Both  class VTPRD_License_Options
 *    and non-class Functions below... 
 */
  
 // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed

define( 'VTPRD_STORE_URL', 'https://www.varktech.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

//define( 'VTPRD_STORE_URL', 'https://stage.varktech.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

//***********************************************************
//CHANGE TO specific receiving file???
// http://code.tutsplus.com/tutorials/a-look-at-the-wordpress-http-api-a-practical-example-of-wp_remote_post--wp-32425
//HOST receiving file: http://code.tutsplus.com/tutorials/a-look-at-the-wordpress-http-api-saving-data-from-wp_remote_post--wp-32505
//***********************************************************
//define( 'VTPRD_STORE_URL', 'http://http://www.varktech.com/wp-remote-receiver.php' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in VT exactly
define( 'VTPRD_ITEM_NAME', 'Pricing Deals Pro for WooCommerce Plugin' );
define( 'VTPRD_ITEM_ID', '9' ); // ITEM ID from home STORE -> POST ID of save product
define( 'VTPRD_ITEM_ID_DEMO', '261' ); // ITEM ID from home STORE -> POST ID of save product
define( 'VTPRD_PRO_SLUG', 'pricing-deals-pro-for-woocommerce' );  
//define( 'VTPRD_PRO_PLUGIN_ADDRESS', 'pricing-deals-pro-for-woocommerce/vt-pricing-deals-pro.php' ); 
define( 'VTPRD_PRO_LAST_PRELICENSE_VERSION', '1.1.1.2' );

   
  //error message goes into admin message queue
  // THIS FUNCTION NOW IN vt-pricing-deals.php
  /*
  function vtprd_maybe_license_error() {
      //only applies to PRO plugins
    if (!defined('VTPRD_PRO_DIRNAME')) {
      return;
    }  
    
    $vtprd_license_options = get_option( 'vtprd_license_options' );
    
    if ($vtprd_license_options['status'] == 'valid') {
      return;
    }
    
    
   return;
  } 
  
  //success message goes on licensing page
  function vtprd_maybe_license_success_message ($vtprd_license_options) {
    $message = false;
    if ($vtprd_license_options['status'] == 'invalid') {
      return $message;
    }
    
    
   return $message;
  }   
  //outside of class, so that phone home can be done independantly of class...

*/

/* done in MAIN PLUGIN FILE
   public function vtprd_license_error_notice() {

      global $vtprd_license_options;
      $message  =  '<strong>' . __('This PRO plugin: ' , 'vtprd') . ' &nbsp;&nbsp;'  .VTPRD_PRO_PLUGIN_NAME . '</strong>' ;
      $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;' . __('License  ' , 'vtprd') .$vtprd_license_options['last_action'] ;      
      $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<strong>' . $vtprd_license_options['msg'] . '</strong>' ;
      
      if ($vtprd_license_options['state'] == 'suspended-by-vendor') {
        $message .=  '<br>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<strong>' ;
        $message .=  $vtprd_license_options['msg'] . '</strong>' ;  
      }
      
      $admin_notices = '<div id="message" class="error fade" style="background-color: #FFEBE8 !important;"><p>' . $message . ' </p></div>';
      echo $admin_notices;

      return;    
  } 
*/
  
   
//Set up and run license screen 
class VTPRD_License_Options_screen { 
	
	public function __construct(){ 
  
    add_action( 'admin_init',            array(&$this, 'vtprd_initialize_options' ) );
    add_action( 'admin_menu',            array(&$this, 'vtprd_add_admin_menu_setup_items' ), 99  ); //99 puts it at the bottom of the list
    add_action( "admin_enqueue_scripts", array(&$this, 'vtprd_enqueue_setup_scripts') );
  } 

function vtprd_add_admin_menu_setup_items() {
 // add items to the Pricing Deals custom post type menu structure
  global $vtprd_license_options;
  
  $settingsLocation = 'edit.php?post_type=vtprd-rule';
  
   
	add_submenu_page(
		$settingsLocation,	// The ID of the top-level menu page to which this submenu item belongs
		__( 'Register Pro License', 'vtprd' ), // The value used to populate the browser's title bar when the menu page is active                           
		__( 'Register Pro License', 'vtprd' ),					// The label of this submenu item displayed in the menu
		'administrator',					// What roles are able to access this submenu item
		'vtprd_license_options_page',	// The slug used to represent this submenu item
		array( &$this, 'vtprd_license_options_cntl' ) 				// The callback function used to render the options for this submenu item
	);
  /* 
	add_submenu_page(
		$settingsLocation,	// The ID of the top-level menu page to which this submenu item belongs
		__( 'System Info', 'vtprd' ), // The value used to populate the browser's title bar when the menu page is active                           
		__( 'System Info', 'vtprd' ),					// The label of this submenu item displayed in the menu
		'administrator',					// What roles are able to access this submenu item
		'vtprd_license_options_page',	// The slug used to represent this submenu item
		array( &$this, 'vtprd_system_info_cntl' ) 				// The callback function used to render the options for this submenu item
	); 
  */
} 

/**
 * Renders a simple page to display for the menu item added above.
 */
function vtprd_license_options_cntl() {
  //add help tab to this screen...
  //$vtprd_backbone->vtprd_add_help_tab ();
    $content = '<br><a  href="' . VTPRD_DOCUMENTATION_PATH . '"  title="Access Plugin Documentation">Access Plugin Documentation</a>';
    $screen = get_current_screen();
    $screen->add_help_tab( array( 
       'id' => 'vtprd-help-options',            //unique id for the tab
       'title' => 'Pricing Deals Settings Help',      //unique visible title for the tab
       'content' => $content  //actual help text
      ) );

   global $vtprd_license_options; 
    
   if( !$vtprd_license_options )  {
     $vtprd_license_options = get_option( 'vtprd_license_options' );
   }
   

   //***********************************************
   //***********************************************
   //IF SUSPENDED 
   //***********************************************
/*
    NOW DONE IN MAIN PLUGIN FILE   
   if ($vtprd_license_options['state'] == 'suspended-by-vendor') { 
      vtprd_deactivate_pro_plugin();
      add_action( 'admin_notices', 'vtprd_license_error_notice' );
   }
 */   
   //***********************************************
   //***********************************************   
   
    
  ?>
   <style type="text/css">
      #system-buttons {margin-top:0;}
       #system-info-textarea {
          width: 800px;
          height: 400px;
          font-family: Menlo,Monaco,monospace;
          background: 0 0;
          white-space: pre;
          overflow: auto;
          display: block;
      }
      .green {
        color: green;
        font-size: 18px;
      }
      .red {
        color: red;
        font-size: 14px;
        background-color: rgb(255, 235, 232) !important;
        margin: 5px 0 15px;
        border: 1px solid red;
        border-left: 4px solid red;
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        padding: 10px 12px 20px 12px;
      } 
      .yellow {
          color: black;
          font-size: 14px;
          margin: 5px 0 15px;
          border: 4px solid yellow;
          border-left: 4px solid yellow;
          box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
          padding: 10px 12px 20px 12px;
          background-color: RGB(255, 255, 180) !important;
      }
      .smallGreyText {
          color: gray;
          font-size: 12px;
      }              
       .sub-label {
        color: grey;
        font-size: 11px !important;
      }  
       .grey {
        color: grey;
        font-size: 11px !important;
      } 
       .black {
        color: black;
        font-size: 11px !important;
      }
      .hidden-button {
        color:rgb(241, 241, 241) !important;
        margin-left: 50px;
      }
      .hidden-button:hover {
        color:white;
      }
      #reset-button .system-buttons-h4 {
        color:#F1F1F1; /*matches background*/
      }
      #reset-button .system-buttons-h4:hover {
        color:#gray; 
      }      
      #reset-button .nuke_buttons, 
      #reset-button input{
        color:#F1F1F1; /*matches background*/
        box-sizing: none;
        border: none;
        float: right;
        margin-right:200px;        
      }  
      #reset-button .nuke_buttons:hover,
      #reset-button input :hover {
        color:red;
        box-sizing: border-box; 
        border: 1px solid black;
      }
      #show-info-button {margin-left:20px; padding:10px; text-decoration::none; border:1px solid gray; cursor: pointer; cursor: hand;  font-weight:bold; font-size:16px;}
      #show-info-button:hover {color:red;}
      #show-licensing-info {display:none} 
      #license-status-msg a {padding:5px; border:1px solid gray;}         
  </style> 
  
  <script type="text/javascript">
     jQuery(document).ready(function($) {
            
         
            //****************************
            // Show Discount Where
            //****************************  
            
                          //first time in
                          screen_init_Control();
                          
                          //on CHANGE
                          $("#radio-prod").click(function(){ //use 'change' rather than 'click' 
                               $(".production_url_for_test").hide("slow");                           
                           });     
                          $("#radio-demo").click(function(){ //use 'change' rather than 'click' 
                               $(".production_url_for_test").hide("slow");                           
                           });     
                          $("#radio-test").click(function(){ //use 'change' rather than 'click' 
                               $(".production_url_for_test").show("slow");                           
                           }); 
                           
                          $("#show-info-button").click(function(){
                              $("#show-licensing-info").show("slow");                             
                          });                              
                                                        
                                   
                          function screen_init_Control() {                     
                            
                            if($('#radio-prod').is(':checked')){ //use 'change' rather than 'click' 
                                 $(".production_url_for_test").hide();                           
                             };     
                            if($('#radio-demo').is(':checked')){ //use 'change' rather than 'click' 
                                 $(".production_url_for_test").hide();                           
                             };     
                            if($('#radio-test').is(':checked')){ //use 'change' rather than 'click' 
                                 $(".production_url_for_test").show("slow");                           
                             };
                             
                            $("#show-licensing-info").hide();  
                                                       
                          }; 
                                             
                        
      }); 
  
  
  </script>
  
  
  
	<div class="wrap">
		<div id="icon-themes" class="icon32"></div>
    
		<h2>
      <?php 

          esc_attr_e('Pricing Deals Pro License Registration', 'vtprd'); 
   
      ?>    
    </h2>
    
    <?php 
    
       if ($vtprd_license_options['prod_or_test'] == 'demo') {
         $item_name = VTPRD_ITEM_NAME . ' Demo';
       } else {
         $item_name = VTPRD_ITEM_NAME;
       }
    
      if ($vtprd_license_options['expires'] > ' ') {
        if ($vtprd_license_options['expires'] == 'lifetime') {
          if ($vtprd_license_options['prod_or_test'] == 'demo') {
            ?> <p id="license-expiry-msg"><?php echo $item_name; ?> - 3-Day License </p> <?php
          } else {
            ?> <p id="license-expiry-msg"><?php echo $item_name; ?> - Lifetime License </p> <?php 
          }             
        } else {
          ?> <h2 id="license-expiry-msg" style="font-size: 1.5em;"><em><?php echo $item_name; ?> - License Expires::  <?php echo $vtprd_license_options['expires']; ?></em> </h2> <?php
        }                
      } 
      /* else {
        if ( ($vtprd_license_options['status'] == 'valid') &&
             ($vtprd_license_options['state']  == 'active') ) {
            //Lifetime license message from above 
        }       
      } */
    ?>  
           
		<?php settings_errors(); //shows errors entered with "add_settings_error" ?>

    <?php  //valid status ONLY allows active or deactivated

      vtprd_maybe_license_state_message();                 

    /*if ( isset( $_GET['settings-updated'] ) ) {
         echo "<div class='updated'><p>Theme settings updated successfully.</p></div>";
    } */
    ?>
		
		<form method="post" action="options.php">
			<?php
          //WP functions to execute the registered settings!
					settings_fields( 'vtprd_license_options_group' );     //activates the field settings setup below
					do_settings_sections( 'vtprd_license_options_page' );   //activates the section settings setup below 
          
          
       /*
       3 buttons
          Activate
          Deactivate

          Save Licensing Report as TXT file  ==>> straight to text file...
       */     
       

      // **********************************************************
      // STATUS: valid / invalid / unregistered (default)
      // STATE:  active (only if valid) / deactivated (only if valid) / pending (error but not yet suspended) / suspended-by-vendor / unregistered (default)
      // **********************************************************       
     
          
			?>	

       <p id="system-buttons">

         <?php  //valid status ONLY allows active or deactivated
          if (defined('VTPRD_PRO_DIRNAME')) { //if PRO is ACTIVE
            if ($vtprd_license_options['status'] =='valid') {   
                switch ( $vtprd_license_options['state'] ) { 
                  case 'active' : 
                      $this->vtprd_show_deactivate_button();
                    break;
                  case 'deactivated' : 
                      $this->vtprd_show_activate_button();
                    break;                    

                  default:                   
                      //can't be any other state!!
                    break;
                }
            } else { //'invalid' OR 'unregistered' path ==>> can't have a state of active or deactivated
                switch ( $vtprd_license_options['state'] ) {  
                  case 'unregistered' : 
                      $this->vtprd_show_activate_button();
                    break;
                  case 'pending' :
                      switch ( $vtprd_license_options['last_action'] ) {  
                          case 'activate_license' :
                          case 'check_license' :
                          case ' ' :
                          case '' :
                              $this->vtprd_show_activate_button();
                             break;
                          case 'deactivate_license' :
                              $this->vtprd_show_deactivate_button();
                             break;                             
                      }
                    break;                    

                  default:                   
                      //show suspended-by-vendor message
                    break;
                }            
            
            }
          } else {
            if ($vtprd_license_options['state'] != 'suspended-by-vendor') { 
              $pro_plugin_is_installed = vtprd_check_pro_plugin_installed();
              if ($pro_plugin_is_installed) {
                $url = bloginfo('url'); 
        ?>
               <br><br><br>
               <h3 class="red">
                    <strong> <?php 
                    _e(' - Activate the  &nbsp;&nbsp;<em>', 'vtprd');
                    echo $item_name; 
                    _e('</em> &nbsp;&nbsp;  on the &nbsp;&nbsp; 
                    <a href="'.$url.'/wp-admin/plugins.php">Plugins page</a> &nbsp;&nbsp; 
                    - to show a Registration Button here.', 'vtprd'); ?> </strong>
                </h3> 
        <?php 
              } else {
        ?>
               <br><br><br>
               <h3 class="yellow">
                    <?php 
                    _e(' - Install and Activate the  &nbsp;&nbsp;<em>', 'vtprd');
                    echo $item_name;  
                    _e('</em> &nbsp;&nbsp; to show a Registration Button here.', 'vtprd'); ?>
                </h3> 
        <?php               
              }
            } 
          }  
          

          $this->vtprd_show_clear_button();
        ?> 

          
          <br><br>

          <h3 class="title"><?php esc_attr_e('System Information', 'vtprd'); ?></h3>
        
          <br><br><br>
          
          <h4 class="system-buttons-h4"><?php esc_attr_e('Show Licensing Info', 'vtprd'); ?></h4>
          <br>
          <a id="show-info-button" href="javascript:void(0);" >
          <span> <?php esc_attr_e('Show Licensing Info', 'vtprd'); ?> </span></a>
          <br><br>
    
          <div id="show-licensing-info">
            <p>To copy the system info, click below then press Ctrl + C &nbsp;&nbsp; (or Cmd + C for a Mac).</p>
            <?php
            	
     
              if ($vtprd_license_options['prod_or_test'] == 'demo') {
                $item_name = VTPRD_ITEM_NAME . ' Demo';
              } else {
                $item_name = VTPRD_ITEM_NAME;
              }

              $return  = '### Begin Licensing Info ###' . "\n\n";

            	$return .= 'Home URL:                 ' . $vtprd_license_options['url'] . "\n";
              $return .= 'Plugin Name:              ' . $item_name . "\n";
              $return .= 'Status:                   ' . $vtprd_license_options['status'] . "\n";
              $return .= 'State:                    ' . $vtprd_license_options['state'] . "\n";
              $return .= 'Message:                  ' . $vtprd_license_options['msg'] . "\n";
            	$return .= 'Key:                      ' . $vtprd_license_options['key'] . "\n";
              $return .= 'Email:                    ' . $vtprd_license_options['email'] . "\n";
              $return .= 'Activation Type:          ' . $vtprd_license_options['prod_or_test'] . "\n";
              $return .= 'Prod Site URL (if test):  ' . $vtprd_license_options['prod_url_supplied_for_test_site'] . "\n";
              $return .= 'Strikes:                  ' . $vtprd_license_options['strikes'] . "\n"; 
              $return .= 'Last Action:              ' . $vtprd_license_options['last_action'] . "\n"; 
              $return .= 'Last good attempt:        ' . $vtprd_license_options['last_successful_rego_date_time'] . "\n";
              $return .= 'Last failed attempt:      ' . $vtprd_license_options['last_failed_rego_date_time'] . "\n"; 
              $return .= 'Expires:                  ' . $vtprd_license_options['expires'] . "\n"; 
              $return .= 'Plugin Item ID:           ' . VTPRD_ITEM_ID . "\n";
              $return .= 'Plugin Item ID Demo:      ' . VTPRD_ITEM_ID_DEMO . "\n";
              $return .= 'Registering To:           ' . VTPRD_STORE_URL . "\n"; 
              $return .= 'Diagnostic Message:       ' . $vtprd_license_options['diagnostic_msg'] . "\n";
                            
              $return .= 'Pro Current Version:      ' . $vtprd_license_options['plugin_current_version'] . "\n";  //used by plugin updater only
              $return .= 'Pro New Version:          ' . $vtprd_license_options['plugin_new_version'] . "\n";
              
              $return .= 'Pro Version:              ' . $vtprd_license_options['pro_version'] . "\n";
              $return .= 'Pro Required Version:     ' . VTPRD_MINIMUM_PRO_VERSION . "\n";
              $return .= 'Pro Version Status:       ' . $vtprd_license_options['pro_plugin_version_status'] . "\n";
              
              $return .= 'Free Current Version:     ' . VTPRD_VERSION . "\n";
              $return .= 'Free Required Version:    ' . $vtprd_license_options['pro_minimum_free_version'] . "\n";
              
              $count = get_option( 'vtprd_license_count');
              $return .= 'License Count:            ' . $count . "\n";
              $return .= 'Pro Deactivate Flag:      ' . $vtprd_license_options['pro_deactivate'] . "\n";
              

              $return .= "\n \n \n";
              
              $return .= 'Last Response from Host: <pre>'.print_r($vtprd_license_options['last_response_from_host'], true).'</pre>'  . "\n" ;     

              $return .= "\n \n \n";
              
              $return .= 'Last Parameters sent to Host: <pre>'.print_r($vtprd_license_options['params_sent_to_host'], true).'</pre>'  . "\n" ;   
                                   
             ?>  
             <textarea readonly="readonly" onclick="this.focus(); this.select()" id="system-info-textarea" 
             name="edd-sysinfo" title="To copy the system info, click below then press Ctrl + C (PC) or Cmd + C (Mac)."><?php echo $return; ?></textarea>
           

            <?php  global $vtprd_setup_options;
       //test    if ( ($vtprd_setup_options['allow_license_info_reset'] == 'yes')  &&
       //          ($vtprd_license_options['state'] == 'suspended-by-vendor') ) { ?> 
              <span id="reset-button">         
              <h4 class="system-buttons-h4"><?php esc_attr_e('Reset Licensing Fatal Counter', 'vtprd'); ?></h4>
              <input id="nuke-info-button"    name="vtprd_license_options[reset_fatal_counter]"        type="submit" class="buttons button-third"      value="<?php esc_attr_e('Reset Licensing Fatal Counter', 'vtprd'); ?>" />
              </span>
            <?php //test } ?>             

          </div><!-- /#show-licensing-info -->  
                            
        </p>      
		</form>

  
  
	</div><!-- /.wrap -->

<?php
} // end vtprd_display  


function vtprd_show_activate_button() {
   ?>
    <span id="how-activate-button">
    <h4 class="system-buttons-h4"><?php esc_attr_e('Activate License', 'vtprd'); ?></h4>        
    <input id="activate-button"    style="font-size:18px;"    name="vtprd_license_options[activate]"    type="submit" class="nuke_buttons button-first"     value="<?php esc_attr_e('Activate License', 'vtprd'); ?>" /> 
    </span>
    <?php wp_nonce_field( 'vtprd_nonce', 'vtprd_nonce' ); ?>
  <?php 
}


function vtprd_show_deactivate_button() {
  ?>
    <h4 class="system-buttons-h4"><?php esc_attr_e('Deactivate License', 'vtprd'); ?></h4>
    <input id="deactivate-button"  style="font-size:18px;"  name="vtprd_license_options[deactivate]"      type="submit" class="nuke_buttons button-second"      value="<?php esc_attr_e('Deactivate License', 'vtprd'); ?>" />
    <?php wp_nonce_field( 'vtprd_nonce', 'vtprd_nonce' ); ?>           
  <?php    
}

function vtprd_show_clear_button() {
   ?>  
    <br><br>      
    <h4 class="system-buttons-h4"><?php esc_attr_e('Clear Licensing Info', 'vtprd'); ?></h4>
    <input id="nuke-info-button"    name="vtprd_license_options[nuke-info]"       type="submit" class="nuke_buttons button-third"      value="<?php esc_attr_e('Clear Licensing Info', 'vtprd'); ?>" />
    <?php wp_nonce_field( 'vtprd_nonce', 'vtprd_nonce' ); ?>
     
  <?php 
}


function vtprd_system_info_cntl() {
    require_once  ( VTPRD_DIRNAME . '/admin/vtprd-system-info.php' ); 
}


/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Initializes the theme's Discount Reporting Options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 

function vtprd_initialize_options() {
  
	// If the theme options don't exist, create them.
	if( false == get_option( 'vtprd_license_options' ) ) {
		add_option( 'vtprd_license_options', $this->vtprd_set_default_options() );  //add the option into the table based on the default values in the function.
	} // end if


	add_settings_section(
		'license_activation_section',			// ID used to identify this section and with which to register options
		__( 'Activate Pro License', 'vtprd' ),	// Title to be displayed on the administration page
   /* .'&nbsp;&nbsp; => &nbsp;&nbsp;'.
    __( 'for Production or Test site', 'vtprd' ),*/
		array(&$this, 'vtprd_license_section_callback'),	// Callback used to render the description of the section
		'vtprd_license_options_page'		// Page on which to add this section of options
	);
   
          
    add_settings_field(	       
		'key',						// ID used to identify the field throughout the theme
		__( 'License Key', 'vtprd' )    
    .'<br>'.
    __( '<span class="sub-label">&nbsp;&nbsp;<em>(you may use old SessionID - License Key is returned)</em></span>', 'vtprd' ), // The label to the left of the option interface element
		array(&$this, 'vtprd_key_callback'), // The name of the function responsible for rendering the option interface
		'vtprd_license_options_page',	// The page on which this option will be displayed
		'license_activation_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			 __( 'Pro Plugin License Key', 'vtprd' )
		)
	);
          
          
    add_settings_field(	       
		'email',						// ID used to identify the field throughout the theme
		__( 'License email', 'vtprd' )
    .'<br>'.
    __( '<span class="sub-label">&nbsp;&nbsp;<em>(email address supplied with Purchase)</em></span>', 'vtprd' ), // The label to the left of the option interface element
		array(&$this, 'vtprd_email_callback'), // The name of the function responsible for rendering the option interface
		'vtprd_license_options_page',	// The page on which this option will be displayed
		'license_activation_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			 __( 'Pro Plugin License email', 'vtprd' )
		)
	);

          
    add_settings_field(	       
		'prod_or_test',						// ID used to identify the field throughout the theme
		'<br>' .  __( 'Activation Type', 'vtprd' ), // The label to the left of the option interface element
		array(&$this, 'vtprd_prod_or_test_callback'), // The name of the function responsible for rendering the option interface
		'vtprd_license_options_page',	// The page on which this option will be displayed
		'license_activation_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			 __( 'Pro Plugin License prod_or_test', 'vtprd' )
		)
	);

         
    add_settings_field(	       
		'prod_url_supplied_for_test_site',						// ID used to identify the field throughout the theme
    '<span class="production_url_for_test">'.   
    		__( 'Production URL', 'vtprd' )
    .'<br>'.
      __( '&nbsp;&nbsp;<em>(required for Test Site Activation)</em>', 'vtprd' )

    .'</span>',   // The label to the left of the option interface element
		array(&$this, 'vtprd_prod_url_callback'), // The name of the function responsible for rendering the option interface
		'vtprd_license_options_page',	// The page on which this option will be displayed
		'license_activation_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			 __( 'Pro Plugin License prod_url', 'vtprd' )
		)
	);

  	
	// Finally, we register the fields with WordPress
	register_setting(
		'vtprd_license_options_group',
		'vtprd_license_options' ,
    array(&$this, 'vtprd_validate_setup_input')
	);
  
  /*
  //Licensing Conversion Warning!!
  if ( (VTPRD_VERSION == '1.1.5') && 
       (defined('VTPRD_PRO_VERSION')) ) {
    global $pagenow;
    if ( 'plugins.php' === $pagenow ) {
      add_action( 'in_plugin_update_message-' . VTPRD_PLUGIN_SLUG, 'vtprd_update_notice' );    
    }
  }
  */
	
} // end vtprd_initialize_options

 
  
   
  //****************************
  //  DEFAULT OPTIONS INITIALIZATION
  //****************************
function vtprd_set_default_options() {
     $url = home_url();
     $url = $this->vtprd_strip_out_http($url); 
     
     if (defined('VTPRD_PRO_DIRNAME')) {
      $version = VTPRD_PRO_VERSION;
     } else {
      $version = null;     
     } 
       
     $options = array(           
          //screen fields
          'key'=> '',  //opt1 
          'email' => '',  //opt1 
          'prod_or_test' => 'prod',  //opt1
          'prod_url_supplied_for_test_site' => '',  //IF THIS IS a TEST site, MUST also give PROD URL
          //not screen fields
          'url' =>  $url,    
          'status' => 'unregistered',    //  'valid'/'invalid'/'unregistered'
          'state' => 'unregistered',     // active / deactivated / pending (error but not yet suspended) / suspended-by-vendor  / unregistered      
          'msg' => '', //opt3  code for both valid and invalid license - invalid goes to admin notices, valid goes onscreen
          'strikes' => '', //opt3  code for both valid and invalid license - invalid goes to admin notices, valid goes onscreen
          'error_try_count' => 0,  //opt4
          'last_action' => '',  // 'activate_license', 'deactivate_license' , 'check_license'
          'last_failed_rego_ts' => '',  
          'last_failed_rego_date_time' => '',   
          'last_successful_rego_ts' => '', //opt6
          'last_successful_rego_date_time' => '',
          'last_response_from_host' => '',
          'last_check_date_in_seconds' => '',
          'params_sent_to_host' => '',
          'expires' => '',
          'diagnostic_msg' => '',
          'strikes_possible' => 3,
          'plugin_current_version' => $version,  //used by plugin-updater exclusively
          'plugin_new_version' => $version,
          'pro_plugin_version_status' => '',
          'pro_version' => '',    //used by main plugin file
          'pro_minimum_free_version' => '',
          'pro_deactivate' => '', //used as a switch to allow the deactivate to happen in admin_init in main plugin file 
          'localhost_warning_done' => '' //if > null, warning has been produced once
     );
     return $options;
}

function vtprd_processing_options_callback () {
    ?>
    <h4 id="vtprd-processing-options"><?php esc_attr_e('These options apply to general discount processing.', 'vtprd'); ?></h4>
    <?php                                                                                                                                                                                      
}



function vtprd_license_section_callback () {
    global $vtprd_license_options; 
    $vtprd_license_options = get_option( 'vtprd_license_options' );

//error_log( print_r(  'activation callback $vtprd_license_options', true ) );
//error_log( var_export($vtprd_license_options, true ) ); 
      // **********************************************************
      // STATUS: valid / invalid / unregistered (default)
      // STATE:  active (only if valid) / deactivated (only if valid) / pending (error but not yet suspended) / suspended-by-vendor / unregistered (default)
      // **********************************************************       
    switch ( $vtprd_license_options['status'] ) { 
      //new license display
      case ''  :
      case ' ' :
      case 'unregistered' :
          if ( $vtprd_license_options['last_action'] <= ' '  ) {
          ?>                                   
            <h4 id="vtprd-license-messaging">
                  <strong><?php _e('Pro Plugin License Activation', 'vtprd'); ?></strong> 
            </h4> 
          <?php 
          } else {
          ?>                                   
            <h4 id="vtprd-license-messaging">
                  <strong><?php _e('Varktech Registration Process busy.  Please try again.', 'vtprd'); ?></strong> 
            </h4> 
          <?php           
          } 
        break; 
        
      //activation/deactivation successful
      case 'valid'  :
          ?>                                   
            <h2 id="vtprd-license-messaging" style="color: green !important;" >
                  <strong><?php echo $vtprd_license_options['msg']; ?></strong> 
            </h2> 
          <?php  
        break;

      
      //activation/deactivation successful
      case 'invalid'  :
     
          switch ( $vtprd_license_options['state'] ) { 
          
              case ($vtprd_license_options['state'] == 'pending')  :
                 //if license expired, no 'tries' left!
                 if ($vtprd_license_options['diagnostic_msg'] == 'demo_license_expired') {
                    ?>                                   
                      <h2 id="vtprd-license-messaging" style="color: red !important;" >
                            <strong><?php echo $vtprd_license_options['msg']; ?></strong>
                      </h2> 
                    <?php                  
                 
                    return;
                 }
                 
                 if ($vtprd_license_options['strikes'] > 0) {
                    $tries_left = $vtprd_license_options['strikes_possible'] - $vtprd_license_options['strikes'];
                    if ($tries_left == 1) {
                      $tries_left_msg = ' try remaining';
                    } else {
                      $tries_left_msg = ' tries remaining';                    
                    }
                    ?>                                   
                      <h2 id="vtprd-license-messaging" style="color: red !important;" >
                            <strong><?php echo $vtprd_license_options['msg']; ?></strong>
                            <br><br> 
                            <strong><?php echo 'You have ' .$tries_left. $tries_left_msg ; ?></strong>
                      </h2> 
                    <?php 
                    if ($tries_left == 1) {
                      ?>                                   
                        <h2 class="red" >
                              <strong><?php echo 'If you make a mistake with your last try, the License will be Suspended for ALL SITES using this license.'; ?></strong>
                        </h2> 
                      <?php 
                    }
                  } else {
                    ?>                                   
                      <h2 id="vtprd-license-messaging" style="color: red !important;" >
                            <strong><?php echo $vtprd_license_options['msg']; ?></strong>
                      </h2> 
                    <?php 
                  }
                break;                 
              case ($vtprd_license_options['state'] == 'suspended-by-vendor')  :
                  ?>                                   
                    <h2 id="vtprd-license-messaging" style="color: red !important;" >
                          <strong><?php echo $vtprd_license_options['msg']; ?></strong> 
                    </h2> 
                  <?php  
                break;            
          }
          
        break;
               
                 
    } 
                    

    return;      
    
}


  
  
  function vtprd_key_callback() {    //opt4
  	$options = get_option( 'vtprd_license_options' );	
    $html = '<textarea type="text" id="key"  rows="1" cols="60" name="vtprd_license_options[key]">' . $options['key'] . '</textarea>';  	
  	echo $html;
    return;
  }
  
  function vtprd_email_callback() {    //opt4
  	$options = get_option( 'vtprd_license_options' );	
    $html = '<textarea type="text" id="email"  rows="1" cols="60" name="vtprd_license_options[email]">' . $options['email'] . '</textarea>';  
  	echo $html;
    return;
  }
  
  
  function vtprd_prod_or_test_callback() {   
  	$options = get_option( 'vtprd_license_options' );	
    
//error_log( print_r(  'showing prod or test radio buttons, $vtprd_license_options= ' , true ) );
//error_log( var_export($options, true ) );    
    
    
/* 
   	$html = '<select id="prod_or_test" name="vtprd_license_options[prod_or_test]">';	
    $html .= '<option value="prod"'  . selected( $options['prod_or_test'], 'prod', false) . '>'   . __('Production Site', 'vtprd') .  '&nbsp;</option>';
    $html .= '<option value="test"'  . selected( $options['prod_or_test'], 'test', false) . '>'   . __('Test Site', 'vtprd') . '</option>';
    $html .= '<option value="demo"'  . selected( $options['prod_or_test'], 'demo', false) . '>'   . __('3-Day Full Demo License', 'vtprd') . '</option>';
  	$html .= '</select>';
    $html .= '<img id="prod-or-test" style="padding-left: 10px;padding-top: 5px;" width="" title="" src="/wp-content/plugins/pricing-deals-for-woocommerce/admin/images/activation-type.png" alt="">';
*/

    $checked = 'checked="checked"';
    
    if ($options['prod_or_test'] == 'prod') {$prod_checked = $checked;} else {$prod_checked = null;}
    if ($options['prod_or_test'] == 'test') {$test_checked = $checked;} else {$test_checked = null;}
    if ($options['prod_or_test'] == 'demo') {$demo_checked = $checked;} else {$demo_checked = null;}
    
    $html  = '<ul style="
              padding: 15px;
              background: white none repeat scroll 0% 0%;
              width: 250px;
              border: 1px solid rgb(221, 221, 221);
               " >';			
       
    $html .= '<li> <input id="radio-prod" class="" name="prod_or_test" value="prod" type="radio" '  . $prod_checked . '><span>'    . __("Production Site", "vtprd") .  '</span> </li>';
    $html .= '<li> <input id="radio-test" class="" name="prod_or_test" value="test" type="radio" '  . $test_checked . '><span>'    . __("Test Site", "vtprd") .  '</span> </li>';
    $html .= '<li> <input id="radio-demo" class="" name="prod_or_test" value="demo" type="radio" '  . $demo_checked . '><span>'    . __("3-Day Full Free Demo License", "vtprd") .  '</span> </li>';     
    $html .= '</ul>';	
  	echo $html;    
    return;
  }
  
  

 
  function vtprd_prod_url_callback() {    //opt4
  	$options = get_option( 'vtprd_license_options' );	
    $html = '<div class="production_url_for_test">';
    $html .= '<textarea type="text" id="prod_url_supplied_for_test_site"  rows="1" cols="60" name="vtprd_license_options[prod_url_supplied_for_test_site]">' . $options['prod_url_supplied_for_test_site'] . '</textarea>';
     
    $html .= '<br><br><p><em>';
    $html .= 'There is a strict relationship Required between the Production Site Name and the Test Site Name.';
    $html .= '<br><br>';
    $html .= 'The Test site **must** be a <strong>subdomain of the Production Site</strong>';
    $html .= '<br><br>';
    $html .= '<strong>The Test site URL **must Include** </strong> one of these:';
    $html .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  "test." &nbsp;&nbsp; or';
    $html .= '&nbsp;&nbsp;  "stage." &nbsp;&nbsp; or ';
    $html .= '&nbsp;&nbsp;  "staging." &nbsp;&nbsp; or ';
    $html .= '&nbsp;&nbsp;  "beta." &nbsp;&nbsp; or ';
    $html .= '&nbsp;&nbsp;  "demo." ';
    $html .=  '</em></p>'; 
    $html .= '<br>';
    $html .= '<strong>For Example:</strong>';
    $html .= '<br>&nbsp;&nbsp; Production URL: &nbsp;&nbsp;&nbsp; www.sellyourstuff.com';
    $html .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Test URL: &nbsp;&nbsp;&nbsp; www<strong><em>.test.</em></strong>sellyourstuff.com';
    $html .=  '</div>'; 
      	
  	echo $html;
    return;
  }

  function vtprd_license_phone_home($input, $action, $skip_admin_check=null) {
 //test test test function vtprd_license_phone_home($input, $action, $skip_admin_check=null)) {
  //$skip_admin_check is for phone_home exec from function vtprd_maybe_recheck_license_activation()
    
//  error_log( print_r(  'Begin vtprd_license_phone_home' , true ) );

     global $vtprd_license_options;
   /*
   verify basic stuff:
    license supplied, email supplied
    if test selected, PRO is supplied
    if test selected, enforce the '.test. etc node requirement'
        IN THE ERROR msg for node requirement, explain that 
          if TEST is 1st installation (no Prod yet), you can register the test as PROD
          and then Deactivate the test and re-register as PROD
          
    COUNT LICENSE NOT FOUND ==>> ONLY error NOT counted at the Server
    3 STRIKES you're out...


  ********************************************************
  *  IF TEST
  *  popup field PROD URL ==>> put into the regular URL field
  *  grab the current URL which is the test URL in this case
  *  put in into TEST_URL      
  *********************************************************    

   */
   
		// run a quick security check
	 	 if ($skip_admin_check == 'yes') {
        $carry_on = true;  
     } else {
       if  ( ! check_admin_referer( 'vtprd_nonce', 'vtprd_nonce' ) ) {	
          return; // get out if we didn't click the Activate button
        }
     }

   
		// retrieve the license from the database
		$license       = trim( $input['key'] );
    
    $email         = $input['email'];
    $prod_or_test  = $input['prod_or_test'];
    
    //********************************************
    //$url ALWAYS has the PROD url in it, for BOTH 'prod_or_test' = PROD and DEMO
    //********************************************  
    if ( ($prod_or_test == 'prod') ||
         ($prod_or_test == 'demo') ) {
      $url = $input['url'];
      $test_url = '';    
    } else {
      $test_url = $input['url'];
      $url = $input['prod_url_supplied_for_test_site']; //PROD URL off of screen
      $url = $this->vtprd_strip_out_http($url);
      $input['prod_url_supplied_for_test_site'] = $url;
    }

//********************************************************** 
//TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST TEST 
// $url = 'www.nefarious.com';
//$url = 'prod.varktech.com';
// $ip_address = '1.2.3.4';
//**********************************************************   
      
    if ($prod_or_test == 'demo') {
      $item_name = VTPRD_ITEM_NAME . ' Demo';
      $item_id   = VTPRD_ITEM_ID_DEMO;
    }  else {
      $item_name = VTPRD_ITEM_NAME;
      $item_id   = VTPRD_ITEM_ID;
    }
    
    /*
    //**************************
    //* Begin GET IP - somehwnat complex logic to get Host's IP address!!!!!!!!!!
    //**************************
    //get host IP, from http://stackoverflow.com/questions/5800927/how-to-identify-server-ip-address-in-php
    $host = gethostname();
    
    // from http://stackoverflow.com/questions/4305604/get-ip-from-dns-without-using-gethostbyname
    $ip = $this->vtprd_getAddrByHost($host);  //returns $host if IP not found
    if ($ip == $host) {  //if the address did not resolve, then use gethostbyname
      $ip = gethostbyname($host);
    }
    */
    //the definitive solution!!!!!!!!!!!
    //$ip = vtprd_get_ip(); ==>> now in vtprd_get_ip_address
    //end GET IP
    
      
    // data to send in our API request
		$api_params = array(
			'edd_action'   => $action,
			'license' 	   => $license,
			'item_name'    => urlencode( $item_name ), // the name of our product in VTPRD
      'item_id'      => $item_id , // the ID of our product in VTPRD
			'url'          => urlencode( $url ),
      'prod_or_test' => $prod_or_test,
      'test_url'     => urlencode( $test_url ),
      'email'        => urlencode($email),
      
      
      'ip_address'   => vtprd_get_ip_address() 
//TEST TEST TEST           'ip_address'   => $ip_address 
      
      
      
      
      // from http://stackoverflow.com/questions/5800927/how-to-identify-server-ip-address-in-php
      // 'ip_address'   =>  $_SERVER["SERVER_ADDR"]  - don't use this either!! 
      //'ip_address' = $ip  
		);

		// Call the custom API.
    //https://wordpress.org/support/topic/wp_remote_post-and-timeout ==>> adjust timeout for efficiency during testing
    /*
    compress was introduced in WordPress 2.6 and allows you to send the body of the request in a compressed format. This will be outside the scope of our future articles.
    decompress is similar to compress except that it's on our end - if compressed data is received, this will allow us to decompress the content before doing any further work or processing on it.
    */
     $remote_data = array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) ;
 

		$response = wp_remote_post( VTPRD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
    
    $input['params_sent_to_host'] = $api_params;
    $input['last_response_from_host'] = $response;


		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			//no change to input, just send back
      $input['msg']     =  "License activation function was temporarily busy, please try again!";
      return $input;    
    }


		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
    
    //COMES BACK VALID OR INVALID
    if (isset($license_data->status)) {
      $input['status']      =  $license_data->status;
    }
    if (isset($license_data->state)) {
      $input['state']      =  $license_data->state;
    }
    if (isset($license_data->msg)) {
      $input['msg']         =  $license_data->msg;
    }
    if (isset($license_data->msg)) {
      $input['diagnostic_msg'] =  $license_data->diagnostic_msg;
    }
    if (isset($license_data->strikes)) {
      $input['strikes']     =  $license_data->strikes;
    }             

    $input['last_action'] =  $action;
    
    if (isset($license_data->expires)) {
      $input['expires']  =  $license_data->expires;
    }

      // **********************************************************
      // STATUS: valid / invalid / unregistered (default)
      // STATE:  active / deactivated / pending (error but not yet suspended) / suspended-by-vendor
      // **********************************************************   
    
    if ($license_data->state == 'suspended-by-vendor') {
      //deactivate PRO plugin     
      vtprd_deactivate_pro_plugin();
      vtprd_increment_license_count();
    }  

    If ($input['status'] == 'valid') {
      $input['last_successful_rego_ts'] = time(); 
      $input['last_successful_rego_date_time'] = date("Y-m-d H:i:s"); 
    } else {
      $input['last_failed_rego_ts'] = time(); 
      $input['last_failed_rego_date_time'] = date("Y-m-d H:i:s");     
    }
    
    //in case the USEr used the old SESSIONID, move the returned LICENSE KEY back into the License field.    
    if ( ($license_data->key > ' ') &&
         ($license_data->key != $input['key']) ) {
      $input['key'] = $license_data->key;    
    }
 
   return $input;
  } 


  public function vtprd_enqueue_setup_scripts($hook_suffix) {
    switch( $hook_suffix) {        //weird but true
      case 'vtprd-rule_page_vtprd_license_options_page':                
        wp_register_style('vtprd-admin-style', VTPRD_URL.'/admin/css/vtprd-admin-style-' .VTPRD_ADMIN_CSS_FILE_VERSION. '.css' );  //v1.1.0.7
        wp_enqueue_style ('vtprd-admin-style');
        wp_register_style('vtprd-admin-settings-style', VTPRD_URL.'/admin/css/vtprd-admin-settings-style.css' );  
        wp_enqueue_style ('vtprd-admin-settings-style');
      break;
    }
  }    

  
  function vtprd_validate_setup_input( $input ) {
 //error_log( print_r(  'Begin  vtprd_validate_setup_input' , true ) ); 
    //Get the existing settings!
    $existing_license_options = get_option( 'vtprd_license_options' );
  
/*  COMMENTED FOR TESTING ONLY!  
    //*********************************
    // BAIL if suspended 
    //*********************************
    if ($existing_license_options['state'] == 'suspended-by-vendor') {
      $admin_errorMsg = $existing_license_options['msg'] . ' - no action possible without contacting Vendor';
      $admin_errorMsgTitle = 'License Key Suspended';
      add_settings_error( 'vtprd Options', $admin_errorMsgTitle , $admin_errorMsg , 'error' );
      return $existing_license_options;  
    }
    //***************************************
 */ 
  
    $new_combined_options = array_merge($existing_license_options, $input);
  
  
    //did this come from on of the secondary buttons?
    $activate     = ( ! empty($input['activate']) ? true : false );
    $deactivate   = ( ! empty($input['deactivate']) ? true : false );
    $nuke_info    = ( ! empty($input['nuke-info']) ? true : false );
    $reset_fatal_counter    = ( ! empty($input['reset_fatal_counter']) ? true : false );
  
    //global $vtprd_license_options; 
  
    
    if ($nuke_info) {
      $license_options = $this->vtprd_set_default_options();
      return $license_options;
    }
     
    if ($reset_fatal_counter) {      
      //if an unlicensed customer needs to clear the error state after license purchase
      update_option('vtprd_license_count', 0 );
      delete_option('vtprd_rego_clock');
      $license_options = $this->vtprd_set_default_options();    
      return $license_options;
    }
    
    
    $settings_error = false;
  
    if ( (isset($new_combined_options['key'])) &&
         ($new_combined_options['key'] > ' ') ) {
      $carry_on = true;    
    } else {
      $admin_errorMsg = 'License Key required';
      $admin_errorMsgTitle = 'License Key required';
      add_settings_error( 'vtprd Options', $admin_errorMsgTitle , $admin_errorMsg , 'error' );
      $settings_error = true;    
    }
    
    if ( (isset($new_combined_options['email'])) &&
         ($new_combined_options['email'] > ' ') ) {
      $carry_on = true;    
    } else {
      $admin_errorMsg = 'Registered purchaser email address required';
      $admin_errorMsgTitle = 'Registered purchaser email address';
      add_settings_error( 'vtprd Options', $admin_errorMsgTitle , $admin_errorMsg , 'error' );
      $settings_error = true;     
    }


    //Pick up RADIO button
    $new_combined_options['prod_or_test'] = $_REQUEST['prod_or_test'];
    
    /* defaults to PROD, not necessary
    if ( (isset($new_combined_options['prod_or_test'])) &&
    */
    if ($new_combined_options['prod_or_test'] == 'test')  {
      
      if ( (isset($new_combined_options['prod_url_supplied_for_test_site'])) &&
           ($new_combined_options['prod_url_supplied_for_test_site'] > ' ') ) {
  
  
        //*****************************************
        //TEST URL MUST HAVE '.demo.' '.test.' '.stage.' '.staging.' '.beta.'
        //*****************************************
        
        //current site IS a TEST site
  
        if ( (strpos($new_combined_options['url'],'test.') !== false)  ||
             (strpos($new_combined_options['url'],'demo.') !== false)  ||
             (strpos($new_combined_options['url'],'beta.') !== false)  ||
             (strpos($new_combined_options['url'],'stage.') !== false)  ||
             (strpos($new_combined_options['url'],'staging.') !== false) ) {
           $carry_on = true; 
          
        } else {
          $admin_errorMsg = 'This Test site Does NOT meet naming requirements, must be a production subdomain and have "test." or "beta." or "demo." or "stage." or "staging." in the URL!';
          $admin_errorMsgTitle = 'TEST site registration';
          add_settings_error( 'vtprd Options', $admin_errorMsgTitle , $admin_errorMsg , 'error' );
          $settings_error = true;      
  
        }
        
        if (!$settings_error) {
          //*****************************************
          //Require TEST URL to be a SUBDOMAIN of PROD URL
          //  last 2 nodes XXXXX.com  must be the SAME
          //*****************************************
         
          //remove slashes
          $test_url_no_slashes = str_replace ('/', '', $new_combined_options['url']);
          //explode parts
          $test_url = explode('.',$test_url_no_slashes);
      
          $test_url_last_piece = array_pop($test_url); //gets last entry, reduces source array
          $test_url_2nd_to_last_piece = array_pop($test_url);
          
          //LAST 2 NODES OF URL FOR COMPARISON
          $test_url_last2 = $test_url_2nd_to_last_piece . '.' .$test_url_last_piece ;
          
 //error_log( print_r(  '$test_url_2nd_to_last_piece= ' .$test_url_2nd_to_last_piece, true  ) ); 
 //error_log( print_r(  '$test_url_2nd_to_last_piece= ' .$test_url_last_piece, true  ) );        
          
          //remove slashes
          $prod_url = str_replace ('/', '', $new_combined_options['prod_url_supplied_for_test_site']);
          //explode parts
          $prod_url_array = explode('.',$prod_url);

          $prod_url_last_piece = array_pop($prod_url_array); //gets last entry, reduces source array
 
          //only 1 other piece left
          if ( sizeof($prod_url_array) == 1) {
            $prod_url_2nd_to_last_piece = $prod_url_array[0];
          } else {
            $prod_url_2nd_to_last_piece = array_pop($prod_url_array);
          }

 //error_log( print_r(  '$prod_url_2nd_to_last_piece= ' .$prod_url_2nd_to_last_piece, true  ) ); 
 //error_log( print_r(  '$prod_url_2nd_to_last_piece= ' .$prod_url_last_piece, true  ) );

          //LAST 2 NODES OF URL FOR COMPARISON
          $prod_url_last2 = $prod_url_2nd_to_last_piece . '.' .$prod_url_last_piece ;    

 //error_log( print_r(  '$test_url_last2= ' .$test_url_last2 .'x', true  ) ); 
 //error_log( print_r(  '$prod_url_last2= ' .$prod_url_last2 .'x', true  ) );


          if ($test_url_last2 != $prod_url_last2) {
            $admin_errorMsg = 'Test site Does NOT meet naming requirements - last 2 nodes of the TEST URL (this site) and the PROD URL (supplied) Must be the same!';
            $admin_errorMsgTitle = 'TEST site registration';
            add_settings_error( 'vtprd Options', $admin_errorMsgTitle , $admin_errorMsg , 'error' );
            $settings_error = true;   
          } 
        }     
   
      } else {
        $admin_errorMsg = 'TEST site registration ALSO requires the PROD site URL';
        $admin_errorMsgTitle = 'TEST site registration';
        add_settings_error( 'vtprd Options', $admin_errorMsgTitle , $admin_errorMsg , 'error' );
        $settings_error = true;    
      }    
    } else {
      //if PROD, clear out test site data
      $new_combined_options['prod_url_supplied_for_test_site'] = null;
    }
    
    if ($settings_error) {
      $new_combined_options['status'] = 'invalid';
      $new_combined_options['state']  = 'pending';
      $new_combined_options['msg']    = $admin_errorMsg;
      return $new_combined_options;    
    }
    

    switch( true ) { 
      case $activate        === true : 
          //if already active, no action required
          if ( $new_combined_options['state'] == 'active') { 
            return $new_combined_options;   
          }
          
          $action = 'activate_license';
          
          $new_combined_options = $this->vtprd_license_phone_home($new_combined_options, $action);
//TEST    $new_combined_options['msg'] = 'License Activated';
          
          /*
          //clear out plugin version fields for new activation:
          $new_combined_options['pro_plugin_version_status'] = null;
          $new_combined_options['pro_version'] = null;
          */          
          //*****
          // MESSAGING handled in main plugin file, in function vtprd_maybe_pro_license_error
          //*****
          
          /*  INVALID  done in main file
          If ($new_combined_options['status'] == 'invalid') {
            $vtprd_license_options = $new_combined_options; //OVERWRITE temporarily so that admin_notices can pick up the text          
            add_action( 'admin_notices', 'vtprd_license_error_notice' );        
          }
          */
          If ($new_combined_options['status'] == 'valid') {
            //MESSAGE, built-in perhaps ===>>> always displays status
          }
   
        break;
      case $deactivate       === true :  
          //if already deactivated, no action required
          if ( $new_combined_options['state'] == 'deactivated') { 
            return $new_combined_options;   
          }
          
          $action = 'deactivate_license';
          $new_combined_options = $this->vtprd_license_phone_home($new_combined_options, $action);
          
          /*
          //clear out plugin version fields for new activation:
          $new_combined_options['pro_plugin_version_status'] = null;
          $new_combined_options['pro_version'] = null;
          $new_combined_options['msg'] = 'License Deactivated';
          */
          
          //*****
          // MESSAGING handled in main plugin file, in function vtprd_maybe_pro_license_error
          //*****
                  
          /* INVALID done in main file
          add_action( 'admin_notices', 'vtprd_license_error_notice' );
          */ 
        break;

      default:   //standard update button hit...                 
      
        break;
    }
 

         
      /*
      CLIENT tracks these statuses:
      
          $failure_msg = 'License Not Found'
          $failure_msg = 'Email Not Supplied';
          $failure_msg = 'Prod_or_Test value Not Supplied';
          $failure_msg = 'Test URL Not Supplied';
          $failure_msg = 'IP Address Not Supplied';
          $failure_msg = 'Prod URL not supplied for Test URL registration';
          
          $vark_args['verify_response'] = 'test_name_invalid'
          $vark_args['verify_response'] = 'license_invalid'
          $vark_args['verify_response'] = 'email_mismatch'
          $vark_args['verify_response'] = 'test_already_activated'; //info only, not a strike
          $vark_args['verify_response'] = 'prod_already_activated'; //info only, not a strike
      
      */
    

    //SUSPEND LOCALLY ONLY for THESE issues
    if ($new_combined_options['status'] == 'invalid') {
      if ( ($new_combined_options['msg'] == 'Email supplied does not match email address for License') ||
           ($new_combined_options['msg'] == 'License Not Found') ||
           ($new_combined_options['msg'] == 'Email Not Supplied') ||
           ($new_combined_options['msg'] == 'Prod_or_Test value Not Supplied') ||
           ($new_combined_options['msg'] == 'Test URL Not Supplied') ||
           ($new_combined_options['msg'] == 'IP Address Not Supplied') ||
           ($new_combined_options['msg'] == 'Prod URL not supplied for Test URL registration') ||
           ($new_combined_options['diagnostic_msg'] == 'different_test_site_already_registered' ) ||
           ($new_combined_options['diagnostic_msg'] == 'test_name_invalid' ) ||
           ($new_combined_options['diagnostic_msg'] == 'license_invalid' ) ||
           ($new_combined_options['diagnostic_msg'] == 'item_name_mismatch' ) ||
           ($new_combined_options['diagnostic_msg'] == 'email_mismatch' ) ||
           ($new_combined_options['diagnostic_msg'] == 'demo_license_expired')  ) {
      
        //strikes are NOT increased at the HOST, only HERE  

        if ($existing_license_options['strikes'] > $new_combined_options['strikes'] ) {
          $new_combined_options['strikes'] = $existing_license_options['strikes'];
        }        
        $new_combined_options['strikes']++;

        if ($new_combined_options['strikes'] >= 5) {
           $new_combined_options['state']  = 'suspended-by-vendor';
           $new_combined_options['status'] = 'invalid'; 
           $new_combined_options['diagnostic_msg'] = 'suspended after 5 strikes!';
           vtprd_deactivate_pro_plugin();
           vtprd_increment_license_count();           
    			 //not needed, taken care of elsewhere here...
           //$new_combined_options['msg']    = 'License Suspended by Vendor.  Please contact www.varktech.com/support for more Information.';     
        }
        $new_combined_options['strikes_possible'] = 5;
      } else {
        $new_combined_options['strikes_possible'] = 3;      
      }
    } 


    return $new_combined_options;                       
  } 



  //from http://stackoverflow.com/questions/15699101/get-client-ip-address-using-php
  public  function  vtprd_strip_out_http($url) {
      $url = str_replace( 'https://', '', $url  ) ; 
      $url = str_replace( 'http://', '', $url  ) ; 
      $url = rtrim($url, "/" ); //remove trailing slash
      return $url;
  }


  /*
  // from http://stackoverflow.com/questions/4305604/get-ip-from-dns-without-using-gethostbyname
  public function vtprd_getAddrByHost($host, $timeout = 3) {
   $query = `nslookup -timeout=$timeout -retry=1 $host`;
   if(preg_match('/\nAddress: (.*)\n/', $query, $matches))
      return trim($matches[1]);
   return $host;
  }
  */


} //end class

$vtprd_license_options_screen = new VTPRD_License_Options_screen;


/*
PLUGIN UPDATER
*/

if( !class_exists( 'VTPRD_Plugin_Updater' ) ) {
	// load our custom updater
  include ( VTPRD_DIRNAME . '/admin/vtprd-plugin-updater.php');   
}


//***************************
add_action( 'admin_init', 'vtprd_maybe_exec_plugin_updater', 0 );
//***************************
function vtprd_maybe_exec_plugin_updater() {
 
  global $vtprd_license_options;
  $vtprd_license_options = get_option( 'vtprd_license_options' );


  //demo licenses are NEVER updated
  if ($vtprd_license_options['prod_or_test'] == 'demo') {  
    return;
  } 
  
  if ( ($vtprd_license_options['state'] == 'suspended-by-vendor') ||
       ($vtprd_license_options['state'] == 'unregistered') ||
       ($vtprd_license_options['state'] == 'deactivated') ) { 
    return;
  }
    
  if (VTPRD_PRO_VERSION) {
    $pro_plugin_is_installed = true;
  } else {
    $pro_plugin_is_installed = vtprd_check_pro_plugin_installed();
  }     

  
  if ($pro_plugin_is_installed) {
     
  	// setup the updater
  	$edd_updater = new VTPRD_Plugin_Updater( VTPRD_STORE_URL, __FILE__, array(
  			'version' 	=> VTPRD_PRO_VERSION, 				// current version number
  		//gotten directly later
      //	'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
  			'item_name' => urlencode( VTPRD_ITEM_NAME ), 	// name of this plugin
  			'author' 	=> 'Vark'  // author of this plugin
  		)
  	);     
  }


  return;  
}


       
  /* ************************************************
  **   Admin - v1.1.5 new function
  *************************************************** */ 

  function vtprd_check_pro_plugin_installed() {
     
    // Check if get_plugins() function exists. This is required on the front end of the
    // site, since it is in a file that is normally only loaded in the admin.
    if ( ! function_exists( 'get_plugins' ) ) {
    	require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    $all_plugins = get_plugins();

    foreach ($all_plugins as $key => $data) { 
      if ($key == VTPRD_PRO_PLUGIN_FOLDER.'/'.VTPRD_PRO_PLUGIN_FILE) {
        return true;      
      } 
    } 
    
    return false;  
 
  }
  
     
  //****************************
  //  suspended PRO plugin is DEACTIVATED
  //****************************
  function vtprd_deactivate_pro_plugin() {
    //deactivate the PRO plugin, having FAILED licensing
    $plugin = VTPRD_PRO_PLUGIN_SLUG;
    if( is_plugin_active($plugin) ) {
	    deactivate_plugins( $plugin );
    }
  }
  
  
  function  vtprd_maybe_license_state_message() { 
 // error_log( print_r(  'Begin vtprd_maybe_license_state_message', true ) );
      global $vtprd_license_options;
      
      $pro_plugin_is_installed = vtprd_check_pro_plugin_installed();
      if (!$pro_plugin_is_installed) {
        if ($vtprd_license_options['state'] == 'suspended-by-vendor') {
          ?> <p class="yellow" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME; ?> Pro Plugin ** not installed **, no action required.  <br><br> However, license was previously suspended by vendor </strong></p> 
          <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a  href=" <?php echo VTPRD_PURCHASE_PRO_VERSION_BY_PARENT ; ?> "  title="Purchase a full Pro license"><?php _e('Purchase a full Pro license', 'vtprd'); ?></a>
          <?php
        
        } else {
          ?> <p class="green" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME; ?> Pro Plugin not installed, no action required. 
          <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a  href=" <?php echo VTPRD_PURCHASE_PRO_VERSION_BY_PARENT ; ?> "  title="Purchase a full Pro license"><?php _e('Purchase a full Pro license', 'vtprd'); ?></a>
           &nbsp; or &nbsp; 
          <a  href=" <?php echo VTPRD_PURCHASE_PRO_VERSION_BY_PARENT ; ?> "  title="Purchase a full Pro license"><?php _e('Get a 3-Day Full Free Demo License', 'vtprd'); ?></a>
          </strong></p><br> <?php 
        }
        
        return; 
        
      }

      //plugin is installed, if not active, and always gets the suspended message      
      if ($vtprd_license_options['state'] == 'suspended-by-vendor') {
        vtprd_license_suspended_message();
        return;
      }
 
      if ($vtprd_license_options['diagnostic_msg'] == 'demo_license_expired') {
        //vtprd_deactivate_pro_plugin();
        vtprd_demo_license_expired_message();
        return;
      }     
      
      
      //if not active, no other messages are displayed here.
      if (!defined('VTPRD_PRO_DIRNAME')) { 
        return;
      }
              
      switch ( $vtprd_license_options['prod_or_test'] ) { 
            case 'prod' : 
                $prod_or_test = ' - Production Site - ';
              break;
            case 'test' : 
                $prod_or_test = ' - Test Site - ';
              break;      
            case 'demo' : 
                $prod_or_test = ' - 3-Day Full Free Demo License - ';
              break;                
      }

     
      if ($vtprd_license_options['status'] == 'valid') {   
          switch ( $vtprd_license_options['state'] ) { 
            case 'active' : 
                if ( ($vtprd_license_options['last_action'] == 'activate_license') ||
                     ($vtprd_license_options['last_action'] == 'check_license') ) {
                  ?> <p class="green" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME . $prod_or_test; ?> <em> Activated Successfully! </em> </strong></p> <?php    
                } else { //tried to deactivate
                  ?> <p class="green" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME . $prod_or_test; ?> Deactivation failed, please try again! </strong></p> <?php 
                }
                
              break;
            case 'deactivated' : 
                if ( ($vtprd_license_options['last_action'] == 'deactivate_license') ||
                     ($vtprd_license_options['last_action'] == 'check_license') ) {
                  ?> <p class="green" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME . $prod_or_test; ?> <em> Deactivated Successfully! </em> </strong>
                     &nbsp;&nbsp;&nbsp;<span class="smallGreyText"> (Pro Plugin will not function until activated)</span>
                     </p>                  
                  <?php    
                } else { //tried to activate
                  ?> <p class="green" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME . $prod_or_test; ?> Activation failed, please try again! </strong></p> <?php 
                }
              break;                    

            default:                   
                //can't be any other state!!
              break;
          }
      } else { //'invalid' OR 'unregistered' path ==>> can't have a state of active or deactivated    
         switch ( $vtprd_license_options['state'] ) {  
          case 'unregistered' : 
              if ($vtprd_license_options['last_action'] > ' ') {
                ?> <p class="red" id="license-status-msg"><strong> <?php // echo VTPRD_ITEM_NAME . $prod_or_test; ?> Failed to Connect to Host.  Please Try Again. </strong></p> <?php
              } else { //no action taken yet
                if ($vtprd_license_options['pro_plugin_version_status'] == 'valid') {  //version status test is done IN ADVANCE of registration!
                ?> <p class="yellow" id="license-status-msg"><strong>License key registration required -  Pro Plugin will not function until registered. </strong>
                
                    <?php                     
                      $message =  '<strong>';       
                      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('- Register with a License Key ', 'vtprd') ;
                      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'     . __('- OR with a SessionID.', 'vtprd') ;
                      $message .=  '</strong>';
                      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('- If you do not have either ID, Go to <a href="https://www.varktech.com">Varktech.com</a>', 'vtprd') ;
                      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'     . __('- Log In and get your License Key to Register.', 'vtprd') ;
                      $message .=  '<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . __('- OR for older purchases, <em>where a SessionID was furnished</em>,', 'vtprd') ; 
                      $message .=  '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'     . __('- by Name and Email Address', 'vtprd') .'&nbsp;&nbsp;&nbsp; <a href="https://www.varktech.com/your-account/license-lookup/">License Key Lookup by Name and Email</a>' ;                                           
                      echo $message;                
                     ?>            
                  </p> <?php
                }
              }
              
            break;
          case 'pending' :
              switch ( $vtprd_license_options['last_action'] ) {  
                  case 'activate_license' :
                  case 'check_license' :
                      ?> <p class="yellow" id="license-status-msg" style="font-size:12px;"><strong> <?php echo VTPRD_ITEM_NAME . $prod_or_test; ?> is in a Pending Activation state. <br><br> Please edit the Licensing Information and then activate. </strong></p> <?php
                     break;
                  case 'deactivate_license' :
                      ?> <p class="yellow" id="license-status-msg" style="font-size:12px;"><strong> <?php echo VTPRD_ITEM_NAME . $prod_or_test; ?> is in a Pending Deactivation state. <br><br> Please edit the Licensing Information and then activate. </strong></p> <?php
                     break;                             
              }
            break;                    
          case 'suspended-by-vendor' :
              vtprd_license_suspended_message();
            break;                    
    
        }  
    }
    
    return;
  }
 

  function  vtprd_demo_license_expired_message() { 
   //error_log( print_r(  'Begin vtprd_demo_license_expired_message', true ) );
    
    global $vtprd_license_options;
    
    ?> <p class="yellow" id="license-status-msg"><strong>

        &nbsp;&nbsp;&nbsp;   3-Day Demo license has expired. 

        <br><br>&nbsp;&nbsp;&nbsp;
         <a  href=" <?php echo VTPRD_PURCHASE_PRO_VERSION_BY_PARENT ; ?> "  title="Purchase a full Pro license:"><?php _e('Purchase a full Pro license', 'vtprd'); ?></a>

        <span style="color:black !important; font-size:14px; ">
         
          and then:
          
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  1.&nbsp; Just enter the new Pro License Key and purchasing email address
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  2.&nbsp; Select "Activation Type" of "Production Site" (or "Test Site", if desired)
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  3.&nbsp; Click 'Activate License'

        </span>
        </strong></p> <?php      
    return;
  } 
  
  function  vtprd_license_suspended_message() { 
    //error_log( print_r(  'Begin vtprd_license_suspended_message', true ) );
    
    global $vtprd_license_options;
    
    ?> <p class="red" id="license-status-msg"><strong>
        <span style="color:black !important;">
        <?php echo '<br>&nbsp;&nbsp;&nbsp; ' .VTPRD_ITEM_NAME;  ?> 
        </span>
        
        <br><br>&nbsp;&nbsp;&nbsp;  <em>*** License Suspended by Vendor due to a breach of Licensing Rules. ***</em>

        <br><br>&nbsp;&nbsp;&nbsp;  <em>*** Pro Plugin Deactivated ***</em> 
        
        <span style="color:black !important; font-size:14px; "> 
 
        <br><br>&nbsp;&nbsp;&nbsp; License suspended because of too many License activations, or a number of failed attempts at License activation:

        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  1. Multiple failed attempts at a <em>Single Site</em> &nbsp;&nbsp; (or a 3-day Demo license has expired)
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  - OR -
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  2. <em>Multiple production/test sites</em> have attempted to <em>register with the same single-site license key</em>.
        
        <span style="color:grey !important;"> 
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  (with a Single-Site License, you are allowed ONE Production and One Test Site Registration.)
        </span>
        
        <span style="background-color: RGB(255, 255, 180) !important;"> 
        <br><br>&nbsp;&nbsp;&nbsp; For Assistance, Please contact <a  href="www.varktech.com/support"  title="Support">www.varktech.com/support</a> and supply the following Information: 
        </span>
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  1. Licensing info - copy using Button "Show licensing Info" below -
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Follow the copy directions, and paste the info into your email to varktech.
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  2. License purchaser name and address, as supplied at purchase time.
        
        <span style="color:grey !important;"> 
        <br><br><em>&nbsp;&nbsp;&nbsp; (This message displays when the Pro version is installed, regardless of whether it's active)</em>
        </span>

        </span>
        <br><br></strong></p> <?php      
    return;
  }
  
  //from plugin "Force Plugin Updates Check", hook in main plugin file
	function vtprd_maybe_force_plugin_updates_check() {
      //only activated by a button available    	
      if( ! isset( $_GET['action'] ) || 'force_plugin_updates_check' != $_GET['action'] ) {
    		return;
    	}    
    	if( ! current_user_can( 'install_plugins' ) ) {
    		return;
    	}    
    	set_site_transient( 'update_plugins', null );    
    	wp_safe_redirect( network_admin_url( 'update-core.php' ) ); exit;  
   return;
  } 
  
  //*****************************************
	//CLEAR data on PRO plugin DELETE, to get rid of stored status used in VERION Comparison
  //*****************************************
  function vtprd_maybe_delete_pro_plugin_action() {
   
      $pageURL = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
  //error_log( print_r(  'Begin vtprd_maybe_delete_pro_plugin_action, URL= ' .$pageURL , true ) );
  
      if ( (strpos($pageURL,'plugins.php') !== false)  &&
           (strpos($pageURL,'delete-selected') !== false) &&
           (strpos($pageURL,VTPRD_PRO_PLUGIN_FILE) !== false) ) {
          $carry_on = true;
      } else {
 //error_log( print_r(  'vtprd_maybe_delete_pro_plugin_action ENTRY exit =  ' , true ) );        
        return;
    	}    
    	if( ! current_user_can( 'install_plugins' ) ) {
  //error_log( print_r(  'vtprd_maybe_delete_pro_plugin_action permission exit =  ' , true ) );      
    		return;
    	}    

      
      //this check prevents a recurrence, as this is executed twice during a delete action...
      $vtprd_license_options = get_option( 'vtprd_license_options' );
      if ($vtprd_license_options['pro_version'] > null) {
        update_option('vtprd_pro_plugin_deleted', 'yes');
      }

      /*  this update yields inaccurate results.  Moved to main plugin file.    
      $vtprd_license_options['pro_version'] = null;      
      $vtprd_license_options['pro_plugin_version_status'] = null;
      $vtprd_license_options['pro_minimum_free_version'] = null;
      */
       
  
   return;
  }  
  
  //increment license count when SUSPENDED
  function vtprd_increment_license_count() { 
      $vtprd_license_count = get_option( 'vtprd_license_count');
      if (!$vtprd_license_count) {  
        $vtprd_license_count = 0;
      }
      $vtprd_license_count++;
      update_option('vtprd_license_count', $vtprd_license_count);      
   return;
  }
  
  //increment license count when SUSPENDED
  function vtprd_update_notice() {
  	$info = '<br>After this update, PRO version will require Registration. <br>Please have your License ID/Session ID and Purchaser Email ready.';
  	echo '<span class="spam">' . strip_tags( $info, '<br><a><b><i><span>' ) . '</span>';      
   return;
  }
      

  
  /*
  //from https://code.garyjones.co.uk/get-wordpress-plugin-version
   function vtprd_get_pro_plugin_version() {
    	if ( ! function_exists( 'get_plugins' ) )
    		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    	$plugin_folder = get_plugins( '/' .VTPRD_PRO_PLUGIN_FOLDER);
    	return $plugin_folder[VTPRD_PRO_PLUGIN_FILE]['Version'];
  }
  */
