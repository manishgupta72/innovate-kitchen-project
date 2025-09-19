<?php
class PageModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all pages ordered by created_at descending
    public function getAllPages()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pages ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single page by ID
    public function getPageById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPageBySlug($slug)
    {
        if (!empty($slug)) {
            $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE slug = :slug LIMIT 1");
            $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }


    // Add a new page
    public function addPage($pageType, $title, $subTitle, $description, $slug)
    {
        $sql = "INSERT INTO pages (page_type, title, sub_title, description, slug, created_at)
                VALUES (:page_type, :title, :sub_title, :description, :slug, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':page_type'   => $pageType,
            ':title'       => $title,
            ':sub_title'   => $subTitle,
            ':description' => $description,
            ':slug'        => $slug
        ]);
    }

    // Update an existing page
    public function updatePage($id, $pageType, $title, $subTitle, $description, $slug)
    {
        $sql = "UPDATE pages SET
                    page_type = :page_type,
                    title = :title,
                    sub_title = :sub_title,
                    description = :description,
                    slug = :slug,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':page_type'   => $pageType,
            ':title'       => $title,
            ':sub_title'   => $subTitle,
            ':description' => $description,
            ':slug'        => $slug
        ]);
    }

    // Delete a page by ID
    public function deletePage($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM pages WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
