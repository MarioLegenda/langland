import React from 'react';
import {factory} from "./repository/factory.js";

const Error = () => {
    return <div className="error">
        <p>You have to provide an answer for this question</p>
    </div>
};

class Item extends React.Component {
    constructor(props) {
        super(props);

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);

        this.onAnswer = this.onAnswer.bind(this);
        this.onInputAnswer = this.onInputAnswer.bind(this);

        this.answers = {};

        this.state = {
            error: {
                showError: false
            }
        };
    }

    componentDidUpdate() {
        this._handleUnhighlight();

        const answers = $('.answer-wrapper').find('p');

        answers.each((index, value) => {
            if (this._hasAnswer(this.props.item.name)) {
                const answer = this.answers[this.props.item.name];
                const answerType = value.dataset.type;

                if (answer === answerType) {
                    value.classList.add('highlight-answer');
                    value.classList.remove('unhighlight-answer');
                }
            }
        });
    }

    onAnswer(e) {
        this._handleUnhighlight();

        e.target.classList.remove('unhighlight-answer');
        e.target.classList.add('highlight-answer');

        const name = this.props.item.name;
        const type = e.target.dataset.type;

        this.answers[name] = type;

        this.props.onAnswer(name, type);
    }

    onInputAnswer(e) {
        const type = e.target.dataset.type;
        const value = e.target.value;

        this.answers[type] = value;

        this.props.onAnswer(type, value);
    }

    next(e) {
        if (!this.answers.hasOwnProperty(e.target.dataset.type)) {
            this.setState((prevState) => prevState.error.showError = true);

            e.preventDefault();

            return false;
        }

        if (this.answers.hasOwnProperty(e.target.dataset.type)) {
            const answer = this.answers[e.target.dataset.type];

            if (answer.length === 0) {
                this.setState((prevState) => prevState.error.showError = true);

                e.preventDefault();

                return false;
            }

            this.setState((prevState) => prevState.error.showError = false);
        }

        this.props.nextItem();
    }

    prev() {
        this.props.prevItem();
    }

    _hasAnswer(name) {
        return this.answers.hasOwnProperty(name);
    }

    _handleUnhighlight() {
        const answers = $('.answer-wrapper').find('p');

        answers.each((index, value) => {
            value.classList.remove('highlight-answer');
            value.classList.add('unhighlight-answer');
        });
    }

    render() {
        if (typeof this.props.item === 'undefined') {
            return null;
        }

        const item = this.props.item,
              showError = this.state.error.showError,
              question = item.question,
              answers = (item.answers.length === 0) ? null : Object.entries(item.answers).map((answers, index) => {
                  const answerType = answers[0];
                  const answerView = answers[1];

                  return <div key={index} className="answer-wrapper">
                      <p
                          data-type={answerType}
                          className={"actual-answer"}
                          onClick={this.onAnswer}><i className="fa fa-circle fa-lg"></i>{answerView}
                      </p>
                  </div>
              });

        return (
            <div className="animated fadeInDown question-item">
                <h1 className="animated animated-field">{question}</h1>

                {showError && <Error/>}

                <div className="animated animated-field text">
                    {answers === null &&
                    <div className="input-wrapper">
                        <input autoFocus value={this.answers[this.props.item.name]} data-type={this.props.item.name} type="text" onChange={this.onInputAnswer} />
                    </div>
                    }

                    {answers !== null && answers}
                </div>

                <div className="button-wrapper">
                    {this.props.firstItem !== true && <a onClick={this.prev} className="previous-button"><i className="fa fa-arrow-left"></i></a>}

                    <a onClick={this.next} data-type={this.props.item.name}><i data-type={this.props.item.name} className="fa fa-thumbs-o-up fa-2x"></i></a>
                </div>
            </div>
        )
    }
}

export class QuestionsContainer extends React.Component {
    constructor(props) {
        super(props);

        this.learningUserRepository = factory('learning-user');

        this.state = {
            items: null,
            counter: 0
        };

        this.answers = {};

        this.inNextClick = false;
        this.inPrevClick = false;

        this.next = this.next.bind(this);
        this.prev = this.prev.bind(this);
        this.onAnswer = this.onAnswer.bind(this);
    }

    componentDidMount() {
        this.learningUserRepository.getQuestions($.proxy(function(data) {
            this.setState( (prevState) => {
                prevState.items = data.collection.data;
            });
        }, this));
    }

    onAnswer(name, answer) {
        this.answers[name] = answer;
    }

    next() {
        this._moveSlide('onNextClick');
    }

    prev() {
        this._moveSlide('onPrevClick');
    }

    _markQuestionsAnswered() {
        this.learningUserRepository.markQuestionsAnswered();
    }

    _moveSlide(clickType) {
        if (this.state.counter === this.state.items.length - 1) {
            this._markQuestionsAnswered();
        }

        this[clickType] = true;

        const infoElem = jQuery('.question-item');

        infoElem.removeClass('fadeInDown').addClass('fadeOutUp');

        infoElem.on('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', jQuery.proxy(function(event) {
            if (event.originalEvent.animationName === 'fadeOutUp' && this[clickType] === true) {
                switch(clickType) {
                    case 'onNextClick':
                        this.setState((prevState) => ({
                            counter: ++prevState.counter
                        }));

                        break;
                    case 'onPrevClick':
                        this.setState((prevState) => ({
                            counter: --prevState.counter
                        }));

                        break;
                }

                jQuery('.question-item').removeClass('fadeOutUp').addClass('fadeInDown');

                this[clickType] = false;
            }
        }, this));
    }

    render() {
        if (this.state.items === null) {
            return null;
        }

        const counter = this.state.counter;
        const item = this.state.items[counter];
        const firstItem = counter === 0;

        return (
            <div className="questions-wrapper">
                <Item
                    item={item}
                    nextItem={this.next}
                    prevItem={this.prev}
                    onAnswer={this.onAnswer}
                    firstItem={firstItem}
                />
            </div>
        )
    }
}