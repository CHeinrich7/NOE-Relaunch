<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment_chain")
 */
class CommentChain extends Comment
{
    const COMMENT_TYPE = self::COMMENT_TYPE_CHAIN;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="childComments")
     * @ORM\JoinColumn(name="parent_comment_id", referencedColumnName="id", nullable=true)
     *
     * @var Comment
     */
    protected $parentComment;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parentComment")
     *
     * @var ArrayCollection|CommentChain[]
     */
    protected $childComments;


    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $depth;


    public function __construct()
    {
        $this->childComments = new ArrayCollection();
    }
}