<?php

// Create uploads directory if it doesn't exist
$upload_dir = '../uploads/restaurants/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Sample photo URLs with direct links to reliable sources
$photos = [
    // Italian Restaurant
    'la_piazza_1.jpg' => 'https://images.pexels.com/photos/1537635/pexels-photo-1537635.jpeg',
    'la_piazza_2.jpg' => 'https://images.pexels.com/photos/1527603/pexels-photo-1527603.jpeg',
    'la_piazza_3.jpg' => 'https://images.pexels.com/photos/1437267/pexels-photo-1437267.jpeg',
    'la_piazza_4.jpg' => 'https://images.pexels.com/photos/1566837/pexels-photo-1566837.jpeg',

    // Japanese Restaurant
    'sakura_1.jpg' => 'https://images.pexels.com/photos/2098085/pexels-photo-2098085.jpeg',
    'sakura_2.jpg' => 'https://images.pexels.com/photos/2323398/pexels-photo-2323398.jpeg',
    'sakura_3.jpg' => 'https://images.pexels.com/photos/884600/pexels-photo-884600.jpeg',
    'sakura_4.jpg' => 'https://images.pexels.com/photos/1028425/pexels-photo-1028425.jpeg',

    // Mexican Restaurant
    'mariachi_1.jpg' => 'https://images.pexels.com/photos/2092897/pexels-photo-2092897.jpeg',
    'mariachi_2.jpg' => 'https://images.pexels.com/photos/2092507/pexels-photo-2092507.jpeg',
    'mariachi_3.jpg' => 'https://images.pexels.com/photos/5737247/pexels-photo-5737247.jpeg',
    'mariachi_4.jpg' => 'https://images.pexels.com/photos/2087748/pexels-photo-2087748.jpeg',

    // Chinese Restaurant
    'dragon_1.jpg' => 'https://images.pexels.com/photos/955137/pexels-photo-955137.jpeg',
    'dragon_2.jpg' => 'https://images.pexels.com/photos/1234535/pexels-photo-1234535.jpeg',
    'dragon_3.jpg' => 'https://images.pexels.com/photos/1437590/pexels-photo-1437590.jpeg',
    'dragon_4.jpg' => 'https://images.pexels.com/photos/2347311/pexels-photo-2347311.jpeg',

    // Indian Restaurant
    'taj_1.jpg' => 'https://images.pexels.com/photos/2474661/pexels-photo-2474661.jpeg',
    'taj_2.jpg' => 'https://images.pexels.com/photos/958545/pexels-photo-958545.jpeg',
    'taj_3.jpg' => 'https://images.pexels.com/photos/2474658/pexels-photo-2474658.jpeg',
    'taj_4.jpg' => 'https://images.pexels.com/photos/2474659/pexels-photo-2474659.jpeg',

    // French Restaurant
    'bistro_1.jpg' => 'https://images.pexels.com/photos/1307698/pexels-photo-1307698.jpeg',
    'bistro_2.jpg' => 'https://images.pexels.com/photos/299410/pexels-photo-299410.jpeg',
    'bistro_3.jpg' => 'https://images.pexels.com/photos/2014696/pexels-photo-2014696.jpeg',
    'bistro_4.jpg' => 'https://images.pexels.com/photos/541216/pexels-photo-541216.jpeg',

    // Mediterranean Restaurant
    'med_1.jpg' => 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg',
    'med_2.jpg' => 'https://images.pexels.com/photos/1618898/pexels-photo-1618898.jpeg',
    'med_3.jpg' => 'https://images.pexels.com/photos/1618955/pexels-photo-1618955.jpeg',
    'med_4.jpg' => 'https://images.pexels.com/photos/1618950/pexels-photo-1618950.jpeg'
];

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Download each photo
foreach ($photos as $filename => $url) {
    echo "Downloading $filename... ";
    
    curl_setopt($ch, CURLOPT_URL, $url);
    $image_data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($http_code === 200 && $image_data) {
        if (file_put_contents($upload_dir . $filename, $image_data)) {
            echo "SUCCESS\n";
        } else {
            echo "FAILED (Could not save file)\n";
        }
    } else {
        echo "FAILED (HTTP $http_code)\n";
    }
}

// Close cURL handle
curl_close($ch);

echo "\nAll photos have been downloaded to: " . realpath($upload_dir) . "\n";

// Verify downloaded files
$downloaded_files = scandir($upload_dir);
$missing_files = [];

foreach ($photos as $filename => $url) {
    if (!in_array($filename, $downloaded_files)) {
        $missing_files[] = $filename;
    }
}

if (!empty($missing_files)) {
    echo "\nWarning: The following files were not downloaded successfully:\n";
    foreach ($missing_files as $file) {
        echo "- $file\n";
    }
    echo "\nPlease try running the script again to download missing files.\n";
}
?>
