<?php
/* @var $view Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $formHelper Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper */
/* @var $form       Symfony\Component\Form\FormView */

$formHelper = $view['form'];

if (!isset($render_rest) || $render_rest) {
    $formHelper->rest($form);
}

//$formHelper->
?>
</form>
