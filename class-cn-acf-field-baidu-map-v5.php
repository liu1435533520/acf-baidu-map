<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('cn_acf_field_baidu_map') ) :


class cn_acf_field_baidu_map extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {

		$this->defaults = array(
			'lng'	=> 116.39277,
            'lat'   => 39.912057
		);

        // vars
        $this->name = 'baidu_map';
        $this->label = __("Baidu Map",'acf-baidu-map');
        $this->category = 'jquery';
        $this->defaults = array(
            'height'		=> '',
            'center_lat'	=> '',
            'center_lng'	=> '',
            'zoom'			=> ''
        );
        $this->default_values = array(
            'height'		=> '450',
            'center_lat'	=> '39.912057',
            'center_lng'	=> '116.39277',
            'zoom'			=> '5'
        );

		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

        // center_lat
        acf_render_field_setting( $field, array(
            'label'			=> __('中心位置','acf'),
            'instructions'	=> __('默认中心位置经纬度','acf-baidu-map'),
            'type'			=> 'text',
            'name'			=> 'center_lat',
            'prepend'		=> __('纬度','acf-baidu-map'),
			'placeholder'	=> $this->default_values['center_lat'],
			'_append' 		=> 'center_lng'
        ));


        // center_lng
        acf_render_field_setting( $field, array(
            'label'			=> __('中心位置','acf'),
            'instructions'	=> __('默认中心位置经纬度','acf-baidu-map'),
            'type'			=> 'text',
            'name'			=> 'center_lng',
            'prepend'		=> __('经度','acf-baidu-map'),
            'placeholder'	=> $this->default_values['center_lng']
            
        ));


        // zoom
        acf_render_field_setting( $field, array(
            'label'			=> __('缩放','acf'),
            'instructions'	=> __('设置缩放等级','acf-baidu-map'),
            'type'			=> 'number',
			'name'			=> 'zoom',
			'min'			=> '1',
			'max'			=> '16',
            'placeholder'	=> $this->default_values['zoom']
        ));


        // allow_null
        acf_render_field_setting( $field, array(
            'label'			=> __('高度','acf'),
            'instructions'	=> __('自定义地图高度','acf-baidu-map'),
            'type'			=> 'number',
            'name'			=> 'height',
            'append'		=> 'px',
            'placeholder'	=> $this->default_values['height']
		));



		acf_render_field_setting( $field, array(
            'label'			=> __('宽高','acf'),
            'instructions'	=> __('设置裁剪图片的宽高','acf-baidu-map'),
            'type'			=> 'number',
            'name'			=> 'width',
			'append'		=> 'px',
			'prepend'		=> __('宽度','acf-baidu-map'),
            'placeholder'	=> $this->default_values['width']
        ));
		acf_render_field_setting( $field, array(
            'label'			=> __('宽高','acf-baidu-map'),
            'type'			=> 'number',
            'name'			=> 'height',
			'append'		=> 'px',
			'prepend'		=> __('高度','acf-baidu-map'),
			'placeholder'	=> $this->default_values['height'],
			'_append'     	=>'width'
        ));
	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
        // validate value
        if( empty($field['value']) ) {
        $field['value'] = array();
        }


        // value
        $field['value'] = wp_parse_args($field['value'], array(
        'lat'		=> '',
        'lng'		=> ''
        ));


        // default options
        foreach( $this->default_values as $k => $v ) {

        if( empty($field[ $k ]) ) {
        $field[ $k ] = $v;
        }

        }

        // vars
        $atts = array(
        'id'			=> $field['id'],
        'class'			=> "acf-baidu-map {$field['class']}",
        'data-lat'		=> $field['center_lat'],
        'data-lng'		=> $field['center_lng'],
        'data-zoom'		=> $field['zoom'],
        );

        ?>
        <div <?php acf_esc_attr_e($atts); ?>>
						<div class="baidu-map-container" id="baidu-map-<?php echo $atts['id'];?>" style="<?php echo esc_attr('height: '.$field['height'].'px'); ?>"></div>
            <div class="acf-hidden">
                <?php foreach( $field['value'] as $k => $v ):
                    acf_hidden_input(array( 'name' => $field['name'].'['.$k.']', 'value' => $v, 'data-name' => $k ));
                endforeach; ?>
            </div>
            
        </div>
			<!-- <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=xMxUmHK0I4OQpBcGWee4uhEqBniGlg5U"></script> -->
		<?php
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
//wp_register_script('baidu-map-api','http://api.map.baidu.com/api?v=2.0&ak='.$ak);


	function input_admin_enqueue_scripts() {

		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		$ak = $this->settings['user_settings']['baidu_map_key'];
		// loading baidu api
		wp_register_script('baidu-map-api','http://api.map.baidu.com/api?v=2.0&ak='.$ak);
		wp_enqueue_script( 'baidu-map-api' );
		// loading js
		wp_register_script('acf-baidu-map', "{$url}assets/js/input.js", array('acf-input'), $version);
		wp_enqueue_script( 'acf-baidu-map' );
	}

	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function load_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

    function update_value( $value, $post_id, $field ) {

        if( empty($value) || empty($value['lat']) || empty($value['lng']) ) {

            return false;

        }


        // return
        return $value;
    }
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font_size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	

	
	function validate_value( $valid, $value, $field, $input ){
		
		// bail early if not required
		if( ! $field['required'] ) {

			return $valid;

		}


		if( empty($value) || empty($value['lat']) || empty($value['lng']) ) {

			return false;

		}
		
		
		// return
		return $valid;
		
	}

	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function load_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function update_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// initialize
new cn_acf_field_baidu_map( $this->settings );


// class_exists check
endif;

?>