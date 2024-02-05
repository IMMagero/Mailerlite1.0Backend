<?php
require __DIR__ . "/includes/bootstrap.php";
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if ((isset($uri[3]) && $uri[3] != 'user') || !isset($uri[4])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
else {
    require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
    $objFeedController = new UserController();
// Routes 
if($uri[4] == 'list') {

    $strMethodName = $uri[4] . 'Action';
    $objFeedController->{$strMethodName}();

}
else if($uri[4] == 'create'){
    $strMethodName = $uri[4] . 'Action';
    $objFeedController->{$strMethodName}();
}
else if($uri[4] == 'search') {

    $strMethodName = $uri[4] . 'Action';
    $objFeedController->{$strMethodName}();
}
else{
    header("HTTP/1.1 404 Not Found");
    exit();

}
    


}

?>