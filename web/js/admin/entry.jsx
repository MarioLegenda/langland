( function($) {

    var $collectionHolder;

// setup an "add a tag" link
    var $addTagLink = $('<a href="#" class="add_tag_link">Add new ... </a>');
    var $newLinkLi = $('<div class="margin-top-30"></div>').append($addTagLink);

    $(document).ready(function() {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('div.translations');

        $collectionHolder.find('.translation').each(function() {
            addTagFormDeleteLink($(this));
        });

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addTagForm($collectionHolder, $newLinkLi);
        });
    });

    function addTagForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<div class="margin-bottom-10"></div>').append(newForm);
        $newLinkLi.before($newFormLi);
        addTagFormDeleteLink($newFormLi);
    }

    function addTagFormDeleteLink($tagFormLi) {
        var $removeFormA = $('<div class="margin-top-10"><a class="margin-top-10 text-highlight" href="#">Remove ... </a></div>');
        $tagFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $tagFormLi.remove();
        });
    }
} (jQuery) );

import React from 'react';
import ReactDOM from 'react-dom';
import {GameInit} from './game/gameInit.jsx';

import {Autocomplete} from './autocomplete.jsx';
import {LessonApp} from './lesson.jsx';
import {GameListSelection} from './game/gameListSelection.jsx';

const acWidget = document.getElementById('word-pool-widget');

if (acWidget !== null) {
    let elem = $('input[data-autocomplete-widget]');
    const buttonName = (/create/.test(location.pathname) ? 'Create' : 'Edit');

    ReactDOM.render(
        React.createElement(
            Autocomplete,
            {
                jQuery: jQuery,
                elem: elem,
                buttonName: buttonName,
                url: 'admin/word/search/'
            }
        ),
        acWidget
    );
}

const lessonApp = document.getElementById('react-lesson-app');

if (lessonApp !== null) {
    ReactDOM.render(
        <LessonApp/>,
        lessonApp
    );
}

const gameApp = document.getElementById('react-create-game-app');

if (gameApp !== null) {
    ReactDOM.render(
        <GameInit/>,
        gameApp
    );
}

const gameSelection = document.getElementById('react-game-list-selection');

if (gameSelection !== null) {
    ReactDOM.render(
        <GameListSelection/>,
        gameSelection
    );
}








