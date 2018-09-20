<?php
/* @var $view Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine  */
/* @var $form       Symfony\Component\Form\FormView */

/* @var $action     string */
/* @var $multipart  boolean */

$attr['class'] = (!isset($attr['class'])) ? 'form-horizontal' : $attr['class'];
?>

<?php $method = strtoupper($method) ?>
<?php $form_method = $method === 'GET' || $method === 'POST' ? $method : 'POST' ?>
<form <?php echo (!isset($attr['id']) && isset ($form->vars['id'])?'id="' . $form->vars['id'] . '" ':'');?>
    <?php echo (!isset($attr['name']) && isset ($form->vars['name'])?'name="' . $form->vars['name'] . '" ':'');?>
    method="<?php echo strtolower($form_method) ?>" action="<?php echo $action ?>"
    <?php foreach ($attr as $k => $v) {
        printf(' %s="%s"', $view->escape($k), $view->escape($v));
        } ?><?php if ($multipart): ?> enctype="multipart/form-data"<?php endif ?>>
    <?php if ($form_method !== $method): ?>
        <input type="hidden" name="_method" value="<?php echo $method ?>"/>
    <?php endif ?>
