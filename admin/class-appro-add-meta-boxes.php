<?php

/**
 * The settings of the plugin.
 *
 * @link       https://rafael.work
 * @since      1.0.0
 *
 * @package    Appro
 * @subpackage Appro/admin
 */

/**
 * Class Appro_Add_Meta_Boxes
 *
 */
class Appro_Add_Meta_Boxes {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'add_meta_boxes', array( $this, 'appro_add_meta_boxes' 	));
        add_action( 'save_post',      array( $this, 'appro_save_meta_boxes' ));

	}

	public function appro_add_meta_boxes( $post_type ){

		$post_types = array( 'appro_archive_page' );
 
        if ( in_array( $post_type, $post_types ) ) {

            add_meta_box(
                'appro_archive_page_options',
                __( 'Options', 'appro' ),
                array( $this, 'render_appro_archive_page_options_content' ),
                $post_type,
                'advanced',
                'high'
            );

            /*
            add_meta_box(
                'appro_archive_page_shortcode',
                __( 'Shortcode', 'appro' ),
                array( $this, 'render_appro_archive_page_shortcode_content' ),
                $post_type,
                'side',
                'high'
            );
            */
        }
	}

	/**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function appro_save_meta_boxes( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['appro_options_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['appro_options_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'appro_options' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $appro_post_type    = sanitize_text_field( $_POST['appro_post_type'] );
        $appro_layouts      = $_POST['appro_layouts'];
 
        // Update the meta field.
        update_post_meta( $post_id, '_appro_options_post_type'  , $appro_post_type      );
        update_post_meta( $post_id, '_appro_options_layouts'    , $appro_layouts        );

        $layouts_list = array( 'table' => 0, 'cards' => 0, 'calendar' => 0 );
        
        foreach ( $layouts_list as $layout => $status ) : 
            
            $appro_data         = $_POST['appro_'. $layout .'_data'];
            $appro_custom_meta  = sanitize_text_field( $_POST['appro_'. $layout .'_custom_meta'] );
            
            update_post_meta( $post_id, '_appro_options_'. $layout .'_data'       , $appro_data           );
            update_post_meta( $post_id, '_appro_options_'. $layout .'_custom_meta', $appro_custom_meta    );

            if ( $layout === 'calendar' ) : 

                $appro_start_field  = sanitize_text_field( $_POST['appro_'. $layout .'_start_field'] );
                update_post_meta( $post_id, '_appro_options_'. $layout .'_start_field', $appro_start_field );
            endif;

            $editable_roles = get_editable_roles();
            foreach ( $editable_roles as $role => $details ) : 

                $appro_role = $_POST['appro_'. $layout .'_role'];
                update_post_meta( $post_id, '_appro_options_'. $layout .'_role', $appro_role );
            endforeach;
        endforeach;
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_appro_archive_page_options_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'appro_options', 'appro_options_nonce' );

        //print_r( get_post_meta( $post->ID ) );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $appro_options_post_type    = get_post_meta( $post->ID, '_appro_options_post_type',     true );
        $appro_options_layouts      = get_post_meta( $post->ID, '_appro_options_layouts',       true );

        $layouts_list = array( 'table' => 0, 'cards' => 0, 'calendar' => 0 );

        foreach ( $layouts_list as $layout => $status ) : 

            $appro_options_data[$layout]         = get_post_meta( $post->ID, '_appro_options_'. $layout .'_data',          true );
            $appro_options_custom_meta[$layout]  = get_post_meta( $post->ID, '_appro_options_'. $layout .'_custom_meta',   true );

            $aocm[$layout] = $appro_options_custom_meta ? true : false;
        endforeach;

        $appro_options_start_field = get_post_meta( $post->ID, '_appro_options_calendar_start_field', true );
        $aosf = $appro_options_start_field && !empty( $appro_options_start_field ) ? true : false;
 
        // Display the form, using the current value.
        ?>
        <fieldset class="fields">
            <legend><?php _e( 'List', 'appro' ) ?></legend>

            <div class="field">
                <label for="appro_post_type">
                    <?php _e( 'Post Type', 'appro' ); ?>
                </label>
                <?php
                    $args       = array( 'public' => true );
                    $post_types = get_post_types( $args, 'objects' );
                    $post_type  = esc_attr( $appro_options_post_type );
                ?>
                     
                <select id="appro_post_type" name="appro_post_type">
                    <option value=""><?php _e( '-- select a post type --', 'appro' ) ?></option>
                    <?php 
                    foreach ( $post_types as $post_type_obj ):
                        $labels     = get_post_type_labels( $post_type_obj );
                        $selected   = $post_type === $post_type_obj->name ? 'selected' : '';
                        ?>
                        <option 
                            value="<?php echo esc_attr( $post_type_obj->name ); ?>" 
                            <?echo $selected; ?> 
                        ><?php echo esc_html( $labels->name ); ?></option>
                    <?php 
                    endforeach; 
                    ?>
                </select>
            </div>

            <div class="field">
                <label>
                    <?php _e( 'Layouts', 'appro' ); ?>
                </label>
                <div class="sortable">
                <?php

                if ( $appro_options_layouts ) : 

                    foreach ( $appro_options_layouts as $key => $value ) : 
                        
                    ?>
                    <label>
                        <input 
                            type="checkbox" 
                            id="appro_layouts_<?php echo $key; ?>" 
                            name="appro_layouts[<?php echo $key; ?>]" 
                            value="1" 
                            <?php echo $value == '1' ? 'checked' : ''; ?> 
                        /> <?php _e( ucfirst( $key ), 'appro' ) ?>
                    </label>
                    <?php

                    unset( $layouts_list[$key] );
                    endforeach;
                endif;

                if ( $layouts_list ) : 

                    foreach ( $layouts_list as $key => $value ) : 
                        
                    ?>
                    <label>
                        <input 
                            type="checkbox" 
                            id="appro_layouts_<?php echo $key; ?>" 
                            name="appro_layouts[<?php echo $key; ?>]" 
                            value="1" 
                            <?php echo $value == '1' ? 'checked' : ''; ?> 
                        /> <?php _e( ucfirst( $key ), 'appro' ) ?>
                    </label>
                    <?php 
                    endforeach;
                endif;
                ?>
                </div>
            </div>
        </fieldset>
    <?php
    if ( $appro_options_layouts ) : 

        foreach ( $appro_options_layouts as $layout => $status ) : 
            
        ?>
        <fieldset class="fields">
            <legend><?php _e( ucfirst( $layout ), 'appro' ) ?></legend>

            <div class="field">
                <label for="appro_post_type">
                    <?php _e( 'Roles', 'appro' ); ?>
                </label>
                <?php

                $editable_roles = get_editable_roles();
                $roles = array();
                foreach ( $editable_roles as $role => $details ) : 

                    $sub['role']    = esc_attr( $role );
                    $sub['name']    = translate_user_role( $details['name'] );

                    $aolr           = get_post_meta( $post->ID, '_appro_options_'. $layout .'_role', true );
                    $sub['status']  = $aolr ? maybe_unserialize( $aolr ) : null;
                    $roles[] = $sub;
                endforeach;

                if ( $roles ) : 

                    foreach ( $roles as $role ) : 

                        $rsr = $role['status'] && isset( $role['status'][$role['role']] ) && $role['status'][$role['role']] == '1' ? true : false;
                        
                    ?>
                    <label>
                        <input 
                            type="checkbox" 
                            id="appro_<?php echo $layout; ?>_role_<?php echo $role['role']; ?>" 
                            name="appro_<?php echo $layout; ?>_role[<?php echo $role['role']; ?>]" 
                            value="1" 
                            <?php echo $rsr ? 'checked' : ''; ?> 
                        /> <?php _e( $role['name'] ) ?>
                    </label>
                    <?php 
                    endforeach;
                endif;
                ?>
            </div>

            <div class="field">
                <label>
                    <?php _e( ucfirst( $layout ) .' data', 'appro' ); ?>
                </label>
                <div class="sortable">
                <?php

                $data_list = array( 'ID' => 0, 'title' => 0, 'thumbnail' => 0, 'excerpt' => 0 );

                if ( $appro_options_data[$layout] ) : 

                    foreach ( $appro_options_data[$layout] as $key => $value ) : 
                        
                    ?>
                    <label>
                        <input 
                            type="checkbox" 
                            id="appro_<?php echo $layout; ?>_data_<?php echo $key; ?>" 
                            name="appro_<?php echo $layout; ?>_data[<?php echo $key; ?>]" 
                            value="1" 
                            <?php echo $value == '1' ? 'checked' : ''; ?> 
                        /> <?php _e( ucfirst( $key ) ) ?>
                    </label>
                    <?php

                    unset( $data_list[$key] );
                    endforeach;
                endif;

                if ( $data_list ) : 

                    foreach ( $data_list as $key => $value ) : 
                        
                    ?>
                    <label>
                        <input 
                            type="checkbox" 
                            id="appro_<?php echo $layout; ?>_data_<?php echo $key; ?>" 
                            name="appro_<?php echo $layout; ?>_data[<?php echo $key; ?>]" 
                            value="1" 
                            <?php echo $value == '1' ? 'checked' : ''; ?> 
                        /> <?php _e( ucfirst( $key ) ) ?>
                    </label>
                    <?php 
                    endforeach;
                endif;
                ?>
                </div>
            </div>

            <div class="field">
                <label>
                    <?php _e( ucfirst( $layout ) .' custom Meta', 'appro' ); ?>
                </label>
                <textarea id="appro_<?php echo $layout; ?>_custom_meta" name="appro_<?php echo $layout; ?>_custom_meta" rows="3"><?php echo $aocm[$layout] ? $appro_options_custom_meta[$layout] : ''; ?></textarea>
            </div>

            <?php
            if ( $layout === 'calendar' ) : 
            ?>
            <div class="field">
                <label for="appro_calendar_start">
                    <?php _e( 'Start field', 'appro' ); ?>
                </label>
                <input 
                    type="text" 
                    id="appro_calendar_start_field" 
                    name="appro_calendar_start_field" 
                    value="<?php echo $aosf ? $appro_options_start_field : ''; ?>" 
                />
            </div>
            <?php
            endif;
            ?>
        </fieldset>
        <?php
        endforeach;
    endif;
    }

    public function render_appro_archive_page_shortcode_content( $post ){

        ?>
        <p><?php _e( 'Copy and paste the shortcode below into any page or post on your site.', 'appro' ); ?></p>
        <code>[appro id="<?php echo $post->ID; ?>"]</code>
        <?php
    }

}