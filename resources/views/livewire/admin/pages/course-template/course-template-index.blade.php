<div>
    <x-admin.shared.bread-crumbs :breadcrumbs="$breadcrumbs" :breadcrumbs-actions="$breadcrumbsActions" />

    <livewire:admin.pages.course-template.course-template-table />

    <x-drawer
            wire:model="showRunCourseDrawer"
            title="Hello"
            subtitle="Livewire"
            separator
            with-close-button
            close-on-escape
            right
            class="w-11/12 lg:w-1/3"
    >
        <div>Hey!</div>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.showRunCourseDrawer = false" />
            <x-button label="Confirm" class="btn-primary" icon="o-check" />
        </x-slot:actions>
    </x-drawer>
</div>
