<?php

namespace AdminBundle\Entity;

use Symfony\Component\HttpFoundation\ParameterBag;

class Search
{
    /**
     * @var int $language
     */
    private $language;
    /**
     * @var string $word
     */
    private $word;
    /**
     * @var $offset
     */
    private $offset;
    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }
    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }
    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }
    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }
    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
    /**
     * @param ParameterBag $request
     * @return Search
     */
    public static function createFromRequest(ParameterBag $request)
    {
        $search = new Search();

        $search->setLanguage($request->get('language'));
        $search->setWord($request->get('word'));
        $search->setOffset($request->get('offset'));

        return $search;
    }
}