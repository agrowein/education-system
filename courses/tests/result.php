<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 15.07.2020
 * Time: 12:08
 */
require_once '../../db/db.php';
$test_id = $_POST['test_id'];
$test = R::findOne('tests','WHERE id = '.$test_id);
$title = $test->name;
require __DIR__ . './../../header_two_level.php';
error_reporting(0);

if (isset($_POST['save_question'])){
    $test = R::findOne('tests','WHERE id = '.$_POST['test_id']);
    $questions = R::findAll('questions','WHERE test_id = '.$test->id);
    $point = 0;

    $result = R::dispense('results');
    $result->user_id = $_SESSION['logged_user']->id;
    $result->test_id = $test->id;

    foreach ($questions as $question) {
        $answers = R::findAll('answers','WHERE question_id = '.$question->id);
        if ($question->type == 'text'){
            foreach ($answers as $answer)
                if ($answer->text == $_POST['answer'][$question->id][$answer->id])
                    $point += $answer->point;
        }
        elseif ($question->type == 'radio'){
            foreach ($answers as $answer)
                if ($answer->id == $_POST['radio'][$question->id])
                    $point += $answer->point;
        }
        elseif ($question->type == 'checkbox'){
            foreach ($answers as $answer)
                if (isset($_POST['answer'][$question->id][$answer->id]) and $_POST['answer'][$question->id][$answer->id] == 'on')
                    $point += $answer->point;
        }
    }
    $point /= (int)(count($questions));
    if ($point < 0)
        $point = 0;
    $result->point = (int)($point);
    R::store($result);
    unset($_POST['save_question']);

    $point_5 = 2;
    if ($point > 50)
        $point_5 = 3;
    if ($point > 70)
        $point_5 = 4;
    if ($point > 90)
        $point_5 = 5;

    $progress = R::findAll('results','WHERE test_id = '.$test->id.' AND user_id = '.$_SESSION['logged_user']->id);
    $best = 0;
    foreach ($progress as $progress_0) {
        if ($progress_0->point > $best)
            $best = $progress_0->point;
    }
    $best_5 = 2;
    if ($best > 50)
        $best_5 = 3;
    if ($best > 70)
        $best_5 = 4;
    if ($best > 90)
        $best_5 = 5;
}

echo "
<div class='container mt-4'>
    <h2>Вы завершили тест \"{$test->name}\"!</h2><br>
</div>";
?>

<link href="../../css/style-318.css" rel="stylesheet">

<div class="ui-318">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6 col-mob">
                <div class="db-item db-br-red item-one">
                    <h4>Ваш Текущий результат</h4>
                    <input class="knob" data-angleOffset=-180 data-angleArc=360 data-bgColor="rgba(247,83,83,0.3)"
                           data-fgColor="#f75353" data-thickness=".2" value="<?php echo (int)($point); ?>" data-end="<?php echo (int)($point); ?>">
                    <div class="db-details">
                        <ul class="text-left list-unstyled">
                            <li><i class="fa fa-square red"></i> &nbsp;Правильных ответов:
                                <b><?php echo (int)($point); ?>%</b></li>
                            <li><i class="fa fa-square green"></i> &nbsp;Примерная оценка:
                                <b><?php echo $point_5; ?></b></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6 col-mob">
                <div class="db-item db-br-red item-one">
                    <h4>Ваш Лучший результат</h4>
                    <input class="knob" data-angleOffset=-180 data-angleArc=360 data-bgColor="rgba(247,83,83,0.3)"
                           data-fgColor="#f75353" data-thickness=".2" value="<?php echo (int)($best); ?>" data-end="<?php echo (int)($best); ?>">
                    <div class="db-details">
                        <ul class="text-left list-unstyled">
                            <li><i class="fa fa-square red"></i> &nbsp;Правильных ответов:
                                <b><?php echo (int)($best); ?>%</b></li>
                            <li><i class="fa fa-square green"></i> &nbsp;Примерная оценка:
                                <b><?php echo $best_5; ?></b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form action="../course_student.php" method="post">
    <input type='hidden' id='course_id' name='course_id' value='<?php echo $_POST['course_id']; ?>'><br>
    <button class='btn btn-success' name='return'>Назад к курсу</button><br><br>
</form>


<script src="../../js/jquery.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/jquery.knob.min.js"></script>
<script src="../../js/placeholder.js"></script>
<script src="../../js/respond.min.js"></script>
<script src="../../js/html5shiv.js"></script>

<script>
    $(function() {
        $(".knob").knob({
            width: 100,
            height: 100,
            readOnly: true
        });
    });
</script>
