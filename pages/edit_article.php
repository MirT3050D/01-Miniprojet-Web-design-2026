<?php
session_start();
if (!isset($_SESSION['user'])) {
	header("Location: login.php?error=2");
	exit;
}

require_once '../inc/connection.php';

$article_id = intval($_GET['id'] ?? 0);
if ($article_id === 0) {
	header("Location: articles_list.php?error=invalid");
	exit;
}

$conn = getConnection();
$stmt = $conn->prepare('SELECT * FROM articles WHERE id = :id');
$stmt->bindParam(':id', $article_id);
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
	header("Location: articles_list.php?error=not_found");
	exit;
}

$upload_error = $_GET['upload_error'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Éditer article - Iran Info</title>
	<meta name="description" content="Edit article for Iran conflict updates.">
	<link rel="stylesheet" href="../assets/css/backoffice.css">
</head>
<body>
<main class="backoffice-page">
	<section class="editor-card">
		<div class="brand">
			<span class="brand-mark logo-newsroom">SD</span>
			<div class="brand-text">
				<h1>Iran Situation Desk</h1>
				<p>Éditer article</p>
			</div>
		</div>

		<?php if ($upload_error) { ?>
			<p class="upload-error">✗ <?php echo htmlspecialchars($upload_error); ?></p>
		<?php } ?>
		<?php if ($error === 'slug_exists') { ?>
			<p class="upload-error">✗ Cet URL slug existe déjà</p>
		<?php } ?>

		<form class="editor-form" action="../inc/article_controller.php?action=update" method="post" enctype="multipart/form-data">
			<input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">

			<div class="form-grid">
				<div class="field">
					<label for="title">Title (H1)</label>
					<input id="title" name="titre_h1" type="text" placeholder="Strategic update: coastal corridor" value="<?php echo htmlspecialchars($article['titre_h1']); ?>" required>
				</div>

				<div class="field">
					<label for="slug">URL slug</label>
					<input id="slug" name="url_slug" type="text" placeholder="strategic-update-coastal-corridor" value="<?php echo htmlspecialchars($article['url_slug']); ?>" required>
					<p class="helper">Auto-generated from title, but you can edit it.</p>
				</div>

				<div class="field">
					<label for="image_file">Remplacer l'image</label>
					<input id="image_file" name="image_file" type="file" accept="image/*">
					<p class="helper">Laissez vide pour garder l'image actuelle</p>
				</div>

				<?php if ($article['image_url']) { ?>
					<div class="field">
						<label>Image actuelle</label>
						<p style="margin: 10px 0; font-size: 0.9em; color: var(--ink-soft);">
							<a href="<?php echo htmlspecialchars($article['image_url']); ?>" target="_blank">
								<?php echo htmlspecialchars($article['image_url']); ?>
							</a>
						</p>
					</div>
				<?php } ?>

				<div class="field">
					<label for="image_alt">Image alt text</label>
					<input id="image_alt" name="image_alt" type="text" placeholder="Satellite view of the harbor at dawn" value="<?php echo htmlspecialchars($article['image_alt']); ?>">
				</div>

				<div class="field full">
					<label for="meta_description">Meta description</label>
					<textarea id="meta_description" name="meta_description" rows="2" maxlength="160" placeholder="Short summary for search engines."><?php echo htmlspecialchars($article['meta_description']); ?></textarea>
					<p class="helper"><span id="meta_count">0</span>/160 characters</p>
				</div>
			</div>

			<div class="field full">
				<label for="content">Article content</label>
				<textarea id="content" name="contenu_html" rows="14"><?php echo $article['contenu_html']; ?></textarea>
			</div>

			<div class="actions">
				<button type="submit">Mettre à jour l'article</button>
				<a href="articles_list.php" class="btn btn-secondary">Annuler</a>
				<p class="note">Les modifications seront mises à jour immédiatement.</p>
			</div>
		</form>
	</section>

	<aside class="editor-panel">
		<div class="panel-inner">
			<h2>Infos article</h2>
			<ul>
				<li><strong>ID:</strong> <?php echo $article['id']; ?></li>
				<li><strong>Créé:</strong> <?php echo date('d/m/Y H:i', strtotime($article['date_creation'])); ?></li>
			</ul>

			<div class="panel-alert">
				<span class="alert-tag">Important</span>
				<p>Vérifiez que l'URL slug reste unique.</p>
			</div>
		</div>
	</aside>
</main>

<script src="../assets/vendor/tinymce_8.3.2/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="../assets/js/backoffice.js"></script>
</body>
</html>
