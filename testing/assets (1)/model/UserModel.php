<?php
class UserModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }



    public function createUser($username, $email, $role, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, user_email, user_roll, user_password) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$username, $email, $role, $hashedPassword]);
    }

    public function getUserById($userId)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetches a single user as an associative array
    }


    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $username, $email, $role, $password = null)
    {
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, user_email = ?, user_roll = ?, user_password = ? WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$username, $email, $role, $hashedPassword, $userId]);
        } else {
            $sql = "UPDATE users SET username = ?, user_email = ?, user_roll = ? WHERE user_id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$username, $email, $role, $userId]);
        }
    }

    public function deleteUser($userId)
    {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId]);
    }
    public function getUserRoleById($userId)
    {
        $sql = "SELECT user_roll FROM users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn(); // Return the role only
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLastLogin($userId)
    {
        $sql = "UPDATE users SET user_last_login = CURRENT_TIMESTAMP WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId]);
    }
}
