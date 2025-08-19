# Dashboard Header with Date Filtering

## Overview

A modern, responsive dashboard header component that provides date filtering functionality for all dashboard widgets. The header allows users to select from predefined date ranges or create custom date ranges, and automatically updates all widgets with the selected date range.

## Features

### 🗓️ **Date Range Selection**
- **Predefined Periods**: Today, Yesterday, Last 7 days, Last 30 days, Last 90 days, This month, Last month, This year
- **Custom Range**: Select custom start and end dates
- **Real-time Updates**: All widgets update automatically when date range changes

### 🎨 **Modern UI**
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Dark Mode Support**: Full dark mode compatibility
- **Mary UI Components**: Uses Mary UI for consistent styling
- **Alpine.js Integration**: Smooth dropdown animations and interactions

### 🔄 **Widget Integration**
- **Automatic Refresh**: All widgets refresh when date range changes
- **Event-driven**: Uses Livewire events for communication
- **Backward Compatible**: Works with existing widgets

## Components

### DashboardHeader Component
**File**: `app/Livewire/Admin/Pages/Dashboard/DashboardHeader.php`

**Features**:
- Date range management
- Event dispatching for widget updates
- Translation support (English & Persian)
- Custom date picker functionality

### DashboardHeader View
**File**: `resources/views/livewire/admin/pages/dashboard/dashboard-header.blade.php`

**Features**:
- Modern dropdown interface
- Custom date picker form
- Date range display
- Refresh button

## Usage

### Basic Implementation

The dashboard header is automatically included in the main dashboard:

```php
// In dashboard-index.blade.php
<livewire:admin.pages.dashboard.dashboard-header />
```

### Widget Integration

All widgets automatically receive the date range parameters:

```php
<livewire:admin.widget.new-users-widget 
    :limit="5" 
    :start_date="$startDate" 
    :end_date="$endDate" 
/>
```

### Widget Refresh Method

Each widget includes a `refreshData` method for dynamic updates:

```php
// In widget components
$refreshData = function (?string $startDate = null, ?string $endDate = null) {
    if ($startDate) {
        $this->start_date = $startDate;
    }
    if ($endDate) {
        $this->end_date = $endDate;
    }
    $this->dispatch('$refresh');
};
```

## Events

### dateRangeChanged
Dispatched when the date range is updated:

```javascript
Livewire.on('dateRangeChanged', (data) => {
    console.log('Date range changed:', data);
    // data contains: startDate, endDate, period
});
```

### refreshWidgets
Dispatched when widgets should refresh:

```javascript
Livewire.on('refreshWidgets', (data) => {
    // Refresh all widgets with new date range
    const widgets = document.querySelectorAll('[wire\\:id*="widget"]');
    widgets.forEach(widget => {
        const component = Livewire.find(widget.getAttribute('wire:id'));
        if (component) {
            component.call('refreshData', data.startDate, data.endDate);
        }
    });
});
```

## Translations

### English (`lang/en/dashboard.php`)
```php
'date_filter' => [
    'title' => 'Date Filter',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'apply' => 'Apply',
    'cancel' => 'Cancel',
    'custom_range' => 'Custom Range',
    'showing_data_from' => 'Showing data from',
    'to' => 'to',
    'periods' => [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'last_7_days' => 'Last 7 days',
        'last_30_days' => 'Last 30 days',
        'last_90_days' => 'Last 90 days',
        'this_month' => 'This month',
        'last_month' => 'Last month',
        'this_year' => 'This year',
        'custom' => 'Custom range',
    ],
],
```

### Persian (`lang/fa/dashboard.php`)
```php
'date_filter' => [
    'title' => 'فیلتر تاریخ',
    'start_date' => 'تاریخ شروع',
    'end_date' => 'تاریخ پایان',
    'apply' => 'اعمال',
    'cancel' => 'لغو',
    'custom_range' => 'محدوده سفارشی',
    'showing_data_from' => 'نمایش داده‌ها از',
    'to' => 'تا',
    'periods' => [
        'today' => 'امروز',
        'yesterday' => 'دیروز',
        'last_7_days' => '۷ روز گذشته',
        'last_30_days' => '۳۰ روز گذشته',
        'last_90_days' => '۹۰ روز گذشته',
        'this_month' => 'این ماه',
        'last_month' => 'ماه گذشته',
        'this_year' => 'امسال',
        'custom' => 'محدوده سفارشی',
    ],
],
```

## Styling

The component uses Tailwind CSS with DaisyUI and includes:

- **Responsive Design**: Mobile-first approach
- **Dark Mode**: Full dark mode support
- **Hover Effects**: Interactive hover states
- **Transitions**: Smooth animations for dropdown
- **Accessibility**: Proper ARIA labels and keyboard navigation

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Dependencies

- Laravel 12+
- Livewire 3.6+
- Mary UI 2.0+
- Alpine.js (included with Livewire)
- Tailwind CSS with DaisyUI

## Customization

### Adding New Date Periods

To add new predefined date periods:

1. Update the `getDatePeriods()` method in `DashboardHeader.php`
2. Add the corresponding case in `setDateRange()` method
3. Add translations to language files

### Modifying Widget Behavior

To customize how widgets handle date changes:

1. Override the `refreshData` method in your widget
2. Add custom logic for date range processing
3. Implement widget-specific refresh behavior

### Styling Customization

To customize the appearance:

1. Modify the Blade template in `dashboard-header.blade.php`
2. Update Tailwind classes for desired styling
3. Add custom CSS if needed

## Troubleshooting

### Widgets Not Updating
- Ensure widgets have the `refreshData` method
- Check that widgets are listening for the `refreshWidgets` event
- Verify date parameters are being passed correctly

### Date Picker Not Working
- Check Alpine.js is loaded
- Verify date input validation
- Ensure proper date format (Y-m-d)

### Translation Issues
- Verify language files are properly formatted
- Check translation keys match exactly
- Clear application cache if needed

## Future Enhancements

- **Date Range Presets**: Save custom date ranges for quick access
- **Advanced Filtering**: Add more filter options (status, category, etc.)
- **Export Functionality**: Export filtered data
- **Analytics Integration**: Connect with analytics platforms
- **Real-time Updates**: WebSocket integration for live data updates 
