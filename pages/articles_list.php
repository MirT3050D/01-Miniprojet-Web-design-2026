<?php
session_start();
if (!isset($_SESSION['user'])) {
	header("Location: login.php?error=2");
	exit;
}

require_once '../inc/connection.php';

$conn = getConnection();
$stmt = $conn->prepare('SELECT id, titre_h1, url_slug, image_url, date_creation FROM articles ORDER BY date_creation DESC');
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestion des articles - Iran Info</title>
	<meta name="description" content="Manage articles for Iran conflict updates.">
	<link rel="stylesheet" href="../assets/css/backoffice.css">
</head>
<body>
<main class="backoffice-page articles-list-page">
	<section class="articles-section">
		<div class="brand">
			<span class="brand-mark logo-newsroom">SD</span>
			<div class="brand-text">
				<h1>Iran Situation Desk</h1>
				<p>Gestion des articles</p>
			</div>
		</div>

		<?php if ($msg === 'created') { ?>
			<div class="alert alert-success">✓ Article créé avec succès</div>
		<?php } ?>
		<?php if ($msg === 'updated') { ?>
			<div class="alert alert-success">✓ Article mis à jour avec succès</div>
		<?php } ?>
		<?php if ($msg === 'deleted') { ?>
			<div class="alert alert-success">✓ Article supprimé avec succès</div>
		<?php } ?>
		<?php if ($error === 'not_found') { ?>
			<div class="alert alert-error">✗ Article introuvable</div>
		<?php } ?>
		<?php if ($error === 'invalid') { ?>
			<div class="alert alert-error">✗ Données invalides</div>
		<?php } ?>

		<div class="articles-toolbar">
			<a href="index_back_office.php" class="btn btn-primary">+ Nouvel article</a>
		</div>

		<?php if (count($articles) > 0) { ?>
			<div class="articles-table-wrapper">
				<table class="articles-table">
					<thead>
						<tr>
							<th>Titre</th>
							<th>URL slug</th>
							<th>Image</th>
							<th>Date création</th>
							<th class="actions-col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($articles as $article) { ?>
							<tr>
								<td class="title-cell">
									<a href="article_details.php?id=<?php echo $article['id']; ?>" class="title-link">
										<strong><?php echo htmlspecialchars($article['titre_h1']); ?></strong>
									</a>
								</td>
								<td class="slug-cell">
									<code><?php echo htmlspecialchars($article['url_slug']); ?></code>
								</td>
								<td class="image-cell">
									<?php if ($article['image_url']) { ?>
										<a href="../../<?php echo htmlspecialchars($article['image_url']); ?>" target="_blank" class="image-link">
											↗ Voir image
										</a>
									<?php } else { ?>
										<span class="no-image">—</span>
									<?php } ?>
								</td>
								<td class="date-cell">
									<?php echo date('d/m/Y H:i', strtotime($article['date_creation'])); ?>
								</td>
								<td class="actions-cell">
									<a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-secondary">✎ Éditer</a>
									<form method="post" action="../inc/article_controller.php?action=delete" style="display: inline;">
										<input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
										<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">✕ Supprimer</button>
									</form>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else { ?>
			<div class="empty-state">
				<p>Aucun article créé pour le moment.</p>
				<a href="index_back_office.php" class="btn btn-primary">Créer le premier article</a>
			</div>
		<?php } ?>
	</section>

	<aside class="editor-panel">
		<div class="panel-inner">
			<h2>Total des articles</h2>
			<div class="stat-number"><?php echo count($articles); ?></div>
			
			<hr style="margin: 24px 0; border: none; border-top: 1px solid var(--line);">
			
			<h3>Navigation</h3>
			<ul>
				<li><a href="index_back_office.php">➕ Créer</a></li>
				<li><a href="articles_list.php" class="active">📋 Articles</a></li>
			</ul>
		</div>
	</aside>
</main>

<script>
	// Confirmation before delete
	document.querySelectorAll('form[action*="delete"] button[type="submit"]').forEach(btn => {
		btn.addEventListener('click', (e) => {
			if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
				e.preventDefault();
			}
		});
	});
</script>
</body>
</html>
