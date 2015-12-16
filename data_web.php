<?php
/**
 * Created by PhpStorm.
 * User: radityacandra
 * Date: 30/10/2015
 * Time: 4:57
 */
require_once 'coba.php';
if(isset($_POST['selected_site'])){
    $site = $_POST['selected_site'];
    $model = new porn_filtering_model();
    $get_site =$model->get_website_id($site);
    echo json_encode($get_site);
}
?>