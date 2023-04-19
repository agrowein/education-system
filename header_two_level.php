<?php
require_once "../../db/db.php"; // подключаем файл для соединения с БД
?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <link href="../../css/style-94.css" rel="stylesheet">
        <meta content="text/html; charset=utf-8">
    </head>
    <header>
        <nav class="navbar navbar-light" style="background-color: #acfa9e;">
            <a class="navbar-brand">
                <img src="../../images/logotip.png" width="30" height="30" class="d-inline-block align-top" alt="">
                Educational Testing Service | ETS
            </a>
            <div class="text-right"
            <?php if(isset($_SESSION['logged_user'])) : ?>
                <a>
                    Вы авторизованы под именем <b><?php echo $_SESSION['logged_user']->name." ".$_SESSION['logged_user']->surname; ?></b>
                </a>
            <?php else : ?>
                <a class="btn btn-primary" href="./../../login_files/login.php" role="button"></a>
                <a class="btn btn-warning" href="./../../login_files/login.php" role="button">Войти</a>
                <a class="btn btn-warning" href="./../../login_files/signup.php" role="button">Регистрация</a>
            <?php endif; ?>
            </div>
        </nav>
    </header>
<body bgcolor="#7fffd4">
<?php if(isset($_SESSION['logged_user'])) : ?>
    <div class="ui-94">
        <div class="container">
            <!-- Navigation Menu Start -->
            <div class="navigation">
                <div class="row">
                    <?php if((int)($_SESSION['logged_user']->role / 3) > 1 or $_SESSION['logged_user']->role % 3 == 1) : ?>
                        <div class="col-md-3 col-sm-4 col-xs-6 col">
                            <div class="menu">
                                <span class="bg-green">&nbsp; Администрирование</span>
                                <div class="menu-list">
                                    <ul>
                                        <li><a href="../../admin_files/users.php"> Учетные записи</a></li>
                                        <li><a href="../../admin_files/courses.php"> Курсы</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif;
                    if($_SESSION['logged_user']->role % 2 == 1) : ?>
                        <div class="col-md-3 col-sm-4 col-xs-6 col">
                            <div class="menu">
                                <span class="bg-yellow">&nbsp; Управление курсами</span>
                                <div class="menu-list">
                                    <ul>
                                        <li><a href="../../courses/courses_control.php"> Управление курсами</a></li>
                                        <li><a href="../../courses/create_course.php"> Новый курс</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif;
                    if((int)($_SESSION['logged_user']->role / 3) > 1 or $_SESSION['logged_user']->role % 3 == 2) : ?>
                        <div class="col-md-3 col-sm-4 col-xs-6 col">
                            <div class="menu">
                                <span class="bg-blue">&nbsp; Мои курсы</span>
                                <div class="menu-list">
                                    <ul>
                                        <li><a href="../../courses/my_courses.php"> Курсы</a></li>
                                        <li><a href="../../courses/progress.php"> Успеваемость</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3 col-sm-4 col-xs-6 col">
                        <div class="menu">
                            <span class="bg-red">&nbsp; Личное</span>
                            <div class="menu-list">
                                <ul>
                                    <li><a href="../../login_files/account.php"> Личный кабинет</a></li>
                                    <li><a href="../../login_files/logout.php"> Выход</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation menu end -->
    </div>
    <!-- Navigation Menu Button -->
    <div class="menu-btn">
        <a class="bg-green" href="#">МЕНЮ </a>
    </div>

    <!-- Javascript files -->
    <!-- jQuery -->
    <script src="../../js/jquery.js"></script>
    <!-- Bootstrap JS -->
    <script src="../../js/bootstrap.min.js"></script>
    <!-- Placeholder JS -->
    <script src="../../js/placeholder.js"></script>
    <!-- Respond JS for IE8 -->
    <script src="../../js/respond.min.js"></script>
    <!-- HTML5 Support for IE -->
    <script src="../../js/html5shiv.js"></script>

    <script>

        /* Menu Slide JS  */

        $(document).ready(function(){
            $(".menu-btn").on('click',function(e){
                e.preventDefault();

                //Check this block is open or not..
                if(!$(this).prev().hasClass("open")) {
                    $(".ui-94").slideDown(400);
                    $(".ui-94").addClass("open");
                    $(this).find("i").removeClass().addClass("fa fa-chevron-up");
                }

                else if($(this).prev().hasClass("open")) {
                    $(".ui-94").removeClass("open");
                    $(".ui-94").slideUp(400);
                    $(this).find("i").removeClass().addClass("fa fa-chevron-down");
                }
            });
        });

    </script>
<?php endif; ?>