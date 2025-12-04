<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Schedule;

class ScheduleReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $schedule;
    protected $notificationRecord;

    public function __construct(Schedule $schedule, $notificationRecord = null)
    {
        $this->schedule = $schedule;
        $this->notificationRecord = $notificationRecord;
        $this->onQueue('notifications');
    }

    public function via(object $notifiable): array
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'notification_id' => $this->notificationRecord?->notification_id,
            'schedule_id' => $this->schedule->schedule_id,
            'course_name' => $this->schedule->course->course_name ?? 'Unknown',
            'class_name' => $this->schedule->class->class_name ?? '',
            'room_name' => $this->schedule->room->room_name ?? 'Unknown',
            'day' => $this->schedule->day,
            'time_slot' => $this->schedule->time_slot,
            'message' => "Pengingat: Anda mengajar {$this->schedule->course->course_name} ({$this->schedule->class->class_name}) di ruang {$this->schedule->room->room_name} pada pukul {$this->schedule->time_slot}",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'notification_id' => $this->notificationRecord?->notification_id,
            'schedule_id' => $this->schedule->schedule_id,
            'title' => 'Pengingat Jadwal Mengajar',
            'message' => "Pengingat: Anda mengajar {$this->schedule->course->course_name} ({$this->schedule->class->class_name}) di ruang {$this->schedule->room->room_name} pada pukul {$this->schedule->time_slot}",
            'course_name' => $this->schedule->course->course_name ?? 'Unknown',
            'class_name' => $this->schedule->class->class_name ?? '',
            'room_name' => $this->schedule->room->room_name ?? 'Unknown',
            'day' => $this->schedule->day,
            'time_slot' => $this->schedule->time_slot,
            'notified_at' => now()->toIso8601String(),
        ]);
    }
}