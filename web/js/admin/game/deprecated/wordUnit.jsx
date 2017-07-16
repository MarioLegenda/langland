import React from 'react';
import Select from 'react-select';
import {url} from './../url.js';
import {envr} from './../env.js';

class SelectedWordList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            items: this.props.items,
            isCorrect: false
        };

        this.onRemove = this.onRemove.bind(this);
        this.onSelectedCorrect = this.onSelectedCorrect.bind(this);
    }

    onRemove(e) {
        let currentItems = this.state.items;

        currentItems.splice(e.currentTarget.getAttribute('data-index'), 1);

        this.setState({
            items: currentItems
        });
    };

    onSelectedCorrect(e) {
        this.props.onSelectedCorrect(e.currentTarget.getAttribute('data-index'));
    }

    render() {
        const items = this.state.items.map((item, index) =>
            <tr key={index}>
                <td>{item.id}</td>
                <td>{item.name}</td>
                <td>{item.type}</td>
                <td>{item.createdAt}</td>
                <td><a onClick={this.onRemove} data-index={index}><i className="fa fa-remove"></i></a></td>
            </tr>
        );

        return (
            <div className="selected-word-list full-width align-left">
                <div className="selected-word">
                    <table>
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Word</th>
                            <th>Type</th>
                            <th>Date created</th>
                            <th>Remove</th>
                        </tr>

                        {items}
                        </tbody>
                    </table>
                </div>
            </div>
        );
    }
}

class WordSelect extends React.Component {
    constructor(props) {
        super(props);

        this.onChange = this.onChange.bind(this);
    }

    onChange(selectedValue) {
        this.props.onSelectedValue(selectedValue);
    }

    render() {
        const items = this.props.items;

        return (
            <Select
                options={items}
                onChange={this.onChange}
            />
        )
    }
}

class WordUnit extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            selectedWords: [],
            error: false
        };

        this.onSelectedWord = this.onSelectedWord.bind(this);
    }

    _changeToSelectableValues(words) {
        let selectWords = [];

        for (let index in words) {
            selectWords.push({
                label: words[index].name,
                value: index
            });
        }

        return selectWords;
    }

    onSelectedWord(selected) {
        const selectedWord = this.props.items[selected.value];

        for (let index in this.state.selectedWords) {
            let word = this.state.selectedWords[index];

            if (word.id === selectedWord.id) {

                this.setState({
                    error: true
                });

                return null;
            }
        }

        this.setState(function(prevState, prevProps) {
            let selectedWords = prevState.selectedWords;

            selectedWords.push(selectedWord);

            this.props.setField('words', selectedWords);

            return {
                selectedWords: selectedWords,
                error: false
            }
        });
    }

    render() {
        const items = this._changeToSelectableValues(this.props.items);
        const selectedWords = this.state.selectedWords;

        return (
            <div>
                <div className="full-width align-left word-select">
                    <h1>Select words for this game</h1>
                    <WordSelect
                        items={items}
                        onSelectedValue={this.onSelectedWord}
                    />


                    <i className="description margin-top-10">
                        <span className="highlight">*</span>
                        add words to this game. Duplicated words will be ignored
                    </i>

                    {this.state.error === true &&
                    <div className="error margin-top-20">Word already selected</div>
                    }
                </div>

                <div className="full-width align-left margin-top-20 word-unit">
                    <SelectedWordList
                        items={selectedWords}
                        onSelectedCorrect={this.onSelectedCorrect}
                    />
                </div>
            </div>
        )
    }
}

export class WordUnitContainer extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            words: null
        };
    }

    componentDidMount() {
        jQuery.ajax({
            url: envr + 'admin/course/manage/' + url.getParsed()[3] + '/game/word-game/find-game-words',
            method: 'GET'
        }).done(jQuery.proxy(function(data) {
            if (data.status === 'success') {
                this.setState({
                    words: data.data
                });
            }
        }, this));
    }

    render() {
        const words = this.state.words;

        if (words === null) {
            return null;
        }

        return (
            <div className="full-width align-left">
                <WordUnit
                    items={words}
                    setField={this.props.setField}
                />
            </div>
        )
    }
}