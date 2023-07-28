<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Config.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 * @ORM\Table(
 *     name="`config`",
 *     indexes={
 *         @ORM\Index(name="config_feed_token", columns={"feed_token"}, options={"lengths"={255}}),
 *     }
 * )
 */
class Config
{
    public const REDIRECT_TO_HOMEPAGE = 0;
    public const REDIRECT_TO_CURRENT_PAGE = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"config_api"})
     */
    private $id;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 100000,
     *      maxMessage = "validator.item_per_page_too_high"
     * )
     * @ORM\Column(name="items_per_page", type="integer", nullable=false)
     *
     * @Groups({"config_api"})
     */
    private $itemsPerPage;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="language", type="string", nullable=false)
     *
     * @Groups({"config_api"})
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_token", type="string", nullable=true)
     *
     * @Groups({"config_api"})
     */
    private $feedToken;

    /**
     * @var int
     *
     * @ORM\Column(name="feed_limit", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 100000,
     *      maxMessage = "validator.feed_limit_too_high"
     * )
     *
     * @Groups({"config_api"})
     */
    private $feedLimit;

    /**
     * @var float
     *
     * @ORM\Column(name="reading_speed", type="float", nullable=true)
     *
     * @Groups({"config_api"})
     */
    private $readingSpeed;

    /**
     * @var string
     *
     * @ORM\Column(name="pocket_consumer_key", type="string", nullable=true)
     */
    private $pocketConsumerKey;

    /**
     * @var int
     *
     * @ORM\Column(name="action_mark_as_read", type="integer", nullable=true, options={"default" = 0})
     *
     * @Groups({"config_api"})
     */
    private $actionMarkAsRead;

    /**
     * @var int
     *
     * @ORM\Column(name="list_mode", type="integer", nullable=true)
     *
     * @Groups({"config_api"})
     */
    private $listMode;

    /**
     * @var int
     *
     * @ORM\Column(name="display_thumbnails", type="integer", nullable=true, options={"default" = 1})
     *
     * @Groups({"config_api"})
     */
    private $displayThumbnails;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="config")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TaggingRule", mappedBy="config", cascade={"remove"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $taggingRules;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\IgnoreOriginUserRule", mappedBy="config", cascade={"remove"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $ignoreOriginRules;

    /*
     * @param User     $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->taggingRules = new ArrayCollection();
        $this->ignoreOriginRules = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set itemsPerPage.
     *
     * @param int $itemsPerPage
     *
     * @return Config
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * Get itemsPerPage.
     *
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Set language.
     *
     * @param string $language
     *
     * @return Config
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return Config
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set feed Token.
     *
     * @param string $feedToken
     *
     * @return Config
     */
    public function setFeedToken($feedToken)
    {
        $this->feedToken = $feedToken;

        return $this;
    }

    /**
     * Get feedToken.
     *
     * @return string
     */
    public function getFeedToken()
    {
        return $this->feedToken;
    }

    /**
     * Set Feed Limit.
     *
     * @param int $feedLimit
     *
     * @return Config
     */
    public function setFeedLimit($feedLimit)
    {
        $this->feedLimit = $feedLimit;

        return $this;
    }

    /**
     * Get Feed Limit.
     *
     * @return int
     */
    public function getFeedLimit()
    {
        return $this->feedLimit;
    }

    /**
     * Set readingSpeed.
     *
     * @param float $readingSpeed
     *
     * @return Config
     */
    public function setReadingSpeed($readingSpeed)
    {
        $this->readingSpeed = $readingSpeed;

        return $this;
    }

    /**
     * Get readingSpeed.
     *
     * @return float
     */
    public function getReadingSpeed()
    {
        return $this->readingSpeed;
    }

    /**
     * Set pocketConsumerKey.
     *
     * @param string $pocketConsumerKey
     *
     * @return Config
     */
    public function setPocketConsumerKey($pocketConsumerKey)
    {
        $this->pocketConsumerKey = $pocketConsumerKey;

        return $this;
    }

    /**
     * Get pocketConsumerKey.
     *
     * @return string
     */
    public function getPocketConsumerKey()
    {
        return $this->pocketConsumerKey;
    }

    /**
     * @return int
     */
    public function getActionMarkAsRead()
    {
        return $this->actionMarkAsRead;
    }

    /**
     * @param int $actionMarkAsRead
     *
     * @return Config
     */
    public function setActionMarkAsRead($actionMarkAsRead)
    {
        $this->actionMarkAsRead = $actionMarkAsRead;

        return $this;
    }

    /**
     * @return int
     */
    public function getListMode()
    {
        return $this->listMode;
    }

    /**
     * @param int $listMode
     *
     * @return Config
     */
    public function setListMode($listMode)
    {
        $this->listMode = $listMode;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDisplayThumbnails(): ?bool
    {
        return $this->displayThumbnails;
    }

    /**
     * @return Config
     */
    public function setDisplayThumbnails(bool $displayThumbnails)
    {
        $this->displayThumbnails = $displayThumbnails;

        return $this;
    }

    /**
     * @return Config
     */
    public function addTaggingRule(TaggingRule $rule)
    {
        $this->taggingRules[] = $rule;

        return $this;
    }

    /**
     * @return ArrayCollection<TaggingRule>
     */
    public function getTaggingRules()
    {
        return $this->taggingRules;
    }

    /**
     * @return Config
     */
    public function addIgnoreOriginRule(IgnoreOriginUserRule $rule)
    {
        $this->ignoreOriginRules[] = $rule;

        return $this;
    }

    /**
     * @return ArrayCollection<IgnoreOriginUserRule>
     */
    public function getIgnoreOriginRules()
    {
        return $this->ignoreOriginRules;
    }
}
