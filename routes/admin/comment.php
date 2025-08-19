<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Comment\CommentTable;
use App\Livewire\Admin\Pages\Comment\CommentUpdateOrCreate;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/comment', 'as' => 'admin.comment.'], function () {
    Route::get('/', CommentTable::class)->name('index');
    Route::get('create', CommentUpdateOrCreate::class)->name('create')->can('create,App\Models\Comment');
    Route::get('{comment}/edit', CommentUpdateOrCreate::class)->name('edit')->can('update,comment');
});
