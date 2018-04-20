import React from 'react';

export class LessonRunner extends React.Component {
    constructor(props) {
        super(props);

        this._disableBackButton();

        console.log('This is lesson');
    }

    _disableBackButton() {
        history.pushState(null, null, location.href);

        window.onpopstate = function () {
            history.go(1);
        };
    }

    render() {
        return <div></div>
    }
}