<?php
/*
*Plugin Name: CRAC - Custom Roles And Capabilities
*Version: 1.0.0
*Description: Create custom roles and add/change/delete capabilities to existing and custome roles

*/

// Add styles
add_action('admin_print_styles', 'add_my_stylesheet');
function add_my_stylesheet() {
    wp_enqueue_style( 'myCSS', plugins_url( '/admin/css/admin-settings.css', __FILE__ ) );
}

add_action('admin_enqueue_scripts', 'add_my_sripts');
function add_my_sripts()
{   
    wp_enqueue_script( 'my_custom_script', plugins_url('/admin/js/admin.js', __FILE__ ), '1.0.0', false );
}

function wpb_hook_javascript_footer() {
    ?>
        <script>
            let update_selet_role = document.querySelector('#crac_update_user_roles_select_filed')
            let update_role_name = document.querySelector('#crac_update_user_roles_name_field')
            let update_role_capabilities = document.querySelectorAll('.crac_update_user_roles_checkboxe_fields')

            // window.addEventListener('DOMContentLoaded', function() {
            //     let event = new Event('change');
            //     update_selet_role.dispatchEvent(event);
            // });

            update_selet_role.addEventListener('change', function(event){
               update_choise_role(event)
            })

            function update_choise_role(event){
                let choised_option = update_selet_role.querySelector(`option[value='${event.target.value}']`)
                let choised_option_data = JSON.parse(choised_option.dataset.role)
                update_role_name.value = choised_option_data.role_name

                update_role_capabilities.forEach(capabilitie_field => {
                    capabilitie_field.checked = false
                })

                for(const avtive_capabilitie in choised_option_data.role_capabilities) {
                    let field = document.querySelector(`.crac_update_user_roles_checkboxe_fields[data-value="${avtive_capabilitie}"]`)
                    if(typeof(field) != 'undefined' && field != null){
                        field.checked = true
                    }
                }
            }
        </script>
    <?php
}
add_action('admin_footer', 'wpb_hook_javascript_footer');

$user_roles = wp_roles();
$user_role_names = array();
foreach(wp_roles()->role_names as $role_slug => $role_name){
    if($role_slug !== 'administrator'){
        $user_role_names[$role_slug] = $role_name;
    }
}

$administrator_caps = get_role( 'administrator' )->capabilities;
$caps_list = array();
foreach($administrator_caps as $cap_name => $cap_value){
    if(str_contains( $cap_name , 'level_') === false){
        $caps_list[] = $cap_name;
    }
}


include('admin/crac_general_page.php');
include('admin/crac_update_page.php');
include('admin/crac_delete_page.php');