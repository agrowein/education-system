<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 28.06.2020
 * Time: 15:49
 */

$title = "Новый курс";
require __DIR__ . './../header_one_level.php';
require_once '../db/db.php';
?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2>Новый курс</h2>
            <form action="courses_control.php" method="post">
                <label class="col-form-label">Название курса: </label><br>
                <input type="text" class="form-control" name="name" id="name" placeholder="Введите название"><br>
                <label class="col-form-label" for="info">Краткое описание (при необходимости): </label><br>
                <textarea class="form-control" name="info" id="info" rows="4"></textarea><br>
                <div align="center">
                    <button class="btn btn-success" name="save" type="submit">Сохранить</button><br>
                </div>
            </form>
        </div>
    </div>
</div>
