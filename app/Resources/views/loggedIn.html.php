<?php
/**
 * User: Daniel
 * Date: 16.11.2015
 * Time: 11:18
 */

/**
 * @var $app            Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables
 * @var $view           Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine
 * @var $slotsHelper    \ToolboxBundle\Helper\SlotsHelper
 * @var $routerHelper   Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper
 */

$slotsHelper = $view['slots'];
$routerHelper = $view['router'];
$formHelper = $view['form'];

$isAdmin = $app->getSecurity()->isGranted(\UserBundle\Entity\Role::ROLE_ADMIN);

$view->extend('::base.html.php');
?>

<?php $slotsHelper->append('header-js'); ?>
    <script src="/js/moment.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>
    <!-- locale 'de_DE' for datepicker -->
    <script src="/js/bootstrap-datepicker.de.js"></script>
    <script src="/js/chosen.js"></script>
<?php $slotsHelper->stop(); ?>

<?php $slotsHelper->append('header-css'); ?>
    <link href="/css/chosen.css" rel="stylesheet" />
    <!-- chosen theme for bootstrap 3 -->
    <link href="/css/chosen-bootstrap.css" rel="stylesheet" />
    <link href="/css/datepicker.css" rel="stylesheet" />

    <!-- some styles should be overwritten for this system -->
    <link href="/css/override-bootstrap.css" rel="stylesheet" />
    <link href="/css/override-datepicker.css" rel="stylesheet" />

    <link href="/css/theme.css" rel="stylesheet" />
<?php $slotsHelper->stop(); ?>

<?php $slotsHelper->append('header'); ?>
<div class="col-xs-<?php echo $isAdmin ? 8 : 9; ?>">
    <div class="row">
        <div class="col-xs-4">
            <a href="<?php echo $routerHelper->generate('education_calendar_overview'); ?>">
                <h1 class="text-center"><i class="glyphicon glyphicon-calendar"></i></h1>
            </a>
        </div>
        <div class="col-xs-4">
            <a href="<?php echo $routerHelper->generate('subject_select_class'); ?>">
                <h1 class="text-center"><i class="glyphicon glyphicon-music"></i></h1>
            </a>
        </div>
        <div class="col-xs-4">
            <a href="<?php echo $routerHelper->generate('user_index'); ?>">
                <h1 class="text-center"><i class="glyphicon glyphicon-user"></i></h1>
            </a>
        </div>
    </div>
</div>
<div class="col-xs-<?php echo $isAdmin ? 4 : 3; ?>">
    <div class="row">
        <?php if($isAdmin): ?>
            <div class="col-xs-6">
                <a href="<?php echo $routerHelper->generate('education_class'); ?>">
                    <h1 class="text-center"><i class="glyphicon glyphicon-education"></i></h1>
                </a>
            </div>
        <?php endif; ?>
        <div class="col-xs-<?php echo $isAdmin ? 6 : 12; ?>">
            <a href="<?php echo $routerHelper->generate('user_login'); ?>">
                <h1 class="text-center"><i class="glyphicon glyphicon-off"></i></h1>
            </a>
        </div>
    </div>
</div>
<?php $slotsHelper->stop(); ?>

<?php $slotsHelper->append('footer'); ?>
<?php if (in_array($app->getEnvironment(), array('dev', 'test'))): ?>
    <div class="col-xs-12 text-center">
        <span class="visible-lg pull-left">lg&nbsp;</span>
        <span class="visible-md pull-left">md&nbsp;</span>
        <span class="visible-sm pull-left">sm&nbsp;</span>
        <span class="visible-xs pull-left">xs&nbsp;</span>
        <span id="screen-width" class="pull-left"></span>
        <script>
            $(document).on('ready', function() {
                $(window).resize(function() {
                    $('#screen-width').html(window.innerWidth);
                }).trigger('resize');
            })
        </script>
    </div>
<?php endif; ?>
<?php $slotsHelper->stop(); ?>

<script>
    <?php $slotsHelper->append('jQuery'); ?>
    (function(document, $) {

        function chosenOptionalValue()
        {
            if($(this).hasClass('no-chosen')) {
                $(this).removeClass('chosen-select');
                return;
            }
            var options = {
                    max_selected_options:       1,
                    disable_search_threshold:   0,
                    width:                      '100%',
                    no_results_text: "[Enter] für neuen Eintrag"
                },

                $input = $(this);

            $input.chosen(options)
                .parent()
                .on('keydown', function(event) {
                    if(event.keyCode === 13) {
                        var $defaultInput = $(this).find('input');

                        $input.find('.optional').remove();

                        $input.append('<option value="" class="optional" selected="selected">'+ $defaultInput.val() +'</option>');

                        $input.trigger('chosen:updated');
                    }
                });

            $input
                .on('change init', function() {
                    var $chosenSingle = $(this).parent().find('.chosen-single');

                    $chosenSingle.removeClass('placeholder');

                    if($(this).find('option:selected').hasClass('placeholder') === true) {
                        $chosenSingle.addClass('placeholder');
                    }
                }).trigger('init');
        }

        window.refreshChosen = function() {
            $('.chosen, .chosen-select').each(chosenOptionalValue);
        };

        $( document ).ready(refreshChosen);
    })(document, jQuery);
    <?php $slotsHelper->stop(); ?>
</script>
