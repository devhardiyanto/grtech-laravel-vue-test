<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Employee Added to Your Company')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new employee has been added to your company.')
            ->line('Employee Details:')
            ->line('Name: ' . $this->employee->full_name)
            ->line('Email: ' . ($this->employee->email ?: 'Not provided'))
            ->line('Phone: ' . ($this->employee->phone ?: 'Not provided'))
            ->action('View Employee Details', url('/employees'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->full_name,
            'company_id' => $this->employee->company_id,
        ];
    }
}