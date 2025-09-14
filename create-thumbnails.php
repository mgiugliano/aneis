<?php

// --- CONFIGURATION ---
$sourceDirectory = 'images/photos/';
$thumbDirectory = 'images/thumbnails/';
$thumbWidth = 400; // The maximum width of your thumbnails in pixels

// Get all year folders
$years = array_filter(glob($sourceDirectory . '*'), 'is_dir');

echo "Starting thumbnail generation...\n";

foreach ($years as $yearPath) {
    $year = basename($yearPath);
    $targetYearDir = $thumbDirectory . $year;

    // Create the year directory in thumbnails if it doesn't exist
    if (!file_exists($targetYearDir)) {
        mkdir($targetYearDir, 0755, true);
    }

    // Find all images in the source year directory
    $images = glob($yearPath . '/{*.jpg,*.jpeg,*.png,*.gif}', GLOB_BRACE);

    foreach ($images as $imagePath) {
        $imageName = basename($imagePath);
        $thumb_path = $targetYearDir . '/' . $imageName;

        // Check if the thumbnail already exists
        if (file_exists($thumb_path)) {
            continue; // Skip if thumbnail is already there
        }

        // Get image size
        list($width, $height) = getimagesize($imagePath);

        // Calculate thumbnail height to maintain aspect ratio
        $ratio = $width / $height;
        $thumbHeight = floor($thumbWidth / $ratio);

        // Load the original image
        $sourceImage = null;
        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $sourceImage = imagecreatefromjpeg($imagePath);
        } else if ($extension == 'png') {
            $sourceImage = imagecreatefrompng($imagePath);
        } else if ($extension == 'gif') {
            $sourceImage = imagecreatefromgif($imagePath);
        }

        if ($sourceImage) {
            // Create a new image for the thumbnail
            $thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);

            // Resize the original image into the thumbnail image
            imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

            // Save the thumbnail
            imagejpeg($thumbImage, $thumb_path, 85); // Save as JPEG with 85% quality

            // Free up memory
            imagedestroy($sourceImage);
            imagedestroy($thumbImage);

            echo "Generated thumbnail for: $imageName\n";
        }
    }
}

echo "✅ Thumbnail generation complete.\n";
?>
