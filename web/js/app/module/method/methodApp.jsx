import React from 'react';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {MethodNavigation} from './methodNavigation.jsx';
import {LessonListContainer as LessonList} from './lessonList.jsx';
import {LessonDashboardContainer} from './lessonDashboard.jsx';
import {SidebarHelperContainer} from './sidebarHelper/sidebarHelper.jsx';
import {GameListContainer} from './gamesList.jsx';

class MethodApp extends React.Component {
    constructor(props) {
        super(props);
    }

    _createComponents() {
        const courseName = this.props.match.params.courseName,
              learningUserCourseId = this.props.match.params.learningUserCourseId,
              mainPath = this.props.match.url;

        const lessonList = () => <LessonList
            courseName={courseName}
            learningUserCourseId={learningUserCourseId}
        />;

        const lessonDashboard = (match) => <LessonDashboardContainer
            courseName={courseName}
            learningUserCourseId={learningUserCourseId}
            match={match.match}
            io={this.props.io}
        />;

        const sidebarHelper = () => <SidebarHelperContainer learningUserCourseId={learningUserCourseId}/>;

        return {
            lessonList: lessonList,
            lessonDashboard: lessonDashboard,
            sidebarHelper: sidebarHelper
        }
    }

    render() {
        const components = this._createComponents();

        const courseName = this.props.match.params.courseName,
            learningUserCourseId = this.props.match.params.learningUserCourseId,
            mainPath = this.props.match.url;

        return (
            <div className="animated fadeInDown big-component">
                <h1 className="full-width align-left course-name">{courseName}</h1>

                <MethodNavigation
                    courseName={courseName}
                    learningUserCourseId={learningUserCourseId}/>

                <div className="main-app-dashboard align-left">

                    <Switch>
                        <Route path={mainPath + "/lessons"} render={components.lessonList} />
                        <Route path={mainPath + "/games"} component={GameListContainer} />
                        <Route path={mainPath + "/lesson/:lessonName/:learningUserLessonId"} render={components.lessonDashboard} />
                    </Switch>

                    <Switch>
                        <Route path={mainPath + "/lessons"} render={components.sidebarHelper} />
                        <Route path={mainPath + "/games"} render={components.sidebarHelper} />
                        <Route path={mainPath + "/lesson/:lessonName/:learningUserLessonId"} render={components.sidebarHelper} />
                    </Switch>

                </div>
            </div>
        )
    }
}

export class MethodAppRouteContainer extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <MethodApp match={this.props.match} io={this.props.io}/>
        )
    }
}