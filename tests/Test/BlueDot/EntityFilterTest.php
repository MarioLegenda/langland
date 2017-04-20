<?php

namespace Test\BlueDot;

use BlueDot\Entity\Entity;
use Library\Tests\BlueDotTestCase;

class EntityFilterTest extends BlueDotTestCase
{
    public function testExtractColumn()
    {
        $this->blueDot->api()->putAPI(__DIR__.'/config/_test_filters.yml');
        $this->blueDot->useApi('_test_filters');

        $entity = $this->blueDot->execute('simple.select.find_all_languages_array')->getResult();

        $languages = $entity->extractColumn('language');

        $this->assertArrayHasKey('language', $languages, '$languages array does not contain a key \'language\'');

        $languages = $entity->extractColumn('language', 'languages');

        $this->assertArrayHasKey('languages', $languages, '$languages array does not contain a key \'languages\'');

        $entity = $this->blueDot->execute('simple.select.find_all_languages_object')->getResult();

        $languages = $entity->extractColumn('language');

        $this->assertArrayHasKey('language', $languages, '$languages array does not contain a key \'language\'');

        $languages = $entity->extractColumn('language', 'languages');

        $this->assertArrayHasKey('languages', $languages, '$languages array does not contain a key \'languages\'');
    }

    public function testFind()
    {
        $this->blueDot->api()->putAPI(__DIR__.'/config/_test_filters.yml');
        $this->blueDot->useApi('_test_filters');

        $entity = $this->blueDot->execute('simple.select.find_all_languages_array')->getResult();

        $language = (new Entity($entity->find('id', 6)))->normalizeIfOneExists();

        $this->assertInternalType('int', (int) $language['id'], 'Language id column should be an integer');
        $this->assertInternalType('string', $language['language'], 'Language language column should be a string');

        $entity = $this->blueDot->execute('simple.select.find_all_languages_object')->getResult();

        $language = $entity->find('id', 6);

        $this->assertInternalType('int', (int) $language->getId(), 'Language id column should be an integer');
        $this->assertInternalType('string', $language->getLanguage(), 'Language language column should be a string');
    }

    public function testFindBy()
    {
        $this->blueDot->api()->putAPI(__DIR__.'/config/_test_filters.yml');
        $this->blueDot->useApi('_test_filters');

        $entity = $this->blueDot->execute('simple.select.find_all_languages_array')->getResult();

        $language = (new Entity($entity->findBy('id', 6)))->normalizeIfOneExists();

        $this->assertInternalType('int', (int) $language['id'], 'Language id column should be an integer');
        $this->assertInternalType('string', $language['language'], 'Language language column should be a string');

        $entity = $this->blueDot->execute('simple.select.find_all_languages_object')->getResult();

        $language =$entity[0];

        $this->assertInternalType('int', (int) $language->getId(), 'Language id column should be an integer');
        $this->assertInternalType('string', $language->getLanguage(), 'Language language column should be a string');
    }

    public function testNormalizeJoinedResult()
    {
        $this->blueDot->api()->putAPI(__DIR__.'/config/_test_filters.yml');
        $this->blueDot->useApi('_test_filters');

        $entity = $this->blueDot->execute('simple.select.find_word_translations_simple', array(
            'word_id' => 3,
        ))->getResult();

        $words = $entity->normalizeJoinedResult(array(
            'linking_column' => 'id',
            'columns' => array('translation')
        ));

        $this->assertNotEmpty($words, '$words cannot be empty from filter Entity::normalizeJoinedResult()');

        $entity = $this->blueDot->execute('scenario.find_words', array(
            'find_working_language' => array(
                'user_id' => 1,
            ),
        ))->getResult();

        $words = $entity->normalizeJoinedResult(array(
            'linking_column' => 'id',
            'columns' => array('category', 'translation'),
        ), 'select_all_words');

        $this->assertNotEmpty($words, '$words cannot be empty from filter Entity::normalizeJoinedResult()');
    }
}