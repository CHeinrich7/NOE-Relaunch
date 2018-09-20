<?php
/**
 * @var $view           Symfony\Bundle\FrameworkBundle\Templating\TimedPhpEngine
 * @var $app            Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables
 *
 * @var $errors         Symfony\Component\Form\FormErrorIterator
 */
?>

<?php if (count($errors) > 0): ?>
    <div class="alert alert-danger no-margin no-border-top-radius">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error->getMessage() ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif ?>

