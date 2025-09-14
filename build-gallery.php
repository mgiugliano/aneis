<?php

// --- CONFIGURATION ---
$photoYears = ['2025', '2024']; // Add new years here, most recent first
$outputFile = 'photos.html'; // The name of the final HTML file to be created

// --- HTML TEMPLATE PARTS ---
$htmlTop = <<<EOT
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ANEIS Photo Galleries</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="css/photoswipe.css">
  <link rel="stylesheet" href="css/default-skin.css">
  <link rel="stylesheet" href="css/main.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200&display=swap" rel="stylesheet">
  <style>
    .header-image {
      background: #333366 url("images/photos/Flying neurons and balloons (1).jpeg");
      background-position: center 30%;
      background-repeat: no-repeat;
      background-size: cover;
    }
  </style>
</head>
<body>
  <div class="jumbotron header-image">
    <div class="header-info">
      <h1>ANEIS Photos</h1>
      <div class="header-info-details">Ventotene Summer School</div>
    </div>
  </div>
EOT;

$htmlBottom = <<<EOT
  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="Close (Esc)"></button><button class="pswp__button pswp__button--share" title="Share"></button><button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button><button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div></div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div>
  </div>
  <footer class="container-fluid text-right"><p>Created by <a rel="noreferrer" href="https://www.haltakov.net/simple-photo-gallery">Simple Photo Gallery</a></p></footer>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="js/photoswipe.min.js"></script>
  <script src="js/photoswipe-ui-default.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
EOT;

// --- SCRIPT LOGIC ---
$finalHtml = $htmlTop;

foreach ($photoYears as $year) {
    $imageFolder = "images/photos/$year/";
    $thumbnailFolder = "images/thumbnails/$year/";
    $images = glob($imageFolder . '{*.jpg,*.jpeg,*.png,*.gif}', GLOB_BRACE);

    $finalHtml .= '<div class="container-fluid"><div class="row"><div class="col gallery-section">';
    $finalHtml .= '<h2><a name="section_' . $year . '"></a>' . $year . '</h2>';
    $finalHtml .= '</div></div><div class="row"><div class="col gallery">';

    if ($images) {
        foreach ($images as $image) {
            $thumbnail = str_replace($imageFolder, $thumbnailFolder, $image);
            list($width, $height) = getimagesize($image);

            $finalHtml .= '<a href="' . $image . '" class="gallery-photo" data-width="' . $width . '" data-height="' . $height . '">';
            $finalHtml .= '<img src="' . $thumbnail . '" class="thumbnail rounded" alt="Photo from ' . $year . '"/></a>';
        }
    } else {
        $finalHtml .= '<p>No photos found for ' . $year . '.</p>';
    }

    $finalHtml .= '</div></div></div>';
}

$finalHtml .= $htmlBottom;

// Write the generated HTML to the output file
file_put_contents($outputFile, $finalHtml);

echo "✅ Successfully created '$outputFile' with all photo galleries!\n";
?>
