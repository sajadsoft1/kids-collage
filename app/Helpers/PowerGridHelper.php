<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\BooleanEnum;
use App\Services\Permissions\PermissionsService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Components\Filters\Builders\Boolean;

class PowerGridHelper
{
    public static function published(BooleanEnum $status): string
    {
        return $status->value ? __('datatable.publish') : __('datatable.un_publish');
    }

    public static function icon(string $icon, ?string $class = null): string
    {
        return "<i class='fa fa-{$icon} {$class}'></i>";
    }

    /** Public Powergrid Icons -------------------------------------------------------------------------- */
    public static function iconEdit(string $class = 'text-primary'): string
    {
        return "<i class='fa fa-pencil {$class}'></i>";
    }

    public static function iconTranslate(string $class = 'text-primary'): string
    {
        return "<i class='fa fa-language {$class}'></i>";
    }

    public static function iconSeo(string $class = 'text-primary'): string
    {
        return "<i class='fas fa-chart-line {$class}'></i>";
    }

    public static function iconDelete(string $class = 'text-rose-600'): string
    {
        return "<i class='fa fa-trash {$class}'></i>";
    }

    public static function iconShow(string $class = 'text-info'): string
    {
        return "<i class='fa fa-eye {$class}'></i>";
    }

    public static function iconToggle(bool|int $status, ?string $class = null): string
    {
        $on = $status ? 'fa-toggle-on text-success' : 'fa-toggle-off text-gray-400';

        return "<i class='fa {$on} {$class}'></i>";
    }

    /** Public Powergrid Buttons -------------------------------------------------------------------------- */
    public static function btnShow(mixed $row): Button
    {
        $param = Str::kebab(StringHelper::basename($row::class));

        return Button::add('show')
                     ->slot(self::iconShow())
                     ->attributes([
                         'class' => 'btn btn-square md:btn-sm btn-xs',
                     ])
                     ->route("admin.{$param}.show", [Str::camel(StringHelper::basename($row::class)) => $row->id], '_self')
                     ->tooltip(trans('datatable.buttons.show'));
    }

