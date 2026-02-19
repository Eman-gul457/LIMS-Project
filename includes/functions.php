<?php
function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function status_class($status)
{
    $map = [
        'Pending' => 'badge pending',
        'In Progress' => 'badge progress',
        'Completed' => 'badge completed',
        'Approved' => 'badge approved',
        'Rejected' => 'badge rejected',
    ];

    return $map[$status] ?? 'badge';
}

function redirect_with_message($location, $message, $type = 'success')
{
    $query = http_build_query([
        'msg' => $message,
        'type' => $type,
    ]);

    header('Location: ' . $location . (str_contains($location, '?') ? '&' : '?') . $query);
    exit;
}
