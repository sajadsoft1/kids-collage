# Models Fillable Fields and Relations Analysis with AdvancedSearchFields Implementation

## Overview

This document analyzes all Laravel models in the kids-collage project to document their fillable fields and relationships, then provides comprehensive implementations for the AdvancedSearchFields handlers and drivers based on the User and Blog examples.

## Models Analysis

### Core Content Models

#### User Model
```php
protected $fillable = [
    'name', 'family', 'email', 'mobile', 'gender', 'password', 'status'
];
```
**Relations:**
- `profile()` → HasOne Profile
- `blogs()` → HasMany Blog  
- `boards()` → BelongsToMany Board (with pivot role)
- `assignedCards()` → BelongsToMany Card (assignee role)
- `reviewingCards()` → BelongsToMany Card (reviewer role)
- `watchingCards()` → BelongsToMany Card (watcher role)
- `cardHistory()` → HasMany CardHistory

#### Blog Model
```php
protected $fillable = [
    'slug', 'published', 'published_at', 'user_id', 'category_id', 
    'view_count', 'comment_count', 'wish_count', 'languages'
];
```
**Relations:**
- Uses traits: HasUser, HasCategory, HasComment, HasView, HasWishList, HasTags
- Polymorphic relations through traits

#### Category Model
```php
protected $fillable = [
    'published', 'parent_id', 'slug', 'type', 'languages', 
    'ordering', 'view_count', 'extra_attributes'
];
```
**Relations:**
- `parent()` → BelongsTo Category
- `children()` → HasMany Category
- `sliders()` → MorphMany Slider
- `blogs()` → HasMany Blog
- `faqs()` → HasMany Faq
- `portfolios()` → HasMany Portfolio

### Kanban System Models

#### Board Model
```php
protected $fillable = [
    'name', 'description', 'color', 'is_active', 
    'system_protected', 'extra_attributes'
];
```
**Relations:**
- `users()` → BelongsToMany User (with pivot role)
- `columns()` → HasMany Column
- `cards()` → HasMany Card
- `cardFlows()` → HasMany CardFlow

#### Card Model
```php
protected $fillable = [
    'board_id', 'column_id', 'title', 'description', 'card_type', 
    'priority', 'status', 'due_date', 'order', 'extra_attributes'
];
```
**Relations:**
- `board()` → BelongsTo Board
- `column()` → BelongsTo Column
- `users()` → BelongsToMany User (with pivot role)
- `history()` → HasMany CardHistory
- `assignees()` → BelongsToMany User (assignee role)
- `reviewers()` → BelongsToMany User (reviewer role)
- `watchers()` → BelongsToMany User (watcher role)

### Support System Models

#### Ticket Model
```php
protected $fillable = [
    'subject', 'department', 'user_id', 'closed_by', 
    'status', 'key', 'priority'
];
```
**Relations:**
- `messages()` → HasMany TicketMessage
- `user()` → BelongsTo User
- `closeBy()` → BelongsTo User

#### Comment Model
```php
protected $fillable = [
    'user_id', 'admin_id', 'parent_id', 'morphable_id', 'morphable_type', 
    'published', 'comment', 'admin_note', 'suggest', 'rate', 
    'languages', 'published_at'
];
```
**Relations:**
- `children()` → HasMany Comment
- `parent()` → BelongsTo Comment
- `user()` → BelongsTo User
- `admin()` → BelongsTo User

### Media & Content Models

#### Banner Model
```php
protected $fillable = [
    'published', 'size', 'click', 'published_at', 'languages'
];
```

#### Tag Model
```php
protected $fillable = [
    'languages', 'name', 'slug', 'order_column', 'type'
];
```

## AdvancedSearchFields Implementation

### Handlers Implementation

