import React from 'react';
import ReactDOM from 'react-dom';

import {symfonyCollection} from "./lib/symfonyCollection";

import {Lesson} from './lesson/lesson.jsx';

symfonyCollection();

const isLesson = document.getElementById('react_lesson_form');

if (isLesson !== null) {
    ReactDOM.render(
        <Lesson/>,
        document.getElementById('react_lesson_form')
    );
}

/*
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

const gameApp = document.getElementById('react-new-game');

if (gameApp !== null) {
    ReactDOM.render(
        <GameCreateInit dataSource={DataSource}/>,
        gameApp
    );
}*/








