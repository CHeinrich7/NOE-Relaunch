<?php
/* @var $view       Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper */
/* @var $form       Symfony\Component\Form\FormView */
/* @var $label      string */
$class = (!isset($attr['class'])) ? 'col-sm-6' : $attr['class'];
$formHelper = $view['form'];
?>
<div class="form-group">
    <?php echo $formHelper->label($form, $label) ?>
    <div class="<?php echo $class; ?>">
        <div class="clearfix">
            <?php echo $formHelper->widget($form) ?>
        </div>
        <div class="fieldInfo">
            <?php echo $formHelper->errors($form) ?>
        </div>
    </div>
</div>
