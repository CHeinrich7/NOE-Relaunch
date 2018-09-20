<?php
namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class UserDataLoader to load FileContent of File in Dir of Class
 *
 * @package UserBundle\DataFixtures\ORM
 */
abstract class UserDataLoader extends AbstractFixture {

    /**
     * @param string $filename
     *
     * @return string|false
     */
    protected function getFileContent($filename)
    {
        $finder = new Finder();

        $files = $finder->in(__DIR__);

        foreach($files as $file) /* @var $file SplFileInfo */
        {
            if($file->getFilename() == $filename) {
                return $file->getContents();
            }
        }

        return false;
    }
}