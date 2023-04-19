<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.07.2020
 * Time: 15:11
 */
require_once '../../db/db.php';
$course = R::findOne('courses','WHERE id = '.$_POST['course_id']);
$title = 'Новый тест';
require __DIR__ . './../../header_two_level.php';
?>

<div class='container mt-4'>
    <h2 align="center">Создание нового теста для курса "<?php echo $course->name; ?>"</h2><br>
    <form action="../course_teacher.php" method='post'>
        <label class="col-form-label"><h5>Название теста:</h5></label>
        <input type='text' class='form-control' id='name' name='name' placeholder='Введите название' required><br>
        <label class="col-form-label" for="info">Краткое описание (при необходимости): </label><br>
        <textarea class="form-control" name="info" id="info" rows="4"></textarea><br>
        <label class="col-form-label"><h5>Количество попыток:</h5></label>
        <input type='text' class='form-control' id='attempt' name='attempt' placeholder='По умолчанию не ограничено'><br>
        <input type='hidden' id='course_id' name='course_id' value='<?php echo $course->id; ?>'><br>
        <div align="center">
            <button type="submit" class="btn btn-success" name="save_test">Сохранить</button>
        </div>
    </form>
</div>