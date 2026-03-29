<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("Location: login.php?error=2");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Backoffice - Iran Info</title>
	<meta name="description" content="Backoffice editor to publish Iran conflict updates.">
	<link rel="stylesheet" href="../assets/css/backoffice.css">
</head>
<body>
<main class="backoffice-page">
	<section class="editor-card">
		<div class="brand">
			<span class="brand-mark logo-newsroom">SD</span>
			<div class="brand-text">
				<h1>Iran Situation Desk</h1>
				<p>Backoffice editor</p>
			</div>
		</div>

		<div class="backoffice-toolbar">
			<a href="articles_list.php" class="btn btn-secondary">📋 Voir tous les articles</a>
		</div>

		<form class="editor-form" action="../inc/article_controller.php?action=create" method="post" enctype="multipart/form-data">
			<?php if (isset($_GET['upload_error'])) { ?>
				<p class="upload-error"><?php echo htmlspecialchars($_GET['upload_error']); ?></p>
			<?php } ?>
			<?php if (isset($_GET['error'])) { ?>
				<p class="upload-error">✗ Une erreur est survenue</p>
			<?php } ?>
			<div class="form-grid">
				<div class="field">
					<label for="title">Title (H1)</label>
					<input id="title" name="titre_h1" type="text" placeholder="Strategic update: coastal corridor" required>
				</div>

				<div class="field">
					<label for="slug">URL slug</label>
					<input id="slug" name="url_slug" type="text" placeholder="strategic-update-coastal-corridor" required>
					<p class="helper">Auto-generated from the title. You can edit it.</p>
				</div>

				<div class="field">
					<label for="image_file">Hero image</label>
					<input id="image_file" name="image_file" type="file" accept="image/*">
				</div>

				<div class="field">
					<label for="image_alt">Image alt text</label>
					<input id="image_alt" name="image_alt" type="text" placeholder="Satellite view of the harbor at dawn">
				</div>

				<div class="field full">
					<label for="meta_description">Meta description</label>
					<textarea id="meta_description" name="meta_description" rows="2" maxlength="160" placeholder="Short summary for search engines."></textarea>
					<p class="helper"><span id="meta_count">0</span>/160 characters</p>
				</div>
			</div>

			<div class="field full">
				<label for="content">Article content</label>
				<textarea id="content" name="contenu_html" rows="14"></textarea>
			</div>

			<div class="actions">
				<button type="submit">Créer l'article</button>
				<p class="note">Le contenu sera sauvegardé en HTML et affiché sur la page publique.</p>
			</div>
		</form>
	</section>

	<aside class="editor-panel">
		<div class="panel-inner">
			<h2>Publishing checklist</h2>
			<ul>
				<li>Verify the source and timestamp.</li>
				<li>Add a descriptive image alt text.</li>
				<li>Keep meta description under 160 chars.</li>
				<li>Use H2 headings for structure.</li>
			</ul>

			<div class="panel-alert">
				<span class="alert-tag">Priority</span>
				<p>All updates require review before public release.</p>
			</div>
		</div>
	</aside>
</main>

<script src="../assets/vendor/tinymce_8.3.2/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="../assets/js/backoffice.js"></script>
</body>
</html>
