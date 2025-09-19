<?php
class MasterDataModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPlatforms()
    {
        $stmt = $this->pdo->prepare("SELECT id, name FROM master_data WHERE master_type = 4");  // Assuming '4' is for platforms
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMasterData()
    {
        $stmt = $this->pdo->query("SELECT * FROM master_data ORDER BY master_type, name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMasterDataName($id)
    {
        $stmt = $this->pdo->prepare("SELECT name FROM master_data WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() for a single row
    }

    // New method to fetch master data by type (e.g., media_type = 1)
    public function getMasterDataByType($masterType)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_data WHERE master_type = :masterType ORDER BY name");
        $stmt->execute([':masterType' => $masterType]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Updated to include master_name
    public function addMasterData($name, $description, $masterType, $masterName)
    {
        $sql  = "INSERT INTO master_data (name, description, master_type, master_name) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $description, $masterType, $masterName]);
    }

    // Updated to include master_name
    public function updateMasterData($id, $name, $description, $masterType, $masterName)
    {
        $sql  = "UPDATE master_data SET name = ?, description = ?, master_type = ?, master_name = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$name, $description, $masterType, $masterName, $id]);
    }

    public function deleteMasterData($id)
    {
        $sql  = "DELETE FROM master_data WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
