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
if (isset($_POST['add_group_button'])){
    $new_group = R::findOne('groups','WHERE name = \''.$_POST['add_group'].'\'');
    //$new_group = R::findOne('groups','WHERE id = '.$_POST['add_group']);

    $coursegroup = R::dispense('coursegroups');
    $coursegroup->course_id = $course->id;
    $coursegroup->group_id = $new_group->id;
    R::store($coursegroup);

    if($new_group != NULL) {
        $all = R::findAll('usergroups','WHERE group_id = '.$new_group->id);
        $new_participant = R::dispense('participants');
        foreach ($all as $item){
            $new_participant->course_id = $course->id;
            $new_participant->user_id = $item->user_id;
            $new_participant->role = 2;
            R::store($new_participant);
        }
    }
    else
        echo "<div align='center'>Группа с такими данными не найдена</div>";
}

if (isset($_POST['delete_button'])){
    $groups = R::findAll('coursegroups','WHERE course_id = '.$course->id);
    $check = $_POST['delete'];
    foreach ($groups as $item){
        $tmp = R::findOne('groups','WHERE id = '.$item->group_id);
        if($check[$tmp->id] == "on"){
            R::trash('coursegroups',$item->id);
            $all = R::findAll('usergroups','WHERE group_id = '.$tmp->id);
            foreach ($all as $value){
                $delete = R::findOne('participants','WHERE user_id = '.$value->user_id.' AND course_id = '.$course->id.' AND role = 3');
                if ($delete != NULL) {
                    R::trash('participants', $delete->id);
                }
            }
        }
    }
}
?>

<div class='container mt-4'>
    <div class='row'>
        <div class='col'>
            <h2>Студенты курса "<?php echo $course->name; ?>"</h2>
            При необходимости создания новой группы или редактирования имеющихся обратитесь к администратору.<br>
            <form action="groups.php" method='post'>
                <div class='form-inline'>
                    <input type='text' class='form-control' id='add_group' name='add_group' placeholder='Название группы'>&nbsp;
                    <button type='submit' class='btn btn-light' name='add_group_button'>Добавить группу</button>
                    <input type='hidden' id='course_id' name='course_id' value='<?php echo $course->id; ?>'>
                </div><br>
                <div align="center">
                    <button type="submit" class="btn btn-success" name="delete_button">Применить изменения</button>
                </div><br>
        </div>
    </div>
</div>

<?php
$groups = R::findAll('coursegroups','WHERE course_id = '.$course->id);
echo "
       
            <input type='hidden' id='course_id' name='course_id' value='{$course->id}'>
            <table class='table table-bordered table-hover'>
                <thead>
                    <tr>
                        <th scope='col'>ID</th>
                        <th scope='col'>Название</th>
                        <th scope='col'>Количество участников</th>
                        <th scope='col'>Удалить</th>
                    </tr>
                </thead>
                <tbody>";
foreach ($groups as $group) {
    $tmp = R::findOne('groups','WHERE id = '.$group->group_id);
    $counter = count(R::findAll('usergroups','WHERE group_id ='.$tmp->id));
    echo "
                    <tr>
                        <th scope='row'>{$tmp->id}</th>
                        <td>{$tmp->name}</td>                      
                        <td>{$counter}</td>
                        <td align='center'>                           
                            <input type='checkbox' class='form-check-input' id='delete[{$tmp->id}]' name='delete[{$tmp->id}]'>
                            <label for='delete[{$tmp->id}]' class='form-check-label'>Удалить</label> 
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
