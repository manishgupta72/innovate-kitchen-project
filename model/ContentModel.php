<?php
class ContentModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all content items, ordered by created_at descending
    public function getAllContent()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM content ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getContentByType($contentType)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM content WHERE content_type = :content_type ORDER BY id ASC");
        $stmt->execute(['content_type' => $contentType]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Fetch a single content item by ID
    public function getContentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM content WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new content item
    public function addContent($contentType, $title, $description, $url, $uploadImage = null, $uploadImage2 = null)
    {
        $sql = "INSERT INTO content (content_type, title, description, url, upload_image,upload_image2, created_at) 
                VALUES (:content_type, :title, :description, :url, :upload_image,:upload_image2, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':content_type'  => $contentType,
            ':title'         => $title,
            ':description'   => $description,
            ':url'           => $url,
            ':upload_image'  => $uploadImage,
            ':upload_image2'  => $uploadImage2
        ]);
    }

    // Update an existing content item
    public function updateContent($id, $contentType, $title, $description, $url, $uploadImage = null, $uploadImage2 = null)
    {
        $sql = "UPDATE content SET 
                    content_type = :content_type,
                    title = :title,
                    description = :description,
                    url = :url,
                    upload_image = :upload_image,
                    upload_image2 = :upload_image2,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':content_type'  => $contentType,
            ':title'         => $title,
            ':description'   => $description,
            ':url'           => $url,
            ':upload_image'  => $uploadImage,
            ':upload_image2'  => $uploadImage2
        ]);
    }

    // Delete a content item by ID
    public function deleteContent($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM content WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
