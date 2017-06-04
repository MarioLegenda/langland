<?php

namespace AdminBundle\Event;

class PrePersistEvent extends MultipleEntityEvent
{
    const NAME = 'admin.pre_persist_event';
}