import React from "react";
import { Link } from "react-router-dom";
import {env} from "../../../../global/constants.js";

import {
    store,
    lessonStarted, handleMenuHeight
} from "../../events/events";

export class Lesson extends React.Component {
    constructor(props) {
        super(props);

        this.enterLesson = this.enterLesson.bind(this);
        this.startLesson = this.startLesson.bind(this);
    }

    _makeClasses(item) {
        let classes = {
            'lesson-menu': 'not-available-lesson-menu-item',
            'circle-wrapper': 'not-available-circle-wrapper'
        };

        if (item.is_available === 1) {
            classes['lesson-menu'] = 'available-lesson-menu-item';
            classes['circle-wrapper'] = 'available-circle-wrapper';
        }

        return classes;
    }

    _handleMenuHeight() {
        const courseList = $('.course-list');

        let h = 0;
        courseList.each(function(index, item) {
            h += item.offsetHeight;
        });

        store.dispatch(handleMenuHeight(h));
    }

    _createPresentationItem(item) {
        const url = env.current + `langland/lesson/${item.urlified_name}/${item.learning_lesson_id}`;

        return <div className="animated fadeIn fadeOut presentation-item">
            <h1>{item.name}</h1>

            <p>{item.description}</p>

            <Link to={url} onClick={this.startLesson} className="learn-button">Learn <i className="learn-button-icon fa fa-angle-right"></i></Link>
        </div>;
    }

    startLesson() {
        store.dispatch(lessonStarted(true));
    }

    enterLesson(e) {
        e.preventDefault();

        if (this.props.item.is_available === 0) {
            console.log('Lesson is not available');
            return false;
        }

        const presentationItem = this._createPresentationItem(this.props.item);

        this.props.displayPresentationItem(presentationItem);

        this._handleMenuHeight();
    }

    render() {
        const item = this.props.item;
        const classes = this._makeClasses(item);
        const clickableMethod = (item.is_available === 1) ? this.enterLesson : null;

        return <div className="lesson-absolute-item-holder">
            <div className={classes['lesson-menu']}>
                <button className={classes['circle-wrapper']} onClick={clickableMethod}>
                    <span className="circle-wrapper-position lesson-text-wrapper">{item.name}</span>
                    {item.is_available === 0 &&
                        <span className="circle-wrapper-position lesson-icon-wrapper fa fa-lock"></span>
                    }
                </button>
            </div>
        </div>
    }
}