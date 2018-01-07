<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Message
 *
 * @author Farida
 */
namespace BackendBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity
 */
class Message extends BaseMessage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"message"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="BackendBundle\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @var \FOS\MessageBundle\Model\ThreadInterface
     * @Groups({"message"})
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     * @Groups({"message"})
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="BackendBundle\Entity\MessageMetadata",
     *   mappedBy="message",
     *   cascade={"all"}
     * )
     * 
     * @var MessageMetadata[]|\Doctrine\Common\Collections\Collection
     * 
     */
    protected $metadata;

    /**
     * Add metadatum
     *
     * @param \BackendBundle\Entity\MessageMetadata $metadatum
     *
     * @return Message
     */
    public function addMetadatum(\BackendBundle\Entity\MessageMetadata $metadatum)
    {
        $this->metadata[] = $metadatum;

        return $this;
    }

    /**
     * Remove metadatum
     *
     * @param \BackendBundle\Entity\MessageMetadata $metadatum
     */
    public function removeMetadatum(\BackendBundle\Entity\MessageMetadata $metadatum)
    {
        $this->metadata->removeElement($metadatum);
    }

    /**
     * Get metadata
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
