<?php
use Symfony\Component\Templating\Helper\SlotsHelper;
/* @var $view Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
$slotsHelper = $view['slots']; /* @var $slotsHelper SlotsHelper */
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.js"></script>
    <?php $slotsHelper->output('header-js') ?>


    <title><?php $slotsHelper->output('title') ?> - Noemi</title>

    <link href="/css/bootstrap.css" rel="stylesheet">

    <?php $slotsHelper->output('header-css') ?>
    <link href="/css/helper.css" rel="stylesheet">
    <?php $slotsHelper->output('styles', ''); ?>

    <script type="text/javascript"></script>

    <style>
        #header {
            border-bottom: 1px solid #eee;
            margin-bottom:20px;
        }

        #header .row > div {
            overflow: hidden;
        }

        #header a {
            text-decoration: none;
            color:           #337ab7;
            display:inline-block;
            width: 100%;
            height: 100%;
        }

        #header a:hover {
            background: #eee;
        }
    </style>
</head>
<body>
<div class="container">
    <div id="header" class="row">
        <?php $slotsHelper->output('header', ''); ?>
    </div>

    <?php $slotsHelper->output('content', ''); ?>

    <div id="footer" class="row" style="width:100%">
        <div class="container">
            <div class="row">
                <?php $slotsHelper->output('footer', '');  ?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    (function(document, $) {
        $( document ).ready(function() {
            <?php $slotsHelper->output('jQuery', ''); ?>
        });
    })(document, jQuery);
</script>
</body>
</html>