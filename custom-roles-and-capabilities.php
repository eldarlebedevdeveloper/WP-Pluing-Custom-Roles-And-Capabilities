<?php
/*
*Plugin Name: CRAC - Custom Roles And Capabilities
*Version: 1.0.0
*Description: Create custom roles and add/change/delete capabilities to existing and custome roles

*/


// register_deactivation_hook(__FILE__, 'remove_added_option_field_in_db');
// function remove_added_option_field_in_db(){
//     $added_option_fields = array(
//         'crac_general_add_role_name_field',
//         'crac_general_add_role_name_field2',
//     );
//     foreach($added_option_fields as $option){
//         delete_option($option);
//     }
// }

add_action('admin_menu', 'crac_options_page');
function crac_options_page(){
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

function crac_general_options_page_html(){
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields('crac_general');
                do_settings_sections('crac_general');
                // settings_fields('crac_general_options2');
                // do_settings_sections('crac_general_options2');
                submit_button(__('Save Settings', 'textdomain'));
            ?>
        </form>
    </div>
<?php
}


add_action('admin_init', 'crac_general_settings_api_init');
function crac_general_settings_api_init(){
    add_settings_section(
        'crac_general_create_custom_user_role_section',
        'Create custom user role', 
        function() {
            echo '<p> Create a custom user role by simply adding a new custom role name and specifying the capabilities for the role</p>';
        }, 
        'crac_general'
    );
    add_settings_field(
        'crac_general_add_role_name_field',
        'Add role name',
        function(){
            echo '<input 
                    id="crac_general_add_role_name_field"
                    name="crac_general_add_role_name_field"
                    value="' . get_option('crac_general_add_role_name_field', false) . '"
                    />';
        }, 
        'crac_general',
        'crac_general_create_custom_user_role_section'
    );
    register_setting('crac_general', 'crac_general_add_role_name_field');

    add_settings_section(
        'crac_general_create_custom_user_role_section2',
        'Create custom user role 2', 
        function (){
            echo '<p> Create a custom user role by simply adding a new custom role name and specifying the capabilities for the role</p>';
        }, 
        'crac_general'
    );
    add_settings_field(
        'crac_general_add_role_name_field2',
        'Add role name 2',
        function (){
            echo '<input 
                    id="crac_general_add_role_name_field2"
                    name="crac_general_add_role_name_field2"
                    value="' . get_option('crac_general_add_role_name_field2', false) . '"
                    />';
        }, 
        'crac_general',
        'crac_general_create_custom_user_role_section2'
    );
    register_setting('crac_general', 'crac_general_add_role_name_field2');
}

// function crac_general_create_custom_user_role_section_html(){
//     echo '<p> Create a custom user role by simply adding a new custom role name and specifying the capabilities for the role</p>';
// }

// function crac_general_add_role_name_field_html(){
//     echo '<input 
//             id="crac_general_add_role_name_field"
//             name="crac_general_add_role_name_field"
//             value="' . get_option('crac_general_add_role_name_field', false) . '"
//             />';
// }


// function crac_general_create_custom_user_role_section_html2(){
//     echo '<p> Create a custom user role by simply adding a new custom role name and specifying the capabilities for the role</p>';
// }

// function crac_general_add_role_name_field_html2(){
//     echo '<input 
//             id="crac_general_add_role_name_field2"
//             name="crac_general_add_role_name_field2"
//             value="' . get_option('crac_general_add_role_name_field2', false) . '"
//             />';
// }


