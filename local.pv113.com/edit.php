<?php
global $dbh;
include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $datepublish = $_POST["datepublish"];
    $description = $_POST["description"];

    $sql = "UPDATE news SET name = :name, datepublish = :datepublish, description = :description WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':datepublish', $datepublish);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header("Location: /");
    exit();
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $dbh->prepare("SELECT * FROM news WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <form method="post" action="">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Назва новини</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="datepublish" class="form-label">Дата і час публікації</label>
        <input type="datetime-local" class="form-control" id="datepublish" name="datepublish" value="<?php echo date('Y-m-d\TH:i', strtotime($row['datepublish'])); ?>" required
               min="<?php echo date('Y-m-d\TH:i'); ?>">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Опис новини</label>
        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($row['description']); ?></textarea>
    </div>
        <button type="submit" class="btn btn-primary">Зберегти зміни</button>
    </form>
</div>
</body>
</html>
