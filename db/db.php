<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 01.06.2020
 * Time: 12:56
 */
require "rb-mysql.php";

R::setup('mysql:host=localhost;dbname=f0471781_01','f0471781_01','user');

if(!R::testConnection()) die('No DB connection!');

session_start();
?>