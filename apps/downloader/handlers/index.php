<?php
// create a POST form and inject the Controller
$form = new Form ('post', $this);
// pass a function to handle the submitted form
echo $form->handle (function ($form) {
    // form handling goes here
    info ($_POST);
});
?>
