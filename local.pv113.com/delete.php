<?php
global $dbh;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    include_once $_SERVER["DOCUMENT_ROOT"]."/connection_database.php";

    $id = $_POST["id"];

    $sql = "DELETE FROM news WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header("Location: /");
    exit();
} else {
    echo "Помилка: Не вдалося видалити новину.";
}
?>