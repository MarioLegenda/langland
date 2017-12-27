import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {env, user} from "../global/constants.js";
import {List} from "./source/language.jsx";
import {factory as repoFactory} from "./source/repository/factory.js";

function App() {

    const langList = (match) => <List/>;

    return (
        <Router>
            <div>
                <Switch>
                    <Route exact path={env.current + "langland"} render={langList} />
                </Switch>
            </div>
        </Router>
    );
}

const react_language_list = document.getElementById('react_language_list');

if (react_language_list !== null) {
    repoFactory('user').getLoggedInUser($.proxy(function() {
        ReactDOM.render(
            <App/>,
            react_language_list
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



