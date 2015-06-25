<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $tags; ?>">
    <link rel=”author” href=”https://www.facebook.com/vienvong.vn”/>
    <meta property="og:title" content="<?php echo $title; ?>"/>
    <meta property="og:description" content="<?php echo $description; ?>"/>
    <meta property="og:image" content="http://<?php echo $_SERVER['HTTP_HOST'].'/'.$lAvatar; ?>"/>
    <meta property="og:url" content="<?php echo 'http://vienvong.vn/express/'.$_id; ?>"/>
    <meta property="og:site_name" content="Viễn Vọng"/>
    <meta property="fb:app_id" content="550251971759267" />
    <meta property="og:type" content="article"/>
    <meta property="og:locale:alternate" content="vi_VI" />
    <meta property="article:author" content="https://vienvong.vn" />

    <!-- etc. -->
</head>
<body>
    <h1>
        <?php echo $title?>
    </h1>
    <article>
        <?php echo $content?>
    </article>
    <aside>
        <?php
        foreach($suggest as $link) {
            $href = 'http://vienvong.vn/express/'.$link['_id'];
            echo '<a href="'.$href.'">'.$link['title'].'</a>';
        }
        ?>

    </aside>
</body>
</html>