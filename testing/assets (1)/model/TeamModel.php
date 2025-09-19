<?php
class TeamModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all team members ordered by created_at descending
    public function getAllTeam()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM team ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single team member by ID
    public function getTeamById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM team WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new team member
    public function addTeam($teamType, $title, $designation, $description, $uploadImage, $url)
    {
        $sql = "INSERT INTO team (team_type, title, designation, description, upload_image, url, created_at)
                VALUES (:team_type, :title, :designation, :description, :upload_image, :url, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':team_type'    => $teamType,
            ':title'        => $title,
            ':designation'  => $designation,
            ':description'  => $description,
            ':upload_image' => $uploadImage,
            ':url'          => $url
        ]);
    }

    // Update an existing team member
    public function updateTeam($id, $teamType, $title, $designation, $description, $uploadImage, $url)
    {
        $sql = "UPDATE team SET
                    team_type = :team_type,
                    title = :title,
                    designation = :designation,
                    description = :description,
                    upload_image = CASE WHEN :upload_image = '' THEN upload_image ELSE :upload_image END,
                    url = :url,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':team_type'    => $teamType,
            ':title'        => $title,
            ':designation'  => $designation,
            ':description'  => $description,
            ':upload_image' => $uploadImage,
            ':url'          => $url
        ]);
    }

    // Delete a team member by ID
    public function deleteTeam($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM team WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}