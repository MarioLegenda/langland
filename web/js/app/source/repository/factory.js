import {LanguageRepository} from "./languageRepository.js";
import {UserRepository} from "./userRepository.js";
import {LearningUserRepository} from "./learningUserRepository.js";
import {LearningSystemRepository} from "./learningSystemRepository.js";
import {MetadataPresentationRepository} from "./metadataPresentationRepository";

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
        case 'metadata-presentation':
            return new MetadataPresentationRepository();
    }

    throw new Error('Repository ' + repository + ' not found');
}