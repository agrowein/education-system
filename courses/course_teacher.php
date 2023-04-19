<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.07.2020
 * Time: 11:04
 */

require_once '../db/db.php';
$course = R::findOne('courses','WHERE id = '.$_POST['course_id']);
$title = $course->name;
require __DIR__ . './../header_one_level.php';

if(isset($_POST['save_test'])){
    $test = R::dispense('tests');
    $test->name = $_POST['name'];
    $test->description = $_POST['info'];
    $test->course_id = $_POST['course_id'];
    if(isset($_POST['attempt']) and $_POST['attempt'] != "")
        $test->attempts = $_POST['attempt'];
    else
        $test->attempts = -1;
    R::store($test);
    unset($_POST['save_test']);
}
?>

<div class='container mt-4'>
    <div class='row'>
        <div class='col'>
            <h2>Управление курсом "<?php echo $course->name; ?>"</h2><br>
        </div>
    </div>
</div>

<form action='settings/teachers.php' method='post' class='form-inline'>
    <label for="course_id"><h5>Настроить списки преподавателей:</h5></label>&nbsp;&nbsp;
    <input type='hidden' id='course_id' name='course_id' value='<?php echo $course->id; ?>'>
    <button type='submit' class='btn btn-green btn-sm' name='teachers'>Преподаватели</button>
</form>

<form action='settings/groups.php' method='post' class='form-inline'>
    <label for="course_id"><h5>Настроить списки студентов:</h5></label>&nbsp;&nbsp;
    <input type='hidden' id='course_id' name='course_id' value='<?php echo $course->id; ?>'>
    <button type='submit' class='btn btn-green btn-sm' name='teachers'>Слушатели</button>
</form>

<form action='settings/new_test.php' method='post' class='form-inline'>
    <label for="course_id"><h5>Создать новый тест:</h5></label>&nbsp;&nbsp;
    <input type='hidden' id='course_id' name='course_id' value='<?php echo $course->id; ?>'>
    <button type='submit' class='btn btn-green btn-sm' name='teachers'>Новый тест</button>
</form><br>
<form action='courses_control.php' method='post'>
    <div align='center'>
        <button class='btn btn-success' name='return'>Назад к курсам</button><br><br>
    </div>
</form>

<div class="container">
    <?php
        $tests = R::findAll('tests','WHERE course_id = '.$course->id);
        foreach ($tests as $item){
            $question_count = count(R::findAll('questions','WHERE test_id = '.$item->id));
            if ($item->attempts == -1)
                $attempts = "Не ограничено";
            else
                $attempts = $item->attempts;
            $users_count = 0;
            foreach (R::findAll('participants','WHERE course_id = '.$course->id.' AND role = 2') as $value){
                $results = R::findAll('results','WHERE test_id = '.$item->id.' AND user_id = '.$value->user_id);
                if ($results != []){
                    $prt = 0;
                    foreach ($results as $result) {
                        if ($result->point > $prt)
                            $prt = $result->point;
                    }
                    if ($prt > 50)
                        $users_count++;
                }
            }
            echo "
            <table class='table table-bordered'>
                <tbody>
                    <tr>
                        <th rowspan='2'><h4>{$item->name}</h4></th>                       
                            <td><b>Вопросов:</b></td>
                            <td><b>Попыток разрешено:</b></td>
                            <td><b>Прошедших тест:</b></td>
                        <td align='center'>
                            <form action='tests/test_teacher.php' method='post' class='form-row'>
                                <div class='col'>                           
                                    <button type='submit' class='btn btn-green btn-sm' id='test' name='test'>Редактировать вопросы</button>
                                    <input type='hidden' id='test_id' name='test_id' value='{$item->id}'>
                                </div> 
                            </form>
                        </td>                       
                    </tr>
                    <tr>
                        <th>{$question_count}</th>     
                        <th>{$attempts}</th> 
                        <th>{$users_count}</th> 
                        <td align='center'>
                            <form action='tests/all_test_results.php' method='post' class='form-row'>
                                <div class='col'>                           
                                    <button type='submit' class='btn btn-green btn-sm' id='test' name='test'>Оценки участников</button>
                                    <input type='hidden' id='test_id' name='test_id' value='{$item->id}'>
                                </div> 
                            </form>
                        </td>                                                  
                    </tr>
                </tbody>
            </table>";
        }
    ?>
</div>



