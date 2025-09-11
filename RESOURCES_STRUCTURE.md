# HTTP Resources Structure Documentation

This document outlines the restructured API resources following the new rules and patterns.

## Resource Types

### 1. Index Resources (for datatable and website list data)
- **Purpose**: Handle listing data for datatables and website lists
- **Pattern**: `{Entity}Resource` (e.g., `UserResource`, `BlogResource`)
- **Content**: Essential fields for listing, relationships use Simple resources
- **Examples**: UserResource, BlogResource, CategoryResource

### 2. Detail Resources (for detail and update page default data)
- **Purpose**: Handle detailed data for individual pages and form defaults
- **Pattern**: `{Entity}DetailResource` (e.g., `UserDetailResource`, `BlogDetailResource`)
- **Content**: Extends index resource with additional fields needed for forms/details
- **Examples**: UserDetailResource, BlogDetailResource, CategoryDetailResource

### 3. Simple Resources (for relationships)
- **Purpose**: Used when an entity appears as a relationship in other resources
- **Pattern**: `Simple{Entity}Resource` (e.g., `SimpleUserResource`, `SimpleCategoryResource`)
- **Content**: Minimal essential fields (typically: id, name/title, key identifiers, image)
- **Examples**: SimpleUserResource, SimpleCategoryResource, SimpleTagResource

## Available Simple Resources

- `SimpleUserResource` - For user relationships
- `SimpleCategoryResource` - For category relationships
- `SimpleCommentResource` - For comment relationships  
- `SimpleTagResource` - For tag relationships
- `SimpleTicketResource` - For ticket relationships
- `SimpleBoardResource` - For board relationships
- `SimpleCardResource` - For card relationships
- `SimplePortfolioResource` - For portfolio relationships
- `SimpleTeammateResource` - For teammate relationships

## Usage Guidelines

### In Index Resources
```php
// Use Simple resources for relationships
'user' => $this->whenLoaded('user', fn () => SimpleUserResource::make($this->user)),
'category' => $this->whenLoaded('category', fn () => SimpleCategoryResource::make($this->category)),
'tags' => $this->whenLoaded('tags', fn () => SimpleTagResource::collection($this->tags)),
```

### In Detail Resources
```php
// Extend index resource and add extra fields
public function toArray(Request $request): array
{
    $resource = BlogResource::make($this)->toArray($request);
    
    return array_merge($resource, [
        'body' => $this->body,
        'languages' => $this->languages,
        'seo_option' => $this->whenLoaded('seoOption', fn () => $this->seoOption),
    ]);
}
```

### In Simple Resources
```php
// Keep it minimal - only essential fields
return [
    'id' => $this->id,
    'name' => $this->name,
    'slug' => $this->slug,
    'image' => $this->resource->getFirstMediaUrl('image', Constants::RESOLUTION_512_SQUARE),
];
```

## Benefits

1. **Consistency**: All resources follow the same pattern
2. **Performance**: Simple resources reduce payload size in relationships
3. **Maintainability**: Clear separation of concerns
4. **Scalability**: Easy to add new fields without breaking existing functionality
5. **Developer Experience**: Predictable structure for frontend teams
