<?
if (Login::isLoggedIn()) {
    include( 'pages/401.php' );
} else {
    $p->template('html5','top');
    $p->script[] = "$(document).ready(function() { $.skybox('/ajax/login-skybox'); });";
    $p->template('html5','bottom');
}//if