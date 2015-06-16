<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $tags; ?>">
    <meta property="og:title" content="<?php echo $title; ?>"/>
    <meta property="og:description" content="<?php echo $description; ?>"/>
    <meta property="og:image" content="http://<?php echo $_SERVER['HTTP_HOST'].'/'.$lAvatar; ?>"/>
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