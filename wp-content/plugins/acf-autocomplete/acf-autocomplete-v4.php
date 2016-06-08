<?php

class acf_field_autocomplete extends acf_field {

    // vars
    var $settings, // will hold info such as dir / path
        $defaults; // will hold default field options
    var $rest_route = 'http://blog.chefsemporiumct.com/wp-json/wp/v2/';

    /*
     *  __construct
     *
     *  Set name / label needed for actions / filters
     *
     *  @since	3.6
     *  @date	23/01/13
     */

    function __construct() {
        // vars
        $this->name = 'autocomplete';
        $this->label = __('Autocomplete');
        $this->category = __("Relational", 'acf'); // Basic, Content, Choice, etc
        $this->defaults = array(
            'post_type' => array('all'),
            'multiple' => 0,
            'allow_null' => 0
        );
        // do not delete!
        parent::__construct();
        // settings
        $this->settings = array(
            'path' => apply_filters('acf/helpers/get_path', __FILE__),
            'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
            'version' => '1.0.0'
        );
        add_action('wp_ajax_autocomplete_suggestions', array($this, 'autocomplete_handler_suggestions'));
    }

    /*
     *  create_options()
     *
     *  Create extra options for your field. This is rendered when editing a field.
     *  The value of $field['name'] can be used (like below) to save extra data to the $field
     *
     *  @type	action
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$field	- an array holding all the field's data
     */

    function create_options($field) {
        // defaults?

        $field = array_merge($this->defaults, $field);
        // key is needed in the field names to correctly save the data
        $key = $field['name'];

        // Create Field Options HTML
        ?>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label for=""><?php _e("Post Type", 'acf'); ?></label>
            </td>
            <td>
                <?php
                $choices = array(
                    'all' => __("All", 'acf')
                );
                $choices = apply_filters('acf/get_post_types', $choices);

                do_action('acf/create_field', array(
                    'type' => 'select',
                    'name' => 'fields[' . $key . '][post_type]',
                    'value' => $field['post_type'],
                    'choices' => $choices,
                    'multiple' => 1,
                ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e("Allow Null?", 'acf'); ?></label>
            </td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'radio',
                    'name' => 'fields[' . $key . '][allow_null]',
                    'value' => $field['allow_null'],
                    'choices' => array(
                        1 => __("Yes", 'acf'),
                        0 => __("No", 'acf'),
                    ),
                    'layout' => 'horizontal',
                ));
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo $this->name; ?>">
            <td class="label">
                <label><?php _e("Select multiple values?", 'acf'); ?></label>
            </td>
            <td>
                <?php
                do_action('acf/create_field', array(
                    'type' => 'radio',
                    'name' => 'fields[' . $key . '][multiple]',
                    'value' => $field['multiple'],
                    'choices' => array(
                        1 => __("Yes", 'acf'),
                        0 => __("No", 'acf'),
                    ),
                    'layout' => 'horizontal',
                ));
                ?>
            </td>
        </tr>
    <?php
    }

    /*
     *  create_field()
     *
     *  Create the HTML interface for your field
     *
     *  @param	$field - an array holding all the field's data
     *
     *  @type	action
     *  @since	3.6
     *  @date	23/01/13
     */

    function create_field($field) {
        // defaults?
        /*
          $field = array_merge($this->defaults, $field);
         */

        // perhaps use $field['preview_size'] to alter the markup?
        // create Field HTML

        // vars
        $args = array(
            'numberposts' => -1,
            'post_type' => null,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => array('publish', 'private', 'draft', 'inherit', 'future'),
            'suppress_filters' => false,
        );

        // load all post types by default
        if (in_array('all', $field['post_type'])) {
            $field['post_type'] = apply_filters('acf/get_post_types', array());
        }

        $input_value = "";
        $itemlist = "";
        $field_value = array();
        if (!empty($field['value'])) {
            $post_ids_array = explode("—", $field['value']);
            $post_ids = array();
            foreach ($post_ids_array as $p_a) {
                $post_ids[] = trim($p_a);
            }
            foreach ($post_ids as $id) {
                $str = file_get_contents($this->rest_route.'posts/'.$id);
                $json = json_decode($str, true); // decode the JSON into an associative array
                if(isset($json["id"])){
                    $field_value[] = $json["id"];
                    $link = $json["link"];
                    $title = $json["title"]["rendered"];
                    $id_post = $json["id"];
                    $itemlist.="<h3 class='ui-item-list'><span class='ui-accordion-header-icon ui-icon ui-icon-triangle-1-e'></span><span class='ui-item-list-content'>" . $title . " (id: " . $id_post . ")</span><a class='acf-button-delete ir' href='#'  data-item-id='" . $id_post . "'  data-input_id='" . esc_attr($field['id']). "' >Remove</a></h3>";
                    $post_labels[] = $title;
                }
            }

        }

        // Change Field into a select
        $field['type'] = 'select';
        $field['choices'] = array();
        $field['value'] = implode("—", $field_value);

        $str_post_type = implode("—", $field['post_type']);

        // create field
        //do_action('acf/create_field', $field);
        ?>
        <input type="text" name="search" placeholder="type the title of the <?php echo implode(",", $field['post_type']); ?> to add..." class="search-autocomplete" id="search-autocomplete-<?php echo esc_attr($field['id']); ?>" value="" data-post_type="<?php echo $str_post_type; ?>" data-input_id="<?php echo esc_attr($field['id']); ?>" />
        <input type="hidden" id="<?php echo esc_attr($field['id']); ?>" class="<?php echo esc_attr($field['class']); ?> search-autocomplete-hidden" name="<?php echo esc_attr($field['name']); ?>" value="<?php echo esc_attr($field['value']); ?>">
        <div class="field-items box-autocomplete">
            <h4><strong><?php echo ucfirst(implode(",", $field['post_type'])); ?> List</strong></h4>
            <?php
            if (!empty($itemlist)) {
                echo $itemlist;
            } else {

                echo "<p class='empty-list-msg'>No items in list.<p>";
            }
            ?>
        </div>
    <?php
    }

