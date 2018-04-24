import React from 'react';
import {factory as repoFactory} from "./repository/factory.js";
import {LanguageInfo} from "./languageInfo.jsx";
import {QuestionsContainer} from "./questions.jsx";
import {MainAppContainer} from "./app/mainAppContainer.jsx";

export class ComponentFactory extends React.Component {
    constructor(props) {
        super(props);

        this.learningUserRepository = repoFactory('learning-user');

        this.componentChange = this.componentChange.bind(this);
    }

    componentChange(manualComponent = null) {
        this.props.componentChange(manualComponent);
    }

    render() {
        const comp = this.props.currentComponent;

        switch (comp) {
            case 'isLanguageInfoLooked':
                console.log('Component decision: Decision is on language info');
                return <LanguageInfo
                    languageId={this.props.languageId}
                    componentChange={this.componentChange}
                />;
            case 'areQuestionsLooked':
                console.log('Component decision: Decision is on question');
                return <QuestionsContainer
                    componentChange={this.componentChange}
                />;
            case 'isMainAppReady':
                console.log('Component decision: Decision is on main app');

                return <MainAppContainer/>;
        }
    }
}