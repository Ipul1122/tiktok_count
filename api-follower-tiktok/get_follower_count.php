<?php
function getTikTokFollowerCount($username) {
    $url = "https://www.tiktok.com/@$username";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
    $html = curl_exec($ch);

    if ($html === FALSE) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => "cURL Error: $error"];
    }

    curl_close($ch);

    // Improved regex to extract follower count
    preg_match('/"followerCount":\s*"?([\d,]+)"?/', $html, $matches);
    
    // Check if follower count is found
    if (empty($matches)) {
        return ['error' => 'Unable to find follower count in the response'];
    }

    return $matches[1]; // Return the follower count found
}

$username = 'pul_ipul_pul'; // TikTok Username
$result = getTikTokFollowerCount($username);

if (isset($result['error'])) {
    echo json_encode(['error' => $result['error']]);
} else {
    echo json_encode(['username' => $username, 'followerCount' => $result]);
}
?>
