<?php

namespace AdminBundle\Event;

class PostPersistEvent extends MultipleEntityEvent
{
    const NAME = 'admin.post_persist_event';
}