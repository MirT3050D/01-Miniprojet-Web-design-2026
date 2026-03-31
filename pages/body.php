<?php
require_once '../inc/util.php';
$article = getArticleBySlug($_GET['id']);
$articleId = $article['id'] ?? 0;
$otherArticles = getOtherArticles($articleId, 3);

function get_image_dimensions($image_url) {
    if (!$image_url) {
        return null;
    }

    $root_dir = dirname(__DIR__);
    $image_path = $root_dir . $image_url;
    if (!file_exists($image_path)) {
        return null;
    }

    $size = @getimagesize($image_path);
    if (!$size) {
        return null;
    }

    return ['width' => $size[0], 'height' => $size[1]];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $article['titre_h1']; ?> - Infos Iran</title>
    <meta name="description" content="<?php echo $article['meta_description']; ?>">
    <link rel="stylesheet" href="../../assets/css/article.css">
</head>
<body>
<header class="site-chrome">
    <div class="site-chrome-inner">
        <a class="site-brand" href="../../pages/index.php" aria-label="Retour a l'accueil">
            <span class="brand-mark logo-newsroom" aria-hidden="true">SD</span>
            <span class="site-brand-text">Iran Situation Desk</span>
        </a>
        <p class="site-rubric"><span>Rubrique</span> Situation Iran</p>
        <form class="chrome-search" method="get" action="../../pages/search_results.php">
            <label for="article-search" class="sr-only">Rechercher</label>
            <input id="article-search" type="search" name="q" placeholder="Rechercher..." required>
            <button type="submit">OK</button>
        </form>
        <a class="site-home-link" href="../../pages/index.php">Accueil</a>
    </div>
</header>
<main class="article-layout">
    <article>
        <h1><?php echo $article['titre_h1']; ?></h1>
        
        <figure>
            <?php
                $hero_url = $article['image_url'] ?? '';
                $hero_dims = get_image_dimensions($hero_url);
            ?>
            <img
                src="../../<?php echo $hero_url; ?>"
                alt="<?php echo $article['image_alt']; ?>"
                <?php if ($hero_dims) { ?>width="<?php echo $hero_dims['width']; ?>" height="<?php echo $hero_dims['height']; ?>"<?php } ?>
                fetchpriority="high"
                decoding="async"
                style="max-width:100%;">
        </figure>

        <div class="article-content">
            <?php 
                // On affiche le contenu HTML brut stocké en base
                // Le navigateur interprétera les <h2>, <p>, <strong>, etc.
                echo $article['contenu_html']; 
            ?>
        </div>
        
        <footer>
            <p>Publié le : <?php echo $article['date_creation']; ?></p>
        </footer>
    </article>

    <section class="article-related">
        <h2>Autres articles</h2>
        <?php if (empty($otherArticles)) { ?>
            <p>Aucun autre article pour le moment.</p>
        <?php } else { ?>
            <div class="related-grid">
                <?php foreach ($otherArticles as $related) { ?>
                    <a class="related-card" href="../../Iran/article/<?php echo htmlspecialchars($related['url_slug'] ?? ''); ?>.html">
                        <?php
                            $related_url = $related['image_url'] ?? '';
                            $related_dims = get_image_dimensions($related_url);
                        ?>
                        <img
                            src="../../<?php echo htmlspecialchars($related_url); ?>"
                            alt="<?php echo htmlspecialchars($related['image_alt'] ?? ''); ?>"
                            <?php if ($related_dims) { ?>width="<?php echo $related_dims['width']; ?>" height="<?php echo $related_dims['height']; ?>"<?php } ?>
                            loading="lazy"
                            decoding="async">
                        <span><?php echo htmlspecialchars($related['titre_h1'] ?? ''); ?></span>
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
</main>
</body>
</html>