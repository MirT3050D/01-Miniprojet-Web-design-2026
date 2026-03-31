<?php
require_once '../inc/util.php';
$articles = getLatestArticles(3);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Iran Situation Desk</title>
	<meta name="description" content="Chronologie, analyses et briefings verifies sur la situation en Iran.">
	<link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
<main>
	<header>
		<div class="brand front-brand">
			<span class="brand-mark logo-newsroom" aria-hidden="true">SD</span>
			<p class="brand-kicker">Signal Desk</p>
		</div>
		<h1>Iran Situation Desk</h1>
		<p>Chronologie, analyses et briefings verifies sur la situation en Iran.</p>
		<form class="search-form" method="get" action="search_results.php">
			<label for="home-search" class="search-label">Rechercher un article</label>
			<div class="search-row">
				<input id="home-search" type="search" name="q" placeholder="Mot-cle, sujet, lieu..." required>
				<button type="submit">Rechercher</button>
			</div>
		</form>
		<p><a href="#articles">Voir les derniers articles</a></p>
	</header>

	<section id="articles">
		<h2>Dernieres publications</h2>
		<?php if (empty($articles)) { ?>
			<p>Aucun article pour le moment.</p>
		<?php } else { ?>
			<?php foreach ($articles as $article) { ?>
				<article>
					<h3>
						<a href="../../Iran/article/<?php echo htmlspecialchars($article['url_slug'] ?? ''); ?>.html">
							<?php echo htmlspecialchars($article['titre_h1'] ?? ''); ?>
						</a>
					</h3>
					<p>Date : <?php echo htmlspecialchars(date('d/m/Y', strtotime($article['date_creation'] ?? ''))); ?></p>
					<p><?php echo htmlspecialchars($article['meta_description'] ?? ''); ?></p>
				</article>
			<?php } ?>
		<?php } ?>
	</section>

	<section>
		<h2>Ce que tu trouveras ici</h2>
		<ul>
			<li>Chronologies structurees.</li>
			<li>Briefings synthetiques.</li>
			<li>Sources verifiees.</li>
		</ul>
	</section>

	<section>
		<h2>Confiance et methodologie</h2>
		<p>Donnees croisees avec sources OSINT et medias internationaux.</p>
	</section>

	<section>
		<h2>Acceder aux archives</h2>
		<p><a href="#">Voir les archives</a></p>
	</section>
</main>
</body>
</html>
