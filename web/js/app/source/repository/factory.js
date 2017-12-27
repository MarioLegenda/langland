import {LanguageRepository} from "./languageRepository.js";
import {UserRepository} from "./userRepository.js";

export function factory(repository) {
    switch (repository) {
        case 'language':
            return new LanguageRepository();
        case 'user':
            return new UserRepository();
    }
}