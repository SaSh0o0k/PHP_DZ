<?php
global $dbh;
include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/constants.php";

$name_value = $datepublish_value = $description_value = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $datepublish = $_POST["datepublish"];
    $description = $_POST["description"];

    $name_value = $name;
    $datepublish_value = $datepublish;
    $description_value = $description;

    // Перевірка, чи назва новини унікальна
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM news WHERE name = ? AND id != ?");
    $stmt->execute([$name, $id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error_message = "Назва новини \"$name\" вже існує. Будь ласка, виберіть іншу назву.";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];

            // Видалення старого зображення
            $stmt = $dbh->prepare("SELECT image FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $old_image = $stmt->fetchColumn();

            if ($old_image) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . UPLOADING . '/' . $old_image);
            }

            // Завантаження нового зображення
            $image_save = uniqid() . '.' . pathinfo($image["name"], PATHINFO_EXTENSION);
            $path_save = $_SERVER['DOCUMENT_ROOT'] . '/' . UPLOADING . '/' . $image_save;
            move_uploaded_file($image['tmp_name'], $path_save);

            // Оновлення бази даних новим зображенням
            $sql = "UPDATE news SET name = ?, datepublish = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$name, $datepublish, $description, $image_save, $id]);
        } else {
            // Не завантажено нове зображення, оновити без зміни зображення
            $sql = "UPDATE news SET name = ?, datepublish = ?, description = ? WHERE id = ?";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$name, $datepublish, $description, $id]);
        }

        header("Location: /");
        exit();
    }
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Встановлення значень полів форми з бази даних
    $name_value = htmlspecialchars($row['name']);
    $datepublish_value = htmlspecialchars($row['datepublish']);
    $description_value = htmlspecialchars($row['description']);
} else {
    echo "Недопустимий запит.";
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Редагувати новину</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>
<body>
<div class="container py-3">
    <?php include_once $_SERVER["DOCUMENT_ROOT"]."/_header.php"; ?>
    <h2 class="mb-3">Редагувати новину</h2>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Назва новини</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?php echo htmlspecialchars($name_value); ?>" required>
        </div>
        <div class="mb-3">
            <label for="datepublish" class="form-label">Дата і час публікації</label>
            <input type="datetime-local" class="form-control" id="datepublish" name="datepublish"
                   value="<?php echo htmlspecialchars($datepublish_value); ?>" required
                   min="<?php echo date('Y-m-d\TH:i'); ?>">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Опис новини</label>
            <textarea class="form-control" id="description" name="description" rows="5"
                      required><?php echo htmlspecialchars($description_value); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">Змінити фото</label>
            <input class="form-control" type="file" accept="image/*" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
    </form>
</div>
</body>
</html>
