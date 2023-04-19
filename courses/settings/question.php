<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.07.2020
 * Time: 15:11
 */
require_once '../../db/db.php';
$test_id = $_POST['test_id'];
$test = R::findOne('tests','WHERE id = '.$test_id);
$title = 'Вопрос для '.$test->name;
require __DIR__ . './../../header_two_level.php';
error_reporting(0);

echo "
<div class='container mt-4'>
    <h2>Новый вопрос для теста \"{$test->name}\"</h2><br>
    <form method='post'>
        <div class='form-group'>
            <label for='question_type'><h5>Выберите тип вопроса:</h5></label>
            <select class='form-control' id='question_type' name='question_type'>
                <option value='1'>С ответом в виде текста</option>
                <option value='2'>С выбором одного ответа</option>
                <option value='3'>С выбором нескольких ответов</option>
            </select>
        </div>
        <input class='form-control' type='text' id='number' name='number' placeholder='Количество вариантов ответа'><br>
        <input type='hidden' id='test_id' name='test_id' value='{$test->id}'>
        <div align='center'>
            <button class='btn btn-success btn-sm' name='button'>Применить</button>
        </div>
    </form>
</div>";
?>

<div class="container">
<?php
if (isset($_POST['button'])){
    echo "
        <form method='post'>
            <input type='hidden' id='test_id' name='test_id' value='{$test->id}'>
            <input type='hidden' id='question_type' name='question_type' value='{$_POST['question_type']}'>
            <label class='col-form-label' for='question_text'><h5>Текст вопроса: </h5></label><br>
            <textarea class='form-control' name='question_text' id='question_text' rows='3' required></textarea><br>
    ";
    $number = $_POST['number'];
    if ($number == "")
        $number = 1;

    if ($_POST['question_type'] == 1){
        for ($i = 0; $i < $number; $i++) {
            echo "
                <input type='text' class='form-control' id='answer[{$i}]' name='answer[{$i}]' placeholder='Правильный ответ' required><br>
            ";
        }
        echo "</div>";
    }
    else if ($_POST['question_type'] == 2){
        echo "<p align='center'>Не забудьте выбрать правильный вариант ответа!</p>";
        echo "<div class='form-check'>";
        for ($i = 0; $i < $number; $i++) {
            echo "
                <div class='form-inline'>   
                    <input class='form-check-input' type='radio' name='radio' id='radio' value='{$i}'>&nbsp;
                    <input class='form-control' type='text' id='answer[{$i}]' name='answer[{$i}]' placeholder='Вариант ответа' required><br>
                </div>
            ";
        }
        echo "</div>";
    }
    else{
        echo "<p align='center'>Не забудьте выбрать правильные варианты ответа!</p>";
        for ($i = 0; $i < $number; $i++) {
            echo "
                <div class='form-inline'>
                    <input class='form-check-input' type='checkbox' name='check[{$i}]' id='check[{$i}]'>&nbsp;
                    <input class='form-control' type='text' id='answer[{$i}]' name='answer[{$i}]' placeholder='Вариант ответа' required><br>
                </div>
            ";
        }
    }

    echo "
        <div align='center'><br>
            <input type='hidden' id='number' name='number' value='{$number}'>
            <button class='btn btn-success btn-sm' name='save_question'>Сохранить</button>
        </div>
       
       
    ";
}
?>
    </form>
    <form action='../tests/test_teacher.php' method='post'>
        <div align='center'>
            <br><br><button class='btn btn-success' name='return'>Назад к тесту</button>
            <input type='hidden' name='test_id' id='test_id' value='<?php echo $test->id; ?>'>
        </div>
    </form>
</div>

<?php

if(isset($_POST['save_question'])){
    $type = $_POST['question_type'];
    $question = R::dispense('questions');
    $question->test_id = $test_id;
    $question->text = $_POST['question_text'];
    if ($type == 1)
        $question->type = 'text';
    elseif ($type == 2)
        $question->type = 'radio';
    elseif ($type == 3)
        $question->type = 'checkbox';
    R::store($question);

    $number = $_POST['number'];
    $number_pos = 0; $number_neg = 0;
    if ($type == 3) {
        for ($i = 0; $i < $number; $i++) {
            if ($_POST['check'][$i] == 'on')
                $number_pos++;
            else
                $number_neg++;
        }
    }
    $answers = $_POST['answer'];

    for ($i = 0; $i < $number; $i++){
        $answer = R::dispense('answers');
        $answer->question_id = $question->id;
        $answer->text = $answers[$i];

        if ($type == 1){
            $answer->point = (int)(100 / $number);
        }
        elseif ($type == 2){
            if ($_POST['radio'] == $i)
                $answer->point = 100;
            else
                $answer->point = 0;
        }
        elseif ($type == 3){
            if ($_POST['check'][$i] == 'on')
                $answer->point = (int)((100 / $number_pos));
            else
                $answer->point = (int)(-(100 / $number_neg));
        }

        R::store($answer);
    }
}

?>