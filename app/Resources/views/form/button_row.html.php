<?php
/* @var $view Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper */
/* @var $form       Symfony\Component\Form\FormView */

/* @var $name       string */
/* @var $id         string */

$formHelper = $view['form'];

$class = (!isset($attr['class'])) ? 'col-sm-9' : $attr['class'];

if (!$label) {
    $label = isset($label_format)
        ? strtr($label_format, array('%name%' => $name, '%id%' => $id))
        : $formHelper->humanize($name);
}
?>

<div class="form-group">
    <div class="<?php echo $class; ?>">
        <div class="clearfix">
            <div class=""></div>
            <button class="btn btn-primary pull-right" type="<?php echo isset($type) ? $view->escape($type) : 'submit' ?>" <?php echo $formHelper->block($form, 'button_attributes') ?>><?php echo $label; ?></button>
        </div>
    </div>
</div>
