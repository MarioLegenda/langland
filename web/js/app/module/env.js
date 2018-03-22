function env () {
    const path = window.location.pathname;

    if (/app_dev.php/.test(path)) {
        let environment = '/app_dev.php/';
    } else {
        let environment = '/';
    }

    return environment;
}

export const envr = env();