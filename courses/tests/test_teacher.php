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
$title = $test->name;
require './../../header_two_level.php';

$questions = R::findAll('questions','WHERE test_id = '.$test->id);
foreach ($questions as $question) {
    if (isset($_POST['delete'][$question->id])) {
        $question = R::findOne('questions', 'WHERE id = ' . $_POST['question_id']);
        $answers = R::findAll('answers', 'WHERE question_id = ' . $question->id);
        R::trash($question);
        R::trashAll($answers);
    }
}

echo "
<div class='container mt-4'>
    <h2>Настройки теста \"{$test->name}\"</h2><br>
</div>";

$counter = 1;
?>


<div class="container">
    <div class="col">
        <form action="../settings/question.php" method="post">
            <div align="center">
                <button class="btn btn-success" name="new">Новый вопрос</button><br><br>
                <input type='hidden' id='test_id' name='test_id' value='<?php echo $test->id; ?>'>
            </div>
        </form>
        <form method="post">
            <?php
            $questions = R::findAll('questions','WHERE test_id = '.$test->id);
            foreach ($questions as $question){
                echo "<h4>{$counter}. {$question->text}</h4>";
                $answers = R::findAll('answers','WHERE question_id = '.$question->id);
                foreach ($answers as $answer) {
                    if ($question->type == 'text')
                        echo "<input class='form-control' name='answer[{$question->id}][{$answer->id}]' value='{$answer->text}'><br>";
                    elseif ($question->type == 'radio'){
                        echo "<div class='form-inline'>
                                  <input class='form-check-input' type='radio' name='radio[{$question->id}]' id='radio{$question->id}]' value='{$answer->id}'";
                        if ($answer->point > 0)
                            echo " checked";
                        echo ">
                                  <label for='radio[{$question->id}]' class='label'><h5>{$answer->text}</h5></label><br>
                              </div>";
                    }
                    elseif ($question->type == 'checkbox'){
                        echo "<div class='form-inline'>
                                  <input class='form-check-input' type='checkbox' name='answer[{$question->id}][{$answer->id}]' id='answer[{$question->id}][{$answer->id}]'";
                        if ($answer->point > 0)
                            echo " checked";
                        echo ">
                                  <label for='answer[{$question->id}][{$answer->id}]' class='label'><h5>{$answer->text}</h5></label>
                               </div>";
                    }
                }
                echo "<div align='center'><button class='btn btn-success btn-sm' name='delete[{$question->id}]'>Удалить этот вопрос</button></div>
                      <input type='hidden' id='test_id' name='test_id' value='{$test->id}'>
                      <input type='hidden' id='question_id' name='question_id' value='{$question->id}'><br><br>";
                $counter++;
            }
            ?>
        </form>
    </div>
    <?php
    $course_id = R::findOne('courses','WHERE id = '.$test->course_id)->id;
    echo "<form action='../course_teacher.php' method='post'>
               <div align='center'><br>
                   <input type='hidden' id='course_id' name='course_id' value='{$course_id}'>
                   <button class='btn btn-success btn-sm' name='save_question'>Назад к курсу</button><br><br>
               </div>
          </form>";
    ?>
</div>