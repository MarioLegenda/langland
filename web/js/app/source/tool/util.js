class UrlRecorderClass {
    constructor() {
        this.urlRecord = {};
    }

    static create() {

    }

    record(name, value) {
        this.urlRecord[name] = value;
    }

    getRecord(name) {
        if (!this.urlRecord.hasOwnProperty('language-list')) {
            throw new Error(`Url record with name ${name} does not exist`);
        }

        return this.urlRecord[name];
    }
}

const urlRecorder = new UrlRecorderClass();

export const UrlRecorder = urlRecorder;

export class Util {
    static isString(value) {
        return Object.prototype.toString.call(value) === "[object String]"
    }

    static isObject(value) {
        if (value === null) { return false; }

        return ( (typeof value === 'function') || (typeof value === 'object') );
    }

    static isFunction(value) {
        return value && {}.toString.call(value) === '[object Function]';
    }

    static toArray(value) {
        return toString.call(obj) === "[object Array]";
    }
}
