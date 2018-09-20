<?php
/* @var $view       Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper */
/* @var $form       Symfony\Component\Form\FormView */

$formHelper = $view['form'];
$parameters = array();
$parameters['attr']['class'] = 'form-control no-padding text-center';
?>
<div class="form-group">
    <div class="col-xs-4">
        <?php echo $formHelper->widget($form['day'],$parameters); ?>
    </div>
    <div class="col-xs-4 no-padding-left">
        <?php echo $formHelper->widget($form['month'],$parameters); ?>
    </div>
    <div class="col-xs-4 no-padding-left">
        <?php echo $formHelper->widget($form['year'],$parameters); ?>
    </div>
</div>
