<?php

namespace AdminBundle\Controller;

interface GenericControllerInterface
{
    public function getListingTitle() : string;
    public function getName() : string;
}