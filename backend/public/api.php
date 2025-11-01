<?php

// Handling CORS
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/../src/Repository/PostRepository.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/api';
$path = '/'. trim(substr($uri, strlen($base)), '/');

$postRepository = new PostRepository();

if($method === 'GET' && $path === '/posts'){
    $posts = $postRepository->all();

    $data = array_map(fn($d) => [
        'id' => $d->id,
        'title' => $d->title,
        'body' => $d->body,
        'created_at' => $d->created_at
    ], $posts);

    jsonResponse($data);
}

if($method === 'POST' && $path === '/posts'){
    $payload = getJsonInput();
    
    $title = $payload['title'] ?? '';
    $body = $payload['body'] ?? '';
    
    if(!is_string($title) || !is_string($body)){
        jsonError(409, 'Invalid format for title and body.');
    }

    if(!$title || !$body){
        jsonError(409, 'title and body required');
    }

    if(mb_strlen($title) > 100){
        jsonError(409, 'body too long max 100');
    }

    if(mb_strlen($body) > 255){
        jsonError(409, 'body too long max 255');
    }

    try {
        if($postRepository->assertDuplicateTitle($title)){
            jsonError(409, 'duplicate title');
        }

        $post = $postRepository->create($title, $body);
        
        jsonResponse([
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'created_at' => $post->created_at,
        ], 201);
    } catch (Exception $e) {
        jsonError(409, 'failed to create post');
    }
}

if($method === 'DELETE' && preg_match('#^/posts/(\d+)$#', $path, $m)){
    $id = (int)$m[1];
    $existing = $postRepository->find($id);

    if(!$existing) jsonError(409, 'Post not found');
    
    try {
        $deleted = $postRepository->delete($id);
        if($deleted){
            jsonResponse(['message' => sprintf('Post %s Deleted!', $id)]);
        }
    } catch (\Throwable $th) {
        jsonResponse(['error' => 'failed to delete', 'message' => $e->getMessage()], 500);
    }
}

jsonError(404, 'endpoint not found');