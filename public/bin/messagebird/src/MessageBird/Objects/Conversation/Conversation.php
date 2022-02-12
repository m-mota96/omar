<?php

namespace MessageBird\Objects\Conversation;

use MessageBird\Objects\Base;

/**
 * A conversation is the view of all messages between you and a customer across
 * any of your configured channels. Messages from multiple channels are
 * collected and displayed in a single thread. A conversation status can be
 * active or archived, but only one active conversation exists for each
 * customer at a time. If a message is received from a customer with no active
 * conversations, a new one will be created automatically.
 */
class Conversation extends Base
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * A unique ID generated by the MessageBird platform that identifies the
     * conversation.
     *
     * @var string
     */
    public $id;

    /**
     * The URL of this conversation object.
     *
     * @var string
     */
    public $href;

    /**
     * The expanded contact for this conversation.
     *
     * @var Contact
     */
    public $contact;

    /**
     * Associative array of Channels that the contact of this conversation can
     * be reached on.
     *
     * @var Channel[]
     */
    public $channels;

    /**
     * The status of this conversation. This can be either 'active' or
     * 'archived'.
     *
     * @var string
     */
    public $status;

    /**
     * Object that references the messages and the count belonging to this
     * conversation.
     *
     * @var MessageReference
     */
    public $messages;

    /**
     * Unique ID that references the last channel used for this conversation.
     *
     * @var string
     */
    public $lastUsedChannelId;

    /**
     * The date and time when the most recent message was added to this
     * conversation in RFC3339 format.
     *
     * @var string
     */
    public $lastReceivedDatetime;

    /**
     * The date and time when this conversation was first created in RFC3339
     * format.
     *
     * @var string
     */
    public $createdDatetime;

    /**
     * The date and time when this conversation was most recently updated in
     * RFC3339 format. This applies only to changes of the Conversation object
     * itself, not messages, i.e. currently just status changes.
     *
     * @var string
     */
    public $updatedDatetime;

    /**
     * @param mixed $object
     */
    public function loadFromArray($object): Conversation
    {
        parent::loadFromArray($object);

        if (!empty($this->contact)) {
            $newContact = new Contact();
            $newContact->loadFromArray($this->contact);

            $this->contact = $newContact;
        }

        if (!empty($this->channels)) {
            $channels = [];

            foreach ($this->channels as $channel) {
                $newChannel = new Channel();
                $newChannel->loadFromArray($channel);

                $channels[] = $newChannel;
            }

            $this->channels = $channels;
        }

        if (!empty($this->messages)) {
            $messages = new MessageReference();
            $messages->loadFromArray($this->messages);

            $this->messages = $messages;
        }

        return $this;
    }
}
