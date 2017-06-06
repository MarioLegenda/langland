import React from 'react';
import ReactDOM from 'react-dom';

export class GameListSelection extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            value: ''
        };

        this.onChange = this.onChange.bind(this);
    }

    onChange(e) {
        const value = e.currentTarget.value;
        let href = window.location.origin + window.location.pathname;

        window.location.href = href + '?gameType=' + value;
    }

    render() {
        const value = this.state.value;

        return (
            <select value={value} onChange={this.onChange}>
                <option defaultValue="default">Select game</option>
                <option value="wordGames">Word games</option>
                <option value="questionGames">Question games</option>
            </select>
        )
    }
}