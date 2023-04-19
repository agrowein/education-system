<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 15.07.2020
 * Time: 13:41
 */
require_once '../../db/db.php';
$course_id = $_POST['course_id'];
$course = R::findOne('courses','WHERE id = '.$course_id);
$title = "Результаты курса \"".$course->name."\"";
require __DIR__ . './../../header_two_level.php';

error_reporting(0);

echo "
<div class='container mt-4'>
    <h2>Результаты прохождений участниками курса \"{$course->name}\"</h2><br>
";

echo "
    <form method='post'>
        <div class='form-inline' align='center'>
            <input type='text' class='form-control' name='find_text' id='find_text' placeholder='Имя и фамилия'>
            <input type='hidden' name='course_id' id='course_id' value='{$course->id}'>&nbsp;
            <button class='btn btn-light' name='find'>Найти участника</button><br><br>
        </div>
    </form>
    <form action='../courses_control.php' method='post'>
        <div align='center'>
            <button class='btn btn-success' name='return'>Назад к курсам</button><br><br>
        </div>
    </form>
";

if(isset($_POST['find']) and $_POST['find_text'] != ""){
    $name = explode(" ",$_POST['find_text']);
    $find_user = R::findOne('users','WHERE name = \''.$name[0].'\' AND surname = \''.$name[1].'\'');
    if ($find_user == NULL)
        echo "<div align='center'>Пользователь с такими данными не найден</div>";
    else {
        $find_participant = R::findOne('participants', 'WHERE user_id = ' . $find_user->id . ' AND course_id = ' . $course->id . ' AND role = 2');
        if ($find_participant == NULL)
            echo "<div align='center'>Пользователь с такими данными не записан на данный курс</div>";
        else {
            $tests = R::findAll('tests','WHERE course_id = '.$course->id);
            $point = 0;
            foreach ($tests as $test){
                $results = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$find_user->id);
                if ($results != []){
                    $temp = 0;
                    foreach ($results as $result){
                        if ($result->point > $temp)
                            $temp = $result->point;
                    }
                    $point += $temp;
                }
            }
            $point /= count($tests);
            $point = (int)($point);

            $user = R::findOne('users','WHERE id = '.$find_user->id);
            $point_5 = 2;
            if ($point > 50)
                $point_5 = 3;
            if ($point > 70)
                $point_5 = 4;
            if ($point > 90)
                $point_5 = 5;
            echo "<table class='table table-bordered table-hover'>
                    <thead>
                        <th scope='col'>Участник</th>
                        <th scope='col'>Набранный балл</th>
                        <th scope='col'>Рекомендуемая оценка</th>
                    </thead>
                    <tbody>
                        <tr><th scope='row'>{$user->name} {$user->surname}</th><td>{$point}%</td><td>{$point_5}</td></tr>
                    </tbody>
                 </table>";
        }
    }
}


$all = R::findAll('participants','WHERE course_id ='.$course->id.' AND role = 2');
echo "<table class='table table-bordered table-striped'>
          <thead>
              <th scope='col'>Участник</th>
              <th scope='col'>Набранный балл</th>
              <th scope='col'>Рекомендуемая оценка</th>
          </thead>
          <tbody>";

foreach ($all as $participant){
    $tests = R::findAll('tests','WHERE course_id = '.$course->id);
    $point = 0;
    foreach ($tests as $test){
        $results = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$participant->user_id);
        if ($results != []){
            $temp = 0;
            foreach ($results as $result){
                if ($result->point > $temp)
                    $temp = $result->point;
            }
            $point += $temp;
        }
    }
    $point /= count($tests);
    $point = (int)($point);

    $user = R::findOne('users','WHERE id = '.$participant->user_id);
    $point_5 = 2;
    if ($point > 50)
        $point_5 = 3;
    if ($point > 70)
        $point_5 = 4;
    if ($point > 90)
        $point_5 = 5;
    echo "<tr><th scope='row'>{$user->name} {$user->surname}</th><td>{$point}%</td><td>{$point_5}</td></tr>";
}

echo "</tbody></table></div>";
?>