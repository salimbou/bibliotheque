<?php
/**
 * User Model
 * Handles all user-related database operations
 */

class User extends Model {
    
    protected string $table = 'users';
    
    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = Database::query($sql, ['email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Create new user
     */
    public function createUser(array $data): int {
        $data['password'] = hash_password($data['password']);
        $data['status'] = 'pending';
        
        return $this->create($data);
    }
    
    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $email, string $password): ?array {
        $user = $this->findByEmail($email);
        
        if ($user && verify_password($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return null; // User not activated
            }
            
            // Update last login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            return $user;
        }
        
        return null;
    }
    
    /**
     * Update user status
     */
    public function updateStatus(int $userId, string $status): bool {
        if (!in_array($status, ['active', 'inactive', 'pending'])) {
            return false;
        }
        
        return $this->update($userId, ['status' => $status]);
    }
    
    /**
     * Get users by role
     */
    public function getUsersByRole(string $role): array {
        $sql = "SELECT * FROM {$this->table} WHERE role = :role ORDER BY created_at DESC";
        $stmt = Database::query($sql, ['role' => $role]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get users with pagination
     */
    public function getPaginatedUsers(int $offset, int $limit, ?string $role = null): array {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($role !== null) {
            $sql .= " WHERE role = :role";
            $params['role'] = $role;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Search users
     */
    public function searchUsers(string $query): array {
        $searchTerm = "%$query%";
        $sql = "SELECT * FROM {$this->table} 
                WHERE first_name LIKE :search 
                OR last_name LIKE :search 
                OR email LIKE :search 
                ORDER BY created_at DESC";
        
        $stmt = Database::query($sql, ['search' => $searchTerm]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get user statistics
     */
    public function getStatistics(): array {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN role = 'lecteur' THEN 1 ELSE 0 END) as lecteurs,
                    SUM(CASE WHEN role = 'bibliothecaire' THEN 1 ELSE 0 END) as bibliothecaires,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive
                FROM {$this->table}";
        
        $stmt = Database::query($sql);
        return $stmt->fetch();
    }
    
    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool {
        $allowedFields = ['first_name', 'last_name', 'phone', 'address'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));
        
        return $this->update($userId, $updateData);
    }
    
    /**
     * Change user password
     */
    public function changePassword(int $userId, string $newPassword): bool {
        $hashedPassword = hash_password($newPassword);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
}