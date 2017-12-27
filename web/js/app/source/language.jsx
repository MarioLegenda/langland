import React from 'react';
import ReactDOM from 'react-dom';
import {factory as repoFactory} from "./repository/factory.js";

export class List extends React.Component{
    constructor() {
        super();

        console.log('ulazak');

        this.languageRepository = repoFactory('language')
    }

    componentDidMount() {
        this.languageRepository.getAll($.proxy(function(data) {
            console.log(data);
        }), $.proxy(function(data) {

        }));
    }

    render() {
        return <div>

        </div>
    }
}