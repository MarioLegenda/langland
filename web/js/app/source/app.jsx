import React from 'react';
import {ComponentFactory} from "./componentFactory.jsx";


export class App extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            componentChange: false
        };

        this.componentChange = this.componentChange.bind(this);
    }

    componentChange() {

    }

    render() {
        const languageId = this.props.match.params.languageId;

        return <div className="app-wrapper">
            <ComponentFactory languageId={languageId} componentChange={this.componentChange}/>
        </div>
    }
}