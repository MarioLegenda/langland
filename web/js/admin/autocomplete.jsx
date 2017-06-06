import React from 'react';

class Tags extends React.Component {
    constructor(props) {
        super(props);

        this.removeTag = this.removeTag.bind(this);
    }

    removeTag(e) {
        e.preventDefault();

        let tags = this.state.tags;

        tags.splice(e.currentTarget.id, 1);

        this.setState((prevState, prevProps) => ({
            tags: tags
        }));
    }

    render() {
        this.state = {
            tags: this.props.tags
        };

        const tags = this.state.tags.map((tag, index) =>
            <a href="#" key={index} className="tag margin-bottom-10" id={index} onClick={this.removeTag}>{tag.value}<i className="fa fa-remove"></i></a>
        );

        return (
            <div className="full-width align-left andrea-tags margin-top-30">
                {tags}
            </div>
        )
    }
}

class AutocompleteList extends React.Component {
    constructor(props) {
        super(props);

        this.onItemSelect = this.onItemSelect.bind(this);
    }

    onItemSelect(e) {
        e.preventDefault();

        this.props.onSelect({
            id: e.currentTarget.id,
            value: e.currentTarget.textContent
        });
    }

    render() {
        const values = this.props.values.map((value, index) =>
            <a href="#" key={index} id={value.id} onClick={this.onItemSelect}>{value.value}</a>
        );

        return (
            <div className="full-width absolute andrea-autocomplete-list">
                {values}
            </div>
        )
    }
}

export class Autocomplete extends React.Component {
    constructor(props) {
        super(props);

        props.elem.hide();

        this.jQuery = props.jQuery;
        this.elem = props.elem;
        this.state = {
            inputValue: ''
        };

        this.state.values = [];
        this.state.tags = [];

        this.onChange = this.onChange.bind(this);
        this.onAutocompleteSelect = this.onAutocompleteSelect.bind(this);
        this.submitTags = this.submitTags.bind(this);

        const ids = props.elem.val();

        if (ids.length !== 0) {
            this.fetchTags(ids);
        }
    }

    fetchTags(ids) {
        this.jQuery.ajax({
            url: "admin/word/search/id/" + ids,
            method: 'POST'
        })
            .done(this.jQuery.proxy(function(data) {
                const tags =  data.data;

                this.setState((prevState, props) => ({
                    tags: tags
                }));

            }, this));
    }

    onChange(e) {
        let value = e.target.value;

        if (value.length !== 0) {
            switch(e.keyCode) {
                case 8: {
                    this.setState((prevState, props) => ({
                        inputValue: prevState.inputValue.substring(0, prevState.inputValue.length - 1)
                    }));

                    break;
                }
                case 37: break;
                case 38: break;
                case 39: break;
                case 40: break;
                default: {
                    this.setState((prevState, props) => ({
                        inputValue: value
                    }));
                }
            }
        } else if (value.length === 0) {
            this.setState((prevState, prevProps) => ({
                values: [],
                inputValue: ''
            }));
        }
    }

    onAutocompleteSelect(word) {
        const currentState = this.state.tags;

        currentState.push(word);

        this.setState((prevState, prevProps) => ({
            tags: currentState
        }));

        this.setState((prevState, prevProps) => ({
            values: []
        }));
    }

    componentDidUpdate(prevProps, prevState) {
        if (prevState.inputValue !== this.state.inputValue && this.state.inputValue.length !== 0) {
            this.jQuery.ajax({
                url: this.props.url + this.state.inputValue,
                method: 'POST'
            })
                .done(this.jQuery.proxy(function(data) {
                    const words = data.data;
                    let values = [];

                    for (let i = 0; i < words.length; i++) {
                        values.push({
                            id: words[i].id,
                            value: words[i].name
                        });
                    }

                    this.setState((prevState, props) => ({
                        values: values
                    }));

                }, this));
        }
    }

    submitTags(e) {
        const tags = this.state.tags;
        let ids = '';

        for (let i = 0; i < tags.length; i++) {
            ids += tags[i].id + ',';
        }

        this.elem.val(ids.substring(0, ids.length - 1));
    }

    render() {
        const values = this.state.values;
        const tags = this.state.tags;

        return (
            <div className="full-width align-left form-field relative">
                <label>Add words: </label>
                <input id="autocomplete-input" type="text" placeholder="... type to autocomplete" onChange={this.onChange}/>

                {values.length > 0 &&
                    <AutocompleteList
                        values={values}
                        onSelect={this.onAutocompleteSelect}
                    />
                }

                <Tags tags={tags}/>

                <div className="align-right relative margin-top-50 relative button-wrapper relative">
                    <button type="submit" className="align-right" onClick={this.submitTags}>{this.props.buttonName}</button>
                </div>
            </div>
        )
    }
}