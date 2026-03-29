<?php
session_start();
if (!isset($_SESSION['user'])) {
	header('Location: ../pages/login.php?error=2');
	exit;
}

require_once 'connection.php';
require_once 'upload.php';

$action = $_GET['action'] ?? '';
$conn = getConnection();

// CREATE
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$titre_h1 = trim($_POST['titre_h1'] ?? '');
	$url_slug = trim($_POST['url_slug'] ?? '');
	$contenu_html = $_POST['contenu_html'] ?? '';
	$image_alt = trim($_POST['image_alt'] ?? '');
	$meta_description = trim($_POST['meta_description'] ?? '');
	$image_url = '';

	if ($titre_h1 === '' || $url_slug === '') {
		header('Location: ../pages/index_back_office.php?error=empty_fields');
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

	try {
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

		header('Location: ../pages/articles_list.php?msg=created');
		exit;
	} catch (PDOException $e) {
		header('Location: ../pages/index_back_office.php?error=slug_exists');
		exit;
	}
}

// UPDATE
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$article_id = intval($_POST['article_id'] ?? 0);
	$titre_h1 = trim($_POST['titre_h1'] ?? '');
	$url_slug = trim($_POST['url_slug'] ?? '');
	$contenu_html = $_POST['contenu_html'] ?? '';
	$image_alt = trim($_POST['image_alt'] ?? '');
	$meta_description = trim($_POST['meta_description'] ?? '');

	if ($article_id === 0 || $titre_h1 === '' || $url_slug === '') {
		header('Location: ../pages/articles_list.php?error=invalid');
		exit;
	}

	// Get current article to preserve image if not changed
	$stmt = $conn->prepare('SELECT image_url FROM articles WHERE id = :id');
	$stmt->bindParam(':id', $article_id);
	$stmt->execute();
	$article = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!$article) {
		header('Location: ../pages/articles_list.php?error=not_found');
		exit;
	}

	$image_url = $article['image_url'];

	// Handle new image upload
	if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
		$upload_dir = dirname(__DIR__) . '/assets/img/uploads/';
		$upload_error = upload('image_file', $upload_dir);

		if ($upload_error === '') {
			$image_url = '/assets/img/uploads/' . basename($_FILES['image_file']['name']);
		} else {
			header('Location: ../pages/edit_article.php?id=' . $article_id . '&upload_error=' . urlencode($upload_error));
			exit;
		}
	}

	try {
		$stmt = $conn->prepare(
			'UPDATE articles SET titre_h1 = :titre_h1, url_slug = :url_slug, contenu_html = :contenu_html, '
			. 'image_url = :image_url, image_alt = :image_alt, meta_description = :meta_description '
			. 'WHERE id = :id'
		);
		$stmt->bindParam(':id', $article_id);
		$stmt->bindParam(':titre_h1', $titre_h1);
		$stmt->bindParam(':url_slug', $url_slug);
		$stmt->bindParam(':contenu_html', $contenu_html);
		$stmt->bindParam(':image_url', $image_url);
		$stmt->bindParam(':image_alt', $image_alt);
		$stmt->bindParam(':meta_description', $meta_description);
		$stmt->execute();

		header('Location: ../pages/articles_list.php?msg=updated');
		exit;
	} catch (PDOException $e) {
		header('Location: ../pages/edit_article.php?id=' . $article_id . '&error=slug_exists');
		exit;
	}
}

// DELETE
if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$article_id = intval($_POST['article_id'] ?? 0);

	if ($article_id === 0) {
		header('Location: ../pages/articles_list.php?error=invalid');
		exit;
	}

	try {
		$stmt = $conn->prepare('DELETE FROM articles WHERE id = :id');
		$stmt->bindParam(':id', $article_id);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			header('Location: ../pages/articles_list.php?msg=deleted');
		} else {
			header('Location: ../pages/articles_list.php?error=not_found');
		}
		exit;
	} catch (PDOException $e) {
		header('Location: ../pages/articles_list.php?error=delete_failed');
		exit;
	}
}

// If no valid action, redirect
header('Location: ../pages/articles_list.php');
exit;
