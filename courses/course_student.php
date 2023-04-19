<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 03.07.2020
 * Time: 16:03
 */

require_once '../db/db.php';
$course = R::findOne('courses','WHERE id = '.$_POST['course_id']);
$title = $course->name;
require __DIR__ . './../header_one_level.php';
error_reporting(0);
?>

<div class='container mt-4'>
    <div class='row'>
        <div class='col'>
            <h2>Учебный курс "<?php echo $course->name; ?>"</h2><br>
        </div>
    </div>
</div>

<div class="container">
    <?php
    $tests = R::findAll('tests','WHERE course_id = '.$course->id);
    foreach ($tests as $item){
        $question_count = count(R::findAll('questions','WHERE test_id = '.$item->id));
        $progress = R::findAll('results','WHERE test_id = '.$item->id.' AND user_id = '.$_SESSION['logged_user']->id);
        if($progress == []) {
            $prt = '---';
        }
        else {
            $prt = 0;
            foreach ($progress as $progress_0)
                if ($progress_0->point > $prt)
                    $prt = $progress_0->point;
            $prt .= '%';
        }

        if($item->attempts == -1) {
            $attempt = 'Не ограничено';
            $is_a = true;
        }
        else {
            $attempt = $item->attempts - count(R::findAll('results', 'WHERE user_id = ' . $_SESSION['logged_user']->id . ' AND test_id = ' . $item->id));
            if ($attempt > 0)
                $is_a = true;
            else
                $is_a = false;
        }
        echo "
            <table class='table table-bordered table-hover'>
                <tbody>
                    <tr>
                        <th rowspan='2'><h4>{$item->name}</h4></th> 
                        <td rowspan='2'>{$item->description}</td>                       
                            <td><b>Вопросов:</b></td>
                            <td><b>Осталось попыток:</b></td>
                            <td><b>Ваш прогресс:</b></td>                       
                        <td rowspan='2' align='center'>
                            <form action='tests/test_student.php' method='post' class='form-row'>
                                <div class='col'>                           
                                    <button type='submit' class='btn btn-green btn-sm' id='test' name='test'";
                                    if (!$is_a)
                                        echo "disabled";
                                    echo ">Подробнее</button>
                                    <input type='hidden' id='test_id' name='test_id' value='{$item->id}'>
                                </div> 
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th>{$question_count}</th>
                        <th>{$attempt}</th>
                        <th>{$prt}</th>                                                       
                    </tr>
                </tbody>
            </table>";
    }
    ?>
</div>