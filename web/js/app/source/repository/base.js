import {user} from "../../../global/constants";

export class BaseRepository {
    makeRequest(config) {
        fetch(config.url, config.config)
            .then(config.success);
    }

    getTypicalHeaders() {
        return new Headers({
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-LANGLAND-PUBLIC-API': user.current.username,
            'X-Requested-With': 'XMLHttpRequest'
        });
    }

    handleBasicInvalidResponse(response) {
        if (!response.ok) {
            throw new Error(`Invalid response. Status code: ${response.status}; Text: ${response.statusText}`);
        }

        return response;
    }
}