#### CategoryHandler
```php
class CategoryHandler extends BaseHandler
{
    public function handle(): array
    {
        $parentCategories = [];
        foreach (Category::whereNull('parent_id')->get() as $category) {
            $parentCategories[] = $this->option((string) $category->id, $category->title);
        }

        $types = [];
        foreach (CategoryTypeEnum::cases() as $type) {
            $types[] = $this->option($type->value, $type->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published', __('validation.attributes.published'), self::SELECT, [
                $this->option((string)BooleanEnum::ENABLE->value, BooleanEnum::ENABLE->title()),
                $this->option((string)BooleanEnum::DISABLE->value, BooleanEnum::DISABLE->title())
            ]),
            $this->add('parent_id', __('validation.attributes.parent_category'), self::SELECT, $parentCategories),
            $this->add('type', __('validation.attributes.type'), self::SELECT, $types),
            $this->add('ordering', __('validation.attributes.ordering'), self::NUMBER),
            $this->add('view_count', __('validation.attributes.view_count'), self::NUMBER),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

#### CardHandler
```php
class CardHandler extends BaseHandler
{
    public function handle(): array
    {
        $boards = [];
        foreach (Board::where('is_active', true)->get() as $board) {
            $boards[] = $this->option((string) $board->id, $board->name);
        }

        $cardTypes = [];
        foreach (CardTypeEnum::cases() as $type) {
            $cardTypes[] = $this->option($type->value, $type->title());
        }

        $priorities = [];
        foreach (PriorityEnum::cases() as $priority) {
            $priorities[] = $this->option($priority->value, $priority->title());
        }

        $statuses = [];
        foreach (CardStatusEnum::cases() as $status) {
            $statuses[] = $this->option($status->value, $status->title());
        }

        $assignees = [];
        foreach (User::whereHas('assignedCards')->get() as $user) {
            $assignees[] = $this->option((string) $user->id, $user->full_name);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('board_id', __('validation.attributes.board'), self::SELECT, $boards),
            $this->add('title', __('validation.attributes.title'), self::INPUT),
            $this->add('card_type', __('validation.attributes.card_type'), self::SELECT, $cardTypes),
            $this->add('priority', __('validation.attributes.priority'), self::SELECT, $priorities),
            $this->add('status', __('validation.attributes.status'), self::SELECT, $statuses),
            $this->add('due_date', __('validation.attributes.due_date'), self::DATE),
            $this->add('assignee_id', __('validation.attributes.assignee'), self::SELECT, $assignees),
            $this->add('overdue', __('validation.attributes.overdue'), self::SELECT, [
                $this->option('yes', __('common.yes')),
                $this->option('no', __('common.no'))
            ]),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

#### TicketHandler
```php
class TicketHandler extends BaseHandler
{
    public function handle(): array
    {
        $departments = [];
        foreach (TicketDepartmentEnum::cases() as $department) {
            $departments[] = $this->option($department->value, $department->title());
        }

        $priorities = [];
        foreach (TicketPriorityEnum::cases() as $priority) {
            $priorities[] = $this->option((string) $priority->value, $priority->title());
        }

        $statuses = [];
        foreach (TicketStatusEnum::cases() as $status) {
            $statuses[] = $this->option($status->value, $status->title());
        }

        $users = [];
        foreach (User::whereHas('tickets')->get() as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('subject', __('validation.attributes.subject'), self::INPUT),
            $this->add('department', __('validation.attributes.department'), self::SELECT, $departments),
            $this->add('status', __('validation.attributes.status'), self::SELECT, $statuses),
            $this->add('priority', __('validation.attributes.priority'), self::SELECT, $priorities),
            $this->add('user_id', __('validation.attributes.user'), self::SELECT, $users),
            $this->add('key', __('validation.attributes.ticket_key'), self::INPUT),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

#### CommentHandler
```php
class CommentHandler extends BaseHandler
{
    public function handle(): array
    {
        $users = [];
        foreach (User::whereHas('comments')->get() as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        $suggests = [];
        foreach (YesNoEnum::cases() as $suggest) {
            $suggests[] = $this->option($suggest->value, $suggest->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published', __('validation.attributes.published'), self::SELECT, [
                $this->option((string)BooleanEnum::ENABLE->value, BooleanEnum::ENABLE->title()),
                $this->option((string)BooleanEnum::DISABLE->value, BooleanEnum::DISABLE->title())
            ]),
            $this->add('user_id', __('validation.attributes.user'), self::SELECT, $users),
            $this->add('suggest', __('validation.attributes.suggest'), self::SELECT, $suggests),
            $this->add('rate', __('validation.attributes.rate'), self::NUMBER),
            $this->add('morphable_type', __('validation.attributes.content_type'), self::SELECT, [
                $this->option('App\\Models\\Blog', __('models.blog')),
                $this->option('App\\Models\\Portfolio', __('models.portfolio')),
            ]),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

#### BannerHandler
```php
class BannerHandler extends BaseHandler
{
    public function handle(): array
    {
        $sizes = [];
        foreach (BannerSizeEnum::cases() as $size) {
            $sizes[] = $this->option($size->value, $size->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('published', __('validation.attributes.published'), self::SELECT, [
                $this->option((string)BooleanEnum::ENABLE->value, BooleanEnum::ENABLE->title()),
                $this->option((string)BooleanEnum::DISABLE->value, BooleanEnum::DISABLE->title())
            ]),
            $this->add('size', __('validation.attributes.size'), self::SELECT, $sizes),
            $this->add('click', __('validation.attributes.click_count'), self::NUMBER),
            $this->add('published_at', __('validation.attributes.published_at'), self::DATE),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

#### TagHandler
```php
class TagHandler extends BaseHandler
{
    public function handle(): array
    {
        $types = [];
        foreach (TagTypeEnum::cases() as $type) {
            $types[] = $this->option($type->value, $type->title());
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('name', __('validation.attributes.name'), self::INPUT),
            $this->add('slug', __('validation.attributes.slug'), self::INPUT),
            $this->add('type', __('validation.attributes.type'), self::SELECT, $types),
            $this->add('order_column', __('validation.attributes.order'), self::NUMBER),
            $this->add('usage_count', __('validation.attributes.usage_count'), self::NUMBER),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

#### BoardHandler
```php
class BoardHandler extends BaseHandler
{
    public function handle(): array
    {
        $users = [];
        foreach (User::whereHas('boards')->get() as $user) {
            $users[] = $this->option((string) $user->id, $user->full_name);
        }

        return [
            $this->add('id', __('validation.attributes.id'), self::NUMBER),
            $this->add('name', __('validation.attributes.name'), self::INPUT),
            $this->add('is_active', __('validation.attributes.is_active'), self::SELECT, [
                $this->option('1', __('common.active')),
                $this->option('0', __('common.inactive'))
            ]),
            $this->add('system_protected', __('validation.attributes.system_protected'), self::SELECT, [
                $this->option('1', __('common.yes')),
                $this->option('0', __('common.no'))
            ]),
            $this->add('owner_id', __('validation.attributes.owner'), self::SELECT, $users),
            $this->add('created_at', __('validation.attributes.created_at'), self::DATE),
        ];
    }
}
```

### Drivers Implementation

#### CategoryDriver
```php
class CategoryDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'has_children':
                    if ($item['from'] == 'yes') {
                        $query->has('children');
                    } else {
                        $query->doesntHave('children');
                    }
                    break;
                case 'content_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount(['blogs', 'faqs', 'portfolios'])
                          ->havingRaw('(blogs_count + faqs_count + portfolios_count) ' . $operator . ' ?', [$item['from']]);
                    break;
            }
        }
        
        return $query;
    }
}
```

#### CardDriver
```php
class CardDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'assignee_id':
                    $query->whereHas('assignees', function ($q) use ($item) {
                        $q->where('user_id', $item['from']);
                    });
                    break;
                case 'reviewer_id':
                    $query->whereHas('reviewers', function ($q) use ($item) {
                        $q->where('user_id', $item['from']);
                    });
                    break;
                case 'watcher_id':
                    $query->whereHas('watchers', function ($q) use ($item) {
                        $q->where('user_id', $item['from']);
                    });
                    break;
                case 'overdue':
                    if ($item['from'] == 'yes') {
                        $query->where('due_date', '<', now())
                              ->where('status', '!=', CardStatusEnum::COMPLETED->value);
                    } else {
                        $query->where(function ($q) {
                            $q->where('due_date', '>=', now())
                              ->orWhere('status', CardStatusEnum::COMPLETED->value)
                              ->orWhereNull('due_date');
                        });
                    }
                    break;
                case 'has_assignees':
                    if ($item['from'] == 'yes') {
                        $query->has('assignees');
                    } else {
                        $query->doesntHave('assignees');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
```

#### TicketDriver
```php
class TicketDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'has_messages':
                    if ($item['from'] == 'yes') {
                        $query->has('messages');
                    } else {
                        $query->doesntHave('messages');
                    }
                    break;
                case 'unread_messages':
                    if ($item['from'] == 'yes') {
                        $query->whereHas('messages', function ($q) {
                            $q->whereNull('read_by');
                        });
                    }
                    break;
                case 'messages_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount('messages')
                          ->having('messages_count', $operator, $item['from']);
                    break;
                case 'closed_by_admin':
                    if ($item['from'] == 'yes') {
                        $query->whereNotNull('closed_by');
                    } else {
                        $query->whereNull('closed_by');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
```

#### CommentDriver
```php
class CommentDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'has_replies':
                    if ($item['from'] == 'yes') {
                        $query->has('children');
                    } else {
                        $query->doesntHave('children');
                    }
                    break;
                case 'is_reply':
                    if ($item['from'] == 'yes') {
                        $query->whereNotNull('parent_id');
                    } else {
                        $query->whereNull('parent_id');
                    }
                    break;
                case 'content_type':
                    $query->where('morphable_type', $item['from']);
                    break;
                case 'high_rated':
                    $query->where('rate', '>=', 4);
                    break;
                case 'has_admin_note':
                    if ($item['from'] == 'yes') {
                        $query->whereNotNull('admin_note');
                    } else {
                        $query->whereNull('admin_note');
                    }
                    break;
            }
        }
        
        return $query;
    }
}
```

#### BannerDriver
```php
class BannerDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'scheduled':
                    if ($item['from'] == 'yes') {
                        $query->where('published_at', '>', now());
                    } else {
                        $query->where('published_at', '<=', now());
                    }
                    break;
                case 'high_performance':
                    $query->where('click', '>=', 100);
                    break;
                case 'has_image':
                    $query->whereHas('media');
                    break;
            }
        }
        
        return $query;
    }
}
```

#### TagDriver
```php
class TagDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'usage_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount('taggables')
                          ->having('taggables_count', $operator, $item['from']);
                    break;
                case 'in_use':
                    if ($item['from'] == 'yes') {
                        $query->whereHas('taggables');
                    } else {
                        $query->doesntHave('taggables');
                    }
                    break;
                case 'has_description':
                    $query->whereHas('translations', function ($q) {
                        $q->where('key', 'description')
                          ->whereNotNull('value')
                          ->where('value', '!=', '');
                    });
                    break;
            }
        }
        
        return $query;
    }
}
```

#### BoardDriver
```php
class BoardDriver extends BaseDriver
{
    public function handle(Builder $query, array $values): Builder
    {
        $query = $this->filter($query, $values);

        $extra_filters = collect($values)->whereNotIn('column', $this->fillable_columns);
        foreach ($extra_filters as $item) {
            switch ($item['column']) {
                case 'owner_id':
                    $query->whereHas('users', function ($q) use ($item) {
                        $q->where('user_id', $item['from'])
                          ->where('role', 'owner');
                    });
                    break;
                case 'member_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount('users')
                          ->having('users_count', $operator, $item['from']);
                    break;
                case 'has_cards':
                    if ($item['from'] == 'yes') {
                        $query->has('cards');
                    } else {
                        $query->doesntHave('cards');
                    }
                    break;
                case 'cards_count':
                    $operator = $item['operator'] ?? '>=';
                    $query->withCount('cards')
                          ->having('cards_count', $operator, $item['from']);
                    break;
            }
        }
        
        return $query;
    }
}
```

## Implementation Guidelines

### Handler Best Practices

1. **Enum Integration**: Always use enum cases for select options with proper title() methods
2. **Relationship-based Options**: Populate select options from related models using whereHas()
3. **Translation Keys**: Use proper translation keys for labels and options
4. **Field Types**: Choose appropriate field types (INPUT, SELECT, NUMBER, DATE)
5. **Performance**: Use efficient queries with proper indexing considerations

### Driver Best Practices

1. **Base Filtering**: Always call parent filter() method first
2. **Extra Filters**: Handle non-fillable columns in the extra_filters section
3. **Relationship Queries**: Use whereHas() for relationship-based filtering
4. **Query Optimization**: Use withCount() and having() for count-based filters
5. **Date Handling**: Properly handle date comparisons with timezone considerations

### Testing Strategy

1. **Unit Tests**: Test each handler and driver independently
2. **Integration Tests**: Test the complete filter chain
3. **Performance Tests**: Ensure filters don't create N+1 queries
4. **Edge Cases**: Test with empty results, invalid operators, and null values

This implementation provides a comprehensive advanced search system that leverages the rich relationship structure of the kids-collage application while maintaining performance and usability.
