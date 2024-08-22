<?php

add_action('admin_menu', 'crac_delete_options_page');
function crac_delete_options_page(){
    add_submenu_page(
        'crac_general', 
        'Delete roles', 
        'Delete roles', 
        'manage_options', 
        'crac_delete_page', 
        'crac_delete_options_page_html'
    );
}

function crac_delete_options_page_html(){
    if(!current_user_can('manage_options')){
        return;
    }
    ?>
        <div class="wrap">
            <form action="options.php" method="post" id="crac_delete_page_form" name="crac_delete_page_form">
                <?php 
                    settings_fields('crac_delete_page');
                    do_settings_sections('crac_delete_page');
                    submit_button(__('Delete', 'textdomain'));
                ?>
            </form>
        </div>

    <?php
}

add_action('admin_init', 'crac_delete_settings_api_init');
function crac_delete_settings_api_init(){
    add_settings_section(
        'crac_delete_roles_section',
        '',
        'crac_delete_roles_section_html',
        'crac_delete_page'
    );
    global $user_role_names;
    foreach($user_role_names as $role_name => $role_display_name){
        add_settings_field(
            'crac_delete_role_field_' . $role_name,
            '',
            'crac_delete_role_field_html',
            'crac_delete_page',
            'crac_delete_roles_section',
            array(
                'role_name' => $role_name, 
                'role_display_name' => $role_display_name, 
                'field_name' => 'crac_delete_role_field_' . $role_name
            )
        );
        register_setting('crac_delete_page', 'crac_delete_role_field_' . $role_name);
    }

}

function crac_delete_roles_section_html(){
    global $user_role_names;
    $d = '';
    foreach($user_role_names as $role_name => $role_display_name){
        $role_option_name =  'crac_delete_role_field_' . $role_name;
        $role = get_option($role_option_name);
        if($role == 1){
            $d .= ' ' . $role_name;
            remove_role($role_name);
            delete_option($role_option_name);
        }

    }
    update_option('crac_delete_role_field_test', $d);

}
function crac_delete_role_field_html($array){
    echo '<input
            type="checkbox"
            name="'.$array['field_name'].'"
            id="'.$array['field_name'].'"
            value="1"
            '. checked(1, get_option($array['field_name']), false) .'
        />
        <label for="'.$array['field_name'].'">'.$array['role_name'].'</label>
    ';
}