import React from 'react';
import {RouteCreator} from './../routes.js';

class LessonText extends React.Component {
    constructor(props) {
        super(props);

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
    }

    _createMarkup(html) {
        return {__html: html};
    }

    next() {
        this.props.next();
    }

    prev() {
        this.props.prev();
    }

    render() {
        const item = this.props.item;

        return (
            <div>
                <div className="full-width align-left">
                    <h1 className="full-width align-right margin-top-10 margin-bottom-10">{item.name}</h1>

                    <div className="full-width align-right margin-bottom-30" dangerouslySetInnerHTML={this._createMarkup(item.text)}>
                    </div>
                </div>

                <div className="full-width align-left directional-buttons">
                    <button className="direction-button next" onClick={this.next}>Next</button>
                    <button className="direction-button prev" onClick={this.prev}>Previous</button>
                </div>
            </div>
        )
    }
}

class LessonDashboard extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            counter: 0
        };

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
    }

    _markLessonFinished() {
        console.log('Lesson marked as finished');
    }

    next() {
        let counter = this.state.counter + 1;

        if (counter >= this.props.item.lessonText.length) {
            this._markLessonFinished();

            return null;
        }

        this.setState({
            counter: counter
        });
    }

    prev() {
        const counter = this.state.counter - 1;

        if (counter < 0) {
            return null;
        }

        this.setState({
            counter: counter
        });
    }

    render() {
        const
            item = this.props.item,
            currentItem = item.lessonText[this.state.counter];

        return (
            <div className="animated fadeInDown full-width align-left lesson-dashboard">
                <span className="lesson-name">{item.name} <i className="fa fa-mortar-board"></i></span>
                <LessonText
                    item={currentItem}
                    next={this.next}
                    prev={this.prev}
                />
            </div>
        )
    }
}

export class LessonDashboardContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            item: null
        };

        this.learningUserLessonId = this.props.match.params.learningUserLessonId;
    }

    _fetchLesson() {
        jQuery.ajax({
            url: RouteCreator.create('app_learning_user_lesson', [this.learningUserLessonId]),
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            if (data.status === 'success') {
                this.setState({
                    item: data.data
                });
            }
        }, this));
    }

    componentDidMount() {
        this._fetchLesson();
    }

    render() {
        if (this.state.item === null) {
            return null;
        }

        const item = this.state.item.lesson;

        return (
            <LessonDashboard item={item}/>
        )
    }
}