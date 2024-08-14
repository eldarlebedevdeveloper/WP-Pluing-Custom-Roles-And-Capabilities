<?php
if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

$added_option_fields = array(
    'crac_general_add_role_name_field',
    'crac_general_add_role_name_field2',
);
foreach($added_option_fields as $option){
    delete_option($option);
}