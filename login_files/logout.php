<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 01.06.2020
 * Time: 13:36
 */
require_once "../db/db.php";

unset($_SESSION['logged_user']);

header('Location: ../index.php');

?>