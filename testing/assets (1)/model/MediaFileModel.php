<?php
class MediaFileModel {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Method to fetch all media files
    public function getAllMediaFiles() {
        $stmt = $this->pdo->prepare("SELECT * FROM media_file ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to add a new media file
    public function addMediaFile($mediaType, $mediaName,$media_url, $media_upload, $createdBy) {
        $sql = "INSERT INTO media_file (media_type, media_name, media_url, media_upload, created_by) VALUES (:media_type, :media_name, :media_url, :media_upload, :created_by)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'media_type' => $mediaType,
            'media_name' => $mediaName,
            'media_url' => $media_url,
            'media_upload' => $media_upload,
            'created_by' => $createdBy
        ]);
    }

    // Method to update an existing media file
    public function updateMediaFile($id, $mediaType, $mediaName,$media_url, $media_upload, $updatedBy) {
        $sql = "UPDATE media_file SET media_type = :media_type, media_name = :media_name, media_url = :media_url, media_upload = :media_upload, updated_by = :updated_by WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'media_type' => $mediaType,
            'media_name' => $mediaName,
            'media_url' => $media_url,
            'media_upload' => $media_upload,
            'updated_by' => $updatedBy
        ]);
    }

    // Method to delete a media file
    public function deleteMediaFile($id) {
        $stmt = $this->pdo->prepare("DELETE FROM media_file WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function isDuplicateMediaName($mediaName, $id = null) {
        $sql = "SELECT COUNT(*) FROM media_file WHERE media_name = :media_name";
        $params = ['media_name' => $mediaName];

        if ($id !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $id;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }



}
