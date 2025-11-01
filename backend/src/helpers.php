<?php

function jsonResponse($data, int $status = 200): void 
{
    $meta = [
        'hostname' => gethostname(),
        'client_ip' => getClientIp()
    ];
    
    $response = [
        'data' => $data,
        'meta' => $meta
    ];

    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function jsonError($code, $message): void 
{
    echo jsonResponse([ 'code' => $code, 'error' => $message]);
    exit;
}

function getJsonInput() 
{
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    
    return sanitize($data);
}

// HANDLING XSS
function sanitize($payloads)
{
    if(is_array($payloads)){
        $clean = [];
        foreach($payloads as $key => $payload){
            $clean[$key] = sanitize($payload);
        }

        return $clean;
    }

    if(is_string($payloads)){
        return htmlspecialchars($payloads);
    }

    return $payloads;
}

function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }
}