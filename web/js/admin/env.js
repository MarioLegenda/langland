function env () {
    const path = window.location.pathname;
    let env = '';

    if (/app_dev.php/.test(path)) {
        env = '/app_dev.php/';
    } else {
        env = '/';
    }

    return env;
}

export const envr = env();