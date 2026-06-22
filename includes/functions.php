<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function uploadImage($file, $targetDir) {
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $newFileName = uniqid() . '.' . $imageFileType;
    $targetPath = $targetDir . $newFileName;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        return $newFileName;
    }
    return false;
}
?>
