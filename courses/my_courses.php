<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 02.07.2020
 * Time: 14:35
 */
$title = "Мои курсы";
require __DIR__ . './../header_one_level.php';
require_once '../db/db.php';
error_reporting(0);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2 align="center">Мои курсы</h2>
            <h4>Найти курс: </h4>
        </div>
    </div>
</div>

<form action="my_courses.php" method="post" class="form-inline">
    <input type="text" class="form-control" name="find" id="find" placeholder="Название">&nbsp;
    <button type="submit" class="btn btn-light" name="find_course">Найти</button>
</form><br>

<div class="container">
    <div class="container-fluid">
        <?php
        if (isset($_POST['find_course']) and $_POST['find'] != ""){
            $name = $_POST['find'];
            $course = R::findOne('courses', 'WHERE name = \''.$name.'\'');

            if($course != NULL){
                $is = false;
                if(R::findOne('participants', 'WHERE course_id = '.$course->id.' AND user_id = '.$_SESSION['logged_user']->id.' AND role = 2') != NULL)
                    $is = true;

                if($is){
                    $tests_no_all = R::findAll('tests','WHERE course_id = '.$course->id);
                    $tests_no = 0;
                    $point = 0;
                    foreach ($tests_no_all as $test){
                        $results = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$_SESSION['logged_user']->id);
                        if ($results == []){
                            $tests_no++;
                        }
                        else {
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
                    echo "
                    <table class='table table-bordered table-hover'>
                        <tbody>
                            <tr>
                                <th rowspan='2'><h4>{$course->name}</h4></th>                                                     
                                <td><b>Нерешенных тестов:</b></td>
                                <td><b>Примерная оценка:</b></td>
                                <td rowspan='2' align='center'>
                                    <form action='course_student.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course_no' name='course_no'>Подробнее</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <th>{$tests_no}</th>
                                <th>{$point_5} ({$point}%)</th>                                                        
                            </tr>
                        </tbody>
                    </table>";
                } else {
                    echo "
                    <table class='table table-bordered table-hover'>
                        <tbody>
                            <tr>
                                <th><h4>{$course->name}</h4></th>                       
                                <td><b>Вы не являетесь участником данного курса, но можете присоединиться к нему.</b></td>
                                <td align='center'>
                                    <form action='course_student.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course_no' name='course_no'>Присоединиться</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>";
                }
            }
        }

        $courses = R::findAll('participants', 'WHERE user_id = '.$_SESSION['logged_user']->id.' AND role = 2');
        if($courses != []){
            $counter = 1;
            foreach ($courses as $item) {
                $course = R::findOne('courses','WHERE id = '.$item->course_id);
                $tests_no_all = R::findAll('tests','WHERE course_id = '.$course->id);
                $tests_no = 0;
                $point = 0;
                foreach ($tests_no_all as $test){
                    $results = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$_SESSION['logged_user']->id);
                    if ($results == []){
                        $tests_no++;
                    }
                    else {
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

                echo "
                    <table class='table table-bordered table-hover'>
                        <tbody>
                            <tr>
                                <th rowspan='2'><h4>{$course->name}</h4></th>                       
                                <td><b>Нерешенных тестов:</b></td>
                                <td><b>Примерная оценка:</b></td>
                                <td rowspan='2' align='center'>
                                    <form action='course_student.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course_no' name='course_no'>Подробнее</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>
                            </tr>
                            <tr>                               
                                <th>{$tests_no}</th>
                                <th>{$point_5} ({$point}%)</th>                                                        
                            </tr>
                        </tbody>
                    </table>";
                $counter++;
            }
        }
        ?>
    </div>
</div>

<!-- Javascript files -->
<!-- jQuery -->
<script src="../js/jquery.js"></script>
<!-- Bootstrap JS -->
<script src="../js/bootstrap.min.js"></script>
<!-- Placeholder JS -->
<script src="../js/placeholder.js"></script>
<!-- Respond JS for IE8 -->
<script src="../js/respond.min.js"></script>
<!-- HTML5 Support for IE -->
<script src="../js/html5shiv.js"></script>

<script>