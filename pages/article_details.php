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

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Détails article - Iran Info</title>
	<meta name="description" content="View article details.">
	<link rel="stylesheet" href="../assets/css/backoffice.css">
</head>
<body>
<main class="backoffice-page article-details-page">
	<section class="article-details-section">
		<!-- Back link -->
		<a href="articles_list.php" class="back-link">← Retour à la liste</a>

		<!-- Article display like body.php -->
		<article class="article-display">
			<h1><?php echo htmlspecialchars($article['titre_h1']); ?></h1>
			
			<?php if ($article['image_url']) { ?>
				<figure>
					<img src="../../<?php echo htmlspecialchars($article['image_url']); ?>" 
						 alt="<?php echo htmlspecialchars($article['image_alt']); ?>"
						 width="1200"
						 height="675"
						 style="max-width:100%;">
				</figure>
			<?php } ?>

			<div class="article-content">
				<?php echo $article['contenu_html']; ?>
			</div>
			
			<footer class="article-footer">
				<p>Publié le : <?php echo date('d/m/Y à H:i', strtotime($article['date_creation'])); ?></p>
			</footer>
		</article>

		<!-- Action buttons -->
		<div class="details-actions-footer">
			<a href="edit_article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">✎ Éditer cet article</a>
			<form method="post" action="../inc/article_controller.php?action=delete" style="display: inline;">
				<input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
				<button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous absolument sûr de vouloir supprimer cet article ? Cette action est irréversible.');">✕ Supprimer définitivement</button>
			</form>
		</div>
	</section>

	<!-- Right panel -->
	<aside class="editor-panel details-panel">
		<div class="panel-inner">
			<h2>Infos article</h2>
			<ul class="details-metadata">
				<li>
					<strong>ID</strong>
					<span><?php echo $article['id']; ?></span>
				</li>
				<li>
					<strong>Créé</strong>
					<span><?php echo date('d/m/Y à H:i', strtotime($article['date_creation'])); ?></span>
				</li>
				<li>
					<strong>Slug</strong>
					<span><code><?php echo htmlspecialchars($article['url_slug']); ?></code></span>
				</li>
				<li>
					<strong>Image</strong>
					<span><?php echo $article['image_url'] ? '✓ Présente' : '✗ Absente'; ?></span>
				</li>
				<li>
					<strong>Contenu</strong>
					<span><?php echo strlen($article['contenu_html']); ?> caractères</span>
				</li>
			</ul>

			<hr style="margin: 24px 0; border: none; border-top: 1px solid rgba(255,255,255,0.1);">

			<h3>Actions rapides</h3>
			<ul class="quick-actions">
				<li><a href="edit_article.php?id=<?php echo $article['id']; ?>">✎ Éditer</a></li>
				<li><a href="articles_list.php">📋 Liste complète</a></li>
				<li><a href="index_back_office.php">➕ Nouvel article</a></li>
			</ul>

			<div class="panel-alert">
				<span class="alert-tag">Conseil</span>
				<p>Consultez tous les détails avant de modifier ou supprimer cet article.</p>
			</div>
		</div>
	</aside>
</main>

<style>
	.article-details-section {
		background: var(--paper);
		border: 1px solid var(--line);
		border-radius: 24px;
		padding: 34px 32px 40px;
		box-shadow: 0 20px 50px rgba(17, 19, 21, 0.12);
		position: relative;
		overflow: hidden;
		animation: rise 600ms ease-out;
	}

	.back-link {
		display: inline-block;
		margin-bottom: 20px;
		color: var(--rust);
		text-decoration: none;
		font-weight: 500;
		font-size: 14px;
		transition: color 200ms ease;
	}

	.back-link:hover {
		color: #9f341e;
		text-decoration: underline;
	}

	/* Article display styling (from body.php) */
	.article-display {
		margin-bottom: 32px;
	}

	.article-display h1 {
		font-family: var(--font-display);
		font-size: 32px;
		margin: 0 0 24px;
		line-height: 1.3;
		color: var(--ink);
	}

	.article-display figure {
		margin: 0 0 32px;
		padding: 0;
		border-radius: 16px;
		overflow: hidden;
		background: #f0ede8;
		border: 1px solid var(--line);
	}

	.article-display figure img {
		width: 100%;
		height: auto;
		display: block;
	}

	.article-content {
		background: #fff;
		border: 1px solid var(--line);
		border-radius: 12px;
		padding: 28px;
		margin-bottom: 24px;
		line-height: 1.8;
		color: var(--ink);
	}

	.article-content h2,
	.article-content h3 {
		font-family: var(--font-display);
		margin-top: 28px;
		margin-bottom: 14px;
		color: var(--ink);
	}

	.article-content h2:first-child,
	.article-content h3:first-child {
		margin-top: 0;
	}

	.article-content p {
		margin: 14px 0;
	}

	.article-content ul,
	.article-content ol {
		margin: 14px 0;
		padding-left: 24px;
	}

	.article-content li {
		margin: 6px 0;
	}

	.article-content strong {
		font-weight: 600;
	}

	.article-content em {
		font-style: italic;
	}

	.article-footer {
		padding: 16px 0;
		border-top: 1px solid var(--line);
		font-size: 13px;
		color: var(--ink-soft);
	}

	.article-footer p {
		margin: 8px 0;
	}

	.details-actions-footer {
		display: flex;
		gap: 12px;
		flex-wrap: wrap;
		padding-top: 20px;
		border-top: 1px solid var(--line);
	}

	.details-panel {
		height: fit-content;
		position: sticky;
		top: 50px;
	}

	.details-metadata {
		list-style: none;
		margin: 0;
		padding: 0;
		display: grid;
		gap: 12px;
	}

	.details-metadata li {
		padding: 12px;
		background: rgba(255, 255, 255, 0.06);
		border-radius: 10px;
		border: 1px solid rgba(255, 255, 255, 0.08);
		font-size: 13px;
	}

	.details-metadata li strong {
		display: block;
		color: rgba(196, 72, 43, 0.9);
		margin-bottom: 4px;
		font-size: 11px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.details-metadata li span {
		color: rgba(255, 255, 255, 0.85);
		word-break: break-word;
	}

	.details-metadata li code {
		background: rgba(0, 0, 0, 0.3);
		padding: 2px 6px;
		border-radius: 4px;
		font-size: 11px;
	}

	.quick-actions {
		list-style: none;
		margin: 0;
		padding: 0;
		display: grid;
		gap: 8px;
	}

	.quick-actions a {
		display: block;
		padding: 10px 12px;
		background: rgba(196, 72, 43, 0.15);
		border: 1px solid rgba(196, 72, 43, 0.3);
		border-radius: 8px;
		color: rgba(196, 72, 43, 0.9);
		text-decoration: none;
		font-size: 13px;
		text-align: center;
		transition: all 200ms ease;
	}

	.quick-actions a:hover {
		background: rgba(196, 72, 43, 0.25);
		color: #f8d6cf;
	}

	@media (max-width: 900px) {
		.details-panel {
			position: static;
		}
	}
</style>
</body>
</html>
