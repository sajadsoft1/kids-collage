<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class Spotlight
{
    /** Search method for Spotlight component */
    public function search(Request $request): array
    {
        // Security check - only authenticated users can search
        if ( ! Auth::check()) {
            return [];
        }

        $search = $request->search ?? '';

        return collect()
            ->merge($this->actions($search))
            ->merge($this->users($search))
            ->merge($this->blogs($search))
            ->merge($this->categories($search))
            ->toArray();
    }

    /** Search for users */
    private function users(string $search = ''): array
    {
        if (empty($search)) {
            return [];
        }

        return User::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->take(5)
            ->get()
            ->map(function (User $user) {
                return [
                    'avatar'      => $user->avatar,
                    'name'        => $user->name,
                    'description' => $user->email,
                    'link'        => route('admin.user.edit', $user->id),
                ];
            })
            ->toArray();
    }

    /** Search for blogs */
    private function blogs(string $search = ''): array
    {
        if (empty($search)) {
            return [];
        }

        return Blog::query()
            ->where('title', 'like', "%{$search}%")
            ->orWhere('content', 'like', "%{$search}%")
            ->take(5)
            ->get()
            ->map(function (Blog $blog) {
                return [
                    'icon'        => Blade::render("<x-icon name='o-document-text' class='w-11 h-11 p-2 bg-info/10 rounded-full' />"),
                    'name'        => $blog->title,
                    'description' => 'Blog post',
                    'link'        => route('admin.blog.edit', $blog->id),
                ];
            })
            ->toArray();
    }

    /** Search for categories */
    private function categories(string $search = ''): array
    {
        if (empty($search)) {
            return [];
        }

        return Category::query()
            ->where('name', 'like', "%{$search}%")
            ->take(5)
            ->get()
            ->map(function (Category $category) {
                return [
                    'icon'        => Blade::render("<x-icon name='o-folder' class='w-11 h-11 p-2 bg-success/10 rounded-full' />"),
                    'name'        => $category->name,
                    'description' => 'Category',
                    'link'        => route('admin.category.edit', $category->id),
                ];
            })
            ->toArray();
    }

    /** Static actions search */
    private function actions(string $search = ''): array
    {
        $actions = [
            [
                'name'        => 'Dashboard',
                'description' => 'Go to dashboard',
                'icon'        => Blade::render("<x-icon name='o-home' class='w-11 h-11 p-2 bg-primary/10 rounded-full' />"),
                'link'        => route('admin.dashboard'),
            ],
            [
                'name'        => 'Create Blog',
                'description' => 'Create a new blog post',
                'icon'        => Blade::render("<x-icon name='o-plus' class='w-11 h-11 p-2 bg-success/10 rounded-full' />"),
                'link'        => route('admin.blog.create'),
            ],
            [
                'name'        => 'Create Category',
                'description' => 'Create a new category',
                'icon'        => Blade::render("<x-icon name='o-folder-plus' class='w-11 h-11 p-2 bg-warning/10 rounded-full' />"),
                'link'        => route('admin.category.create'),
            ],
            [
                'name'        => 'Create User',
                'description' => 'Create a new user',
                'icon'        => Blade::render("<x-icon name='o-user-plus' class='w-11 h-11 p-2 bg-info/10 rounded-full' />"),
                'link'        => route('admin.user.create'),
            ],
        ];

        if (empty($search)) {
            return $actions;
        }

        return collect($actions)
            ->filter(fn (array $item) => str($item['name'] . ' ' . $item['description'])->contains($search, true))
            ->toArray();
    }
}
