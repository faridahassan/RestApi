<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Thread
 *
 * @author Farida
 */
namespace BackendBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as BaseThread;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
/**
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"message"})
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\User")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     * @Groups({"message"})
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="BackendBundle\Entity\Message",
     *   mappedBy="thread"
     * )
     * @Groups({"message"})
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="BackendBundle\Entity\ThreadMetadata",
     *   mappedBy="thread",
     *   cascade={"all"}
     * )
     * 
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * Remove message
     *
     * @param \BackendBundle\Entity\Message $message
     */
    public function removeMessage(\BackendBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Add metadatum
     *
     * @param \BackendBundle\Entity\ThreadMetadata $metadatum
     *
     * @return Thread
     */
    public function addMetadatum(\BackendBundle\Entity\ThreadMetadata $metadatum)
    {
        $this->metadata[] = $metadatum;

        return $this;
    }

    /**
     * Remove metadatum
     *
     * @param \BackendBundle\Entity\ThreadMetadata $metadatum
     */
    public function removeMetadatum(\BackendBundle\Entity\ThreadMetadata $metadatum)
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
