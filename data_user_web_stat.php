<?php
/**
 * Created by PhpStorm.
 * User: radityacandra
 * Date: 30/10/2015
 * Time: 8:55
 */

require_once 'coba.php';
if(isset($_POST['selected_site'])&&isset($_POST['selected_id'])){
    $user = $_POST['selected_id'];
    $site = $_POST['selected_site'];
    $model = new porn_filtering_model();
    $get_web_stat = $model->get_user_website_statistic($user, $site);
    echo json_encode($get_web_stat);
}