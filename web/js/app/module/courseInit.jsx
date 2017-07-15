import React from 'react';
import {LanguageInfoContainer} from './languageInfo.jsx';
import {CourseListContainer} from './courseList.jsx';
import {routes} from './routes.js';

export class CourseInitContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            isInfoLooked: null
        };

        this.markInfoLooked = this.markInfoLooked.bind(this);
    }

    _fetchIsLookedInfo() {
        this.props.DataSource.fetchIsInfoLooked()
            .done(jQuery.proxy(function(data, content, response) {
                if (response.status === 200) {
                    this.setState({
                        isInfoLooked: true
                    });

                    return null;
                }

                if (data.status === 'failure') {
                    this.setState({
                        isInfoLooked: false
                    });
                }
            }, this));
    }

    _fetchMarkInfoLooked() {
        this.props.DataSource.fetchMarkInfoLooked()
            .done(jQuery.proxy(function(data) {
                if (data.status === 'success') {
                    this.setState({
                        isInfoLooked: true
                    });
                }
            }, this));
    }

    markInfoLooked() {
        this._fetchMarkInfoLooked();
    }

    componentDidMount() {
        this._fetchIsLookedInfo();
    }

    render() {
        const isInfoLooked = this.state.isInfoLooked;
        const languageData = this.props.match.params;

        if (isInfoLooked === true) {
            return <CourseListContainer
                languageData = {languageData}
                DataSource={this.props.DataSource}
            />
        } else if (isInfoLooked === false) {
            return  <LanguageInfoContainer
                        markInfoLooked={this.markInfoLooked}
                        languageData={this.props.match.params}
                        DataSource={this.props.DataSource}
                    />;
        }

        return null;
    }
}