# Migration Summary - Course Management System Refactor

## Overview
Created comprehensive migrations to refactor the course management system from a simple Course->Session->Resource hierarchy to a template-based system supporting multiple course types, terms, payments, and progress tracking.

## Migration Files Created (in execution order)

### 1. **2025_01_16_000001_create_terms_table.php**
- **Purpose**: Create terms table for academic periods
- **Dependencies**: None (independent table)
- **Key Fields**: title, start_date, end_date, status

### 2. **2025_01_16_000002_create_course_templates_table.php**
- **Purpose**: Create course templates table
- **Dependencies**: categories table (existing)
- **Key Fields**: title, description, category_id, level, prerequisites, is_self_paced, languages, syllabus

### 3. **2025_01_16_000003_create_session_templates_table.php**
- **Purpose**: Create session templates table
- **Dependencies**: course_templates table
- **Key Fields**: course_template_id, order, title, description, duration_minutes

### 4. **2025_01_16_000004_create_resources_table.php**
- **Purpose**: Create polymorphic resources table
- **Dependencies**: None (polymorphic)
- **Key Fields**: resourceable_type, resourceable_id, type, path, title, metadata, is_public

### 5. **2025_01_16_000005_update_rooms_table.php**
- **Purpose**: Add name and location fields to existing rooms table
- **Dependencies**: rooms table (existing)
- **Changes**: Add name, location columns

### 6. **2025_01_16_000006_update_courses_table.php**
- **Purpose**: Refactor courses table to new structure
- **Dependencies**: course_templates, terms, rooms tables
- **Major Changes**:
  - Add: course_template_id, term_id, capacity, status, days_of_week, start_time, end_time, room_id, meeting_link
  - Remove: slug, published, published_at, start_date, end_date, view_count, comment_count, wish_count, languages
  - Add soft deletes

### 7. **2025_01_16_000007_update_sessions_table.php**
- **Purpose**: Refactor sessions table and rename from course_sessions
- **Dependencies**: session_templates table
- **Major Changes**:
  - Rename: course_sessions → sessions
  - Add: session_template_id, date, start_time, end_time, recording_link, status, session_type
  - Remove: teacher_id, session_number, languages
  - Add soft deletes

### 8. **2025_01_16_000008_update_enrollments_table.php**
- **Purpose**: Add progress tracking to enrollments
- **Dependencies**: order_items table (existing)
- **Changes**:
  - Add: order_item_id, enrolled_at, progress_percent
  - Remove: enroll_date
  - Update status to use new enum

### 9. **2025_01_16_000009_update_attendances_table.php**
- **Purpose**: Add excuse notes to attendances
- **Dependencies**: sessions table (renamed)
- **Changes**:
  - Add: excuse_note
  - Update foreign key to reference sessions instead of course_sessions

### 10. **2025_01_16_000010_create_certificates_table.php**
- **Purpose**: Create certificates table for course completion
- **Dependencies**: enrollments table
- **Key Fields**: enrollment_id, issue_date, grade, certificate_path, signature_hash

## Database Relationships Established

### Template Hierarchy
```
CourseTemplate (1) → (n) SessionTemplate
CourseTemplate (1) → (n) Course (instances)
SessionTemplate (1) → (n) Session (instances)
```

### Resource Polymorphism
```
Resource → morphTo → [CourseTemplate, SessionTemplate, Session]
```

### Student Journey
```
Course (1) → (n) Enrollment (1) → (n) Attendance
Enrollment (1) → (1) Certificate
```

### Payment Flow
```
OrderItem → morphTo → [Course, Enrollment]
```

## Key Features Supported

### Course Types
- **In-Person**: Traditional classroom courses
- **Online**: Live virtual sessions  
- **Hybrid**: Combination of in-person and online
- **Self-Paced**: Udemy-style courses with virtual sessions

### Progress Tracking
- Enrollment progress percentage
- Session completion tracking
- Activity logging via existing activity_log table
- Certificate issuance on completion

### Resource Management
- Polymorphic attachment to any entity
- Support for PDFs, videos, images, links
- Public/private access control
- Metadata storage for file information

### Academic Structure
- Term-based organization
- Room management for physical courses
- Flexible scheduling with days_of_week JSON field

## Migration Execution Order

**Critical**: These migrations must be run in the exact order shown above due to foreign key dependencies:

1. Terms (independent)
2. Course Templates (depends on categories)
3. Session Templates (depends on course templates)
4. Resources (polymorphic, no dependencies)
5. Update Rooms (adds fields to existing table)
6. Update Courses (major refactor, depends on templates, terms, rooms)
7. Update Sessions (depends on session templates)
8. Update Enrollments (depends on order items)
9. Update Attendances (depends on sessions)
10. Create Certificates (depends on enrollments)

## Data Migration Considerations

When running these migrations on existing data:

1. **Backup First**: Always backup the database before running migrations
2. **Course Data**: Existing courses will need to be converted to use templates
3. **Session Data**: Existing sessions will need to be mapped to session templates
4. **Resource Data**: Existing resources will need to be migrated to the new polymorphic structure
5. **Enrollment Data**: Existing enrollments will need progress tracking added

## Indexes Added

All migrations include appropriate indexes for performance:
- Foreign key indexes
- Status and type indexes
- Date range indexes
- Composite indexes for common query patterns

## Soft Deletes

Soft deletes are enabled for:
- Course Templates
- Session Templates  
- Resources
- Courses
- Sessions

This ensures data integrity while allowing for data recovery if needed.

## Next Steps

After running these migrations:

1. Update existing data to use the new structure
2. Test all relationships and business logic
3. Update any existing code that references the old structure
4. Verify that all foreign key constraints are working correctly
5. Run tests to ensure data integrity

The refactored system now supports a modern, scalable course management platform with proper separation of concerns and comprehensive progress tracking.
