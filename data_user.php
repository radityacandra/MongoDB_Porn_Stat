<?php
/**
 * Created by PhpStorm.
 * User: radityacandra
 * Date: 30/10/2015
 * Time: 3:03
 */
require_once 'coba.php';
if(isset($_POST['selected_id'])){
    $user = $_POST['selected_id'];
    $model = new porn_filtering_model();
    $get_user = $model->get_user($user);
    echo json_encode($get_user);
}
?>