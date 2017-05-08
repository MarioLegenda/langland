<?php

namespace AdminBundle\Listener;

class DoctrineMappingListener
{
    public function onMapping($event)
    {
        var_dump(get_class($event));
        die();
    }
}