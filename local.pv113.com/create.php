<?php global $dbh; ?>

<?php include_once ($_SERVER['DOCUMENT_ROOT']."/config/constants.php"); ?>
<?php include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php"; ?>

<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
    $name = $_POST['name'];
    $datepublish = $_POST['datepublish'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    $folderName = $_SERVER['DOCUMENT_ROOT'].'/'.UPLOADING;
    if (!file_exists($folderName)) {
        mkdir($folderName, 0777);
    }
    $image_save = "";
    if(isset($_FILES['image'])) {
        $image_save = uniqid().'.'.pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION);
        $path_save = $folderName.'/'.$image_save;
        move_uploaded_file($_FILES['image']['tmp_name'], $path_save);
    }

    $stmt = $dbh->prepare("INSERT INTO news (name, datepublish, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $datepublish, $description, $image_save]);
    $lastInsertedId = $dbh->lastInsertId();

    header("Location: /?id=".$lastInsertedId);
    exit();
}

?>

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

    <form method="post" action="" enctype="multipart/form-data">
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
            <label for="description" class="form-label">Опис</label>
            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="formFile" class="form-label">Фото</label>
            <input class="form-control" type="file" accept="image/*" id="image" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Створити</button>
    </form>
</div>

<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>
