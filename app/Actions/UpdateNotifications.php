<?php

namespace App\Actions;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateNotifications
{
    public function __invoke(Request $request): RedirectResponse
    {
        $fields = $request->validate([
            'daily_tasks_due' => ['required', 'boolean'],
            'weekly_tasks_due' => ['required', 'boolean'],
            'tasks_assigned' => ['required', 'boolean'],
            'tasks_updated' => ['required', 'boolean'],
        ]);

        $user = $request->user();

        $user->settings['notifications'] = [
            'daily_tasks_due' => $fields['daily_tasks_due'],
            'weekly_tasks_due' => $fields['weekly_tasks_due'],
            'tasks_assigned' => $fields['tasks_assigned'],
            'tasks_updated' => $fields['tasks_updated'],
        ];

        $user->save();

        session()->flash('inertia', ['status' => 'Notifications updated!']);

        return response()->redirectToRoute('settings.notifications');
    }
}
