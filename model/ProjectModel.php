<?php
class ProjectModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllProjects()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM project ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM project WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProjectByType($type)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM project WHERE project_type = :type ORDER BY id ASC");
        $stmt->execute(['type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProject($type, $title, $description, $url, $file, $date)
    {
        $date = !empty($date) ? $date : null; // Fix here
        $stmt = $this->pdo->prepare("INSERT INTO project (project_type, title, description, url, upload_file, date, created_at) VALUES (:type, :title, :description, :url, :file, :date, NOW())");
        return $stmt->execute([
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'file' => $file,
            'date' => $date
        ]);
    }


    public function updateProject($id, $type, $title, $description, $url, $file, $date)
    {
        $date = !empty($date) ? $date : null; // Fix here
        $stmt = $this->pdo->prepare("UPDATE project SET project_type = :type, title = :title, description = :description, url = :url, upload_file = :file, date = :date, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'file' => $file,
            'date' => $date
        ]);
    }


    public function deleteProject($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM project WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
