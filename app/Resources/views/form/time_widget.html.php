<?php
/* @var $view       Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper */
/* @var $form       Symfony\Component\Form\FormView */

$formHelper = $view['form'];
$parameters = array();
$parameters['attr']['class'] = 'form-control no-padding text-center';
?>
<div class="form-group">
    <div class="col-xs-offset-1 col-xs-4 no-padding-right">
        <?php echo $formHelper->widget($form['hour'],$parameters) ?>
    </div>
    <div class="col-xs-1">
        <p class="text-center">:</p>
    </div>
    <div class="col-xs-4 no-padding-left">
        <?php echo $formHelper->widget($form['minute'],$parameters) ?>
    </div>
</div>
