<?php

declare(strict_types=1);

namespace App\Enums;

enum QuestionTypeEnum: string
{
    use EnumToArray;

    case SINGLE_CHOICE         = 'single_choice';
    case MULTIPLE_CHOICE       = 'multiple_choice';
    case SINGLE_CHOICE_IMAGE   = 'single_choice_image';
    case MULTIPLE_CHOICE_IMAGE = 'multiple_choice_image';

    case TRUE_FALSE   = 'true_false';
    case SHORT_ANSWER = 'short_answer';
    case ESSAY        = 'essay';

    case ORDERING = 'ordering';
    case MATCHING = 'matching';

    case DRAG_AND_DROP  = 'drag_and_drop';
    case TEXT_HIGHLIGHT = 'text_highlight';
    case TEXT_SELECT    = 'text_select';
    case WORD_CHOICE    = 'word_choice';

    case BOW_TIE  = 'bow_tie';
    case MATRIX   = 'matrix';
    case HOT_SPOT = 'hot_spot';

    public function title(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE         => 'تک گزینه‌ای',
            self::MULTIPLE_CHOICE       => 'چند گزینه‌ای',
            self::SINGLE_CHOICE_IMAGE   => 'تک گزینه‌ای تصویری',
            self::MULTIPLE_CHOICE_IMAGE => 'چند گزینه‌ای تصویری',
            self::TRUE_FALSE            => 'درست/غلط',
            self::SHORT_ANSWER          => 'پاسخ کوتاه',
            self::ESSAY                 => 'تشریحی',
            self::ORDERING              => 'مرتب‌سازی',
            self::MATCHING              => 'تطبیق',
            self::DRAG_AND_DROP         => 'کشیدن و رها کردن',
            self::TEXT_HIGHLIGHT        => 'هایلایت متن',
            self::TEXT_SELECT           => 'انتخاب از متن',
            self::WORD_CHOICE           => 'انتخاب کلمه',
            self::BOW_TIE               => 'Bow Tie',
            self::MATRIX                => 'ماتریسی',
            self::HOT_SPOT              => 'نقطه داغ (Hot Spot)',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE         => 'انتخاب یک گزینه از بین چند گزینه',
            self::MULTIPLE_CHOICE       => 'انتخاب چند گزینه از بین گزینه‌های متعدد',
            self::SINGLE_CHOICE_IMAGE   => 'انتخاب یک گزینه تصویری',
            self::MULTIPLE_CHOICE_IMAGE => 'انتخاب چند گزینه تصویری',
            self::TRUE_FALSE            => 'انتخاب بین درست یا غلط',
            self::SHORT_ANSWER          => 'پاسخ کوتاه متنی',
            self::ESSAY                 => 'پاسخ تشریحی بلند',
            self::ORDERING              => 'مرتب‌سازی آیتم‌ها با کشیدن و رها کردن',
            self::MATCHING              => 'تطبیق آیتم‌ها با یکدیگر',
            self::DRAG_AND_DROP         => 'کشیدن و رها کردن در محل مناسب',
            self::TEXT_HIGHLIGHT        => 'انتخاب و هایلایت بخشی از متن',
            self::TEXT_SELECT           => 'انتخاب کلمه یا عبارت از متن',
            self::WORD_CHOICE           => 'انتخاب کلمه مناسب برای جای خالی',
            self::BOW_TIE               => 'سوال پیچیده با چند بخش مرتبط',
            self::MATRIX                => 'پاسخ به سوالات در قالب جدول',
            self::HOT_SPOT              => 'انتخاب نقطه روی تصویر',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE         => 'heroicon-o-check-circle',
            self::MULTIPLE_CHOICE       => 'heroicon-o-check-badge',
            self::SINGLE_CHOICE_IMAGE   => 'heroicon-o-photo',
            self::MULTIPLE_CHOICE_IMAGE => 'heroicon-o-photo',
            self::TRUE_FALSE            => 'heroicon-o-scale',
            self::SHORT_ANSWER          => 'heroicon-o-pencil-square',
            self::ESSAY                 => 'heroicon-o-document-text',
            self::ORDERING              => 'heroicon-o-arrows-up-down',
            self::MATCHING              => 'heroicon-o-arrows-right-left',
            self::DRAG_AND_DROP         => 'heroicon-o-cursor-arrow-rays',
            self::TEXT_HIGHLIGHT        => 'heroicon-o-highlight',
            self::TEXT_SELECT           => 'heroicon-o-cursor-arrow-ripple',
            self::WORD_CHOICE           => 'heroicon-o-queue-list',
            self::BOW_TIE               => 'heroicon-o-puzzle-piece',
            self::MATRIX                => 'heroicon-o-table-cells',
            self::HOT_SPOT              => 'heroicon-o-map-pin',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE         => 'blue',
            self::MULTIPLE_CHOICE       => 'indigo',
            self::SINGLE_CHOICE_IMAGE   => 'purple',
            self::MULTIPLE_CHOICE_IMAGE => 'pink',
            self::TRUE_FALSE            => 'green',
            self::SHORT_ANSWER          => 'yellow',
            self::ESSAY                 => 'orange',
            self::ORDERING              => 'red',
            self::MATCHING              => 'teal',
            self::DRAG_AND_DROP         => 'cyan',
            self::TEXT_HIGHLIGHT        => 'lime',
            self::TEXT_SELECT           => 'emerald',
            self::WORD_CHOICE           => 'sky',
            self::BOW_TIE               => 'violet',
            self::MATRIX                => 'fuchsia',
            self::HOT_SPOT              => 'rose',
        };
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->title(),
            'color' => $this->color(),
        ];
    }

    public function handler(): string
    {
        return match ($this) {
            self::SINGLE_CHOICE         => \App\QuestionTypes\SingleChoiceType::class,
            self::MULTIPLE_CHOICE       => \App\QuestionTypes\MultipleChoiceType::class,
            self::SINGLE_CHOICE_IMAGE   => \App\QuestionTypes\SingleChoiceImageType::class,
            self::MULTIPLE_CHOICE_IMAGE => \App\QuestionTypes\MultipleChoiceImageType::class,
            self::TRUE_FALSE            => \App\QuestionTypes\TrueFalseType::class,
            self::SHORT_ANSWER          => \App\QuestionTypes\ShortAnswerType::class,
            self::ESSAY                 => \App\QuestionTypes\EssayType::class,
            self::ORDERING              => \App\QuestionTypes\OrderingType::class,
            self::MATCHING              => \App\QuestionTypes\MatchingType::class,
            self::DRAG_AND_DROP         => \App\QuestionTypes\DragAndDropType::class,
            self::TEXT_HIGHLIGHT        => \App\QuestionTypes\TextHighlightType::class,
            self::TEXT_SELECT           => \App\QuestionTypes\TextSelectType::class,
            self::WORD_CHOICE           => \App\QuestionTypes\WordChoiceType::class,
            self::BOW_TIE               => \App\QuestionTypes\BowTieType::class,
            self::MATRIX                => \App\QuestionTypes\MatrixType::class,
            self::HOT_SPOT              => \App\QuestionTypes\HotSpotType::class,
        };
    }

    public function needsOptions(): bool
    {
        return in_array($this, [
            self::SINGLE_CHOICE,
            self::MULTIPLE_CHOICE,
            self::SINGLE_CHOICE_IMAGE,
            self::MULTIPLE_CHOICE_IMAGE,
            self::TRUE_FALSE,
            self::ORDERING,
            self::MATCHING,
        ]);
    }

    public function supportsPartialCredit(): bool
    {
        return in_array($this, [
            self::MULTIPLE_CHOICE,
            self::ORDERING,
            self::MATCHING,
            self::MATRIX,
        ]);
    }

    public function requiresManualGrading(): bool
    {
        return in_array($this, [
            self::SHORT_ANSWER,
            self::ESSAY,
        ]);
    }
}
