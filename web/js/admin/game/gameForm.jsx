import React from 'react';

import {TextField, TextareaField, SelectField, SubmitButton} from './form.jsx';
import {UnitContainer} from './unitContainer.jsx';

import {envr} from './../env.js';
import {url} from './../url.js';

export class Form extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            lessonOptions: [],
            errors: [],
            formValues: {
                name: '',
                description: '',
                lesson: '',
                words: []
            },
            gameOptions: []
        };

        this.gameSaving = false;

        this.setField = this.setField.bind(this);
        this.onSubmit = this.onSubmit.bind(this);
    }

    _loadGameSelectableData() {
        jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/game/word-game/find-games',
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            if (data.status === 'success') {
                this.setState({
                    gameOptions: data.data
                });
            }
        }, this));
    }

    _fetchLessons() {
        jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/game/word-game/find-lessons-by-course',
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            let options = [];
            const lessons = data.data;

            for (let index in lessons) {
                let lesson = lessons[index];

                options.push({
                    value: lesson.id,
                    label: lesson.name
                });
            }

            this.setState({
                lessonOptions: options
            });
        }, this));
    }

    _saveGame() {
        this.gameSaving = true;

        jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/game/word-game/create-game',
            method: 'POST',
            data: {
                game: this.state.formValues
            }
        }).done(jQuery.proxy(function(data) {
            if (data.status === 'success') {
                window.location.href = envr + 'admin/course/manage/' + url.getParsed()[3] + '/game';
            }

            if (data.status === 'error') {
                this.setState({
                    errors: data.data
                });

                this.gameSaving = false;

                $("html, body").animate({ scrollTop: "0px" });
            }
        }, this));
    }

    _createGameOptions() {
        const games = this.state.gameOptions;

        let realGames = [];
        for (let index in games) {
            let game = games[index];

            let options = [];
            for (let gameIndex in game.games) {
                let trueGame = game.games[gameIndex];

                options.push(<option key={gameIndex} value={trueGame.id}>{trueGame.name}</option>);
            }

            realGames.push(<optgroup key={index} label={game.name}>{options}</optgroup>);
        }

        return realGames;
    }

    componentDidMount() {
        this._fetchLessons();
        this._loadGameSelectableData();
    }

    setField(name, value) {
        this.setState(function(prevState, prevProps) {
            let formValues = prevState.formValues;

            formValues[name] = value;

            return formValues;
        });
    }

    onSubmit(e) {
        e.preventDefault();

        if (this.gameSaving === false) {
            console.log('ulazak');
            this._saveGame();
        }
    }

    render() {
        const lessonOptions = this.state.lessonOptions;

        const errors = this.state.errors.map((item, index) =>
            <p key={index} className="error">* {item}</p>
        );

        const items = this.state.formValues.words;

        const gameOptions = this._createGameOptions();

        return (
            <div className="full-width align-left margin-top-30 page-content form">
                <div className="margin-top-40 align-left full-width">

                    <div className="full-width align-left">
                        {errors}
                    </div>

                    <div className="full-width align-left form-field field-brake">
                        <select onChange={this.onGameChoosing}>
                            <option defaultValue="default">Select game</option>
                            {gameOptions}
                        </select>

                        <i className="description margin-top-10">
                            <span className="highlight">*</span>
                            load a previously created game for editing
                        </i>
                    </div>

                    <TextField
                        labelName="Game name: "
                        name="name"
                        setField={this.setField}
                        description="name of the game"
                        value={this.state.formValues.name}
                    />

                    <TextareaField
                        labelName="Game description: "
                        name="description"
                        setField={this.setField}
                        description="a short description of the game. This description will be shown in a game filed box in the Games menu"
                        value={this.state.formValues.description}
                    />

                    <SelectField
                        labelName="Select lesson: "
                        name="lesson"
                        setField={this.setField}
                        description="a lesson that will unlock this game. One game can have only one lesson but a lesson can have many games. If you create a new game, you can connect it to the sam lesson"
                        value={this.state.formValues.lesson}
                        options={lessonOptions}
                    />

                    <UnitContainer
                        setField={this.setField}
                        items={items}
                    />

                    <SubmitButton
                        onClick={this.onSubmit}
                    />
                </div>
            </div>
        )
    }
}