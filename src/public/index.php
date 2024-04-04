<?php

require(__DIR__.'/../app.php');

$show = "form";

if(isset($_GET['id'])) {
    if(Helper::checkKey($db, $_GET['id'])) {
            $paste = Helper::getPaste($db, $_GET['id']);
            $show = "result";
    } else {
        $show = "error";
    }
}

if(isset($_GET['link'])) {
    $show = "link";
    $link = $_GET['link'];
}
?>
<html>
    <head>
        <title><?=SITE_TITLE;?></title>
        <link href="/assets/styles.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="container">
            <?php switch($show) {

                default:
                    ?>
                        <h1><a href="/"><?=SITE_TITLE;?></a></h1>
                        <form action="" method="post" class="form">
                            <textarea name="content" class="content"></textarea>

                            <input class="btn" name="add-paste" type="submit" value="Send"/>
                        </form>
                    <?php
                break;
                
                case 'link':
                    ?>
                    <h1><a href="/"><?=SITE_TITLE;?></a></h1>
                    <div class="form">
                    Your url is: 
                        <div class="row">
                            <div class="col-full">
                                <?php if(URL_PRETTY){ ?>
                                    <input type="text" class="link" readonly value="<?=(empty($_SERVER['HTTPS']) ? 'http' : 'https')."://$_SERVER[HTTP_HOST]/l/$link"?>"/>
                                <?php } else { ?>
                                    <input type="text" class="link" readonly value="<?=(empty($_SERVER['HTTPS']) ? 'http' : 'https')."://$_SERVER[HTTP_HOST]/?link=$link"?>"/>
                                <?php } ?>
                            </div>
                            <div class="col">
                                <input class="btn copy-link" type="submit" value="Copy"/>
                            </div>
                        </div>
                        <?php if(URL_PRETTY){ ?>
                            <a class="btn copy-link" href="<?=(empty($_SERVER['HTTPS']) ? 'http' : 'https')."://$_SERVER[HTTP_HOST]/p/$link"?>">Show Content</a>
                        <?php } else { ?>
                            <a class="btn copy-link" href="<?=(empty($_SERVER['HTTPS']) ? 'http' : 'https')."://$_SERVER[HTTP_HOST]/?id=$link"?>">Show Content</a>
                        <?php } ?>

                    </div>
                <?php break; 
                
                case 'result':
                    ?>
                    <h1><a href="/"><?=SITE_TITLE;?></a></h1>
                    <div class="form">
                        <textarea readonly class="content"><?=$paste['content'];?></textarea>

                        <input class="btn copy-content" type="submit" value="Copy"/>
                    </div>
                <?php break; 
                
                case 'error': 
                ?>
                    <h1><a href="/"><?=SITE_TITLE;?></a></h1>
                    <div class="form">
                        Paste Not Found!
                    </div>
                <?php break; 
             } ?>
            
        </div>


        <script src="/assets/script.js"></script>
    </body>
</html>