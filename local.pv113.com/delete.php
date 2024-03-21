<?php global $dbh; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php";

    $id = $_POST["id"];

    $stmt = $dbh->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetchColumn();

    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$id]);

    if ($image) {
        $image_path = $_SERVER['DOCUMENT_ROOT'] . '/images/' . $image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    header("Location: /");
    exit();
} else {
    echo "Помилка: Не вдалося видалити новину.";
}
?>