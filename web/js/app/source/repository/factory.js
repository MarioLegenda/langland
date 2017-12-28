import {LanguageRepository} from "./languageRepository.js";
import {UserRepository} from "./userRepository.js";
import {LearningUserRepository} from "./learningUserRepository";

export function factory(repository) {
    switch (repository) {
        case 'language':
            return new LanguageRepository();
        case 'user':
            return new UserRepository();
        case 'learning-user':
            return new LearningUserRepository();
    }
}