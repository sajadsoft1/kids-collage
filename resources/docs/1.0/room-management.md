# Room Management

---

* [Overview](#overview)
* [Features](#features)
* [Room Types](#room-types)
* [Creating Rooms](#creating-rooms)
* [Managing Rooms](#managing-rooms)
* [Room Assignment](#room-assignment)
* [Room Capacity](#room-capacity)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Room Management module provides comprehensive functionality for managing physical classrooms, learning spaces, and facilities within your educational institution.

> {success} Efficiently organize and allocate physical spaces for courses and sessions

> {primary} Supports multiple room types including classrooms, labs, auditoriums, and outdoor spaces

---

<a name="features"></a>
## Features

The Room Management module includes:

- **Room Creation and Configuration** - Define rooms with specific attributes
- **Capacity Management** - Set maximum student capacity for each room
- **Room Types** - Categorize rooms by purpose (classroom, lab, etc.)
- **Availability Tracking** - Monitor room usage and availability
- **Equipment Management** - Track equipment and facilities in each room
- **Room Assignment** - Assign rooms to courses and sessions
- **Multi-location Support** - Manage rooms across different buildings or campuses

> {info} Rooms can be filtered and searched by type, capacity, location, and availability

---

<a name="room-types"></a>
## Room Types

The system supports various room types:

- **Classroom** - Standard teaching rooms
- **Laboratory** - Science, computer, or specialized labs
- **Auditorium** - Large lecture halls
- **Outdoor Space** - Playgrounds, sports fields
- **Library** - Study and resource rooms
- **Art Studio** - Creative spaces
- **Music Room** - Music practice and performance spaces
- **Gymnasium** - Physical education facilities

> {primary} Custom room types can be added through the system configuration

---

<a name="creating-rooms"></a>
## Creating Rooms

To create a new room:

```php
use App\Actions\Room\CreateRoomAction;
use App\Models\Room;

$roomData = [
    'name' => 'Room 101',
    'type' => 'classroom',
    'capacity' => 30,
    'location' => 'Building A - First Floor',
    'description' => 'Standard classroom with projector',
    'equipment' => [
        'projector' => true,
        'whiteboard' => true,
        'computers' => 0,
    ],
    'is_active' => true,
];

$room = app(CreateRoomAction::class)->execute($roomData);
```

> {success} Rooms are immediately available for assignment after creation

---

<a name="managing-rooms"></a>
## Managing Rooms

### Updating Room Information

```php
use App\Actions\Room\UpdateRoomAction;

$updateData = [
    'capacity' => 35,
    'equipment' => [
        'projector' => true,
        'whiteboard' => true,
        'computers' => 5,
    ],
];

app(UpdateRoomAction::class)->execute($room, $updateData);
```

### Deactivating a Room

```php
use App\Actions\Room\DeleteRoomAction;

// Soft delete - room remains in database but is marked inactive
app(DeleteRoomAction::class)->execute($room);
```

> {warning} Deactivating a room does not remove existing course assignments

---

<a name="room-assignment"></a>
## Room Assignment

Rooms are assigned to course sessions during course creation or scheduling:

```php
use App\Models\CourseSession;

$session = CourseSession::find($sessionId);
$session->update([
    'room_id' => $room->id,
]);
```

### Checking Room Availability

```php
use App\Models\Room;
use Carbon\Carbon;

$room = Room::find($roomId);

// Check if room is available for a specific time slot
$isAvailable = $room->isAvailableAt(
    Carbon::parse('2025-10-15 09:00:00'),
    Carbon::parse('2025-10-15 11:00:00')
);
```

> {info} The system automatically checks for scheduling conflicts

---

<a name="room-capacity"></a>
## Room Capacity

Room capacity is enforced during enrollment:

```php
use App\Models\Room;

$room = Room::find($roomId);

// Get current usage
$currentEnrollments = $room->getCurrentEnrollmentCount();
$remainingCapacity = $room->capacity - $currentEnrollments;

// Check if room is at capacity
if ($room->isAtCapacity()) {
    // Handle full room scenario
}
```

> {warning} Enrollments exceeding room capacity will trigger warnings in the system

---

<a name="examples"></a>
## Examples

### Example 1: Creating a Computer Lab

```php
$computerLab = [
    'name' => 'Computer Lab 1',
    'type' => 'laboratory',
    'capacity' => 25,
    'location' => 'Technology Building - 2nd Floor',
    'description' => 'Computer lab with 25 workstations',
    'equipment' => [
        'computers' => 25,
        'projector' => true,
        'whiteboard' => true,
        'air_conditioning' => true,
    ],
    'is_active' => true,
];

$lab = app(CreateRoomAction::class)->execute($computerLab);
```

### Example 2: Finding Available Rooms

```php
use App\Models\Room;
use Carbon\Carbon;

$startTime = Carbon::parse('2025-10-15 10:00:00');
$endTime = Carbon::parse('2025-10-15 12:00:00');
$minCapacity = 20;

$availableRooms = Room::query()
    ->where('is_active', true)
    ->where('capacity', '>=', $minCapacity)
    ->whereDoesntHave('sessions', function ($query) use ($startTime, $endTime) {
        $query->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime]);
    })
    ->get();
```

### Example 3: Room Utilization Report

```php
use App\Models\Room;

$rooms = Room::withCount('sessions')->get();

foreach ($rooms as $room) {
    $utilization = ($room->sessions_count / $totalAvailableSlots) * 100;
    echo "{$room->name}: {$utilization}% utilized\n";
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Room Not Appearing in Assignment List

**Problem:** Room is not showing up when assigning to a course session.

**Solution:**
- Verify the room is marked as active (`is_active = true`)
- Check if the room has scheduling conflicts
- Ensure the room capacity meets the course requirements

### Capacity Exceeded Warning

**Problem:** System shows capacity warning but room is not full.

**Solution:**
```php
// Recalculate room capacity
$room->refresh();
$actualCount = $room->sessions()
    ->with('enrollments')
    ->get()
    ->sum(function ($session) {
        return $session->enrollments->count();
    });
```

> {warning} Always verify room capacity calculations match actual enrollments

### Double Booking Prevention

**Problem:** Need to prevent double booking of rooms.

**Solution:**
The system automatically checks for conflicts. To manually verify:

```php
use App\Models\CourseSession;

$hasConflict = CourseSession::where('room_id', $roomId)
    ->where(function ($query) use ($startTime, $endTime) {
        $query->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime]);
    })
    ->exists();

if ($hasConflict) {
    throw new \Exception('Room is already booked for this time slot');
}
```

> {danger} Always validate room availability before confirming bookings

---

## Permissions

Room management requires appropriate permissions:

- `room.index` - View rooms list
- `room.create` - Create new rooms
- `room.edit` - Edit existing rooms
- `room.delete` - Deactivate rooms

> {info} Permissions are managed through the Role Management module

