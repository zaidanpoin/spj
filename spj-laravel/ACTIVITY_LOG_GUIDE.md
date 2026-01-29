# Activity Log Implementation Guide

## Overview
Activity logging telah diimplementasikan menggunakan **Spatie Laravel Activity Log** package.

## Database Table
Table: `activity_log`

Struktur table dibuat otomatis dari migrations Spatie:
- `id` - Primary key
- `log_name` - Nama log (nullable)
- `description` - Deskripsi aktivitas
- `subject_type` - Model yang dilog (polymorphic)
- `subject_id` - ID dari model yang dilog
- `causer_type` - Model user yang melakukan aksi (polymorphic)
- `causer_id` - ID user yang melakukan aksi
- `properties` - JSON data perubahan (old & new values)
- `event` - Event type (created, updated, deleted)
- `batch_uuid` - UUID untuk batch operations
- `created_at` - Waktu aktivitas
- `updated_at` - Waktu update log

## Implementasi di Model

### 1. Tambahkan Trait di Model
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kegiatan extends Model
{
    use HasFactory, LogsActivity;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_kegiatan', 'uraian_kegiatan', 'unit_kerja_id', 'unor_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Kegiatan {$eventName}");
    }
}
```

### 2. Konfigurasi LogOptions
- `logOnly()` - Tentukan field mana yang akan dilog
- `logOnlyDirty()` - Hanya log jika ada perubahan
- `dontSubmitEmptyLogs()` - Jangan submit log kosong
- `setDescriptionForEvent()` - Custom deskripsi untuk setiap event

## Routes
```php
// Activity Logs
Route::prefix('activity-logs')->middleware(['auth'])->group(function () {
    Route::get('/', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
});
```

## Views
1. **Index Page** (`activity-logs/index.blade.php`)
   - Filter by date range, event, user
   - Pagination
   - Mobile responsive

2. **Detail Page** (`activity-logs/show.blade.php`)
   - Detail informasi aktivitas
   - Perbandingan data lama vs baru (old vs attributes)

## Cara Penggunaan

### Automatic Logging
Model yang sudah di-setup dengan trait akan otomatis log:
```php
// Created event
$kegiatan = Kegiatan::create([...]);

// Updated event
$kegiatan->update(['nama_kegiatan' => 'New Name']);

// Deleted event
$kegiatan->delete();
```

### Manual Logging
```php
activity()
    ->performedOn($kegiatan)
    ->causedBy(auth()->user())
    ->withProperties(['key' => 'value'])
    ->log('Custom activity description');
```

### Query Activity Logs
```php
// Get all activities
$activities = Activity::all();

// Get activities for specific model
$activities = Activity::forSubject($kegiatan)->get();

// Get activities by specific user
$activities = Activity::causedBy($user)->get();

// Filter by event
$activities = Activity::where('event', 'created')->get();
```

## Menambahkan Model Lain

Untuk menambahkan activity log ke model lain (contoh: User, Konsumsi, dll):

1. Tambahkan trait dan method di model:
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Model
{
    use LogsActivity;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}");
    }
}
```

2. Selesai! Model akan otomatis log semua create, update, delete.

## Event Types
- `created` - Saat data dibuat
- `updated` - Saat data diupdate
- `deleted` - Saat data dihapus

## URL Access
- List: `http://127.0.0.1:8000/activity-logs`
- Detail: `http://127.0.0.1:8000/activity-logs/{id}`

## Permissions (Optional)
Jika ingin tambahkan permission:
```php
Route::prefix('activity-logs')->middleware(['auth', 'permission:view-activity-logs'])->group(function () {
    // routes
});
```

## Testing
Coba create/update/delete Kegiatan dan lihat hasilnya di:
```
http://127.0.0.1:8000/activity-logs
```
