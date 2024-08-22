<?php

add_action('admin_menu', 'crac_update_options_page');
function crac_update_options_page(){
    add_submenu_page(
        'crac_general',
        'Update user role capabilities',
        'Update role capabilities',
        'manage_options',
        'crac_update_page',
        'crac_update_options_page_html'
    );
}
function crac_update_options_page_html(){
    if(!current_user_can('manage_options')){
        return;
    }
    global $user_role_names;
    $roles_array = array();

    foreach($user_role_names as $role_name => $role_display_name){
        $role_capabilities = get_role($role_name)->capabilities;
        $role_capabilities_clean = array();
        foreach($role_capabilities as $cap_name => $cap_value){
            if(str_contains( $cap_name , 'level_') === false){
                $role_capabilities_clean[] = $cap_name;
            }
        }
        $role_array = array(
            'role_name' => $role_name,
            'role_display_name' => $role_display_name,
            'role_capabilities' => $role_capabilities ,
        );

        $roles_array[$role_name] = $role_array;
    }
    ?>
    <div class="wrap">
        <h1><?php echo get_admin_page_title(); ?></h1>
        <select
            name='crac_update_user_roles_select_filed'
            id='crac_update_user_roles_select_filed'>
            <?php
            foreach($user_role_names as $role_name => $role_display_name) {
                if($role_name === get_option('crac_update_user_roles_name_field')){?>
                <option 
                    value="<?php echo $role_name; ?>" 
                    data-role='<?php print_r(json_encode($roles_array[$role_name])); ?>'
                    selected> 
                    <?php echo $role_display_name; ?> 
                </option>
            <?php } else {  ?>
                <option 
                    value="<?php echo $role_name; ?>" 
                    data-role='<?php print_r(json_encode($roles_array[$role_name])); ?>'> 
                    <?php echo $role_display_name; ?> 
                </option>
            <?php }
            }  ?>
        </select>
        <form action="options.php" method="post" id="crac_update_page_form" name="crac_update_page_form">
            <?php 
                settings_fields('crac_update_page');
                do_settings_sections('crac_update_page');
                submit_button(__('Update user role', 'textdomain'));
            ?>
        </form>
    </div>
    <?php
}

add_action('admin_init', 'crac_update_settings_api_init');
function crac_update_settings_api_init(){
    add_settings_section(
        'crac_update_roles_section', 
        '', 
        'crac_update_roles_section_html', 
        'crac_update_page'
    );
    // Name role fields
    add_settings_field(
        'crac_update_user_roles_name_field',
        '',
        'crac_update_user_roles_name_field_html',
        'crac_update_page', 
        'crac_update_roles_section',
    );
    register_setting('crac_update_page', 'crac_update_user_roles_name_field');

    // Checkbox of capabilities fields 
    global $user_roles;
    $role_caps  = $user_roles->roles['administrator']['capabilities'];
    $checkboxe_names_array = array();
    foreach($role_caps as $cap_name => $cap_value){
        if(str_contains( $cap_name , 'level_') === false){
            $checkbox_name = "crac_update_user_roles_checkboxe_fields_" . $cap_name;

            add_settings_field(
                $checkbox_name,
                '',
                'crac_update_user_roles_checkboxe_fields_html',
                'crac_update_page',
                'crac_update_roles_section',
                // $role_caps
                array('checkbox_name' => $checkbox_name, 'cap_name' => $cap_name)
            );
            register_setting('crac_update_page', $checkbox_name);

            array_push($checkboxe_names_array, array('cap_name' => $cap_name, 'checkbox_name' => $checkbox_name));
        }
    }
    $checkboxe_names_array_json = json_encode($checkboxe_names_array);
    update_option('crac_update_user_roles_checkboxe_fields_array', $checkboxe_names_array_json);
}

// Functionality for adding capabilities for choised role
function crac_update_roles_section_html(){
    if(get_option('crac_update_user_roles_name_field')){
        $update_role_name = get_option("crac_update_user_roles_name_field");
        $role = get_role($update_role_name);

        $update_checkbox_fields_json = get_option('crac_update_user_roles_checkboxe_fields_array');
        $update_checkbox_fields = json_decode($update_checkbox_fields_json);
        
        foreach($update_checkbox_fields as $checkbox_object){
            $checkbox = get_option($checkbox_object->checkbox_name);
            $cap_name = $checkbox_object->cap_name;

            $role->remove_cap($cap_name);

            if($checkbox == 1){
                $update_active_checkbox_fields[$cap_name] = $checkbox;
                $role->add_cap($cap_name);
            }
        }
    }

}
// HTML of role name 
function crac_update_user_roles_name_field_html($choised_role_array){
    echo '<input
            type="hidden"
            name="crac_update_user_roles_name_field"
            id="crac_update_user_roles_name_field"
            value="' . get_option('crac_update_user_roles_name_field') . '"
        />';
        $update_role_name = get_option("crac_update_user_roles_name_field");
        $role = get_role($update_role_name);
}
// HTML of capabilities checkboxes 
function crac_update_user_roles_checkboxe_fields_html($array){
    echo '<input
        type="checkbox"
        name="'. $array['checkbox_name'] . '"
        id="' . $array['checkbox_name'] . '"
        class="crac_update_user_roles_checkboxe_fields"
        data-value="'. $array['cap_name'] .'"
        value="1"
        '.  checked(1, get_option($array['checkbox_name']), false) .'
    />
        <label for"' . $array['checkbox_name'] . '"> '. $array['cap_name'] .'</label>';
}