    public static function btnEdit(mixed $row): Button
    {
        $param = Str::kebab(StringHelper::basename($row::class));
        return Button::add('edit')
                     ->slot(self::iconEdit())
                     ->attributes([
                         'class' => 'btn btn-square md:btn-sm btn-xs',
                     ])
                     ->can(auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel($row::class, 'Update')))
                     ->route("admin.$param.edit", [Str::camel($param) => $row->id], '_self')
                     ->navigate()
                     ->tooltip(trans('datatable.buttons.edit'));
    }

    public static function btnSeo(mixed $row): Button
    {
        return Button::add('seo')
                     ->slot(self::iconSeo())
                     ->attributes([
                         'class' => 'btn btn-square md:btn-sm btn-xs',
                     ])
                     ->can(config('custom-modules.seo') && auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel($row::class, 'Update')))
                     ->route('admin.dynamic-seo', ['class' => Str::camel(StringHelper::basename($row::class)), 'id' => $row->id], '_self')
                     ->navigate()
                     ->tooltip(trans('datatable.buttons.seo'));
    }

    public static function btnTranslate(mixed $row): Button
    {
        $param = Str::kebab(StringHelper::basename($row::class));

        return Button::add('translate')
                     ->slot(self::iconTranslate())
                     ->attributes([
                         'class' => 'btn btn-square md:btn-sm btn-xs',
                     ])
                     ->can(config('custom-modules.translation') && auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel($row::class, 'Update')))
                     ->route('admin.dynamic-translate', ['class' => Str::camel($param), 'id' => $row->id], '_self')
                     ->navigate()
                     ->tooltip(trans('datatable.buttons.translate'));
    }

    public static function btnToggle(mixed $row, string $toggleField = 'published'): Button
    {
        if ($row->{$toggleField} instanceof Boolean) {
            $status = $row->{$toggleField};
        } else {
            $status = $row->{$toggleField}->value;
        }
        $param = Str::lower(StringHelper::basename($row::class));

        return Button::add('toggle')
                     ->slot(self::iconToggle($status))
                     ->attributes([
                         'class' => 'btn btn-square md:btn-sm btn-xs',
                     ])
                     ->can(auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel($row::class, 'Update')))
                     ->dispatch('toggle', ['rowId' => $row->id])
                     ->tooltip(trans('datatable.buttons.toggle'));
    }

    public static function btnDelete(mixed $row): Button
    {
        $param = Str::kebab(StringHelper::basename($row::class));

        return Button::add('delete')
                     ->slot(self::iconDelete())
                     ->attributes([
                         'wire:confirm' => trans('general.are_you_shure_to_delete_record'),
                         'class'        => 'btn btn-square md:btn-sm btn-xs',
                     ])
                     ->can(auth()->user()->hasAnyPermission(PermissionsService::generatePermissionsByModel($row::class, 'Delete')))
                     ->dispatch('force-delete', ['rowId' => $row->id])
                     ->tooltip(trans('datatable.buttons.delete'));
    }

    /** Public Powergrid Fields -------------------------------------------------------------------------- */
    public static function fieldPublishedAtFormated($row): View
    {
        //        return $row->published->title();
        return view('admin.datatable-shared.published', [
            'row' => $row,
        ]);
    }

    public static function fieldFormated($row, $field = 'default', $icon = ['yes' => '', 'no' => '']): View
    {
        return view('admin.datatable-shared.field-formated', [
            'row'   => $row,
            'field' => $field,
            'icon'  => $icon,
        ]);
    }

    public static function fieldBooleanFormated($row, string $field = 'published'): string
    {
        return $row->{$field}->title();
    }

    public static function fieldSlug($row): string
    {
        return Str::limit($row->slug, 30);
    }

    public static function fieldTitle($row): ?string
    {
        return Str::limit($row?->title, 30);
    }

    public static function fieldCreatedAt($row)
    {
        return $row->created_at;
    }

    public static function fieldCreatedAtFormated($row): string
    {
        return jdate($row->created_at)->format('%A, %d %B %Y');
    }

    public static function fieldUpdatedAtFormated($row): string
    {
        return jdate($row->updated_at)->format('%A, %d %B %Y');
    }

    public static function fieldCategoryName($row): string
    {
        return Str::limit($row->category->title, 30);
    }

    public static function fieldUserName($row): string
    {
        return $row->user->name;
    }

    public static function fieldImage($row, $collection = 'image', $conversion = '100x100', $w = 11, $h = 11): string
    {
        /*
         * just 1:1 and 16:9
         *
         * w:11 h:11 = 1:1
         * w:16 h:9  = 16:9
         * w:18 h:10 = 16:9
         * w:14 h:8  = 16:9
         * w:11 h:6  = 16:9
         * */
        $template = '<img src=":src" class="image-fit h-:h w-:w overflow-hidden border-[3px] border-slate-200/70" alt="image">';

        return str_replace(
            [':src', ':alt', ':h', ':w'],
            [
                $row->getFirstMediaUrl($collection, $conversion),
                $h,
                $w
            ],
            $template
        );
    }

    public static function fieldViewCount($row): int
    {
        return $row->view_count;
    }

    public static function fieldLikeCount($row): int
    {
        return $row->like_count;
    }

    public static function fieldCommentCount($row): int
    {
        return $row->comment_count;
    }

    /** Public Powergrid Columns -------------------------------------------------------------------------- */
    public static function columnId(string $field = 'id', string $dataField = 'id'): Column
    {
        return Column::make(trans('datatable.id'), $field, $dataField)
                     ->sortable()
                     ->headerAttribute(
                         'powergrid-id',
                         'width:0!important'
                     );
    }

    public static function columnImage(string $field = 'image', string $dataField = 'image'): Column
    {
        return Column::make(trans('datatable.image'), $field, $dataField);
    }

    public static function columnTitle(string $field = 'title', string $dataField = 'title'): Column
    {
        return Column::make(trans('datatable.title'), $field, $dataField);
    }

    public static function columnSlug(string $field = 'slug', string $dataField = 'slug'): Column
    {
        return Column::make(trans('datatable.slug'), $field, $dataField);
    }

    public static function columnPublished(string $field = 'published_formated', string $dataField = 'published'): Column
    {
        return Column::make(trans('datatable.status'), $field, $dataField)
                     ->sortable()
                     ->headerAttribute(
                         '',
                         'width:0!important'
                     );
    }

    public static function columnClickCount(string $field = 'click', string $dataField = 'click'): Column
    {
        return Column::make(trans('datatable.click_count'), $field, $dataField);
    }

    public static function columnCategoryName(string $field = 'category_name', string $dataField = 'category_id'): Column
    {
        return Column::make(trans('datatable.category_title'), $field, $dataField);
    }

    public static function columnCreatedAT(string $field = 'created_at_formatted', string $dataField = 'created_at'): Column
    {
        return Column::make(trans('datatable.created_at'), $field, $dataField)
                     ->sortable();
    }

    public static function columnUpdatedAT(string $field = 'updated_at_formatted', string $dataField = 'updated_at'): Column
    {
        return Column::make(trans('datatable.updated_at'), $field, $dataField)
                     ->sortable();
    }

    public static function columnUserName(string $field = 'user_name', string $dataField = 'user.name'): Column
    {
        return Column::make(trans('datatable.user_name'), $field, $dataField);
    }

    public static function columnViewCount(string $field = 'view_count', string $dataField = 'view_count'): Column
    {
        return Column::make(trans('datatable.view_count'), $field, $dataField);
    }

    public static function columnCommentCount(string $field = 'comment_count', string $dataField = 'comment_count'): Column
    {
        return Column::make(trans('datatable.comment_count'), $field, $dataField);
    }

    public static function columnAction(): Column
    {
        return Column::action(trans('datatable.setting'))->headerAttribute(
            '!w-0',
            'width:0 !important'
        );
    }
}
