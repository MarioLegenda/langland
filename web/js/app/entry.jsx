import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {env, user} from "../global/constants.js";
import {Header} from "./source/header.jsx";
import {LanguageList} from "./source/language.jsx";
import {App} from "./source/app.jsx";
import {factory as repoFactory} from "./source/repository/factory.js";
import {store} from "./source/events/events";

class InitApp extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            actions: {
                lessonStarted: false,
                gameStarted: false
            }
        };

        store.subscribe(() => {
            const appState = store.getState().app;

            console.log(appState);

            if (appState.lessonStarted) {
                this.setState((prevState) => {
                    prevState.actions.lessonStarted = appState.lessonStarted;
                });
            }
        });
    }

    render() {

        const lessonStarted = this.state.actions.lessonStarted;
        const gameStarted = this.state.actions.gameStarted;

        const langList = (lessonStarted || gameStarted) ? null : (match) => <LanguageList history={match.history}/>;
        const app = (lessonStarted || gameStarted) ? null : (match) => <App match={match.match}/>;
        const header = (lessonStarted || gameStarted) ? null : <Header/>;



        return (
            <Router>
                <div className="main-wrapper">
                    {header}
                    <Switch>
                        <Route exact path={env.current + "langland"} render={langList} />
                        <Route path={env.current + "langland/:language/:languageId"} render={app} />
                        <Route path={env.current + "langland/lesson/:lessonName/:lessonId"} />
                        <Route path={env.current + "langland/game/:gameId"} />
                    </Switch>
                </div>
            </Router>
        );
    }
}

const react_app = document.getElementById('react_app');

if (react_app !== null) {
    repoFactory('user').getLoggedInUser($.proxy(function() {
        ReactDOM.render(
            <InitApp/>,
            react_app
        );
    }));
}

/*import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {HeaderContainer as Header} from "./module/header.jsx";
import {LanguageListContainer} from "./module/languages.jsx";
import {envr} from './module/env.js';

import {MethodAppRouteContainer} from './module/method/methodApp.jsx';
import {CourseInitContainer} from './module/courseInit.jsx';
import {DataSource} from './module/dataSource.js';

const NoMatch = () => <div>No match</div>

function App() {
    //const io = window.io('http://33.33.33.10:3000');

    const methodAppContainer = (match) => <MethodAppRouteContainer io={io} match={match.match} DataSource={DataSource}/>;
    const languageListContainer = () => <LanguageListContainer DataSource={DataSource}/>;
    const courseInitContainer = (match) => <CourseInitContainer DataSource={DataSource} match={match.match}/>

    return (
        <Router>
            <div className="app">
                <Header DataSource={DataSource}/>

                <Switch>
                    <Route exact path={envr + "langland/course/:languageName/:languageId"} render={courseInitContainer}/>
                    <Route path={envr + "langland/dashboard/:courseName/:learningUserCourseId"} render={methodAppContainer} />
                    <Route exact path={envr + "langland"} render={languageListContainer} />

                    <Route component={NoMatch}/>
                </Switch>
            </div>
        </Router>
    );
}

ReactDOM.render(
    <App/>,
    document.getElementById('react-app')
);*/



