<?php

declare(strict_types=1);

use App\Enums\BooleanEnum;
use App\Enums\DifficultyEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\ExamTypeEnum;
use App\Enums\QuestionTypeEnum;
use App\Enums\ShowResultsEnum;

return [
    // Question Competency for English
    'question_competency' => [
        [
            'title' => 'English Grammar',
            'description' => 'Grammar and sentence structure competency',
        ],
        [
            'title' => 'English Vocabulary',
            'description' => 'Vocabulary and word usage competency',
        ],
    ],

    // Question Subject for English
    'question_subject' => [
        [
            'title' => 'English Language',
            'description' => 'English Language Subject',
            'ordering' => 2,
            'published' => BooleanEnum::ENABLE->value,
        ],
    ],

    // Exams Configuration
    'exams' => [
        [
            'title' => 'English Language Level 1 Exam',
            'description' => 'Basic English language examination covering fundamental grammar, vocabulary, and comprehension skills.',
            'type' => ExamTypeEnum::SCORED->value,
            'total_score' => 20,
            'duration' => 30,
            'pass_score' => 12,
            'max_attempts' => 1,
            'shuffle_questions' => true,
            'show_results' => ShowResultsEnum::AFTER_SUBMIT->value,
            'allow_review' => true,
            'status' => ExamStatusEnum::PUBLISHED->value,
            'tags' => ['English', 'Level 1', 'Beginner'],
        ],
        [
            'title' => 'English Language Level 2 Exam',
            'description' => 'Intermediate English language examination covering advanced grammar, vocabulary, and reading comprehension.',
            'type' => ExamTypeEnum::SCORED->value,
            'total_score' => 20,
            'duration' => 45,
            'pass_score' => 12,
            'max_attempts' => 1,
            'shuffle_questions' => true,
            'show_results' => ShowResultsEnum::AFTER_SUBMIT->value,
            'allow_review' => true,
            'status' => ExamStatusEnum::PUBLISHED->value,
            'tags' => ['English', 'Level 2', 'Intermediate'],
        ],
    ],

    // Level 1 Questions (20 questions - Beginner)
    'questions_level_1' => [
        // Single Choice Questions (12 questions)
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct form of "to be": She ___ a teacher.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'is', 'is_correct' => true, 'order' => 1],
                ['content' => 'am', 'is_correct' => false, 'order' => 2],
                ['content' => 'are', 'is_correct' => false, 'order' => 3],
                ['content' => 'be', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'With third person singular subjects (he, she, it), we use "is".',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'What is the plural of "child"?',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'children', 'is_correct' => true, 'order' => 1],
                ['content' => 'childs', 'is_correct' => false, 'order' => 2],
                ['content' => 'childrens', 'is_correct' => false, 'order' => 3],
                ['content' => 'child', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Child" has an irregular plural form: "children".',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Select the correct article: I saw ___ elephant at the zoo.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'an', 'is_correct' => true, 'order' => 1],
                ['content' => 'a', 'is_correct' => false, 'order' => 2],
                ['content' => 'the', 'is_correct' => false, 'order' => 3],
                ['content' => 'no article needed', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'We use "an" before words that start with a vowel sound.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Which sentence is correct?',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'He goes to school every day.', 'is_correct' => true, 'order' => 1],
                ['content' => 'He go to school every day.', 'is_correct' => false, 'order' => 2],
                ['content' => 'He going to school every day.', 'is_correct' => false, 'order' => 3],
                ['content' => 'He gos to school every day.', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'With third person singular in simple present, we add "-es" or "-s" to the verb.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'What is the past tense of "eat"?',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'ate', 'is_correct' => true, 'order' => 1],
                ['content' => 'eated', 'is_correct' => false, 'order' => 2],
                ['content' => 'eaten', 'is_correct' => false, 'order' => 3],
                ['content' => 'eating', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Eat" is an irregular verb. Its past tense is "ate".',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct pronoun: ___ is my best friend.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'She', 'is_correct' => true, 'order' => 1],
                ['content' => 'Her', 'is_correct' => false, 'order' => 2],
                ['content' => 'Hers', 'is_correct' => false, 'order' => 3],
                ['content' => 'Him', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"She" is a subject pronoun and should be used as the subject of a sentence.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'What is the opposite of "hot"?',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'cold', 'is_correct' => true, 'order' => 1],
                ['content' => 'warm', 'is_correct' => false, 'order' => 2],
                ['content' => 'cool', 'is_correct' => false, 'order' => 3],
                ['content' => 'heat', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Cold" is the direct opposite of "hot".',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Complete the sentence: There ___ many books on the table.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'are', 'is_correct' => true, 'order' => 1],
                ['content' => 'is', 'is_correct' => false, 'order' => 2],
                ['content' => 'be', 'is_correct' => false, 'order' => 3],
                ['content' => 'was', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'We use "are" with plural nouns like "books".',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Which word means "a place where you sleep"?',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'bedroom', 'is_correct' => true, 'order' => 1],
                ['content' => 'kitchen', 'is_correct' => false, 'order' => 2],
                ['content' => 'bathroom', 'is_correct' => false, 'order' => 3],
                ['content' => 'living room', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'A bedroom is a room primarily used for sleeping.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct preposition: The cat is ___ the table.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'under', 'is_correct' => true, 'order' => 1],
                ['content' => 'in', 'is_correct' => false, 'order' => 2],
                ['content' => 'at', 'is_correct' => false, 'order' => 3],
                ['content' => 'to', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Under" indicates position below something.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'What time is it? (3:00)',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => "It's three o'clock.", 'is_correct' => true, 'order' => 1],
                ['content' => "It's three hours.", 'is_correct' => false, 'order' => 2],
                ['content' => "It's three.", 'is_correct' => false, 'order' => 3],
                ['content' => "It's clock three.", 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'We say "It\'s [number] o\'clock" for times on the hour.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Which day comes after Monday?',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'Tuesday', 'is_correct' => true, 'order' => 1],
                ['content' => 'Wednesday', 'is_correct' => false, 'order' => 2],
                ['content' => 'Sunday', 'is_correct' => false, 'order' => 3],
                ['content' => 'Thursday', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'The days of the week in order: Monday, Tuesday, Wednesday...',
        ],

        // True/False Questions (5 questions)
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => '"Apple" starts with a vowel sound.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => '"Apple" starts with "a" which is a vowel.',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => 'The past tense of "go" is "goed".',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'correct_answer' => ['value' => false],
            'explanation' => '"Go" is an irregular verb. Its past tense is "went".',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => '"I am happy" is a correct sentence.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => 'This is a grammatically correct sentence using "am" with "I".',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => 'A noun is a word that describes an action.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'correct_answer' => ['value' => false],
            'explanation' => 'A noun is a word that names a person, place, thing, or idea. Verbs describe actions.',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => 'The word "beautiful" is an adjective.',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => '"Beautiful" is an adjective because it describes a noun.',
        ],

        // Multiple Choice Questions (3 questions)
        [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
            'title' => 'Select ALL the colors from the list below:',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'config' => ['scoring_type' => 'all_or_nothing'],
            'options' => [
                ['content' => 'red', 'is_correct' => true, 'order' => 1],
                ['content' => 'blue', 'is_correct' => true, 'order' => 2],
                ['content' => 'table', 'is_correct' => false, 'order' => 3],
                ['content' => 'green', 'is_correct' => true, 'order' => 4],
            ],
            'explanation' => 'Red, blue, and green are colors. Table is a piece of furniture.',
        ],
        [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
            'title' => 'Which of these are body parts? (Select all that apply)',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'config' => ['scoring_type' => 'all_or_nothing'],
            'options' => [
                ['content' => 'hand', 'is_correct' => true, 'order' => 1],
                ['content' => 'chair', 'is_correct' => false, 'order' => 2],
                ['content' => 'foot', 'is_correct' => true, 'order' => 3],
                ['content' => 'head', 'is_correct' => true, 'order' => 4],
            ],
            'explanation' => 'Hand, foot, and head are body parts. Chair is furniture.',
        ],
        [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
            'title' => 'Select ALL the fruits:',
            'difficulty' => DifficultyEnum::EASY->value,
            'default_score' => 1,
            'config' => ['scoring_type' => 'all_or_nothing'],
            'options' => [
                ['content' => 'apple', 'is_correct' => true, 'order' => 1],
                ['content' => 'carrot', 'is_correct' => false, 'order' => 2],
                ['content' => 'banana', 'is_correct' => true, 'order' => 3],
                ['content' => 'orange', 'is_correct' => true, 'order' => 4],
            ],
            'explanation' => 'Apple, banana, and orange are fruits. Carrot is a vegetable.',
        ],
    ],

    // Level 2 Questions (20 questions - Intermediate)
    'questions_level_2' => [
        // Single Choice Questions (12 questions)
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct form: If I ___ rich, I would travel the world.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'were', 'is_correct' => true, 'order' => 1],
                ['content' => 'was', 'is_correct' => false, 'order' => 2],
                ['content' => 'am', 'is_correct' => false, 'order' => 3],
                ['content' => 'be', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'In second conditional (hypothetical situations), we use "were" for all subjects.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Select the correct relative pronoun: The book ___ I read was interesting.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'which', 'is_correct' => true, 'order' => 1],
                ['content' => 'who', 'is_correct' => false, 'order' => 2],
                ['content' => 'whom', 'is_correct' => false, 'order' => 3],
                ['content' => 'whose', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Which" is used for things, "who" is used for people.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct passive form: The letter ___ yesterday.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'was written', 'is_correct' => true, 'order' => 1],
                ['content' => 'written', 'is_correct' => false, 'order' => 2],
                ['content' => 'is written', 'is_correct' => false, 'order' => 3],
                ['content' => 'wrote', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'Past passive: was/were + past participle. "Yesterday" indicates past tense.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Which sentence uses the present perfect correctly?',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'I have lived here for five years.', 'is_correct' => true, 'order' => 1],
                ['content' => 'I have lived here since five years.', 'is_correct' => false, 'order' => 2],
                ['content' => 'I am living here for five years.', 'is_correct' => false, 'order' => 3],
                ['content' => 'I lived here for five years.', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'Present perfect with "for" + duration of time.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct modal verb: You ___ see a doctor about that cough.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'should', 'is_correct' => true, 'order' => 1],
                ['content' => 'must to', 'is_correct' => false, 'order' => 2],
                ['content' => 'can to', 'is_correct' => false, 'order' => 3],
                ['content' => 'would to', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Should" gives advice. Modal verbs are not followed by "to".',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'What is the meaning of "postpone"?',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'to delay to a later time', 'is_correct' => true, 'order' => 1],
                ['content' => 'to cancel completely', 'is_correct' => false, 'order' => 2],
                ['content' => 'to do immediately', 'is_correct' => false, 'order' => 3],
                ['content' => 'to forget about', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Postpone" means to arrange for something to happen at a later time.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Complete the sentence: She suggested ___ to the cinema.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'going', 'is_correct' => true, 'order' => 1],
                ['content' => 'to go', 'is_correct' => false, 'order' => 2],
                ['content' => 'go', 'is_correct' => false, 'order' => 3],
                ['content' => 'went', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Suggest" is followed by a gerund (-ing form).',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct phrasal verb: Please ___ the lights when you leave.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'turn off', 'is_correct' => true, 'order' => 1],
                ['content' => 'turn up', 'is_correct' => false, 'order' => 2],
                ['content' => 'turn on', 'is_correct' => false, 'order' => 3],
                ['content' => 'turn down', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Turn off" means to switch off. "Turn on" means to switch on.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Identify the correct reported speech: "I am happy," she said. → She said ___.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'she was happy', 'is_correct' => true, 'order' => 1],
                ['content' => 'she is happy', 'is_correct' => false, 'order' => 2],
                ['content' => 'she am happy', 'is_correct' => false, 'order' => 3],
                ['content' => 'I was happy', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'In reported speech, pronouns and tenses shift back.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'What does "reluctant" mean?',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'unwilling or hesitant', 'is_correct' => true, 'order' => 1],
                ['content' => 'excited and eager', 'is_correct' => false, 'order' => 2],
                ['content' => 'angry and upset', 'is_correct' => false, 'order' => 3],
                ['content' => 'tired and sleepy', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Reluctant" means hesitant or unwilling to do something.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Choose the correct comparative: This exam is ___ than the last one.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'more difficult', 'is_correct' => true, 'order' => 1],
                ['content' => 'difficulter', 'is_correct' => false, 'order' => 2],
                ['content' => 'most difficult', 'is_correct' => false, 'order' => 3],
                ['content' => 'more difficulter', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'For long adjectives, we use "more + adjective" for comparatives.',
        ],
        [
            'type' => QuestionTypeEnum::SINGLE_CHOICE->value,
            'title' => 'Select the correct conjunction: ___ it was raining, we went for a walk.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'options' => [
                ['content' => 'Although', 'is_correct' => true, 'order' => 1],
                ['content' => 'Because', 'is_correct' => false, 'order' => 2],
                ['content' => 'So', 'is_correct' => false, 'order' => 3],
                ['content' => 'Therefore', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => '"Although" shows contrast between two ideas.',
        ],

        // True/False Questions (5 questions)
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => 'In English, adjectives usually come before nouns.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => 'English typically places adjectives before nouns (e.g., "a beautiful house").',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => '"Could" can be used to make polite requests.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => '"Could" is used for polite requests, e.g., "Could you help me?"',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => 'The present perfect tense is formed with "have/has + past participle".',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => 'Present perfect: have/has + past participle (e.g., "I have eaten").',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => '"Affect" and "effect" have the same meaning and usage.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'correct_answer' => ['value' => false],
            'explanation' => '"Affect" is usually a verb; "effect" is usually a noun.',
        ],
        [
            'type' => QuestionTypeEnum::TRUE_FALSE->value,
            'title' => 'A gerund is a verb form that functions as a noun.',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'correct_answer' => ['value' => true],
            'explanation' => 'A gerund is a verb ending in -ing used as a noun (e.g., "Swimming is fun").',
        ],

        // Multiple Choice Questions (3 questions)
        [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
            'title' => 'Which of the following are irregular verbs? (Select all that apply)',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'config' => ['scoring_type' => 'all_or_nothing'],
            'options' => [
                ['content' => 'go', 'is_correct' => true, 'order' => 1],
                ['content' => 'walked', 'is_correct' => false, 'order' => 2],
                ['content' => 'swim', 'is_correct' => true, 'order' => 3],
                ['content' => 'write', 'is_correct' => true, 'order' => 4],
            ],
            'explanation' => 'Go (went), swim (swam), write (wrote) are irregular. Walk is regular (walked).',
        ],
        [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
            'title' => 'Select ALL sentences that use correct subject-verb agreement:',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'config' => ['scoring_type' => 'all_or_nothing'],
            'options' => [
                ['content' => 'The team is playing well.', 'is_correct' => true, 'order' => 1],
                ['content' => 'Neither of the students have finished.', 'is_correct' => false, 'order' => 2],
                ['content' => 'Everyone has their own opinion.', 'is_correct' => true, 'order' => 3],
                ['content' => 'The news are interesting.', 'is_correct' => false, 'order' => 4],
            ],
            'explanation' => 'Collective nouns and "everyone" take singular verbs. "News" is uncountable.',
        ],
        [
            'type' => QuestionTypeEnum::MULTIPLE_CHOICE->value,
            'title' => 'Which words are adverbs? (Select all that apply)',
            'difficulty' => DifficultyEnum::MEDIUM->value,
            'default_score' => 1,
            'config' => ['scoring_type' => 'all_or_nothing'],
            'options' => [
                ['content' => 'quickly', 'is_correct' => true, 'order' => 1],
                ['content' => 'beautiful', 'is_correct' => false, 'order' => 2],
                ['content' => 'carefully', 'is_correct' => true, 'order' => 3],
                ['content' => 'very', 'is_correct' => true, 'order' => 4],
            ],
            'explanation' => 'Adverbs modify verbs, adjectives, or other adverbs. "Beautiful" is an adjective.',
        ],
    ],
];
