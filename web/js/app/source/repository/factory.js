import {LanguageRepository} from "./languageRepository.js";
import {UserRepository} from "./userRepository.js";
import {LearningUserRepository} from "./learningUserRepository.js";
import {LearningSystemRepository} from "./learningSystemRepository.js";
import {MetadataPresentationRepository} from "./metadataPresentationRepository";
import {Cache} from "./cache.js";

const cache = new Cache();

const singletons = {
    'metadata-presentation': null
};

export function factory(repository, asSingleton = false) {
    switch (repository) {
        case 'language':
            return new LanguageRepository();
        case 'user':
            return new UserRepository();
        case 'learning-user':
            return new LearningUserRepository();
        case 'learning-system':
            return new LearningSystemRepository();
        case 'metadata-presentation':
            return new MetadataPresentationRepository();
    }

    throw new Error('Repository ' + repository + ' not found');
}