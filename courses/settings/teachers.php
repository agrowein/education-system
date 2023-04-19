<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 07.07.2020
 * Time: 15:11
 */
require_once '../../db/db.php';
$course = R::findOne('courses','WHERE id = '.$_POST['course_id']);
$title = 'Настройки '.$course->name;
require __DIR__ . './../../header_two_level.php';

error_reporting(0);
if (isset($_POST['add_teacher_button'])){
    $user_string = explode(" ", $_POST['add_teacher']);
    $user_name = $user_string[0];
    $user_surname = $user_string[1];
    $new_teacher = R::findOne('users','WHERE name = \''.$user_name.'\' AND surname = \''.$user_surname.'\'');
    if($new_teacher != NULL) {
        $new_teacher_db = R::dispense('participants');
        $new_teacher_db->course_id = $course->id;
        $new_teacher_db->user_id = $new_teacher->id;
        $new_teacher_db->role = 3;
        R::store($new_teacher_db);
    }
    else
        echo "<div align='center'>Пользователь с такими данными не найден</div>";
}

if (isset($_POST['delete_button'])){
    $teachers = R::findAll('participants','WHERE course_id = '.$course->id.' AND role = 3');
    $check = $_POST['delete'];
    foreach ($teachers as $value){
        if($check[$value->id] == "on"){
            R::trash('participants',$value->id);
        }
    }
}
?>

<div class='container mt-4'>
    <div class='row'>
        <div class='col'>
            <h2>Преподаватели курса "<?php echo $course->name; ?>"</h2>
            Будьте внимательны: после удаления прав преподавателя у самого себя вы потеряете доступ к этой странице.<br>
            <form method='post'>
                <div class='form-inline'>
                    <input type='text' class='form-control' id='add_teacher' name='add_teacher' placeholder='Имя и фамилия'>&nbsp;
                    <button type='submit' class='btn btn-light' name='add_teacher_button'>Добавить преподавателя</button>
                    <input type='hidden' id='course_id' name='course_id' value='<?php echo $course->id; ?>'>
                </div><br>
                <div align="center">
                    <button type="submit" class="btn btn-success" name="delete_button">Применить изменения</button>
                </div><br>
        </div>
    </div>
</div>

<?php
$teachers = R::findAll('participants','WHERE course_id = '.$course->id.' AND role = 3');
echo "
       
            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
            <table class='table table-bordered table-hover'>
                <thead>
                    <tr>
                        <th scope='col'>ID</th>
                        <th scope='col'>Имя</th>
                        <th scope='col'>Удалить</th>
                    </tr>
                </thead>
                <tbody>";
foreach ($teachers as $item) {
    $teacher = R::findOne('users','WHERE id = '.$item->user_id);
    echo "
                    <tr>
                        <th scope='row'>{$teacher->id}</th>
                        <td>{$teacher->name} {$teacher->surname}</td>
                        <td align='center'>                           
                            <input type='checkbox' class='form-check-input' id='delete[{$item->id}]' name='delete[{$item->id}]'>
                            <label for='delete[{$item->id}]' class='form-check-label'>Удалить</label> 
                        </td>
                    </tr>
                    ";
}
echo "
                </tbody>
            </table>                       
        </form>
    ";
?>
<div>
    <form action="../course_teacher.php" method="post">
        <button class="btn btn-success" name="return">Назад к курсу</button>
        <input type="hidden" name="course_id" id="course_id" value="<?php echo $course->id; ?>">
    </form>
</div>
