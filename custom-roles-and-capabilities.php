<?php
/*
*Plugin Name: CRAC - Custom Roles And Capabilities
*Version: 1.0.0
*Description: Create custom roles and add/change/delete capabilities to existing and custome roles

*/

add_action('admin_menu', 'crac_options_page');
function crac_options_page(){
    add_menu_page( 
        'CRAC General Settings',
        'CRAC', 
        'manage_options',
        'crac_general', 
        'crac_general_options_page_html', 
        plugin_dir_url(__FILE__) . 'assets/images/crac.png',
        20
    ); 
}

function crac_general_options_page_html(){
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields('crac_general_options');
                do_settings_sections('crac_general_options');

                settings_fields('crac_general_options2');
                do_settings_sections('crac_general_options2');

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
        'crac_general_create_custom_user_role_section_html', 
        'crac_general_options'
    );

    add_settings_field(
        'crac_general_add_role_name_field',
        'Add role name',
        'crac_general_add_role_name_field_html', 
        'crac_general_options',
        'crac_general_create_custom_user_role_section'
    );

    register_setting('crac_general_options', 'crac_general_add_role_name_field');


    add_settings_section(
        'crac_general_create_custom_user_role_section2',
        'Create custom user role 2', 
        'crac_general_create_custom_user_role_section_html2', 
        'crac_general_options2'
    );

    add_settings_field(
        'crac_general_add_role_name_field2',
        'Add role name 2',
        'crac_general_add_role_name_field_html2', 
        'crac_general_options2',
        'crac_general_create_custom_user_role_section2'
    );

    register_setting('crac_general_options2', 'crac_general_add_role_name_field2');
}

function crac_general_create_custom_user_role_section_html(){
    echo '<p> Create a custom user role by simply adding a new custom role name and specifying the capabilities for the role</p>';
}

function crac_general_add_role_name_field_html(){
    echo '<input 
            id="crac_general_add_role_name_field"
            name="crac_general_add_role_name_field"
            value="' . get_option('crac_general_add_role_name_field', false) . '"
            />';
}


function crac_general_create_custom_user_role_section_html2(){
    echo '<p> Create a custom user role by simply adding a new custom role name and specifying the capabilities for the role</p>';
}

function crac_general_add_role_name_field_html2(){
    echo '<input 
            id="crac_general_add_role_name_field2"
            name="crac_general_add_role_name_field2"
            value="' . get_option('crac_general_add_role_name_field2', false) . '"
            />';
}


