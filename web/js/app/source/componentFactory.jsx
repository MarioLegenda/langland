import React from 'react';
import {factory as repoFactory} from "./repository/factory.js";
import {LanguageInfo} from "./languageInfo.jsx";

export class ComponentFactory extends React.Component {
    constructor(props) {
        super(props);

        this.learningUserRepository = repoFactory('learning-user');

        this.state = {
            component: null,
            languageInfoPass: false
        }
    }

    componentDidMount() {
        this.learningUserRepository.isLanguageInfoLooked($.proxy(function(data) {
            const isLanguageInfoLooked = data.resource.data.isLanguageInfoLooked;
            if (isLanguageInfoLooked === false) {
                this.setState(function(prevState) {
                    prevState.languageInfoPass = true;
                    prevState.component = <LanguageInfo languageId={this.props.languageId}/>
                });
            }
            else if (isLanguageInfoLooked === true) {

            }
        }, this));
    }

    render() {
        return this.state.component;
    }
}