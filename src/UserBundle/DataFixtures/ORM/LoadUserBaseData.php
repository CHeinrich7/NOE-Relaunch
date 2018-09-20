<?php

namespace UserBundle\DataFixtures\ORM;


#use UserBundle\Entity\Profile;
#use UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\DependencyInjection\Container;
use UserBundle\Entity\User;

/**
 * Class LoadUserBaseData
 * @package UserBundle\DataFixtures\ORM
 */
class LoadUserBaseData extends UserDataLoader implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var string
     */
    protected $filename = 'sql.json';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer ( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $objectManager
     *
     * @throws FileNotFoundException
     */
    public function load(ObjectManager $objectManager)
    {
        /*$content = $this->getFileContent($this->filename);

        if($content === false) {
            throw new FileNotFoundException('File \'' . $this->filename . '\' cannot be found');
        }

        $sql = json_decode($content);

        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $connection = $em->getConnection();

        foreach($sql as $query) {
            $connection->executeQuery($query);
        }*/
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
} 