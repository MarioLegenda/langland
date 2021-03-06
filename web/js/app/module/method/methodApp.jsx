import React from 'react';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {MethodNavigation} from './methodNavigation.jsx';
import {LessonListContainer as LessonList} from './lessonList.jsx';
import {LessonDashboardContainer} from './lessonDashboard.jsx';
import {SidebarHelperContainer} from './sidebarHelper/sidebarHelper.jsx';
import {GameListContainer} from './gamesList.jsx';
import {Game} from './game/gameInit.jsx';

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
            DataSource={this.props.DataSource}
        />;

		const gameInit = (match) => <Game
		    match={match.match}
            courseName={courseName}
			learningUserCourseId={learningUserCourseId}
            DataSource={this.props.DataSource}
		/>;

        const gamesList = () => <GameListContainer
            courseName={courseName}
            learningUserCourseId={learningUserCourseId}
            DataSource={this.props.DataSource}
        />;

        const lessonDashboard = (match) => <LessonDashboardContainer
            courseName={courseName}
            learningUserCourseId={learningUserCourseId}
            match={match.match}
            io={this.props.io}
            DataSource={this.props.DataSource}
        />;

        const sidebarHelper = () => <SidebarHelperContainer
            learningUserCourseId={learningUserCourseId}
            io={this.props.io}
        />;

        return {
            lessonList: lessonList,
            gamesList: gamesList,
			game: gameInit,
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
                        <Route path={mainPath + "/games"} render={components.gamesList} />
						<Route path={mainPath + "/lesson/:lessonName/:learningUserLessonId"} render={components.lessonDashboard} />
                        <Route path={mainPath + "/game/:gameName/:learningUserGameId"} render={components.game} />
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
            <MethodApp match={this.props.match} io={this.props.io} DataSource={this.props.DataSource}/>
        )
    }
}
