<?php
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,                                                                                            
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);