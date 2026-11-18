<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class' => 'Security_headers',
    'function' => 'apply',
    'filename' => 'Security_headers.php',
    'filepath' => 'hooks',
);

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_controller'][]=array('class'=>'Phase2_security','function'=>'routes','filename'=>'Phase2_security.php','filepath'=>'hooks');
$hook['post_controller_constructor'][]=array('class'=>'Phase2_security','function'=>'identity','filename'=>'Phase2_security.php','filepath'=>'hooks');
$hook['display_override']=array('class'=>'Phase2_security','function'=>'display','filename'=>'Phase2_security.php','filepath'=>'hooks');