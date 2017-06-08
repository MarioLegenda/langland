import React from 'react';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {MethodNavigation} from './methodNavigation.jsx';
import {LessonListContainer as LessonList} from './lessonList.jsx';
import {LessonDashboardContainer} from './lessonDashboard.jsx';
import {SidebarHelperContainer} from './sidebarHelper/sidebarHelper.jsx';

class MethodApp extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const courseName = this.props.match.params.courseName;
        const learningUserCourseId = this.props.match.params.learningUserCourseId;
        const mainPath = this.props.match.url;

        const lessonList = () => <LessonList
            courseName={courseName}
            learningUserCourseId={learningUserCourseId}
        />;

        const sidebarHelper = () => <SidebarHelperContainer learningUserCourseId={learningUserCourseId}/>

        return (
            <Router>
                <div className="animated fadeInDown big-component">
                    <h1 className="full-width align-left course-name">{courseName}</h1>

                    <MethodNavigation
                        courseName={courseName}
                        learningUserCourseId={learningUserCourseId}/>

                    <div className="main-app-dashboard align-left">
                        <div className="animated fadeInDown lesson-list working-area">

                            <Switch>
                                <Route path={mainPath + "/lessons"} render={lessonList} />
                                <Route path={mainPath + "/lesson/:lessonName/:learningUserLessonId"} component={LessonDashboardContainer} />

                            </Switch>

                        </div>

                        <div className="animated fadeInDown sidebar-helper">

                            <Switch>
                                <Route path={mainPath + "/lessons"} render={sidebarHelper} />
                                <Route path={mainPath + "/lesson/:lessonName/:learningUserLessonId"} render={sidebarHelper} />
                            </Switch>

                        </div>

                        <Switch>
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