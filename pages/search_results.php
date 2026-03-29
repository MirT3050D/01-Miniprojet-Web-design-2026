<?php
require_once '../inc/util.php';

$query = trim($_GET['q'] ?? '');
$sort = strtolower(trim($_GET['sort'] ?? 'date_desc'));
$period = strtolower(trim($_GET['period'] ?? 'all'));
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo = trim($_GET['date_to'] ?? '');
$page = (int) ($_GET['page'] ?? 1);

$searchResult = searchArticles($query, $sort, $dateFrom, $dateTo, $period, $page, 6);
$articles = $searchResult['items'];
$totalResults = $searchResult['total'];
$currentPage = $searchResult['page'];
$totalPages = $searchResult['total_pages'];
$sort = $searchResult['sort'];
$resolvedDateFrom = $searchResult['resolved_date_from'];
$resolvedDateTo = $searchResult['resolved_date_to'];

$queryParams = [
    'q' => $query,
    'sort' => $sort,
    'period' => $period,
    'date_from' => $dateFrom,
    'date_to' => $dateTo
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultats de recherche - Iran Situation Desk</title>
    <meta name="description" content="Resultats de recherche avec tri et filtre par date.">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
<main>
    <header>
        <div class="brand front-brand">
            <span class="brand-mark logo-newsroom" aria-hidden="true">SD</span>
            <p class="brand-kicker">Signal Desk</p>
        </div>
        <h1>Resultats de recherche</h1>
        <p>Affinez vos resultats par mot-cle et par date.</p>

        <form class="search-form search-form-results" method="get" action="search_results.php">
            <label for="results-search" class="search-label">Mot-cle</label>
            <div class="search-row">
                <input id="results-search" type="search" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Mot-cle, sujet, lieu...">
                <button type="submit">Rechercher</button>
            </div>

            <div class="filter-row">
                <div class="filter-group">
                    <label for="sort">Tri</label>
                    <select id="sort" name="sort">
                        <option value="date_desc" <?php echo $sort === 'date_desc' ? 'selected' : ''; ?>>Date - Plus recent</option>
                        <option value="date_asc" <?php echo $sort === 'date_asc' ? 'selected' : ''; ?>>Date - Plus ancien</option>
                        <option value="title_asc" <?php echo $sort === 'title_asc' ? 'selected' : ''; ?>>Titre - A a Z</option>
                        <option value="title_desc" <?php echo $sort === 'title_desc' ? 'selected' : ''; ?>>Titre - Z a A</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="period">Periode</label>
                    <select id="period" name="period">
                        <option value="all" <?php echo $period === 'all' ? 'selected' : ''; ?>>Toutes dates</option>
                        <option value="last_7" <?php echo $period === 'last_7' ? 'selected' : ''; ?>>7 derniers jours</option>
                        <option value="last_30" <?php echo $period === 'last_30' ? 'selected' : ''; ?>>30 derniers jours</option>
                        <option value="last_365" <?php echo $period === 'last_365' ? 'selected' : ''; ?>>12 derniers mois</option>
                        <option value="custom" <?php echo $period === 'custom' ? 'selected' : ''; ?>>Intervalle personnalise</option>
                    </select>
                </div>

                <div class="filter-group filter-dates">
                    <div class="date-input">
                        <label for="date_from">Du</label>
                        <input id="date_from" type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>">
                    </div>
                    <div class="date-input">
                        <label for="date_to">Au</label>
                        <input id="date_to" type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>">
                    </div>
                </div>

                <button type="submit" class="button-secondary">Appliquer</button>
            </div>
        </form>

        <p><a href="index.php">Retour a l'accueil</a></p>
    </header>

    <section id="articles">
        <h2><?php echo $totalResults; ?> resultat(s)</h2>

        <?php if ($resolvedDateFrom !== '' || $resolvedDateTo !== '') { ?>
            <p class="filter-note">
                Periode appliquee :
                <?php echo $resolvedDateFrom !== '' ? htmlspecialchars(date('d/m/Y', strtotime($resolvedDateFrom))) : '...'; ?>
                -
                <?php echo $resolvedDateTo !== '' ? htmlspecialchars(date('d/m/Y', strtotime($resolvedDateTo))) : '...'; ?>
            </p>
        <?php } ?>

        <?php if (empty($articles)) { ?>
            <p>Aucun article ne correspond a votre recherche.</p>
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

            <?php if ($totalPages > 1) { ?>
                <nav class="pagination" aria-label="Pagination des resultats">
                    <span>Page <?php echo $currentPage; ?> / <?php echo $totalPages; ?></span>
                    <div class="pagination-numbers">
                        <?php
                        for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) {
                            $pageParams = $queryParams;
                            $pageParams['page'] = $pageNumber;
                            $isCurrent = $pageNumber === $currentPage;
                        ?>
                            <a
                                href="search_results.php?<?php echo htmlspecialchars(http_build_query($pageParams)); ?>"
                                class="<?php echo $isCurrent ? 'is-active' : ''; ?>"
                                <?php echo $isCurrent ? 'aria-current="page"' : ''; ?>
                            >
                                <?php echo $pageNumber; ?>
                            </a>
                        <?php } ?>
                    </div>
                </nav>
            <?php } ?>
        <?php } ?>
    </section>
</main>
</body>
</html>
