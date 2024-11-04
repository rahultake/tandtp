<?php

    defined('BASEPATH') or exit('No direct script access allowed');
    if (!function_exists('add_from_estimate')) {
        function add_from_estimate($estimate_data) {
            // Load the Boq controller to access its functions
            $CI =& get_instance();
            $CI->load->controller('admin/Boq');
            
            // Call the add_from_estimate function
            $CI->Boq->add_from_estimate($estimate_data);
        }
    }
?>