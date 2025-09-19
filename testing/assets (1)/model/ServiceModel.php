<?php
class ServiceModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all services ordered by display_order and title
    public function getAllServices()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM services ORDER BY display_order ASC, title ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single service by ID
    public function getServiceById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM services WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServiceBySlug($slug)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM services WHERE slug = :slug");
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Add a new service
    // Modify Add Service Method
    public function addService($servicesType, $title, $subTitle, $description, $thumbImage, $slug, $url, $displayOrder, $displayType, $display, $qaData)
    {
        $sql = "INSERT INTO services 
            (services_type, title, sub_title, description, qa_data, thumb_image, slug, url, display_order, display_type, display, created_at)
            VALUES (:services_type, :title, :sub_title, :description, :qa_data, :thumb_image, :slug, :url, :display_order, :display_type, :display, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':services_type' => $servicesType,
            ':title'         => $title,
            ':sub_title'     => $subTitle,
            ':description'   => $description,
            ':qa_data'       => json_encode($qaData), // Save as JSON
            ':thumb_image'   => $thumbImage,
            ':slug'          => $slug,
            ':url'           => $url,
            ':display_order' => $displayOrder,
            ':display_type'  => $displayType,
            ':display'       => $display
        ]);
    }

    // Modify Update Service Method
    public function updateService($id, $servicesType, $title, $subTitle, $description, $thumbImage, $slug, $url, $displayOrder, $displayType, $display, $qaData)
    {
        $sql = "UPDATE services SET 
                services_type = :services_type,
                title = :title,
                sub_title = :sub_title,
                description = :description,
                qa_data = :qa_data, 
                thumb_image = CASE WHEN :thumb_image = '' THEN thumb_image ELSE :thumb_image END,
                slug = :slug,
                url = :url,
                display_order = :display_order,
                display_type = :display_type,
                display = :display,
                updated_at = NOW()
            WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'            => $id,
            ':services_type' => $servicesType,
            ':title'         => $title,
            ':sub_title'     => $subTitle,
            ':description'   => $description,
            ':qa_data'       => json_encode($qaData), // Save as JSON
            ':thumb_image'   => $thumbImage,
            ':slug'          => $slug,
            ':url'           => $url,
            ':display_order' => $displayOrder,
            ':display_type'  => $displayType,
            ':display'       => $display
        ]);
    }


    // Delete a service by ID
    public function deleteService($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM services WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
