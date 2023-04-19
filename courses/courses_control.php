<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 02.07.2020
 * Time: 13:51
 */
$title = "Управление курсами";
require __DIR__ . './../header_one_level.php';
require_once '../db/db.php';

if(isset($_POST['save']) and $_POST['name'] != "") {
    $course = R::dispense('courses');
    $course->name = $_POST['name'];
    $course->description = $_POST['info'];
    R::store($course);

    $course_user = R::dispense('participants');
    $course_user->course_id = $course->id;
    $course_user->user_id = $_SESSION['logged_user']->id;
    $course_user->role = 3;
    R::store($course_user);

    if(!in_array('coursegroups', R::inspect())) {
        $course_group = R::dispense('coursegroups');
        $course_group->course_id = -1;
        $course_group->group_id = -1;
        R::store($course_group);
    }

    unset($_POST['save']);
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2>Управление курсами</h2>
            <h5>Здесь представлены все курсы, в которых вы являетесь преподавателем.</h5>
            <h4>Найти курс: </h4>
        </div>
    </div>
</div>

<form action="courses_control.php" method="post" class="form-inline">
    <div align="center">
    <input type="text" class="form-control" name="find" id="find" placeholder="Название">&nbsp;
    <button type="submit" class="btn btn-light" name="find_course">Найти</button>
    </div>
</form><br>

<div class="container">
    <div class="container-fluid">
        <?php
        if (isset($_POST['find_course']) and $_POST['find'] != ""){
            $name = $_POST['find'];
            $course = R::findOne('courses', 'WHERE name = \''.$name.'\'');

            if($course != NULL){
                $users_count = count(R::findAll('participants', 'WHERE course_id = '.$course->id));
                $tests_count = count(R::findAll('tests','WHERE course_id = '.$course->id));
                echo "
                    <table class='table table-bordered'>
                        <tbody>
                            <tr>
                                <th rowspan='2'><h4>{$course->name}</h4></th> 
                                <th rowspan='2'>{$course->description}</th>                      
                                <td><b>Тестов:</b></td>
                                <td><b>Участников:</b></td>                              
                                <td rowspan='2' align='center'>
                                    <form action='course_teacher.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course' name='course'>Настройки</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <th>{$tests_count}</th>
                                <th>{$users_count}</th>    
                                <td align='center'>
                                    <form action='tests/all_course_results.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course' name='course'>Оценки участников</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>                                                                                 
                            </tr>
                        </tbody>
                    </table>";
            }
        }

        $courses = R::findAll('participants', 'WHERE user_id = '.$_SESSION['logged_user']->id.' AND role = 3');
        if($courses != []){
            $counter = 1;
            foreach ($courses as $item) {
                $course = R::findOne('courses', 'WHERE id = ' . $item->course_id . '');
                $users_count = count(R::findAll('participants', 'WHERE course_id = ' . $course->id . ' AND role = 2'));
                $tests_count = count(R::findAll('tests','WHERE course_id = '.$course->id));
                echo "
                    <table class='table table-bordered table-hover'>
                        <tbody>
                            <tr>
                                <th rowspan='2'><h4>{$course->name}</h4></th>    
                                <th rowspan='2'>{$course->description}</th>                   
                                <td><b>Тестов:</b></td>
                                <td><b>Участников:</b></td>
                                <td align='center'>
                                    <form action='course_teacher.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course' name='course'>Настройки</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <th>{$tests_count}</th>
                                <th>{$users_count}</th>   
                                <td align='center'>
                                    <form action='tests/all_course_results.php' method='post' class='form-row'>
                                        <div class='col'>                           
                                            <button type='submit' class='btn btn-green btn-sm' id='course' name='course'>Оценки участников</button>
                                            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
                                        </div> 
                                    </form>
                                </td>                                                    
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