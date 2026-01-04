<?php
/**
 * Category Model
 * Gère les catégories de livres
 */

class Category extends Model {
    
    protected string $table = 'categories';
    
    /**
     * Get all main categories (no parent)
     */
    public function getMainCategories(): array {
        return $this->findAll(['parent_id' => null, 'status' => 'active'], 'display_order ASC');
    }
    
    /**
     * Get featured categories
     */
    public function getFeaturedCategories(): array {
        return $this->findAll(['is_featured' => 1, 'status' => 'active'], 'display_order ASC');
    }
    
    /**
     * Get subcategories of a category
     */
    public function getSubcategories(int $parentId): array {
        return $this->findAll(['parent_id' => $parentId, 'status' => 'active'], 'display_order ASC');
    }
    
    /**
     * Get category by slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";
        $stmt = Database::query($sql, ['slug' => $slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Get category with book count
     */
    public function getCategoryWithBookCount(int $categoryId): ?array {
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id AND b.status = 'active'
                WHERE c.id = :id
                GROUP BY c.id";
        
        $stmt = Database::query($sql, ['id' => $categoryId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Get all categories with book counts
     */
    public function getAllCategoriesWithCounts(): array {
        $sql = "SELECT c.*, COUNT(b.id) as book_count
                FROM {$this->table} c
                LEFT JOIN books b ON c.id = b.category_id AND b.status = 'active'
                WHERE c.status = 'active'
                GROUP BY c.id
                ORDER BY c.parent_id IS NULL DESC, c.display_order ASC";
        
        $stmt = Database::query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Get category tree (hierarchical)
     */
    public function getCategoryTree(): array {
        $categories = $this->findAll(['status' => 'active'], 'display_order ASC');
        return $this->buildTree($categories);
    }
    
    /**
     * Build hierarchical tree from flat array
     */
    private function buildTree(array $categories, ?int $parentId = null): array {
        $branch = [];
        
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = $this->buildTree($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $branch[] = $category;
            }
        }
        
        return $branch;
    }
    
    /**
     * Get breadcrumb for category
     */
    public function getBreadcrumb(int $categoryId): array {
        $breadcrumb = [];
        $category = $this->findById($categoryId);
        
        while ($category) {
            array_unshift($breadcrumb, $category);
            $category = $category['parent_id'] ? $this->findById($category['parent_id']) : null;
        }
        
        return $breadcrumb;
    }
}