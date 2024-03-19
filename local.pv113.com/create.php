<?php global $dbh; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Додати новину</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
<div class="container py-3">
    <?php include_once $_SERVER["DOCUMENT_ROOT"]."/_header.php"; ?>

    <h1 class="text-center">Додати новину</h1>

    <form method="post" action="">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php";

            $name = $_POST["name"];
            $datepublish = $_POST["datepublish"];
            $description = $_POST["description"];

            $stmt = $dbh->prepare("INSERT INTO news (name, datepublish, description) VALUES (:name, :datepublish, :description)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':datepublish', $datepublish);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            header("Location: /");
            exit();
        }
        ?>
        <div class="mb-3">
            <label for="name" class="form-label">Назва новини</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="datepublish" class="form-label">Дата публікації</label>
            <input type="datetime-local" class="form-control" id="datepublish" name="datepublish" required
                   min="<?php echo date('Y-m-d\TH:i'); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Опис новини</label>
            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Створити</button>
    </form>
</div>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
