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
        const lessonList = () => <LessonList learningUserCourseId={this.props.learningUserCourseId}/>;
        console.log(this.props.mainPath);
        console.log(window.location.pathname);
        return (

            <Router>
                <div className="animated fadeInDown big-component">
                    <MethodNavigation learningUserCourseId={this.props.learningUserCourseId}/>

                    <div className="main-app-dashboard">
                        <Switch>
                            <Route path={envr + this.props.mainPath + "/lesson/lessons/:learningUserCourseId"} render={lessonList} />
{/*
                            <Route path={envr + this.props.mainPath + "/lessons/lesson/:learningUserLessonId"} component={LessonDashboard} />
*/}
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
        const mainPath = envr + 'langland/language-course/dashboard';
        const learningUserCourseId = this.props.match.params.learningUserCourseId;

        return (
            <MethodApp mainPath={mainPath} learningUserCourseId={learningUserCourseId}/>
        )
    }
}