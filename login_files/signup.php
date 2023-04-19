<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 01.06.2020
 * Time: 13:14
 */
$title="Регистрация";
require_once "../db/db.php";

$data = $_POST;
if(isset($data['do_signup'])) {
    $errors = array();

    if(trim($data['login']) == '') {
        $errors[] = "Введите логин!";
    }
    if(trim($data['email']) == '') {
        $errors[] = "Введите e-mail";
    }
    if(trim($data['name']) == '') {
        $errors[] = "Введите имя";
    }
    if(trim($data['surname']) == '') {
        $errors[] = "Введите фамилию";
    }
    if($data['password'] == '') {
        $errors[] = "Введите пароль";
    }

    if($data['password_2'] != $data['password']) {
        $errors[] = "Повторный пароль введен не верно!";
    }

    if(mb_strlen($data['login']) < 3 || mb_strlen($data['login']) > 90) {
        $errors[] = "Недопустимая длина логина";
    }
    if (mb_strlen($data['name']) < 1 || mb_strlen($data['name']) > 50){
        $errors[] = "Недопустимая длина имени";
    }
    if (mb_strlen($data['surname']) < 1 || mb_strlen($data['surname']) > 50){
        $errors[] = "Недопустимая длина фамилии";
    }
    if (mb_strlen($data['password']) < 2 || mb_strlen($data['password']) > 20){
        $errors[] = "Недопустимая длина пароля (от 2 до 20 символов)";
    }

    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {
        $errors[] = 'Неверно введен е-mail';
    }

    if(R::count('users', "login = ?", array($data['login'])) > 0) {
        $errors[] = "Пользователь с таким логином существует!";
    }

    if(R::count('users', "email = ?", array($data['email'])) > 0) {
        $errors[] = "Пользователь с таким e-mail существует!";
    }


    if(empty($errors)) {
        // Выбираем таблицу (если ее не существует, то она будет создана)
        $user = R::dispense('users');
        $user_group = R::dispense('usergroups');

        // Запись в базу
        // Записываем данные пользователя
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->surname = $data['surname'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->role = 2;
        R::store($user);

        if (!in_array('groups', R::inspect()) and R::findOne('groups','WHERE name = 2020_01') == NULL){
            $group = R::dispense('groups');
            $group->name = "2020_01";
            R::store($group);

            $user_group->user_id = $user->id;
            $user_group->group_id = $group->id;
            R::store($user_group);

            $courses = R::findAll('coursegroups','WHERE group_id = '.$group->id);
            foreach ($courses as $course){
                $part = R::dispense('participants');
                $tmp = R::findOne('courses','WHERE id = '.$course->course_id);
                $part->user_id = $user->id;
                $part->course_id = $tmp->id;
                $part->role = 2;
                R::store($part);
            }
        }
        else {
            // Выборка из базы
            // Ищем группу по id
            $group = R::findOne('groups','WHERE id = 1');

            $user_group->user_id = $user->id;
            $user_group->group_id = $group->id;
            R::store($user_group);

            $courses = R::findAll('coursegroups','WHERE group_id = '.$group->id);
            foreach ($courses as $course) {
                $part = R::dispense('participants');
                $tmp = R::findOne('courses', 'WHERE id = ' . $course->course_id);
                $part->user_id = $user->id;
                $part->course_id = $tmp->id;
                $part->role = 2;
                R::store($part);
            }
        }
        echo '<div style="color: green; ">Вы успешно зарегистрированы! Можно <a href="login.php">авторизоваться</a>.</div><hr>';

    } else {
        echo '<div style="color: red; ">' . array_shift($errors). '</div><hr>';
    }
}
?>
<title>Регистрация</title>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

<div class="container mt-4">
    <div class="row">
        <div class="col">
		    <h2 align="center">Регистрация</h2>
            <form action="signup.php" method="post">
                <input type="text" class="form-control" name="login" id="login" placeholder="Логин"><br>
                <input type="email" class="form-control" name="email" id="email" placeholder="E-mail"><br>
                <input type="text" class="form-control" name="name" id="name" placeholder="Имя" required><br>
                <input type="text" class="form-control" name="surname" id="surname" placeholder="Фамилия" required><br>
                <input type="password" class="form-control" name="password" id="password" placeholder="Пароль"><br>
                <input type="password" class="form-control" name="password_2" id="password_2" placeholder="Повторите пароль"><br>
                <div align="center">
                <button class="btn btn-success" name="do_signup" type="submit">Зарегистрироваться</button>
                </div>
            </form>
		    <br>
		    <p>Если вы зарегистрированы, нажмите <a href="login.php">здесь</a>.</p>
		    <p>Вернуться на <a href="../index.php">главную</a>.</p>
        </div>
    </div>
</div>

