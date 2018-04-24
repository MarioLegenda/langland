import React from 'react';

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
