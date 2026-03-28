<?php
include_once 'Connection.php';
function getArticleBySlug($url_slug)
{
    $conn = getConnection();
    $sql = "SELECT * FROM articles WHERE url_slug = :url_slug";
    $article_stmt = $conn->prepare($sql);
    $article_stmt->execute([
        'url_slug' => $url_slug
    ]);
    $res = $article_stmt->fetch();
    return $res;
}

function getLatestArticles($limit = 3)
{
    $conn = getConnection();
    $sql = "SELECT id, url_slug, titre_h1, meta_description, date_creation FROM articles ORDER BY date_creation DESC LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getOtherArticles($currentId, $limit = 3)
{
    $conn = getConnection();
    $sql = "SELECT id, url_slug, titre_h1, image_url, image_alt FROM articles WHERE id <> :currentId ORDER BY date_creation DESC LIMIT :limit";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':currentId', (int) $currentId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
