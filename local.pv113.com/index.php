<?php global $dbh; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Сенонд хенд</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
<div class="container py-3">
    <?php include_once $_SERVER["DOCUMENT_ROOT"]."/_header.php"; ?>
    <h1 class="text-center">Актуальні новини</h1>

    <?php include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php"; ?>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Назва</th>
            <th scope="col">Фото</th>
            <th scope="col">Дата</th>
            <th scope="col">Опис</th>
            <th scope="col">Дія</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $stm = $dbh->query('SELECT * FROM news');
        $rows = $stm->fetchAll();
        foreach($rows as $row) {
            $id = $row["id"];
            $name = $row["name"];
            $image = $row["image"];
            $datepublish = $row["datepublish"];
            $description = $row["description"];
            echo "
                <tr>
                    <th scope='row'>$id</th>
                    <td>$name</td>
                    <td>
                       <img src='/images/$image' alt='' width='100'>
                    </td>
                    <td>" . date('Y-m-d H:i', strtotime($datepublish)) . "</td>
                    <td>$description</td>
                    <td>
                        <a href='edit.php?id=$id' class='btn btn-primary'>Редагувати</a>
                        <form method='post' action='delete.php'>
                            <input type='hidden' name='id' value='$id'>
                            <button type='submit' class='btn btn-danger'>Видалити</button>
                        </form>
                    </td>
                </tr>
            ";
        }
        ?>

        </tbody>
    </table>
    <button type="button" class="btn btn-primary" onclick="location.href='/create.php'">Додати новину</button>
</div>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>