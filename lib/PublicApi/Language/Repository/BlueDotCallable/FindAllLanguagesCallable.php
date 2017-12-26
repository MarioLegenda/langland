<?php

namespace PublicApi\Language\Repository\BlueDotCallable;

use BlueDot\Common\AbstractCallable;

class FindAllLanguagesCallable extends AbstractCallable
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->blueDot->execute('simple.select.find_all_languages')->getResult();
    }
}