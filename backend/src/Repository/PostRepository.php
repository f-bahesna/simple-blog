<?php

require_once __DIR__ . '/../../public/config/database.php';
require_once __DIR__ . '/../Model/Post.php';

class PostRepository {
    private PDO $db;

    public function __construct(){
        $this->db = Database::getConnection();
    }

    public function all(string $orderBy = 'ASC'): array {
        $order = (strtoupper($orderBy) === 'ASC') ? 'ASC' : 'DESC';

        $stmt = $this->db->query("SELECT * FROM posts ORDER BY created_at $order");
        $rows = $stmt->fetchAll();

        return array_map(fn ($r) => Post::fromArray($r), $rows);
    }

    public function find(int $id): ?Post {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? Post::fromArray($row) : null;
    }

    public function findOneByTitle(string $title): ?Post {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE title = :title LIMIT 1");
        $stmt->execute([':title' => $title]);
        $row = $stmt->fetch();

        return $row ? Post::fromArray($row) : null;
    }

    public function findOneByTitleLike(string $title): array
    {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE title LIKE :title");
        $stmt->execute(['title' => "%$title%"]);
        $row = $stmt->fetchAll();
       
        return array_map(fn ($r) => Post::fromArray($r), $row);
    }

    public function create(string $title, string $body): Post {
        $stmt = $this->db->prepare("INSERT INTO posts (title, body) VALUES (:title, :body)");
        $stmt->execute([':title' => $title, ':body' => $body]);
        $id = (int)$this->db->lastInsertId();

        return $this->find($id);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute([':id' => $id ]);

        return $stmt->rowCount() > 0;
    }

    public function assertDuplicateTitle(string $title): bool
    {
        if(null !== $this->findOneByTitle($title)){
            return true;
        }
        
        return false;
    }
}