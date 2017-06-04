<?php

namespace AdminBundle\Event;

class PreUpdateEvent extends MultipleEntityEvent
{
    const NAME = 'admin.pre_update_event';
}