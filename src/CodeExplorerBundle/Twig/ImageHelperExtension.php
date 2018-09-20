<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeExplorerBundle\Twig;
use AppBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Templating\EngineInterface;

/**
 * CAUTION: this is an extremely advanced Twig extension. It's used to get the
 * source code of the controller and the template used to render the current
 * page. If you are starting with Symfony, don't look at this code and consider
 * studying instead the code of the src/AppBundle/Twig/AppExtension.php extension.
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class ImageHelperExtension extends \Twig_Extension
{
    /**
     * @var EngineInterface
     */
    private $renderEngine;

    private $defaults = [
        'src'   => null,
        'title' => null,
        'width' => null,
        'height'=> null,
        'class' => null,
        'style' => null
    ];

    private $requiredDefaults = [ 'src', 'title', 'width', 'height' ];

    public function __construct(DelegatingEngine $renderEingine)
    {
        $this->renderEngine = $renderEingine;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('image_helper', [$this, 'getImageTag']),
        ];
    }

    /**
     * @return OptionsResolver
     */
    public function getResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->defaults);
        $resolver->setRequired($this->requiredDefaults);
        return $resolver;
    }

    public function getImageTag(Image $image, array $options = array()):string
    {
        $fileRatio = $image->getFileRatio();

        if($fileRatio['width'] >= $fileRatio['height']) {
            $class = 'landscape';
        } else {
            $class = 'portrait';
        }

        if(array_key_exists('class', $options)) {
            $options['class'] .= ' ' . $class;
        } else {
            $options['class'] = $class;
        }

        $options = array_merge($fileRatio, $options);

        $imageResolver = $this->getResolver();
        $resolvedOptions  = $imageResolver->resolve( $options );

        return $this->renderEngine->render(':helper:image.html.twig', ['img' => $resolvedOptions]);
    }

    // the name of the Twig extension must be unique in the application
    public function getName()
    {
        return 'image_helper';
    }
}
