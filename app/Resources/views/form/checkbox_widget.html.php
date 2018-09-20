<?php
/* @var $view Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper */
/* @var $form       Symfony\Component\Form\FormView */
/* @var $id         string */

$formHelper = $view['form'];
?>

<?php
    $widgetAttr = $formHelper->block($form, 'widget_attributes');
    $arrWidgetAttr = explode('"', $widgetAttr);
    for($i = 0; $i < count($arrWidgetAttr); $i++)
    {
        if(in_array(trim($arrWidgetAttr[$i]), array('class=', 'class-label=')))
        {
            unset($arrWidgetAttr[$i]);
            unset($arrWidgetAttr[$i+1]);
            $i++;
        }
    }

    $widgetAttr = implode('"', $arrWidgetAttr);
?>
<input <?php echo $widgetAttr; ?> type="checkbox" class="hidden cmh-checkbox" autocomplete="off" checked="<?php echo $checked ? 'checked' : ''; ?>" />
<label for="<?php echo $id; ?>">
    <span class="glyphicon glyphicon-ok"></span>
</label>