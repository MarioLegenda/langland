class Url {
    constructor() {
        this.pathname = window.location.pathname;
    }

    getParsed() {
        let path = this.pathname.split('/');

        for (let i = 0; i < path.length; i++) {
            if (path[i] === "") {
                path.splice(i, 1);
            }

            if (path[i] === 'app_dev.php') {
                path.splice(i, 1);
            }
        }

        return path;
    }
}

export const url = new Url();