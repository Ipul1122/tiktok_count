# TikTok Follower Count

This project fetches and displays the follower count of a specified TikTok user. It consists of an HTML file for the frontend and a PHP file for the backend.

## Files

1. **index.html**: The frontend HTML file that displays the follower count.
2. **get_follower_count.php**: The backend PHP file that fetches the follower count from TikTok.

## index.html

This file contains the HTML structure and JavaScript code to fetch and display the follower count.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TikTok Follower Count</title>
    <script>
        async function updateFollowerCount() {
            try {
                const response = await fetch('get_follower_count.php');
                const data = await response.json();
                // console.log('Response data:', data); // Log the response data for debugging
                if (data.error) {
                    console.error('Error:', data.error);
                    document.getElementById('followerCount').innerText = 'Error fetching data';
                } else {
                    document.getElementById('username').innerText = data.username;
                    document.getElementById('followerCount').innerText = data.followerCount;
                }
            } catch (error) {
                console.error('Error fetching follower count:', error);
                document.getElementById('followerCount').innerText = 'Error fetching data';
            }
        }

        setInterval(updateFollowerCount, 10000); // Update every 10 seconds
        window.onload = updateFollowerCount;
    </script>
</head>
<body>
    <h1>TikTok Follower Count</h1>  
    <p>Username: <span id="username"></span></p>
    <p>Follower Count: <span id="followerCount"></span></p>
</body>
</html>
```

## get_follower_count.php

This file contains the PHP code to fetch the follower count from TikTok using cURL.

```php
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

    // Update the regular expression to match numbers with commas or periods
    preg_match('/"followerCount":\s*"?([\d,\.]+)"?/', $html, $matches);
    return $matches[1] ?? ['error' => 'Unable to find follower count in the response'];
}

$username = 'pul_ipul_pul'; // USERNAME TIKTOK
$result = getTikTokFollowerCount($username);

if (isset($result['error'])) {
    echo json_encode(['error' => $result['error']]);
} else {
    echo json_encode(['username' => $username, 'followerCount' => $result]);
}
?>
```

## How It Works

1. **Frontend (index.html)**:
   - The HTML file contains a script that fetches the follower count from the PHP backend every 10 seconds.
   - The follower count is displayed on the webpage.

2. **Backend (get_follower_count.php)**:
   - The PHP script uses cURL to fetch the TikTok user's page.
   - It uses a regular expression to extract the follower count from the HTML.
   - The follower count is returned as a JSON response.

## Usage

1. Place both `index.html` and `get_follower_count.php` in the same directory on your server.
2. Open `index.html` in a web browser to see the follower count for the specified TikTok user.

## Notes

- Ensure that the TikTok username is correctly set in the PHP script (`$username = 'pul_ipul_pul';`).
- If the structure of the TikTok page changes, you may need to update the regular expression in the PHP script to correctly extract the follower count.
