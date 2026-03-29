<?php
session_start();
if (!isset($_SESSION['user'])) {
	header('Location: ../pages/login.php?error=2');
	exit;
}

require_once 'connection.php';
require_once 'upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: ../pages/index_back_office.php');
	exit;
}

$titre_h1 = trim($_POST['titre_h1'] ?? '');
$url_slug = trim($_POST['url_slug'] ?? '');
$contenu_html = $_POST['contenu_html'] ?? '';
$image_alt = trim($_POST['image_alt'] ?? '');
$meta_description = trim($_POST['meta_description'] ?? '');
$image_url = '';

if ($titre_h1 === '' || $url_slug === '') {
	header('Location: ../pages/index_back_office.php');
	exit;
}

if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
	$upload_dir = dirname(__DIR__) . '/assets/img/uploads/';
	$upload_error = upload('image_file', $upload_dir);

	if ($upload_error === '') {
		$image_url = '/assets/img/uploads/' . basename($_FILES['image_file']['name']);
	} else {
		header('Location: ../pages/index_back_office.php?upload_error=' . urlencode($upload_error));
		exit;
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

header('Location: ../pages/index_back_office.php?msg=0');
exit;
    