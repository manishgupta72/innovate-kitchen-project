<?php

class MediaModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    public function getMediaByType($mediaType)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM media WHERE media_type = :media_type ORDER BY date DESC");
        $stmt->bindParam(':media_type', $mediaType, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all media items ordered by date descending
    public function getAllMedia()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM media ORDER BY date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single media item by ID
    public function getMediaById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM media WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new media item
    public function addMedia($mediaType, $title, $description, $url, $date, $uploadFile = null)
    {
        // If $date is empty, set it to null so that it inserts NULL in the database.
        $date = empty($date) ? null : $date;

        $sql = "INSERT INTO media 
                (media_type, title, description, url, date, upload_file, created_at) 
                VALUES (:media_type, :title, :description, :url, :date, :upload_file, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':media_type'  => $mediaType,
            ':title'       => $title,
            ':description' => $description,
            ':url'         => $url,
            ':date'        => $date,
            ':upload_file' => $uploadFile
        ]);
    }

    // Update an existing media item
    public function updateMedia($id, $mediaType, $title, $description, $url, $date, $uploadFile = null)
    {
        // If $date is empty, set it to null.
        $date = empty($date) ? null : $date;

        $sql = "UPDATE media SET
                media_type = :media_type,
                title = :title,
                description = :description,
                url = :url,
                date = :date,
                upload_file = :upload_file,
                updated_at = NOW()
            WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':media_type'  => $mediaType,
            ':title'       => $title,
            ':description' => $description,
            ':url'         => $url,
            ':date'        => $date,
            ':upload_file' => $uploadFile
        ]);
    }

    // Delete a media item by ID
    public function deleteMedia($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM media WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
