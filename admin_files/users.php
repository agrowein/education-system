<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 22.06.2020
 * Time: 19:09
 */
$title="Отчет по пользователям";
require __DIR__ . './../header_one_level.php';
require_once "../db/db.php";

if (isset($_POST['new_button'])){
    $str = $_POST['new_group'];
    if(R::findOne('groups','WHERE name = '.$str) == NULL){
        $new_group = R::dispense('groups');
        $new_group->name = $str;
        R::store($new_group);
    }
}

error_reporting(0);
if(isset($_POST['save'])){
    $user = R::findAll('users');
    $check4 = $_POST['check4'];
    $check3 = $_POST['check3'];
    $check2 = $_POST['check2'];
    $user_groups = $_POST['group'];
    foreach ($user as $item){
        $item->role = 0;
        if ($check4[$item->id] == 'on') {
            $item->role += 4;
        }
        if ($check3[$item->id] == 'on') {
            $item->role += 3;
        }
        if ($check2[$item->id] == 'on') {
            $item->role += 2;
        }

        $trash = R::findAll('usergroups','WHERE user_id = '.$item->id);
        R::trashAll($trash);

        $groups = R::findAll('groups');
        $user_group = R::dispense('usergroups');
        foreach ($groups as $group){
            if ($user_groups[$item->id][$group->id] == 'on'){
                $user_group->user_id = $item->id;
                $user_group->group_id = $group->id;
                R::store($user_group);
            }
        }
        R::store($item);
    }
}
?>

<body>
<div class="container mt-4">
    <div class="col col-md-10">
        <h4>
            Здесь представлены все пользователи платформы.<br>
            Вы можете настроить их права доступа и группы, выбрав соответствующие пункты в таблице.
        </h4>
        Будьте внимательны: после удаления прав администратора у самого себя вы потеряете доступ к этой странице.<br><br>
    </div>
    <form action="users.php" method="post">
        <div class="form-inline" align="center">
            <input type="text" class="form-control" name="find" id="find" placeholder="Имя и фамилия">&nbsp;
            <button class="btn btn-light" name="find_button" type="submit">Найти пользователя</button>
            <input type="text" class="form-control" name="new_group" id="new_group" placeholder="Название">&nbsp;
            <button class="btn btn-light" name="new_button" type="submit">Создать новую группу</button><br><br><br>
        </div>
        <div align="center">
            <button class="btn btn-success" name="save" type="submit">Сохранить изменения</button>
            <br><br>
        </div>
</div>

<?php
if(isset($_POST['find_button']) and $_POST['find'] != ""){
    $user_string = explode(" ", $_POST['find']);
    $user_name = $user_string[0];
    $user_surname = $user_string[1];

    $find_user = R::findOne('users', ' WHERE name = \''.$user_name.'\' AND surname = \''.$user_surname.'\' ');
    if ($find_user == NULL){
        echo '<div align="center">Пользователь с такими данными не найден</div>';
    } else { ?>
        <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Имя</th>
            <th scope="col">Фамилия</th>
            <th scope="col">E-mail</th>
            <th scope="col">Логин</th>
            <th scope="col">Группы</th>
            <th scope="col">Права доступа</th>
        </tr>
        </thead>
        <tbody>
        <?php
        echo '<tr><th scope="row">' . $find_user->id . '</th><td>' . $find_user->name . '</td><td>' . $find_user->surname . '</td>
                            <td>' . $find_user->email . '</td><td>' . $find_user->login . '</td><td align="center">';
        $groups = R::findAll('groups');
        $group_arr = R::findAll('usergroups','WHERE user_id = '.$find_user->id);
        $names = [];
        foreach ($group_arr as $r){
            $names = [$r->group_id];
        }
        foreach ($groups as $group){
            echo '<input type="checkbox" class="form-check-input" id="group['.$find_user->id.']['.$group->id.']" name="group['.$find_user->id.']['.$group->id.']"';
            if (in_array($group->id,$names)){
                echo 'checked';
            }
            echo '>
                    <label class="form-check-label" for="group['.$find_user->id.']['.$group->id.']">'.$group->name.'</label><br>';
        }

        echo '</td><td align="center">
                                    <input type="checkbox" class="form-check-input" id="check4['.$find_user->id.']" name="check4['.$find_user->id.']"';
        if ($find_user->role % 3 == 1 or (int)($find_user->role / 3) > 1):
            echo 'checked';
        endif;
        echo '>
                                    <label class="form-check-label" for="check4['.$find_user->id.']">Администратор</label><br>                  
                                    <input type="checkbox" class="form-check-input" id="check3['.$find_user->id.']" name="check3['.$find_user->id.']"';
        if ($find_user->role % 2 == 1):
            echo 'checked';
        endif;
        echo '>
                                    <label class="form-check-label" for="check3['.$find_user->id.']">Преподаватель</label><br>
                                    <input type="checkbox" class="form-check-input" id="check2['.$find_user->id.']" name="check2['.$find_user->id.']"';
        if ($find_user->role % 3 == 2 or (int)($find_user->role / 3) > 1):
            echo 'checked';
        endif;
        echo '>
                                    <label class="form-check-label" for="check2['.$find_user->id.']">Студент</label>           
                            </td></tr>                             
                        ';
    }
        ?>
        </tbody>
    </table>
    <?php
}
?>

<div class="container mt-4">
    <div class="col" align="center">

    </div>
</div>
<table class="table table-bordered table-hover">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Имя</th>
        <th scope="col">Фамилия</th>
        <th scope="col">E-mail</th>
        <th scope="col">Логин</th>
        <th scope="col">Группы</th>
        <th scope="col">Права доступа</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $user = R::findAll('users');
    foreach ($user as $item) {
        echo '<tr><th scope="row">'.$item->id.'</th><td>'.$item->name.'</td><td>'.$item->surname.'</td>
            <td>'.$item->email.'</td><td>'.$item->login.'</td><td align="center">';

        $groups = R::findAll('groups');
        $group_arr = R::findAll('usergroups','WHERE user_id = '.$item->id);
        $names = [];
        foreach ($group_arr as $r){
            $names = [$r->group_id];
        }
        foreach ($groups as $group){
            echo '<input type="checkbox" class="form-check-input" id="group['.$item->id.']['.$group->id.']" name="group['.$item->id.']['.$group->id.']"';
            if (in_array($group->id,$names)){
                echo 'checked';
            }
            echo '>
                    <label class="form-check-label" for="group['.$item->id.']['.$group->id.']">'.$group->name.'</label><br>';
        }

        echo '</td><td align="center">               
                   <input type="checkbox" class="form-check-input" id="check4['.$item->id.']" name="check4['.$item->id.']"';
        if ($item->role % 3 == 1 or (int)($item->role / 3) > 1) {
            echo 'checked';
        }
        echo '>
                    <label class="form-check-label" for="check4['.$item->id.']">Администратор</label><br>                  
                    <input type="checkbox" class="form-check-input" id="check3['.$item->id.']" name="check3['.$item->id.']"';
        if ($item->role % 2 == 1){
            echo 'checked';
        }
        echo '>
                    <label class="form-check-label" for="check3['.$item->id.']">Преподаватель</label><br>
                    <input type="checkbox" class="form-check-input" id="check2['.$item->id.']" name="check2['.$item->id.']"';
        if ($item->role % 3 == 2 or (int)($item->role / 3) > 1){
            echo 'checked';
        }
        echo '>
                    <label class="form-check-label" for="check2['.$item->id.']">Студент</label>           
            </td>
            </tr>                             
        ';
    }
    ?>
    </tbody>
</table>
</form>
</body>