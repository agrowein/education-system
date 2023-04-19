<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 15.07.2020
 * Time: 13:41
 */
require_once '../../db/db.php';
$test_id = $_POST['test_id'];
$test = R::findOne('tests','WHERE id = '.$test_id);
$title = "Результаты ".$test->name;
require __DIR__ . './../../header_two_level.php';
$course_id = $test->course_id;

echo "
<div class='container mt-4'>
    <h2>Результаты прохождений участниками теста \"{$test->name}\"</h2><br>
    <form action='../course_teacher.php' method='post'>
    <div align='center'>
    <button class='btn btn-success' name='return'>Назад к курсу</button><br><br>
    <input type='hidden' name='course_id' id='course_id' value='{$course_id}'>
    </div>
    </form>
    <table class='table table-bordered table-hover'>
    <thead>
        <th scope='col'>Участник</th>
        <th scope='col'>Наибольший набранный балл</th>
        <th scope='col'>Попыток сделано</th>
    </thead>
    <tbody>";


$all = R::findAll('participants','WHERE course_id ='.$test->course_id.' AND role = 2');
foreach ($all as $participant){
    $results = R::findAll('results','WHERE user_id = '.$participant->user_id.' AND test_id = '.$test->id);
    $point = 0;
    if ($results != []){
        foreach ($results as $result){
            if ($result->point > $point)
                $point = $result->point;
        }
    }
    $user = R::findOne('users','WHERE id = '.$participant->user_id);
    $count = count($results);
    echo "<tr><th scope='row'>{$user->name} {$user->surname}</th><td>{$point}</td><td>{$count}</td></tr>";
}
echo "</tbody></div>";
?>
