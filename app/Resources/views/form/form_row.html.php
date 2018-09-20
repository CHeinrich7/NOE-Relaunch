<?php

/**
 * @var $view       Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine
 * @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper
 * @var $form       Symfony\Component\Form\FormView
 * @var $label      string
 * @var $errors     Symfony\Component\Form\FormErrorIterator
 *
 */

$class = (!isset($attr['class'])) ? 'col-sm-6' : $attr['class'];
$formHelper = $view['form'];
?>
<div class="form-group <?php if(count($errors)): ?>has-error<?php endif; ?>">
    <?php if($form->vars['label'] !== false): ?>
        <?php echo $formHelper->label($form, $label) ?>
    <?php endif; ?>
    <div class="<?php echo $class; ?>">
        <div class="clearfix">
            <?php echo $formHelper->widget($form) ?>
        </div>
        <div class="fieldInfo">
            <?php echo $formHelper->errors($form) ?>
        </div>
    </div>
</div>
