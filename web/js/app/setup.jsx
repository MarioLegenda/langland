import React from 'react';

class Setup extends React.Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        jQuery.ajax({
            url: '/web/app_dev.php/langland/setup/create-learning-user',
            method: 'POST',
            data: this.props.setupData
        }).done(function(data) {
            if (data.status === 'redirect') {
                location.href = data.redirect_url;
            }
        });
    }

    render() {
        return (
            <div className="initial-info animated fadeInDown">
                <div className="full-width align-left margin-bottom-50">
                    <p className="full-width align-right setup-message">Setting up langland ...</p>
                </div>
            </div>
        )
    }
}

class LanguageList extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            selected: false
        };

        this.languageId = null;

        this.onLanguageSelect = this.onLanguageSelect.bind(this);
        this.onSubmit = this.onSubmit.bind(this);
        this.onPrev = this.onPrev.bind(this);
    }

    onLanguageSelect(e) {
        $('.language-select').each(function() {
            $(this).removeClass('language-selected');
        });

        $(e.currentTarget).addClass('language-selected');

        this.languageId = e.currentTarget.getAttribute('data-id');

        this.setState({
            selected: true
        });
    }

    onPrev() {
        this.props.onPrev();
    }

    onSubmit() {
        this.props.onDataCollect({
            language: this.languageId
        });
    }

    render() {
        const items = this.props.items.map((item, index) =>
            <div key={index} className="language-box">
                <a className="language-select" data-id={item.id} onClick={this.onLanguageSelect}>{item.name}</a>
            </div>
        );

        return (
            <div className="initial-info animated fadeInDown">
                <div className="full-width align-left margin-bottom-50">
                    <div className="margin-bottom-30">
                        <h2 className="text">Choose your first language</h2>
                    </div>

                    <div className="margin-bottom-30 full-width align-left language-select">
                        {items}
                    </div>

                    {this.state.selected === true &&
                    <div className="full-width align-right">
                        <div className="align-right">
                            <button className="align-right next-button" onClick={this.onSubmit}><i className="fa fa-thumbs-o-up fa-2x"></i></button>
                        </div>

                        <div className="align-left">
                            <button className="align-right next-button" onClick={this.onPrev}><i className="fa fa-arrow-left"></i></button>
                        </div>
                    </div>
                    }
                </div>
            </div>
        )
    }
}

class LanguageListContainer extends React.Component {
    constructor(props) {
        super(props);
    };

    render() {
        return (
            <LanguageList
                onDataCollect = {this.props.onDataCollect}
                onPrev = {this.props.onPrev}
                items = {this.props.languages}
            />
        )
    }
}

class IntroQuestion extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            selection: {
                appearance: ['fa fa-circle-o', 'fa fa-circle-o'],
                hoverable: true
            },
            data: {
                duration: null
            }
        };

        this.onEnter = this.onEnter.bind(this);
        this.onExit = this.onExit.bind(this);
        this.onClick = this.onClick.bind(this);
        this.onSubmit = this.onSubmit.bind(this);
    }

    onEnter(e) {
        const selected = parseInt(e.currentTarget.getAttribute('data-question-key'));
        this.setState(function(prevState, prevProps) {
            if (prevState.selection.hoverable === true) {
                prevState.selection.appearance[selected] = 'fa fa-circle';

                return prevState;
            }
        });
    }

    onExit(e) {
        const selected = parseInt(e.currentTarget.getAttribute('data-question-key'));
        this.setState(function(prevState, prevProps) {
            if (prevState.selection.hoverable === true) {
                prevState.selection.appearance[selected] = 'fa fa-circle-o';

                return prevState;
            }
        });
    }

    onClick(e) {
        e.preventDefault();

        this.setState(function(prevState, prevProps) {
            return {
                selection: {
                    appearance: ['fa fa-circle-o', 'fa fa-circle-o'],
                }
            }
        });

        const selected = parseInt(e.currentTarget.getAttribute('data-question-key'));
        const duration = e.currentTarget.getAttribute('data-duration');
        this.setState(function(prevState, prevProps) {
            let appearance = ['fa fa-circle-o', 'fa fa-circle-o'];
            appearance[selected] = 'fa fa-circle';

            return {
                selection: {
                    appearance: appearance,
                    hoverable: false
                },
                data: {
                    duration: duration
                }
            };
        });
    }

    onSubmit() {
        this.props.onDataCollect({
            duration: this.state.data.duration
        });
    }

    render() {
        return (
            <div className="initial-info animated fadeInDown">
                <div className="full-width align-left time-question">
                    <div className="margin-bottom-50">
                        <h2 className="text">How much time a day would you spend on learning a new language?</h2>
                    </div>

                    <div className="full-width align-left margin-bottom-30">
                        <a className="time-choice" data-question-key="0" data-duration="30" onMouseEnter={this.onEnter} onMouseLeave={this.onExit} onClick={this.onClick}><i className={this.state.selection.appearance[0]}></i></a>
                        <label>30 minutes a day</label>
                    </div>

                    <div className="full-width align-left margin-bottom-30">
                        <a className="time-choice" data-question-key="1" data-duration="1" onMouseEnter={this.onEnter} onMouseLeave={this.onExit} onClick={this.onClick}><i className={this.state.selection.appearance[1]}></i></a>
                        <label>1 hour a day</label>
                    </div>
                    {this.state.selection.hoverable === false &&
                    <div className="full-width align-right margin-bottom-30">
                        <a className="align-right next-button" onClick={this.onSubmit}><i className="fa fa-thumbs-o-up fa-2x"></i></a>
                    </div>
                    }
                </div>
            </div>
        )
    }
}

class InitialInfo extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <div className="container-full-width align-left margin-top-30">
                {this.props.element}
            </div>
        )
    }
}

export class SetupContainer extends React.Component {
    constructor(props) {
        super(props);

        this.onNext = this.onNext.bind(this);
        this.onPrev = this.onPrev.bind(this);

        this.initialInfo =  {
            duration: null,
            language: null
        };

        this.state = {
            decks: [
                React.createElement(
                    IntroQuestion,
                    {
                        onDataCollect: this.onNext
                    }
                ),
                React.createElement(
                    LanguageListContainer,
                    {
                        onDataCollect: this.onNext,
                        onPrev: this.onPrev,
                        languages: this.props.languages
                    }
                ),
                React.createElement(
                    Setup,
                    {
                        setupData: this.initialInfo
                    }
                )
            ],
            currentDeck: 0
        };
    }

    onNext(data) {
        if (data.hasOwnProperty('duration')) {
            this.initialInfo.duration = data.duration;
        }

        if (data.hasOwnProperty('language')) {
            this.initialInfo.language = data.language;
        }

        this._handleDeck(this.state.currentDeck + 1);
    }

    onPrev() {
        this._handleDeck(this.state.currentDeck - 1);
    }

    _handleDeck(index) {
        const initInfoElem = jQuery('.initial-info');

        initInfoElem.removeClass('fadeInDown').addClass('fadeOutUp');

        initInfoElem.on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', jQuery.proxy(function() {
            this.setState((prevState, prevProps) => ({
                currentDeck: index
            }));
        }, this));
    }

    render() {
        const elem = this.state.decks[this.state.currentDeck];

        return (
            <InitialInfo
                element = {elem}
            />
        )
    }
}