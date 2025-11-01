<?php

class Post {
    public ?int $id;
    public string $title;
    public string $body;
    public ?string $created_at;

    public function __construct(?int $id, string $title, string $body, ?string $created_at = null) {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->created_at = $created_at;
    }

    public static function fromArray(array $row): Post {
        return new Post(
            (int)$row['id'],
            $row['title'],
            $row['body'],
            $row['created_at'] ?? null
        );
    }
}