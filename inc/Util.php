<?php
include_once 'Connection.php';
function getArticleBySlug($slug)
{
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM articles WHERE url_slug = :slug");
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
