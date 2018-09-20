<?php
/**
 * @var $app            Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables
 * @var $view           Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine
 * @var $slotsHelper    ToolboxBundle\Helper\SlotsHelper
 * @var $routerHelper   Symfony\Bundle\FrameworkBundle\Templating\Helper\RouterHelper
 * @var $formHelper     Symfony\Bundle\FrameworkBundle\Templating\Helper\FormHelper
 *
 * @var $userForm       Symfony\Component\Form\Form
 * @var $userFormView   Symfony\Component\Form\FormView
 *
 * @var $newUser            boolean
 * @var $insertOldPassword  boolean
 * @var $save               boolean
 */

$slotsHelper    = $view['slots'];
$routerHelper   = $view['router'];
$formHelper     = $view['form'];

$userFormView   = $userForm->createView();

$isAdmin        = $app->getSecurity()->isGranted(\UserBundle\Entity\Role::ROLE_ADMIN);

$view->extend('::loggedIn.html.php');
$formHelper->setTheme($userFormView, ':Form');

?>

<?php $slotsHelper->start('title'); ?>
User <?php if($newUser): ?>anlegen<?php else: ?>editieren<?php endif; ?>
<?php $slotsHelper->stop(); ?>

<?php $slotsHelper->start('content') ?>
<?php if($save === true): ?>
    <div class="row">
        <div class="col-sm-offset-3 col-sm-7 col-md-6">
            <div class="alert alert-info">
                <p><b>Speichern erfolgreich!</b></p>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
echo $formHelper->start( $userFormView );
echo $formHelper->rest(  $userFormView );
echo $formHelper->end(   $userFormView );
?>
<?php $slotsHelper->stop() ?>