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
        $('.center-loading').show();
        this.learningUserRepository.isLanguageInfoLooked($.proxy(function(data) {
            if (data['looked'] === false) {
                this.setState(function(prevState) {
                    prevState.languageInfoPass = true;
                    prevState.component = <LanguageInfo languageId={this.props.languageId}/>
                });

                $('.center-loading').hide();
            } else if (data['looked'] === true) {

            }
        }, this));
    }

    render() {
        return this.state.component;
    }
}