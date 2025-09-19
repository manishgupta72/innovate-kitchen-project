<?php
class ReviewModel
{
    protected $pdo;

    public function __construct($pdo)
    {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException("Invalid PDO object provided");
        }
        $this->pdo = $pdo;
    }

    // Fetch all reviews ordered by publish_date descending, then created_at
    public function getAllReviews()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM review ORDER BY publish_date DESC, created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single review by ID
    public function getReviewById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM review WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new review
    public function addReview($reviewType, $title, $description, $authorName, $ratings, $authorImage, $publishDate)
    {
        // Convert empty publish date to NULL
        $publishDate = empty($publishDate) ? null : $publishDate;

        $sql = "INSERT INTO review 
                (review_type, title, description, author_name, ratings, author_image, publish_date, created_at)
                VALUES (:review_type, :title, :description, :author_name, :ratings, :author_image, :publish_date, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':review_type'  => $reviewType,
            ':title'        => $title,
            ':description'  => $description,
            ':author_name'  => $authorName,
            ':ratings'      => $ratings,
            ':author_image' => $authorImage,
            ':publish_date' => $publishDate
        ]);
    }

    // Update an existing review
    public function updateReview($id, $reviewType, $title, $description, $authorName, $ratings, $authorImage, $publishDate)
    {
        // Convert empty publish date to NULL
        $publishDate = empty($publishDate) ? null : $publishDate;

        $sql = "UPDATE review SET
                    review_type = :review_type,
                    title = :title,
                    description = :description,
                    author_name = :author_name,
                    ratings = :ratings,
                    author_image = CASE WHEN :author_image = '' THEN author_image ELSE :author_image END,
                    publish_date = :publish_date,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id'           => $id,
            ':review_type'  => $reviewType,
            ':title'        => $title,
            ':description'  => $description,
            ':author_name'  => $authorName,
            ':ratings'      => $ratings,
            ':author_image' => $authorImage,
            ':publish_date' => $publishDate
        ]);
    }

    // Delete a review by ID
    public function deleteReview($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM review WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}