import React from 'react';
import {factory as repoFactory} from "./repository/factory.js";
import {LanguageInfo} from "./languageInfo.jsx";
import {QuestionsContainer} from "./questions.jsx";

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
                return <LanguageInfo languageId={this.props.languageId} componentChange={this.componentChange}/>
            case 'areQuestionsLooked':
                return <QuestionsContainer componentChange={this.componentChange}/>
        }
    }
}