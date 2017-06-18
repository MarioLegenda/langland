import React from 'react';

export class Game extends React.Component {
	constructor(props) {
		super(props);

		this.data = {
			learningUserCourseId: this.props.learningUserCourseId,
			learningUserGameId: this.props.match.params.learningUserGameId,
			gameName: this.props.match.params.gameName,
			courseName: this.props.courseName
		};

		this.state = {
			gameDecision: false
		};
	}

	_fetchDecideGameType() {
		this.props.DataSource.fetchDecideGameTypes(this.data.learningUserGameId)
			.done(jQuery.proxy(function(data) {
				this.setState({
					gameDecision: true
				});
			}, this));
	}

	componentDidMount() {
		this._fetchDecideGameType();
	}

	render() {
		if (this.state.gameDecision === false) {
			return null;
		}

		return <div></div>
	}
}
