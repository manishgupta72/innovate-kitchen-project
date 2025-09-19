<?php
class ArticleModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all articles ordered by publish_date descending, then created_at
    public function getAllArticles()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles ORDER BY publish_date DESC, created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single article by ID
    public function getArticleById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new article
    public function addArticle($articlesType, $title, $subTitle, $description, $slug, $thumbImage, $uploadImages, $publishDate)
    {
        // Convert empty publish date to NULL
        $publishDate = empty($publishDate) ? null : $publishDate;

        $sql = "INSERT INTO articles 
                (articles_type, title, sub_title, description, slug, thumb_image, upload_images, publish_date, created_at)
                VALUES (:articles_type, :title, :sub_title, :description, :slug, :thumb_image, :upload_images, :publish_date, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':articles_type' => $articlesType,
            ':title'         => $title,
            ':sub_title'     => $subTitle,
            ':description'   => $description,
            ':slug'          => $slug,
            ':thumb_image'   => $thumbImage,
            ':upload_images' => $uploadImages,
            ':publish_date'  => $publishDate
        ]);
    }

    // Update an existing article
    public function updateArticle($id, $articlesType, $title, $subTitle, $description, $slug, $thumbImage, $uploadImages, $publishDate)
    {
        // Convert empty publish date to NULL
        $publishDate = empty($publishDate) ? null : $publishDate;

        $sql = "UPDATE articles SET
                    articles_type = :articles_type,
                    title = :title,
                    sub_title = :sub_title,
                    description = :description,
                    slug = :slug,
                    upload_images = CASE WHEN :upload_images = '' THEN upload_images ELSE :upload_images END,
                    thumb_image = CASE WHEN :thumb_image = '' THEN thumb_image ELSE :thumb_image END,
                    publish_date = :publish_date,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':articles_type' => $articlesType,
            ':title'         => $title,
            ':sub_title'     => $subTitle,
            ':description'   => $description,
            ':slug'          => $slug,
            ':thumb_image'   => $thumbImage,
            ':upload_images' => $uploadImages,
            ':publish_date'  => $publishDate
        ]);
    }

    // Delete an article by ID
    public function deleteArticle($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}