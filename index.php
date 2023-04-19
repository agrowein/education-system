<?php
/**
 * Created by PhpStorm.
 * User: Andrey
 * Date: 01.06.2020
 * Time: 13:34
 */
$title="Главная";
require __DIR__ . './header_current_level.php';
require_once "db/db.php"; // подключаем файл для соединения с БД

error_reporting(0);
?>
<div class="container mt-4">
    <div class="row">
        <div class="col" align="center">
            <br><br><br>
            <h1>Добро пожаловать на наш сайт!</h1>
            <?php
                if (!isset($_SESSION['logged_user']))
                    echo "<h5>Чтобы начать работу с сайтом, необходимо</h5><h4><a href='login_files/signup.php'>зарегистрироваться</a> или <a href='login_files/login.php'>авторизоваться</a>.</h4>";
            ?>
            <br><h5>Данное приложение разработано в рамках проекто-исследовательской практики по теме "Тестирование по учебной дисциплине".<br>
            Оно позволяет создавать курсы и тесты, проводить тестирования студентов, получать статистику прохождений тестов.<br><br>
            Все ссылки на необходимые страницы вы можете найти в выпадающем меню сверху (доступно авторизованным пользователям).<br><br>
            Чтобы изменить свои права доступа, обратитесь к действующим администраторам.<br><br>
            </h5>
        </div>
    </div>
</div>
