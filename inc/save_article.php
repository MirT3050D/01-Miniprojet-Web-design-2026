<?php
require_once 'Connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/index_back_office.php');
    exit;
}

$titre_h1 = trim($_POST['titre_h1'] ?? '');
$url_slug = trim($_POST['url_slug'] ?? '');
$contenu_html = $_POST['contenu_html'] ?? '';
$image_url = '';
$image_alt = trim($_POST['image_alt'] ?? '');
$meta_description = trim($_POST['meta_description'] ?? '');

if ($titre_h1 === '' || $url_slug === '') {
    header('Location: ../pages/index_back_office.php');
    exit;
}

if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $tmp_path = $_FILES['image_file']['tmp_name'];
    $image_info = getimagesize($tmp_path);

    if ($image_info !== false) {
        $extension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $safe_extension = preg_match('/^(jpg|jpeg|png|gif|webp)$/', $extension) ? $extension : 'jpg';
        $file_name = uniqid('hero_', true) . '.' . $safe_extension;
        $upload_dir = dirname(__DIR__) . '/assets/img/uploads/';
        $destination = $upload_dir . $file_name;

        if (move_uploaded_file($tmp_path, $destination)) {
            $image_url = '/assets/img/uploads/' . $file_name;
        }
    }
}

$conn = getConnection();
$stmt = $conn->prepare(
    'INSERT INTO articles (titre_h1, url_slug, contenu_html, image_url, image_alt, meta_description) '
    . 'VALUES (:titre_h1, :url_slug, :contenu_html, :image_url, :image_alt, :meta_description)'
);
$stmt->bindParam(':titre_h1', $titre_h1);
$stmt->bindParam(':url_slug', $url_slug);
$stmt->bindParam(':contenu_html', $contenu_html);
$stmt->bindParam(':image_url', $image_url);
$stmt->bindParam(':image_alt', $image_alt);
$stmt->bindParam(':meta_description', $meta_description);
$stmt->execute();

header('Location: ../pages/body.php?id=' . urlencode($url_slug));
exit;
