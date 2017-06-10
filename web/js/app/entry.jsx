import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import {HeaderContainer as Header} from "./module/header.jsx";
import {LanguageListContainer} from "./module/languages.jsx";
import {envr} from './module/env.js';

import {MethodAppRouteContainer} from './module/method/methodApp.jsx';
import {CourseInitContainer} from './module/courseInit.jsx';
import {learningUser as User} from './module/user.js';

const NoMatch = () => <div>No match</div>

function App() {
    const io = window.io('http://33.33.33.10:3000');

    const methodAppContainer = (match) => <MethodAppRouteContainer io={io} match={match.match}/>

    return (
        <Router>
            <div className="app">
                <Header/>

                <Switch>
                    <Route exact path={envr + "langland/course/:languageName/:languageId"} component={CourseInitContainer}/>
                    <Route path={envr + "langland/dashboard/:courseName/:learningUserCourseId"} render={methodAppContainer} />
                    <Route exact path={envr + "langland"} component = {LanguageListContainer} />

                    <Route component={NoMatch}/>
                </Switch>
            </div>
        </Router>
    );
}

ReactDOM.render(
    <App/>,
    document.getElementById('react-app')
);



