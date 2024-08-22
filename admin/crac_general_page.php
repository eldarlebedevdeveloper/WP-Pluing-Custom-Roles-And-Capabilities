<?php

// Registrate admin page
add_action('admin_menu', 'crac_general_options_page');
function crac_general_options_page(){
    add_menu_page( 
        'CRAC General Settings',
        'CRAC', 
        'manage_options',
        'crac_general', 
        'crac_general_options_page_html', 
        plugin_dir_url(__FILE__) . 'admin/images/crac.png',
        20
    ); 
}       
// HTML registration page
function crac_general_options_page_html(){
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <?php 
        crac_show_list_of_user_roles(); 
        ?>
        <form action="options.php" method="post">
            <?php
                settings_fields('crac_general');
                do_settings_sections('crac_general');
                crac_create_custom_user_role();
                crac_clean_fields_after_creating_user_role();
                submit_button(__('Create User Role', 'textdomain'));
            ?>
                
        </form>
    </div>
<?php
}

// Fields for general settins
add_action('admin_init', 'crac_general_settings_api_init');
function crac_general_settings_api_init(){
    // Section for name, name slug and capabilities checkboxes list
    add_settings_section(
        'crac_general_create_custom_user_role_section',
        'Create custom user role', 
        'crac_general_create_custom_user_role_section_html', 
        'crac_general'
    );
    // Name field
    add_settings_field(
        'crac_general_add_role_name_field',
        'Add role name',
        'crac_general_add_role_name_field_html',
        'crac_general',
        'crac_general_create_custom_user_role_section'
    );
    register_setting('crac_general', 'crac_general_add_role_name_field');

    // Name slug field
    add_settings_field(
        'crac_general_add_role_slug_field',
        'Add role slug',
        'crac_general_add_role_slug_field_html',
        'crac_general',
        'crac_general_create_custom_user_role_section'
    );
    register_setting('crac_general', 'crac_general_add_role_slug_field');

    add_settings_section(
        'crac_general_create_custom_user_role_capabilities_section',
        'Capabilities', 
        'crac_general_create_custom_user_role_capabilities_section_html', 
        'crac_general',
        array(
            'before_section' => '<div class="crac_checkbox_capabilities_container">',
            'after_section' => '</div>',
        )
    );

    // Checkboxes of capabilities
    global $caps_list;
    foreach($caps_list as $cap_name){
        add_settings_field(
            'crac_general_add_role_capabilities_field_' . $cap_name,
            '',
            'crac_general_add_role_capabilities_field_html',
            'crac_general',
            'crac_general_create_custom_user_role_capabilities_section',
            array(
                'capability' => $cap_name,
            )
        );
        register_setting('crac_general', 'crac_general_add_role_capabilities_field_'. $cap_name);
    }
}
function crac_general_create_custom_user_role_capabilities_section_html(){
    echo '<p>Pointing the capabilities</p>';
}
function crac_general_create_custom_user_role_section_html(){
    echo '<p>Add a name, name ID, and specify capabilities for the new role</p>';
}
function crac_general_add_role_name_field_html(){
    echo '<input 
            id="crac_general_add_role_name_field"
            name="crac_general_add_role_name_field"
            value="' . get_option('crac_general_add_role_name_field', false) . '"
            />';
}
function crac_general_add_role_slug_field_html(){
    echo '<input 
            id="crac_general_add_role_slug_field"
            name="crac_general_add_role_slug_field"
            value="' . get_option('crac_general_add_role_slug_field', false) . '"
            />';
}
function crac_general_add_role_capabilities_field_html($cap_name){
    echo '<input 
            class="capability_checkbox"
            type="checkbox"
            id="crac_general_add_role_capabilities_field_'.  $cap_name['capability'] .'"
            name="crac_general_add_role_capabilities_field_'.  $cap_name['capability'] .'"
            value="1"
            ' . checked(1, get_option('crac_general_add_role_capabilities_field_'.  $cap_name['capability']), false) . '
            />
            <label for="crac_general_add_role_capabilities_field_'.  $cap_name['capability'] .'">'. $cap_name['capability'] .'</label>';
}

// ======= Custome roles ===============
// Show list of existing user roles
function crac_show_list_of_user_roles(){
    $all_roles = wp_roles()->roles;
    echo '<div class="crac_show_role">';
    foreach($all_roles as $role){
        echo '<span class="crac_show_role_item">'. $role['name'] . '</span>';
    }
    echo '</div>';
}
// Create custom role with picked capabilities
function crac_create_custom_user_role(){
    global $caps_list;
    $role_name = get_option('crac_general_add_role_name_field');
    $role_slug = get_option('crac_general_add_role_slug_field');

    $checked_caps = array();
    foreach($caps_list as $cap_name){
        $cap_value = get_option('crac_general_add_role_capabilities_field_' . $cap_name);
        if(intval($cap_value) === 1){
            $checked_caps[$cap_name] = $cap_value;
        }
    }

    add_role($role_slug, $role_name, $checked_caps);
}
// Clean fields role name, role slug and role capabilities after created custom user role
function crac_clean_fields_after_creating_user_role(){
    global $caps_list;
    update_option('crac_general_add_role_name_field', '');
    update_option('crac_general_add_role_slug_field', '');
    
    foreach($caps_list as $cap_name){
        update_option('crac_general_add_role_capabilities_field_' . $cap_name, '');

    }
}


