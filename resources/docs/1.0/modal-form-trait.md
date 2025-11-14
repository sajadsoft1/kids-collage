# HasModalForm Trait - Developer Guide

---

* [Overview](#overview)
* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Implementation Steps](#implementation-steps)
* [Field Types](#field-types)
* [Field Configuration](#field-configuration)
* [Custom Validation Rules](#custom-validation)
* [Complete Example](#complete-example)
* [Best Practices](#best-practices)
* [API Reference](#api-reference)

---

<a name="overview"></a>

## Overview

The `HasModalForm` trait provides a reusable way to add create and edit functionality via modals to PowerGrid Tables. This trait is perfect for models with few fields that don't require a separate page.

### Features

* 🎯 **Reusable** - Write once, use everywhere
* ⚡ **Dynamic** - Define fields with a simple array
* 🔄 **Automatic** - Validation, refresh, and messages handled automatically
* 🎨 **Compatible** - Works seamlessly with Mary UI and PowerGrid
* 💪 **Powerful** - Supports 9 different field types + extensible

> {success} Perfect for simple CRUD operations without page navigation

> {info} Uses Mary UI Modal component with persistent mode

---

<a name="installation"></a>

## Installation

The trait and modal component are already included in the project:

* `app/Traits/HasModalForm.php` - The main trait
* `resources/views/components/admin/shared/modal-form.blade.php` - The modal view component

No additional setup required!

---

<a name="basic-usage"></a>

## Basic Usage

Here's a minimal example to get started quickly:

```php
use App\Traits\HasModalForm;
use Mary\Traits\Toast;

final class YourModelTable extends PowerGridComponent
{
    use HasModalForm;
    use PowerGridHelperTrait;
    use Toast;
    
    public string $tableName = 'your_table_name';
    public string $sortDirection = 'desc';
    
    protected function modalFields(): array
    {
        return [
            [
                'name'     => 'title',
                'type'     => 'input',
                'label'    => trans('validation.attributes.title'),
                'required' => true,
            ],
            [
                'name'     => 'published',
                'type'     => 'toggle',
                'label'    => trans('datatable.status'),
                'default'  => false,
            ],
        ];
    }
    
    protected function getModelClass(): string
    {
        return YourModel::class;
    }
    
    protected function getStoreActionClass(): string
    {
        return StoreYourModelAction::class;
    }
    
    protected function getUpdateActionClass(): string
    {
        return UpdateYourModelAction::class;
    }
    
    protected function getModelTranslationKey(): string
    {
        return 'yourModel.model';
    }
}
```

---

<a name="implementation-steps"></a>

## Implementation Steps

### Step 1: Add Trait to Table Class

```php
use App\Traits\HasModalForm;
use Mary\Traits\Toast;

final class YourModelTable extends PowerGridComponent
{
    use HasModalForm;
    use PowerGridHelperTrait;
    use Toast;
    
    public string $tableName = 'your_table_name';
    public string $sortDirection = 'desc';
}
```

> {warning} Don't forget to add the `Toast` trait for success messages

---

### Step 2: Implement Required Methods

Define the modal configuration by implementing these abstract methods:

```php
/**
 * Define modal fields
 */
protected function modalFields(): array
{
    return [
        [
            'name'        => 'title',
            'type'        => 'input',
            'label'       => trans('validation.attributes.title'),
            'placeholder' => trans('validation.attributes.title'),
            'required'    => true,
        ],
        [
            'name'        => 'description',
            'type'        => 'textarea',
            'label'       => trans('validation.attributes.description'),
            'rows'        => 5,
            'required'    => true,
        ],
        [
            'name'    => 'published',
            'type'    => 'toggle',
            'label'   => trans('datatable.status'),
            'default' => false,
        ],
    ];
}

protected function getModelClass(): string
{
    return YourModel::class;
}

protected function getStoreActionClass(): string
{
    return StoreYourModelAction::class;
}

protected function getUpdateActionClass(): string
{
    return UpdateYourModelAction::class;
}

protected function getModelTranslationKey(): string
{
    return 'yourModel.model';
}
```

---

### Step 3: Add Modal to setUp

Include the modal component in your PowerGrid setup:

```php
public function setUp(): array
{
    $setup = [
        PowerGrid::header()
            ->includeViewOnTop('components.admin.shared.bread-crumbs')
            ->showSearchInput(),

        PowerGrid::footer()
            ->showPerPage()
            ->showRecordCount()
            ->includeViewOnBottom('components.admin.shared.modal-form'),
    ];

    if ((new Agent)->isMobile()) {
        $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
    }

    return $setup;
}
```

---

### Step 4: Update breadcrumbsActions

Change from `link` to `action` for the create button:

```php
#[Computed(persist: true)]
public function breadcrumbsActions(): array
{
    return [
        [
            'action' => 'openCreateModal',
            'icon'   => 's-plus',
            'label'  => trans('general.page.create.title', ['model' => trans('yourModel.model')]),
        ],
    ];
}
```

---

### Step 5: Update Edit Button in Actions

Modify the edit button to open the modal instead of navigating to a page:

```php
public function actions(YourModel $row): array
{
    return [
        PowerGridHelper::btnTranslate($row),
        PowerGridHelper::btnToggle($row),
        Button::add('edit')
            ->slot(PowerGridHelper::iconEdit())
            ->attributes([
                'class' => 'btn btn-square md:btn-sm btn-xs',
            ])
            ->dispatch('openEditModal', ['id' => $row->id])
            ->tooltip(trans('datatable.buttons.edit')),
        PowerGridHelper::btnDelete($row),
    ];
}
```

---

<a name="field-types"></a>

## Field Types

The trait supports 9 different field types:

| Type | Description | Extra Parameters |
|---|---|---|
| `input` | Simple text input | `icon` |
| `textarea` | Multi-line text input | `rows` |
| `toggle` | On/off switch | `right` |
| `checkbox` | Checkbox input | `right` |
| `select` | Dropdown selection | `options` |
| `number` | Numeric input | `min`, `max`, `step` |
| `email` | Email input | - |
| `date` | Date picker | - |
| `color` | Color picker | - |

---

<a name="field-configuration"></a>

## Field Configuration

### Common Parameters

All fields support these parameters:

| Parameter | Type | Required | Description |
|---|---|---|---|
| `name` | `string` | ✅ Yes | Field name (must match database column) |
| `type` | `string` | ✅ Yes | Field type (see table above) |
| `label` | `string` | No | Field label |
| `placeholder` | `string` | No | Placeholder text |
| `required` | `boolean` | No | Is field required? |
| `default` | `mixed` | No | Default value |
| `rules` | `string\|array` | No | Additional validation rules |

---

### Input Field

```php
[
    'name'        => 'title',
    'type'        => 'input',
    'label'       => trans('validation.attributes.title'),
    'placeholder' => trans('validation.attributes.title'),
    'required'    => true,
    'icon'        => 'o-user',  // Optional
]
```

---

### Textarea Field

```php
[
    'name'        => 'description',
    'type'        => 'textarea',
    'label'       => trans('validation.attributes.description'),
    'rows'        => 5,  // Optional, default: 3
    'required'    => true,
]
```

---

### Toggle Field

```php
[
    'name'    => 'published',
    'type'    => 'toggle',
    'label'   => trans('datatable.status'),
    'default' => false,
    'right'   => true,  // Optional, show label on right side
]
```

---

### Select Field

```php
[
    'name'     => 'category_id',
    'type'     => 'select',
    'label'    => trans('validation.attributes.category'),
    'options'  => Category::all()->map(fn($c) => ['id' => $c->id, 'name' => $c->title])->toArray(),
    'required' => true,
]
```

---

### Number Field

```php
[
    'name'  => 'price',
    'type'  => 'number',
    'label' => trans('validation.attributes.price'),
    'min'   => 0,
    'max'   => 1000000,
    'step'  => 0.01,
]
```

---

### Email Field

```php
[
    'name'  => 'email',
    'type'  => 'email',
    'label' => trans('validation.attributes.email'),
]
```

---

### Date Field

```php
[
    'name'  => 'birth_date',
    'type'  => 'date',
    'label' => trans('validation.attributes.birth_date'),
]
```

---

### Color Field

```php
[
    'name'  => 'color',
    'type'  => 'color',
    'label' => trans('validation.attributes.color'),
]
```

---

<a name="custom-validation"></a>

## Custom Validation Rules

You can add custom validation rules to any field:

### As Array

```php
[
    'name'     => 'email',
    'type'     => 'email',
    'label'    => trans('validation.attributes.email'),
    'required' => true,
    'rules'    => ['email', 'unique:users,email'],
]
```

### As String

```php
[
    'name'     => 'email',
    'type'     => 'email',
    'label'    => trans('validation.attributes.email'),
    'required' => true,
    'rules'    => 'email|unique:users,email',
]
```

### Default Rules by Type

If you don't specify rules, the trait automatically applies these:

| Field Type | Default Rule |
|---|---|
| `toggle`, `checkbox` | `boolean` |
| `number` | `numeric` |
| `email` | `email` |
| Others | `string` |

---

<a name="complete-example"></a>

## Complete Example

Here's a real-world example from the project:

```php
use App\Actions\QuestionSubject\StoreQuestionSubjectAction;
use App\Actions\QuestionSubject\UpdateQuestionSubjectAction;
use App\Models\QuestionSubject;
use App\Traits\HasModalForm;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class QuestionSubjectTable extends PowerGridComponent
{
    use HasModalForm;
    use PowerGridHelperTrait;
    use Toast;
    
    public string $tableName = 'index_questionSubject_datatable';
    public string $sortDirection = 'desc';

    protected function modalFields(): array
    {
        return [
            [
                'name'        => 'title',
                'type'        => 'input',
                'label'       => trans('validation.attributes.title'),
                'placeholder' => trans('validation.attributes.title'),
                'required'    => true,
            ],
            [
                'name'        => 'description',
                'type'        => 'input',
                'label'       => trans('validation.attributes.description'),
                'placeholder' => trans('validation.attributes.description'),
                'required'    => true,
            ],
            [
                'name'    => 'published',
                'type'    => 'toggle',
                'label'   => trans('datatable.status'),
                'default' => false,
            ],
        ];
    }

    protected function getModelClass(): string
    {
        return QuestionSubject::class;
    }

    protected function getStoreActionClass(): string
    {
        return StoreQuestionSubjectAction::class;
    }

    protected function getUpdateActionClass(): string
    {
        return UpdateQuestionSubjectAction::class;
    }

    protected function getModelTranslationKey(): string
    {
        return 'questionSubject.model';
    }

    public function setUp(): array
    {
        $setup = [
            PowerGrid::header()
                ->includeViewOnTop('components.admin.shared.bread-crumbs')
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount()
                ->includeViewOnBottom('components.admin.shared.modal-form'),
        ];

        if ((new Agent)->isMobile()) {
            $setup[] = PowerGrid::responsive()->fixedColumns('id', 'title', 'actions');
        }

        return $setup;
    }

    #[Computed(persist: true)]
    public function breadcrumbsActions(): array
    {
        return [
            [
                'action' => 'openCreateModal',
                'icon'   => 's-plus',
                'label'  => trans('general.page.create.title', ['model' => trans('questionSubject.model')]),
            ],
        ];
    }

    public function actions(QuestionSubject $row): array
    {
        return [
            PowerGridHelper::btnTranslate($row),
            PowerGridHelper::btnToggle($row),
            Button::add('edit')
                ->slot(PowerGridHelper::iconEdit())
                ->attributes([
                    'class' => 'btn btn-square md:btn-sm btn-xs',
                ])
                ->dispatch('openEditModal', ['id' => $row->id])
                ->tooltip(trans('datatable.buttons.edit')),
            PowerGridHelper::btnDelete($row),
        ];
    }
}
```

> {info} See the complete implementation in `app/Livewire/Admin/Pages/QuestionSubject/QuestionSubjectTable.php`

---

<a name="best-practices"></a>

## Best Practices

### 1. Always Include Toast Trait

```php
use Mary\Traits\Toast;

class YourTable extends PowerGridComponent
{
    use HasModalForm;
    use Toast;  // Required for success messages
}
```

> {danger} Without Toast trait, success messages won't be displayed

---

### 2. Match Field Names with Database Columns

Field names must exactly match your database column names:

```php
// Database column: 'published'
[
    'name' => 'published',  // Must match exactly
    'type' => 'toggle',
]
```

---

### 3. Enum Fields Work Automatically

The trait automatically extracts enum values:

```php
// Model has: published => BooleanEnum::class

// In modalFields, just use the field name:
[
    'name' => 'published',
    'type' => 'toggle',
]
```

The trait will automatically get `->value` from the enum when editing.

---

### 4. Use Persistent Modal

The modal uses `persistent` mode by default, preventing accidental closure:

```blade
<x-modal wire:model="showModal" persistent>
    ...
</x-modal>
```

Users must explicitly click Cancel or Save to close the modal.

---

### 5. Automatic Table Refresh

After saving, the PowerGrid table refreshes automatically:

```php
$this->dispatch('pg:eventRefresh-' . $this->tableName);
```

No additional code needed!

---

<a name="api-reference"></a>

## API Reference

### Abstract Methods (Required)

You must implement these methods in your table class:

#### `modalFields(): array`

Returns array of field configurations.

```php
protected function modalFields(): array
{
    return [
        ['name' => 'title', 'type' => 'input', ...],
    ];
}
```

---

#### `getModelClass(): string`

Returns the fully qualified model class name.

```php
protected function getModelClass(): string
{
    return YourModel::class;
}
```

---

#### `getStoreActionClass(): string`

Returns the action class for creating records.

```php
protected function getStoreActionClass(): string
{
    return StoreYourModelAction::class;
}
```

---

#### `getUpdateActionClass(): string`

Returns the action class for updating records.

```php
protected function getUpdateActionClass(): string
{
    return UpdateYourModelAction::class;
}
```

---

#### `getModelTranslationKey(): string`

Returns the translation key for the model name.

```php
protected function getModelTranslationKey(): string
{
    return 'yourModel.model';
}
```

---

### Public Methods

#### `openCreateModal(): void`

Opens the modal for creating a new record.

```php
// Called automatically by breadcrumbs action
public function openCreateModal(): void;
```

---

#### `openEditModal(int $id): void`

Opens the modal for editing an existing record.

```php
// Called by PowerGrid edit button dispatch
#[On('openEditModal')]
public function openEditModal(int $id): void;
```

---

#### `saveModal(): void`

Saves the form data (create or update).

```php
// Called by modal form submission
public function saveModal(): void;
```

---

#### `closeModal(): void`

Closes the modal without saving.

```php
// Called by cancel button
public function closeModal(): void;
```

---

#### `getModalTitle(): string`

Returns the modal title based on create/edit mode.

```php
// Used in modal view
public function getModalTitle(): string;
```

---

### Protected Methods

#### `resetModalData(): void`

Resets all modal fields to default values.

```php
protected function resetModalData(): void;
```

---

#### `getDefaultValue(string $type): mixed`

Returns default value based on field type.

```php
protected function getDefaultValue(string $type): mixed;
```

---

#### `rules(): array`

Returns validation rules for all fields.

```php
// Override if you need custom validation logic
protected function rules(): array;
```

---

### Properties

#### `$showModal: bool`

Controls modal visibility.

```php
public bool $showModal = false;
```

---

#### `$editingId: ?int`

ID of the record being edited (null for create).

```php
public ?int $editingId = null;
```

---

#### `$modalData: array`

Contains all modal field values.

```php
public array $modalData = [];
```

---

## Tips & Tricks

### Tip 1: Conditional Fields

You can conditionally show fields based on other data:

```php
protected function modalFields(): array
{
    $fields = [
        ['name' => 'title', 'type' => 'input', 'required' => true],
    ];
    
    if (auth()->user()->isAdmin()) {
        $fields[] = ['name' => 'featured', 'type' => 'toggle'];
    }
    
    return $fields;
}
```

---

### Tip 2: Dynamic Options

Load select options dynamically:

```php
protected function modalFields(): array
{
    return [
        [
            'name'    => 'category_id',
            'type'    => 'select',
            'options' => $this->getCategoryOptions(),
        ],
    ];
}

private function getCategoryOptions(): array
{
    return Category::where('active', true)
        ->orderBy('title')
        ->get()
        ->map(fn($c) => ['id' => $c->id, 'name' => $c->title])
        ->toArray();
}
```

---

### Tip 3: Custom Validation Messages

Override the `validationAttributes()` method:

```php
protected function validationAttributes(): array
{
    return [
        'modalData.title' => trans('validation.attributes.title'),
        'modalData.email' => trans('validation.attributes.email'),
    ];
}
```

---

## Troubleshooting

### Modal Not Showing

Check if you added the modal to `setUp()`:

```php
->includeViewOnBottom('components.admin.shared.modal-form')
```

---

### Validation Not Working

Ensure field names match database columns exactly and that you've set `required => true`:

```php
[
    'name'     => 'title',  // Must match DB column
    'required' => true,     // For validation
]
```

---

### Table Not Refreshing

Make sure `$tableName` is set correctly:

```php
public string $tableName = 'index_yourmodel_datatable';
```

---

### Enum Values Not Loading

The trait handles enum conversion automatically. Ensure your model casts the field to an enum:

```php
protected $casts = [
    'published' => BooleanEnum::class,
];
```

---

## What's Next?

Now that you understand the HasModalForm trait, explore related features:

- 📊 [PowerGrid Tables](/docs/1.0/project-architecture#powergrid)
- 🎨 [Mary UI Components](https://mary-ui.com/docs/components/modal)
- ⚡ [Livewire Best Practices](/docs/1.0/project-architecture#livewire)

Happy coding! 🚀

