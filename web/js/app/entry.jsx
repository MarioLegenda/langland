import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {env, user} from "../global/constants.js";
import {Header} from "./source/header.jsx";
import {LanguageList} from "./source/language.jsx";
import {App} from "./source/app.jsx";
import {factory as repoFactory} from "./source/repository/factory.js";
import {store} from "./source/events/events";

import {LessonContainer} from "./source/app/runner/container.jsx";

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

        store.subscribe(() => {
            const appState = store.getState().app;

            console.log(appState);

            if (appState.lessonStarted) {
                this.setState((prevState) => {
                    prevState.actions.lessonStarted = appState.lessonStarted;
                    prevState.actions.lesson = null;
                });
            }
        });
    }

    _createComponents() {
        const lessonStarted = this.state.actions.lessonStarted;
        const gameStarted = this.state.actions.gameStarted;

        if (!lessonStarted && !gameStarted) {
            this.components.langList = (lessonStarted || gameStarted) ? null : (match) => <LanguageList history={match.history}/>;
            this.components.presentation = (lessonStarted || gameStarted) ? null : (match) => <App match={match.match}/>;
            this.components.header = (lessonStarted || gameStarted) ? null : <Header/>;
        }

        if (lessonStarted) {
            this.components.langList = null;
            this.components.presentation = null;
            this.components.header = null;

            this.components.lessonRunner = (match) => <LessonContainer match={match.match}/>;
        }
    }

    render() {
        this._createComponents();

        console.log(this.components);

        return (
            <Router>
                <div className="main-wrapper">
                    {this.components.header}
                    <Switch>
                        <Route exact path={env.current + "langland"} render={this.components.langList} />
                        <Route path={env.current + "langland/lesson/:lessonName/:learningLessonId"} render={this.components.lessonRunner}/>
                        <Route path={env.current + "langland/:language/:languageId"} render={this.components.presentation} />
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



