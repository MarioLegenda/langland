import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';
import createHistory from "history/createBrowserHistory"

import {env, user} from "../global/constants.js";
import {Header} from "./source/header.jsx";
import {LanguageList} from "./source/language.jsx";
import {App} from "./source/app.jsx";
import {factory as repoFactory} from "./source/repository/factory.js";
import {store} from "./source/events/events";

import {LessonRunnerContainer} from "./source/app/runner/container.jsx";

let trackedHistory = {
    current: null,
    previous: null,
};

const history = createHistory();

history.listen((location, action) => {
    console.log(trackedHistory);
    trackedHistory.previous = trackedHistory.current;
    trackedHistory.current = location.pathname;
});

class InitApp extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            actions: {
                lessonStarted: false,
                gameStarted: false
            },
            actionStrings: {
                lesson: null,
                game: null,
            }
        };

        this.components = {
            langList: null,
            presentation: null,
            header: null,
            lessonRunner: null,
            gameRunner: null,
        };
    }

    _createComponents() {
        this.components.langList = (match) => <LanguageList history={match.history}/>;
        this.components.presentation = (match) => <App match={match.match}/>;
        this.components.header = <Header/>;
        this.components.lessonRunner = (match) => <LessonRunnerContainer match={match.match}/>
    }

    render() {
        this._createComponents();

        return (
            <Router>
                <div className="main-wrapper">
                    {this.components.header}
                    <Switch>
                        <Route exact path={env.current + "langland"} render={this.components.langList} />
                        <Route path={env.current + "langland/lesson/:lessonName/:learningLessonId"} render={this.components.lessonRunner}/>
                        <Route path={env.current + "langland/language/:language/:languageId"} render={this.components.presentation} />
                        <Route path={env.current + "langland/game/:gameId"} />
                    </Switch>
                </div>
            </Router>
        )
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



