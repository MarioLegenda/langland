import {MainNavigation} from "./view/mainNavigation.jsx";
import {LessonPresentationContainer} from "./lessonPresentationContainer.jsx";
import {GamesPresentationContainer} from "./gamesPresentationContainer.jsx";

import {
    store,
    mainAppLoaded
} from "../events/events";

import React from "react";

export class MainAppContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            actions: {
                mainAppLoaded: false,
                lessonMenuClicked: false,
                gamesMenuClicked: false,
            }
        };

        store.subscribe(() => {
            const isMainAppLoaded = store.getState().app.isMainAppLoaded;

            if (isMainAppLoaded) {
                this.setState((prevState) => {
                    prevState.actions.mainAppLoaded = isMainAppLoaded;
                    prevState.actions.lessonMenuClicked = true;
                    prevState.actions.gamesMenuClicked = false;
                });
            }
        });
    }

    _menuPresentationInversion(menuState) {
    }

    componentWillUnmount() {
        store.dispatch(mainAppLoaded(false));
    }

    componentDidMount() {
        store.dispatch(mainAppLoaded(true));
    }

    render() {
        const lessonMenu = this.state.actions.lessonMenuClicked,
              gamesMenu = this.state.actions.gamesMenuClicked;

        return <div className="app-console-wrapper">
            <MainNavigation />

            {lessonMenu === true && <LessonPresentationContainer/>}
            {gamesMenu === true && <GamesPresentationContainer/>}
        </div>
    }
}