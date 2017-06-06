import React from 'react';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';
import {envr} from './../env.js';

import {MethodNavigation} from './methodNavigation.jsx';
import {LessonListContainer as LessonList} from './lessonList.jsx';

import {LessonDashboard} from './lessonDashboard.jsx';

class MethodApp extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        console.log(this.props);

        const courseName = this.props.match.params.courseName;
        const learningUserCourseId = this.props.match.params.learningUserCourseId;
        const mainPath = this.props.match.url;

        const lessonList = () => <LessonList learningUserCourseId={learningUserCourseId}/>;
        return (
            <Router>
                <div className="animated fadeInDown big-component">
                    <MethodNavigation
                        courseName={courseName}
                        learningUserCourseId={learningUserCourseId}/>

                    <div className="main-app-dashboard">
                        <Switch>
                            <Route path={mainPath + "/lessons"} render={lessonList} />
                        </Switch>
                    </div>
                </div>
            </Router>
        )
    }
}

export class MethodAppRouteContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <MethodApp match={this.props.match} />
        )
    }
}