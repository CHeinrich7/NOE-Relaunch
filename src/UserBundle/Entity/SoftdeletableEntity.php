<?php
/**
 * User: cheinrich
 * Date: 16.11.2015
 * Time: 13:21
 */

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @package UserBundle\Entity
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Gedmo\SoftDeleteable(fieldName="deletedBy")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
abstract class SoftdeletableEntity
{
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Constraints\DateTime
     */
    protected $createdAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     * @Constraints\DateTime
     */
    protected $modifiedAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     * @Gedmo\Blameable(on="update")
     */
    protected $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Constraints\DateTime
     */
    protected $deletedAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    protected $deletedBy;

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @return UserInterface
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * @return UserInterface
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }
}