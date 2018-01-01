import React from 'react';
import {factory as repoFactory} from "./repository/factory.js";
import {LanguageInfo} from "./languageInfo.jsx";

export class App extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        const languageId = parseInt(this.props.match.params.languageId);

        return <div>
            <LanguageInfo languageId={languageId}/>
        </div>
    }
}