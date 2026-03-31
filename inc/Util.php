<?php
include_once 'Connection.php';

const ARTICLE_STATUS_PUBLISHED = 1;
const ARTICLE_STATUS_DELETED = 2;

function getArticleBySlug($url_slug)
{
    $conn = getConnection();
    $sql = "SELECT * FROM articles WHERE url_slug = :url_slug AND article_status_id <> :deleted_status";
    $article_stmt = $conn->prepare($sql);
    $article_stmt->execute([
        'url_slug' => $url_slug,
        'deleted_status' => ARTICLE_STATUS_DELETED
    ]);
    $res = $article_stmt->fetch();
    return $res;
}

function getLatestArticles($limit = 3)
{
    $conn = getConnection();
    $sql = "SELECT id, url_slug, titre_h1, meta_description, date_creation FROM articles WHERE article_status_id <> :deleted_status ORDER BY date_creation DESC LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':deleted_status', ARTICLE_STATUS_DELETED, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getOtherArticles($currentId, $limit = 3)
{
    $conn = getConnection();
    $sql = "SELECT id, url_slug, titre_h1, image_url, image_alt FROM articles WHERE id <> :currentId AND article_status_id <> :deleted_status ORDER BY date_creation DESC LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':currentId', (int) $currentId, PDO::PARAM_INT);
    $stmt->bindValue(':deleted_status', ARTICLE_STATUS_DELETED, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function normalizeDateValue($value)
{
    if ($value === '') {
        return '';
    }

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return $value;
    }

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
        $parsed = DateTimeImmutable::createFromFormat('d/m/Y', $value);
        return $parsed ? $parsed->format('Y-m-d') : '';
    }

    return '';
}

function resolveDateRangeFromPeriod($period, $dateFrom, $dateTo)
{
    $today = new DateTimeImmutable('today');
    $allowedPeriods = ['all', 'last_7', 'last_30', 'last_365', 'custom'];
    $selectedPeriod = in_array($period, $allowedPeriods, true) ? $period : 'all';
    $normalizedFrom = normalizeDateValue($dateFrom);
    $normalizedTo = normalizeDateValue($dateTo);

    if ($selectedPeriod === 'last_7') {
        return [
            $today->modify('-6 days')->format('Y-m-d'),
            $today->format('Y-m-d')
        ];
    }

    if ($selectedPeriod === 'last_30') {
        return [
            $today->modify('-29 days')->format('Y-m-d'),
            $today->format('Y-m-d')
        ];
    }

    if ($selectedPeriod === 'last_365') {
        return [
            $today->modify('-364 days')->format('Y-m-d'),
            $today->format('Y-m-d')
        ];
    }

    if ($selectedPeriod === 'custom') {
        return [$normalizedFrom, $normalizedTo];
    }

    if ($selectedPeriod === 'all' && ($normalizedFrom !== '' || $normalizedTo !== '')) {
        return [$normalizedFrom, $normalizedTo];
    }

    return ['', ''];
}

function searchArticles($searchTerm = '', $sort = 'date_desc', $dateFrom = '', $dateTo = '', $period = 'all', $page = 1, $perPage = 6)
{
    $conn = getConnection();

    $allowedSorts = [
        'date_desc' => 'date_creation DESC',
        'date_asc' => 'date_creation ASC',
        'title_asc' => 'titre_h1 ASC',
        'title_desc' => 'titre_h1 DESC'
    ];
    $sortKey = array_key_exists($sort, $allowedSorts) ? $sort : 'date_desc';
    $orderBy = $allowedSorts[$sortKey];

    $safePage = max(1, (int) $page);
    $safePerPage = max(1, min(24, (int) $perPage));
    $offset = ($safePage - 1) * $safePerPage;

    $normalizedQuery = trim($searchTerm);
    [$resolvedDateFrom, $resolvedDateTo] = resolveDateRangeFromPeriod($period, $dateFrom, $dateTo);

    $whereSql = " WHERE article_status_id <> :deleted_status";
    $params = [
        'deleted_status' => ARTICLE_STATUS_DELETED
    ];

    if ($normalizedQuery !== '') {
        $whereSql .= " AND (titre_h1 LIKE :search OR meta_description LIKE :search OR contenu_html LIKE :search)";
        $params['search'] = '%' . $normalizedQuery . '%';
    }

    if ($resolvedDateFrom !== '') {
        $whereSql .= " AND DATE(date_creation) >= :date_from";
        $params['date_from'] = $resolvedDateFrom;
    }

    if ($resolvedDateTo !== '') {
        $whereSql .= " AND DATE(date_creation) <= :date_to";
        $params['date_to'] = $resolvedDateTo;
    }

    $countSql = "SELECT COUNT(*) FROM articles" . $whereSql;
    $countStmt = $conn->prepare($countSql);
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();

    $itemsSql = "SELECT id, url_slug, titre_h1, meta_description, date_creation FROM articles" . $whereSql . " ORDER BY " . $orderBy . " LIMIT :limit OFFSET :offset";
    $itemsStmt = $conn->prepare($itemsSql);

    foreach ($params as $key => $value) {
        $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
        $itemsStmt->bindValue(':' . $key, $value, $paramType);
    }
    $itemsStmt->bindValue(':limit', $safePerPage, PDO::PARAM_INT);
    $itemsStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $itemsStmt->execute();

    return [
        'items' => $itemsStmt->fetchAll(),
        'total' => $total,
        'page' => $safePage,
        'per_page' => $safePerPage,
        'total_pages' => max(1, (int) ceil($total / $safePerPage)),
        'resolved_date_from' => $resolvedDateFrom,
        'resolved_date_to' => $resolvedDateTo,
        'sort' => $sortKey
    ];
}
