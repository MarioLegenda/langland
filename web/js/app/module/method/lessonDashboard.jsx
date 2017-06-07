import React from 'react';
import {RouteCreator} from './../routes.js';

class LessonDashboard extends React.Component {
    constructor(props) {
        super(props);

        console.log(this.props.item);
    }

    render() {
        return (
            <div></div>
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

        const item = this.state.item;

        return (
            <LessonDashboard item={item}/>
        )
    }
}