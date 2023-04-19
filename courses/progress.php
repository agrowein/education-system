<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 14.07.2020
 * Time: 18:45
 */
$title = "Моя успеваемость";
require __DIR__ . './../header_one_level.php';
require_once '../db/db.php';
error_reporting(0);

echo "
<div class='container mt-4'>
    <div class='row'>
        <div class='col'>
            <div align='center'>
            <h2>Моя успеваемость</h2><br>
            </div>
            <ul class='list-group'>";

$courses = R::findAll('participants','WHERE user_id = '.$_SESSION['logged_user']->id.' AND role = 2');
foreach ($courses as $participant){
    $course = R::findOne('courses','WHERE id = '.$participant->course_id);
    $tests_no_all = R::findAll('tests','WHERE course_id = '.$course->id);
    $point = 0;
    foreach ($tests_no_all as $test){
        $results = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$_SESSION['logged_user']->id);
        if ($results != []){
            $prt = 0;
            foreach ($results as $result) {
                if ($result->point > $prt)
                    $prt = $result->point;
            }
            if ($prt > 50)
                $point += $prt;
        }
    }
    $point /= count($tests_no_all);
    $point = (int)$point;
    $point_5 = 2;
    if ($point > 50)
        $point_5 = 3;
    if ($point > 70)
        $point_5 = 4;
    if ($point > 90)
        $point_5 = 5;
    echo "<li class='list-group-item'><h4>Курс \"{$course->name}\" - Ваша примерная оценка: {$point_5} ({$point}%)</h4><ol>";

    $tests = R::findAll('tests','WHERE course_id = '.$course->id);
    foreach ($tests as $test){
        $results = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$_SESSION['logged_user']->id);
        if ($results != []) {
            $prt = 0;
            foreach ($results as $result) {
                if ($result->point > $prt)
                    $prt = $result->point;
            }
            $prt .= '%';
        }
        else
            $prt = 'вы еще не проходили этот тест';
        echo "<li><h6>Тест \"{$test->name}\" - Ваш прогресс: {$prt}</h6></li>";
    }
    echo "</ol></li>";
}

echo "    </ul>
        </div>
    </div>
</div>
";

?>
