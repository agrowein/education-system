<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 13.07.2020
 * Time: 13:01
 */
require_once '../../db/db.php';
$test_id = $_POST['test_id'];
$test = R::findOne('tests','WHERE id = '.$test_id);
$title = $test->name;
require __DIR__ . './../../header_two_level.php';

echo "
<div class='container mt-4'>
    <h2 align='center'>Вы проходите тест \"{$test->name}\"</h2><br>
</div>";

$counter = 1;
?>


<div class="container">
    <div class="col">
        <form action="result.php" method="post">
            <?php
            $questions = R::findAll('questions','WHERE test_id = '.$test->id);
            foreach ($questions as $question){
                echo "<h4>{$counter}.   {$question->text}</h4>";
                $answers = R::findAll('answers','WHERE question_id = '.$question->id);
                foreach ($answers as $answer) {
                    if ($question->type == 'text')
                        echo "<input class='form-control' name='answer[{$question->id}][{$answer->id}]'>";
                    elseif ($question->type == 'radio'){
                        echo "<div class='form-inline'>
                                  <input class='form-check-input' type='radio' name='radio[{$question->id}]' id='radio{$question->id}]' value='{$answer->id}'>
                                  <label for='radio[{$question->id}]' class='label'><h5>{$answer->text}</h5></label><br>
                              </div>";
                    }
                    elseif ($question->type == 'checkbox'){
                        echo "<div class='form-inline'>
                                  <input class='form-check-input' type='checkbox' name='answer[{$question->id}][{$answer->id}]' id='answer[{$question->id}][{$answer->id}]'>
                                  <label for='answer[{$question->id}][{$answer->id}]' class='label'><h5>{$answer->text}</h5></label>
                               </div>";
                    }
                }
                echo "<br>";
                $counter++;
            }
            $course_id = R::findOne('courses','WHERE id = '.$test->course_id)->id;
            echo "<div align='center'><br>
                      <input type='hidden' id='course_id' name='course_id' value='{$course_id}'>
                      <input type='hidden' id='test_id' name='test_id' value='{$test_id}'>
                      <button class='btn btn-success btn-sm' name='save_question'>Сохранить и завершить</button><br><br>
                  </div>";
            ?>
        </form>
    </div>
</div>