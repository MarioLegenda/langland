import {LanguageRepository} from "./languageRepository.js";
import {UserRepository} from "./userRepository.js";
import {LearningUserRepository} from "./learningUserRepository.js";
import {LearningSystemRepository} from "./learningSystemRepository.js";
import {Cache} from "./cache.js";

const cache = new Cache();

export function factory(repository) {
    switch (repository) {
        case 'language':
            return new LanguageRepository();
        case 'user':
            return new UserRepository();
        case 'learning-user':
            return new LearningUserRepository();
        case 'learning-system':
            return new LearningSystemRepository();
    }

    throw new Error('Repository ' + repository + ' not found');
}