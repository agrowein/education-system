<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 20.07.2020
 * Time: 11:12
 */
$title="Отчет по курсам";
require './../header_one_level.php';
require_once "../db/db.php";
?>

<div class="container mt-4">
    <div class="col col-md-10">
        <h4>
            Здесь представлены все существующие курсы.<br><br>
        </h4>
    </div>
</div>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th scope="col"><b>ID</b></th>
            <th scope="col"><b>Название</b></th>
            <th scope="col"><b>Тестов на курсе</th>
            <th scope="col"><b>Преподаватели</b></th>
            <th scope="col"><b>Группы на курсе</b></th>
            <th scope="col"><b>Студентов всего</b></th>
        </tr>
    </thead>
    <tbody>

    <?php
    $courses = R::findAll('courses');
    foreach ($courses as $course) {
        $test_count = count(R::findAll('tests','WHERE course_id = '.$course->id));
        $teachers = "";
        $teachers_part = R::findAll('participants','WHERE course_id = '.$course->id.' AND role = 3');
        foreach ($teachers_part as $tmp){
            $teacher = R::findOne('users','WHERE id = '.$tmp->user_id);
            $teachers .= $teacher->name." ".$teacher->surname.";\n";
        }
        $groups = "";
        $groups_all = R::findAll('coursegroups','WHERE course_id = '.$course->id);
        foreach ($groups_all as $tmp){
            $group = R::findOne('groups','WHERE id = '.$tmp->group_id);
            $groups .= $group->name.";\n";
        }
        $students_count = count(R::findAll('participants','WHERE course_id = '.$course->id.' AND role = 2'));
        echo "
            <tr><th scope='row'>{$course->id}</th><td>{$course->name}</td><td>{$test_count}</td><td>{$teachers}</td>
            <td>{$groups}</td><td>{$students_count}</td>
        ";
    }
    ?>
    </tbody>
</table>

