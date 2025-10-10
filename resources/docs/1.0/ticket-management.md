# Ticket Management

---

* [Overview](#overview)
* [Features](#features)
* [Ticket Lifecycle](#ticket-lifecycle)
* [Creating Tickets](#creating-tickets)
* [Managing Tickets](#managing-tickets)
* [Ticket Status](#ticket-status)
* [Ticket Messages](#ticket-messages)
* [Examples](#examples)
* [Troubleshooting](#troubleshooting)

---

<a name="overview"></a>
## Overview

The Ticket Management module provides a comprehensive support ticketing system for handling customer inquiries, support requests, and issue tracking.

> {success} Streamlined customer support with real-time ticket tracking

> {primary} Multi-department ticket routing with priority management

---

<a name="features"></a>
## Features

The Ticket Management module includes:

- **Ticket Creation** - Users can submit support tickets
- **Department Routing** - Route tickets to appropriate departments
- **Priority Levels** - Low, medium, high, urgent priorities
- **Status Tracking** - Open, in progress, resolved, closed
- **Message Threading** - Conversation-style ticket responses
- **File Attachments** - Attach files to tickets and messages
- **Email Notifications** - Automatic email updates
- **Ticket Assignment** - Assign tickets to staff members
- **SLA Management** - Track response and resolution times
- **Ticket Search** - Advanced search and filtering

> {info} Tickets are automatically assigned to departments based on category

---

<a name="ticket-lifecycle"></a>
## Ticket Lifecycle

The ticket workflow:

1. **Submission** - User creates a ticket
2. **Department Assignment** - Ticket routed to appropriate department
3. **Staff Assignment** - Assigned to available staff member
4. **In Progress** - Staff member working on the issue
5. **Awaiting Response** - Waiting for user response
6. **Resolved** - Issue resolved, awaiting confirmation
7. **Closed** - Ticket closed and archived

> {primary} Users can reopen tickets within 7 days of closure

---

<a name="creating-tickets"></a>
## Creating Tickets

### Basic Ticket Creation

```php
use App\Actions\Ticket\CreateTicketAction;
use App\Models\User;

$user = User::find($userId);

$ticketData = [
    'user_id' => $user->id,
    'subject' => 'Cannot access course materials',
    'department' => 'technical',
    'priority' => 'medium',
    'message' => 'I enrolled in Math 101 but cannot see the course materials.',
    'status' => 'open',
];

$ticket = app(CreateTicketAction::class)->execute($ticketData);
```

### Ticket with Attachments

```php
use App\Actions\Ticket\CreateTicketAction;

$ticketData = [
    'user_id' => $userId,
    'subject' => 'Payment receipt not received',
    'department' => 'billing',
    'priority' => 'high',
    'message' => 'I made a payment but did not receive a receipt.',
    'status' => 'open',
];

$ticket = app(CreateTicketAction::class)->execute($ticketData);

// Add attachment
$ticket->addMedia($request->file('attachment'))
    ->toMediaCollection('attachments');
```

### Guest Ticket Creation

```php
$ticketData = [
    'name' => 'محمد رضایی',
    'email' => 'mohammad@example.com',
    'subject' => 'Question about enrollment',
    'department' => 'admissions',
    'priority' => 'low',
    'message' => 'How can I enroll my child?',
    'status' => 'open',
    'is_guest' => true,
];

$ticket = app(CreateTicketAction::class)->execute($ticketData);
```

> {success} Users receive email confirmation when ticket is created

---

<a name="managing-tickets"></a>
## Managing Tickets

### Updating Ticket Information

```php
use App\Actions\Ticket\UpdateTicketAction;

$ticket = Ticket::find($ticketId);

$updateData = [
    'priority' => 'high',
    'department' => 'technical',
    'assigned_to' => $staffId,
];

app(UpdateTicketAction::class)->execute($ticket, $updateData);
```

### Assigning Tickets

```php
use App\Models\Ticket;
use App\Models\User;

$ticket = Ticket::find($ticketId);
$staff = User::where('type', 'employee')
    ->where('department', $ticket->department)
    ->where('is_available', true)
    ->first();

if ($staff) {
    $ticket->update([
        'assigned_to' => $staff->id,
        'assigned_at' => now(),
        'status' => 'in_progress',
    ]);
    
    // Notify staff member
    $staff->notify(new TicketAssignedNotification($ticket));
}
```

### Closing Tickets

```php
use App\Actions\Ticket\UpdateTicketAction;

$ticket = Ticket::find($ticketId);

app(UpdateTicketAction::class)->execute($ticket, [
    'status' => 'closed',
    'closed_at' => now(),
    'closed_by' => auth()->id(),
    'resolution' => 'Issue resolved successfully',
]);
```

---

<a name="ticket-status"></a>
## Ticket Status

Available ticket statuses:

- **open** - Newly created, awaiting assignment
- **in_progress** - Being worked on by staff
- **awaiting_response** - Waiting for user response
- **resolved** - Issue resolved, awaiting confirmation
- **closed** - Ticket closed
- **reopened** - Previously closed ticket reopened

### Status Transitions

```php
use App\Models\Ticket;

$ticket = Ticket::find($ticketId);

// Check if status transition is allowed
$canReopen = $ticket->canBeReopened();     // Within 7 days of closure
$canClose = $ticket->canBeClosed();        // Status is resolved
$isOpen = $ticket->isOpen();               // Status is open
$isInProgress = $ticket->isInProgress();   // Status is in_progress
```

### Priority Levels

```php
// Priority levels affect response time SLA
$priorities = [
    'low' => ['response_time' => 48, 'resolution_time' => 168], // hours
    'medium' => ['response_time' => 24, 'resolution_time' => 72],
    'high' => ['response_time' => 8, 'resolution_time' => 24],
    'urgent' => ['response_time' => 2, 'resolution_time' => 8],
];
```

---

<a name="ticket-messages"></a>
## Ticket Messages

### Adding Messages to Tickets

```php
use App\Actions\TicketMessage\CreateTicketMessageAction;

$messageData = [
    'ticket_id' => $ticketId,
    'user_id' => auth()->id(),
    'message' => 'I have checked and the issue persists.',
    'is_staff_reply' => false,
];

$message = app(CreateTicketMessageAction::class)->execute($messageData);
```

### Staff Reply

```php
$messageData = [
    'ticket_id' => $ticketId,
    'user_id' => $staffId,
    'message' => 'Thank you for the information. I will investigate this further.',
    'is_staff_reply' => true,
];

$message = app(CreateTicketMessageAction::class)->execute($messageData);

// Update ticket status
$ticket->update(['status' => 'in_progress']);

// Notify user
$ticket->user->notify(new TicketReplyNotification($ticket, $message));
```

### Message with Attachments

```php
$messageData = [
    'ticket_id' => $ticketId,
    'user_id' => auth()->id(),
    'message' => 'Here is a screenshot of the error.',
    'is_staff_reply' => false,
];

$message = app(CreateTicketMessageAction::class)->execute($messageData);

// Add attachment
$message->addMedia($request->file('screenshot'))
    ->toMediaCollection('attachments');
```

---

<a name="examples"></a>
## Examples

### Example 1: Complete Ticket Flow

```php
use App\Actions\Ticket\CreateTicketAction;
use App\Actions\TicketMessage\CreateTicketMessageAction;
use App\Actions\Ticket\UpdateTicketAction;

// 1. User creates ticket
$ticket = app(CreateTicketAction::class)->execute([
    'user_id' => $userId,
    'subject' => 'Login Issue',
    'department' => 'technical',
    'priority' => 'high',
    'message' => 'Cannot login to my account',
    'status' => 'open',
]);

// 2. System assigns to staff
$staff = User::where('department', 'technical')
    ->where('is_available', true)
    ->first();

app(UpdateTicketAction::class)->execute($ticket, [
    'assigned_to' => $staff->id,
    'status' => 'in_progress',
]);

// 3. Staff responds
$message = app(CreateTicketMessageAction::class)->execute([
    'ticket_id' => $ticket->id,
    'user_id' => $staff->id,
    'message' => 'Please try resetting your password.',
    'is_staff_reply' => true,
]);

// 4. User responds
$userMessage = app(CreateTicketMessageAction::class)->execute([
    'ticket_id' => $ticket->id,
    'user_id' => $userId,
    'message' => 'That worked! Thank you.',
    'is_staff_reply' => false,
]);

// 5. Staff resolves ticket
app(UpdateTicketAction::class)->execute($ticket, [
    'status' => 'resolved',
    'resolution' => 'Password reset resolved the issue',
]);

// 6. Auto-close after 24 hours if no response
// (scheduled job)
```

### Example 2: Ticket Assignment Algorithm

```php
use App\Models\Ticket;
use App\Models\User;

function assignTicket(Ticket $ticket)
{
    // Find staff in same department
    $staff = User::where('type', 'employee')
        ->where('department', $ticket->department)
        ->where('is_available', true)
        ->withCount(['assignedTickets' => function ($query) {
            $query->whereIn('status', ['open', 'in_progress']);
        }])
        ->orderBy('assigned_tickets_count')
        ->first();
    
    if ($staff) {
        $ticket->update([
            'assigned_to' => $staff->id,
            'assigned_at' => now(),
            'status' => 'in_progress',
        ]);
        
        return $staff;
    }
    
    // No available staff - escalate
    $ticket->update([
        'priority' => 'high',
        'escalated' => true,
    ]);
    
    return null;
}
```

### Example 3: Ticket Report

```php
use App\Models\Ticket;
use Carbon\Carbon;

$startDate = Carbon::now()->startOfMonth();
$endDate = Carbon::now()->endOfMonth();

$report = [
    'total_tickets' => Ticket::whereBetween('created_at', [$startDate, $endDate])->count(),
    
    'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
    
    'resolved_tickets' => Ticket::where('status', 'resolved')
        ->whereBetween('updated_at', [$startDate, $endDate])
        ->count(),
    
    'closed_tickets' => Ticket::where('status', 'closed')
        ->whereBetween('closed_at', [$startDate, $endDate])
        ->count(),
    
    'by_department' => Ticket::whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('department')
        ->selectRaw('department, COUNT(*) as count')
        ->get(),
    
    'by_priority' => Ticket::whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('priority')
        ->selectRaw('priority, COUNT(*) as count')
        ->get(),
    
    'average_resolution_time' => Ticket::where('status', 'resolved')
        ->whereBetween('updated_at', [$startDate, $endDate])
        ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
        ->value('avg_hours'),
];
```

### Example 4: SLA Monitoring

```php
use App\Models\Ticket;
use Carbon\Carbon;

// Find tickets breaching SLA
$slaPriorities = [
    'urgent' => 2,   // hours
    'high' => 8,
    'medium' => 24,
    'low' => 48,
];

$breachingTickets = [];

foreach ($slaPriorities as $priority => $responseTime) {
    $threshold = Carbon::now()->subHours($responseTime);
    
    $tickets = Ticket::where('priority', $priority)
        ->where('status', 'open')
        ->where('created_at', '<', $threshold)
        ->whereNull('first_response_at')
        ->get();
    
    foreach ($tickets as $ticket) {
        $breachingTickets[] = [
            'ticket' => $ticket,
            'breached_by_hours' => $threshold->diffInHours($ticket->created_at),
        ];
        
        // Escalate
        $ticket->update([
            'escalated' => true,
            'escalated_at' => now(),
        ]);
        
        // Notify management
    }
}
```

---

<a name="troubleshooting"></a>
## Troubleshooting

### Tickets Not Being Assigned

**Problem:** New tickets remain unassigned.

**Solution:**
```php
// Check available staff
$availableStaff = User::where('type', 'employee')
    ->where('is_available', true)
    ->count();

if ($availableStaff === 0) {
    // No staff available - notify admin
    User::role('admin')->each(function ($admin) {
        $admin->notify(new NoStaffAvailableNotification());
    });
}

// Manually assign
$ticket = Ticket::find($ticketId);
$staff = User::find($staffId);

$ticket->update([
    'assigned_to' => $staff->id,
    'status' => 'in_progress',
]);
```

### Email Notifications Not Sending

**Problem:** Users not receiving ticket updates.

**Solution:**
```php
// Verify email configuration
$ticket = Ticket::with('user')->find($ticketId);

// Check if user has verified email
if (!$ticket->user->hasVerifiedEmail()) {
    echo "User email not verified\n";
}

// Manually send notification
$ticket->user->notify(new TicketUpdatedNotification($ticket));

// Check mail queue
$pendingJobs = DB::table('jobs')->count();
echo "Pending mail jobs: {$pendingJobs}\n";
```

### Duplicate Ticket Submissions

**Problem:** Users creating multiple tickets for same issue.

**Solution:**
```php
// Check for similar recent tickets
$user = User::find($userId);

$recentTickets = Ticket::where('user_id', $user->id)
    ->where('subject', 'like', "%{$subject}%")
    ->where('created_at', '>', now()->subHours(24))
    ->whereIn('status', ['open', 'in_progress'])
    ->get();

if ($recentTickets->isNotEmpty()) {
    // Show existing tickets to user
    return response()->json([
        'message' => 'You have similar open tickets',
        'tickets' => $recentTickets,
    ], 422);
}
```

### Ticket Response Time Issues

**Problem:** SLA response times being breached.

**Solution:**
```php
// Implement priority queue
$urgentTickets = Ticket::where('priority', 'urgent')
    ->where('status', 'open')
    ->orderBy('created_at')
    ->get();

// Auto-assign to available staff
foreach ($urgentTickets as $ticket) {
    if (!$ticket->assigned_to) {
        $staff = User::where('is_available', true)
            ->where('department', $ticket->department)
            ->first();
        
        if ($staff) {
            $ticket->update([
                'assigned_to' => $staff->id,
                'status' => 'in_progress',
            ]);
        }
    }
}
```

> {danger} Always monitor SLA breaches and escalate appropriately

---

## Integration with Other Modules

### User Management
Link tickets to user accounts:

```php
$user = User::with('tickets')->find($userId);
$openTickets = $user->tickets()->where('status', 'open')->count();
```

### Notification System
Automated ticket notifications:

```php
// New ticket notification to staff
$ticket->assignedStaff->notify(new NewTicketNotification($ticket));

// Status update notification to user
$ticket->user->notify(new TicketStatusChangedNotification($ticket));
```

---

## Permissions

Ticket management requires appropriate permissions:

- `ticket.index` - View tickets list
- `ticket.create` - Create new tickets
- `ticket.edit` - Edit tickets
- `ticket.delete` - Delete tickets
- `ticket.assign` - Assign tickets to staff
- `ticket.resolve` - Resolve tickets

> {info} Users can view and manage their own tickets without special permissions