    /*
     *  input_admin_enqueue_scripts()
     *
     *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
     *  Use this action to add CSS + JavaScript to assist your create_field() action.
     *
     *  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
     *  @type	action
     *  @since	3.6
     *  @date	23/01/13
     */

    function input_admin_enqueue_scripts() {

        // Note: This function can be removed if not used
        // register ACF scripts
        wp_register_script('acf-input-autocomplete', $this->settings['dir'] . 'js/input.js', array('acf-input', 'jquery', 'jquery-ui-autocomplete'), $this->settings['version']);
        wp_register_style('acf-input-autocomplete', $this->settings['dir'] . 'css/input.css', array('acf-input',), $this->settings['version']);

        // Register our jQuery UI style and our custom javascript file
        wp_register_style('acf-input-autocomplete-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

        // scripts
        wp_enqueue_script(array(
            'acf-input-autocomplete',
        ));

        // styles
        wp_enqueue_style(array(
            'acf-input-autocomplete',
            'acf-input-autocomplete-jquery-ui'
        ));
    }

    /*
     *  input_admin_head()
     *
     *  This action is called in the admin_head action on the edit screen where your field is created.
     *  Use this action to add CSS and JavaScript to assist your create_field() action.
     *
     *  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
     *  @type	action
     *  @since	3.6
     *  @date	23/01/13
     */

    function input_admin_head() {
        // Note: This function can be removed if not used
    }

    /*
     *  field_group_admin_enqueue_scripts()
     *
     *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
     *  Use this action to add CSS + JavaScript to assist your create_field_options() action.
     *
     *  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
     *  @type	action
     *  @since	3.6
     *  @date	23/01/13
     */

    function field_group_admin_enqueue_scripts() {
        // Note: This function can be removed if not used
    }

    /*
     *  field_group_admin_head()
     *
     *  This action is called in the admin_head action on the edit screen where your field is edited.
     *  Use this action to add CSS and JavaScript to assist your create_field_options() action.
     *
     *  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
     *  @type	action
     *  @since	3.6
     *  @date	23/01/13
     */

    function field_group_admin_head() {
        // Note: This function can be removed if not used
    }

    /*
     *  load_value()
     *
     *  This filter is applied to the $value after it is loaded from the db
     *
     *  @type	filter
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$value - the value found in the database
     *  @param	$post_id - the $post_id from which the value was loaded
     *  @param	$field - the field array holding all the field options
     *
     *  @return	$value - the value to be saved in the database
     */

    function load_value($value, $post_id, $field) {
        // Note: This function can be removed if not used
        return $value;
    }

    /*
     *  update_value()
     *
     *  This filter is applied to the $value before it is updated in the db
     *
     *  @type	filter
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$value - the value which will be saved in the database
     *  @param	$post_id - the $post_id of which the value will be saved
     *  @param	$field - the field array holding all the field options
     *
     *  @return	$value - the modified value
     */

    function update_value($value, $post_id, $field) {
        // validate
        if (empty($value)) {
            return $value;
        }


        if (is_object($value) && isset($value->ID)) {
            // object
            $value = $value->ID;
        } elseif (is_array($value)) {
            // array
            foreach ($value as $k => $v) {

                // object?
                if (is_object($v) && isset($v->ID)) {
                    $value[$k] = $v->ID;
                }
            }

            // save value as strings, so we can clearly search for them in SQL LIKE statements
            $value = array_map('strval', $value);
        }
        return $value;
    }

    /*
     *  format_value()
     *
     *  This filter is applied to the $value after it is loaded from the db and before it is passed to the create_field action
     *
     *  @type	filter
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$value	- the value which was loaded from the database
     *  @param	$post_id - the $post_id from which the value was loaded
     *  @param	$field	- the field array holding all the field options
     *
     *  @return	$value	- the modified value
     */

    function format_value($value, $post_id, $field) {
        // empty?
        /*
          if (!empty($value)) {
          // convert to integers
          if (is_array($value)) {
          $value = array_map('intval', $value);
          } else {
          $value = intval($value);
          }
          }
         */
        // return value
        return $value;
    }

    /*
     *  format_value_for_api()
     *
     *  This filter is applied to the $value after it is loaded from the db and before it is passed back to the API functions such as the_field
     *
     *  @type	filter
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$value	- the value which was loaded from the database
     *  @param	$post_id - the $post_id from which the value was loaded
     *  @param	$field	- the field array holding all the field options
     *
     *  @return	$value	- the modified value
     */

    function format_value_for_api($value, $post_id, $field) {

        // no value?
        if (!$value) {
            return false;
        }

        // null?
        if ($value == 'null') {
            return false;
        }

        /* multiple / single
          if (is_array($value)) {


          // find posts (DISTINCT POSTS)
          $posts = get_posts(array(
          'numberposts' => -1,
          'post__in' => $post_ids,
          'post_type' => apply_filters('acf/get_post_types', array()),
          'post_status' => array('publish', 'private', 'draft', 'inherit', 'future'),
          ));

          $ordered_posts = array();
          foreach ($posts as $post) {
          // create array to hold value data
          $ordered_posts[$post->ID] = $post;
          }

          // override value array with attachments
          foreach ($value as $k => $v) {
          // check that post exists (my have been trashed)
          if (!isset($ordered_posts[$v])) {
          unset($value[$k]);
          } else {
          $value[$k] = $ordered_posts[$v];
          }
          }
          } else {
          $value = get_post($value);
          }
         */

        $post_ids_array = explode("—", $value);
        $post_ids = array();

        foreach ($post_ids_array as $p_a_news) {
            $post_ids[] = trim($p_a_news);
        }

        $posts = array();

        foreach($post_ids as $post_id){
            $str = file_get_contents($this->rest_route.'posts/'.$post_id);
            $json = json_decode($str, true); // decode the JSON into an associative array
            if(isset($json["id"])){
                $post = array();
                $post["title"] = $json["title"]["rendered"];
                $post["link"] = $json["link"];
                $post["id"] = $json["id"];
                $post["post_excerpt"] = $json["excerpt"]["rendered"];
                $post["format"] = $json["format"];
                $post["featured_image"] = "";

                $featured_image_id = $json["featured_media"];
                $str = file_get_contents($this->rest_route.'media/'.$featured_image_id);
                $featured_image = json_decode($str, true); // decode the JSON into an associative array
                if(isset($featured_image["guid"]["rendered"])){
                    $post["featured_image"] = $featured_image["guid"]["rendered"];
                }

                $posts[] = $post;
            }
        }

        // return the value
        // return $post_ids;
        return $posts;
    }

    /*
     *  load_field()
     *
     *  This filter is applied to the $field after it is loaded from the database
     *
     *  @type	filter
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$field - the field array holding all the field options
     *
     *  @return	$field - the field array holding all the field options
     */

    function load_field($field) {
        // validate post_type
        if (!$field['post_type'] || !is_array($field['post_type']) || in_array('', $field['post_type'])) {
            $field['post_type'] = array('all');
        }
        return $field;
    }

    /*
     *  update_field()
     *
     *  This filter is applied to the $field before it is saved to the database
     *
     *  @type	filter
     *  @since	3.6
     *  @date	23/01/13
     *
     *  @param	$field - the field array holding all the field options
     *  @param	$post_id - the field group ID (post_type = acf)
     *
     *  @return	$field - the modified field
     */

    function update_field($field, $post_id) {
        // Note: This function can be removed if not used
        return $field;
    }

    /*
     *  register_fields
     *
     *  @description:
     *  @since: 3.6
     *  @created: 1/04/13
     */

    function autocomplete_handler_suggestions() {

        // Query for suggestions
        $term = urlencode($_REQUEST['term']);
        $post_type = $_REQUEST['post_type'];
        $suggestions = array();
        $callback = $this->rest_route.'posts?filter[s]='.$term;
        $str = file_get_contents($callback);
        $json = json_decode($str, true); // decode the JSON into an associative array
        foreach($json as $post){
            if(isset($post["id"])){

                $link = $post["link"];
                $title = $post["title"]["rendered"];
                $id_post = $post["id"];

                // Initialise suggestion array
                $suggestion = array();
                $suggestion['label'] = $title . " (id: " . $id_post . ")";
                $suggestion['ID'] = $id_post;
                // Add suggestion to suggestions array
                $suggestions[] = $suggestion;
            }
        }

        // JSON encode and echo
        $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
        //$response = json_encode($suggestions);
        //die(var_dump($suggestions));

        echo $response;
        // Don't forget to exit!
        exit;
    }

}

// create field
new acf_field_autocomplete();
?>
