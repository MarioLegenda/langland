import React from 'react';
import {ComponentFactory} from "./componentFactory.jsx";
import {factory} from "./repository/factory.js";

export class App extends React.Component {
    constructor(props) {
        super(props);

        this.learningUserRepository = factory('learning-user');

        this.state = {
            currentComponent: null
        };

        this.componentOrder = ['isMainAppReady', 'isLanguageInfoLooked', 'areQuestionsLooked'];

        this.componentChange = this.componentChange.bind(this);
    }

    _createDynamicComponentData(manualComponent = null) {
        if (manualComponent === null) {
            this.learningUserRepository.getDynamicComponentsStatus($.proxy(function(data) {
                const components = data.resource.data;

                for (let i = 0; i < this.componentOrder.length; i++) {
                    let comp = this.componentOrder[i];
                    if (components.hasOwnProperty(comp)) {
                        if (components[comp] === false) {
                            this.setState({
                                currentComponent: comp
                            });

                            break;
                        }
                    }
                }
            }, this));
        } else {
            this.setState({
                currentComponent: manualComponent,
            });
        }
    }

    componentDidMount() {
        this._createDynamicComponentData();
    }

    componentChange() {
        this._createDynamicComponentData();
    }

    render() {
        if (this.state.currentComponent === null) {
            return null;
        }

        const currentComponent = this.state.currentComponent;
        const languageId = this.props.match.match.params.languageId;

        return <div className="app-wrapper">
            <ComponentFactory
                languageId={languageId}
                currentComponent={currentComponent}
                componentChange={this.componentChange}
            />
        </div>
    }
}