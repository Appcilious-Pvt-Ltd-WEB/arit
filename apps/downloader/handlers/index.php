<?php
require_once ('platform.php');
// create a POST form and inject the Controller
$form = new Form ('post', $this);
// pass a function to handle the submitted form
echo $form->handle (function ($form) {
    // form handling goes here
    //info ($_POST);
    $platform = new Platform();
    $requestedSite = strtolower($_POST['name']);
    $url_info = parse_url($requestedSite);
    $requestedSite = $url_info['host'];
    $result = json_decode($platform->findPlatform($requestedSite),true);
    if($result['status'] == 'success') {
        $result['downloadurl'] = $_POST['name'];
       echo $platform->download($result);
    }
});
?>
