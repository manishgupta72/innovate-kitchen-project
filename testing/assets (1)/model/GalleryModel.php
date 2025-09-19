<?php
class Gallery
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all gallery items ordered by created_at descending
    public function getAllGalleryItems()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM gallery ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single gallery item by ID
    public function getGalleryItemById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM gallery WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addGalleryItem($gallery_type, $title, $description, $upload_file, $thumb_image, $youtube_url)
    {
        $sql = "INSERT INTO gallery 
                (gallery_type, title, description, upload_file, thumb_image, youtube_url, created_at) 
                VALUES (:gallery_type, :title, :description, :upload_file, :thumb_image, :youtube_url, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':gallery_type' => $gallery_type,
            ':title'        => $title,
            ':description'  => $description,
            ':upload_file'  => $upload_file,
            ':thumb_image'  => $thumb_image,
            ':youtube_url'  => $youtube_url
        ]);
    }

    public function updateGalleryItem($id, $gallery_type, $title, $description, $upload_file, $thumb_image, $youtube_url)
    {
        $sql = "UPDATE gallery SET
                    gallery_type = :gallery_type,
                    title = :title,
                    description = :description,
                    upload_file = CASE WHEN :upload_file = '' THEN upload_file ELSE :upload_file END,
                    thumb_image = CASE WHEN :thumb_image = '' THEN thumb_image ELSE :thumb_image END,
                    youtube_url = :youtube_url,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':gallery_type' => $gallery_type,
            ':title'        => $title,
            ':description'  => $description,
            ':upload_file'  => $upload_file,
            ':thumb_image'  => $thumb_image,
            ':youtube_url'  => $youtube_url
        ]);
    }

    // Delete a gallery item by ID
    public function deleteGalleryItem($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM gallery WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getGalleryItems($offset, $limit)
    {
        $stmt = $this->pdo->prepare("SELECT id, title, upload_file FROM gallery ORDER BY created_at DESC LIMIT :offset, :limit");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
