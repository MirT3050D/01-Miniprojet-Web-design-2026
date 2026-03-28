<?php
require_once '../inc/Util.php';
$article = getArticleBySlug($_GET['id']);

?>
<title><?php echo $article['titre_h1']; ?> - Infos Iran</title>
<meta name="description" content="<?php echo $article['meta_description']; ?>">

<main>
    <article>
        <h1><?php echo $article['titre_h1']; ?></h1>
        
        <figure>
            <img src="<?php echo $article['image_url']; ?>" 
                 alt="<?php echo $article['image_alt']; ?>" 
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
</main>