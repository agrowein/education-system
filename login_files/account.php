<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 28.06.2020
 * Time: 12:20
 */
$title = 'Личный кабинет';
require './../header_one_level.php';
require_once "../db/db.php";

$data = $_POST;
if(isset($data['change'])) {
    $errors = array();

    if($data['email'] != $_SESSION['logged_user']->email){
        // Правильность написания
        if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {
            $errors[] = 'Неверно введен новый е-mail';
        }
        // Проверка на уникальность email
        if(R::count('users', "email = ?", array($data['email'])) > 0) {
            $errors[] = "Пользователь с таким e-mail уже существует";
        }
        // записываем новую почту
        if(empty($errors)) {
            $user = $_SESSION['logged_user'];
            $user->email = $data['email'];
            R::store($user);
            $_SESSION['logged_user'] = $user;
            unset($user);
        } else {
            echo '<div style="color: red; ">' . array_shift($errors). '</div><hr>';
        }
    } else {
        if($data['new_password_1'] != '') {

            if ($data['new_password_2'] != $data['new_password_1']) {
                $errors[] = "Повторный пароль введен неверно";
            }
            if (!password_verify($data['old_password'], $_SESSION['logged_user']->password)) {
                $errors[] = "Неправильно введен старый пароль";
            }
            if (mb_strlen($data['new_password_1']) < 2 || mb_strlen($data['new_password_1']) > 20) {
                $errors[] = "Недопустимая длина нового пароля (от 2 до 20 символов)";
            }

            if (empty($errors)) {
                $user = $_SESSION['logged_user'];
                $user->password = password_hash($data['new_password_1'], PASSWORD_DEFAULT);
                R::store($user);
                $_SESSION['logged_user'] = $user;
                unset($user);
            } else {
                echo '<div style="color: red; " align="center">'.array_shift($errors).'</div><hr>';
            }
        }
    }
}
?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2 align="center">Ваши данные</h2>
            <form action="account.php" method="post">
                <label class="col-form-label">Логин: <?php echo $_SESSION['logged_user']->login; ?></label><br>
                <label class="col-form-label">Имя: <?php echo $_SESSION['logged_user']->name." ".$_SESSION['logged_user']->surname; ?></label><br>
                <label class="col-form-label">Вы состоите в группах:
                    <?php
                        $groups = R::findAll('usergroups','WHERE user_id = '.$_SESSION['logged_user']->id);
                        if($groups != []) {
                            foreach ($groups as $group) {
                                $tmp = R::findOne('groups', 'WHERE id = ' . $group->group_id);
                                echo $tmp->name . '; ';
                            }
                        }
                    ?>
                </label><br>
                <label class="col-form-label">Изменить электронную почту:</label><br>
                <input type="email" class="form-control" name="email" id="email" placeholder="Введите e-mail"
                    value="<?php echo $_SESSION['logged_user']->email; ?>"><br>
                <label class="col-form-label">Изменить пароль:</label><br>
                <input type="password" class="form-control" name="new_password_1" id="new_password_1" placeholder="Введите новый пароль"><br>
                <input type="password" class="form-control" name="new_password_2" id="new_password_2" placeholder="Повторите новый пароль"><br>
                <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Введите старый пароль"><br>
                <div align="center">
                <button class="btn btn-success" name="change" type="submit">Изменить данные</button><br>
                </div>
            </form>
        </div>
    </div>
</div>
