<?php
/**
 * @var $view       Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine
 * @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper
 * @var $form       Symfony\Component\Form\FormView
 * @var $label      string
 * @var $value      string
 * @var $errors     Symfony\Component\Form\FormErrorIterator
 */

$formHelper = $view['form'];

$widgetClass = 'form-control ';

if(isset($attr['class-widget'])) $widgetClass .= $attr['class-widget'];
$realType   = isset($type) ? $view->escape($type) : 'text';
$finalType  = (isset($attr['force_type'])) ? $attr['force_type'] : $realType;

if(count($errors)) {
    $widgetClass .= ' no-border-bottom-radius';
}

?>

<?php if(isset($attr['icon-widget'])): ?>
    <div class="input-group">
    <span class="input-group-addon"><span class="glyphicon glyphicon-<?php echo $attr['icon-widget']; ?>"></span></span>
<?php endif; ?>
    <input type="<?php echo $finalType; ?>" class="<?php echo $widgetClass; ?>" value="<?php echo $view->escape($value); ?>" <?php echo $formHelper->block($form, 'widget_attributes'); ?> />
<?php if(isset($attr['icon-widget'])): ?>
    </div>
<?php endif; ?>