import React from 'react';
import ReactDOM from 'react-dom';

class Admin extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return <div></div>
    }
}

class adminFactory {
    createComponent(id) {
        const elem = document.getElementById(id);

        if (elem === null) {
            return;
        }

        ReactDOM.render(
            React.createElement(
                Admin,
                {
                    $: jQuery
                }
            ),
            elem
        )
    }
}

export const AdminComponent = new adminFactory();