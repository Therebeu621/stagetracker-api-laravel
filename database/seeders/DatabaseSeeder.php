<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Followup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demo1 = User::updateOrCreate(
            ['email' => 'demo1@stagetracker.test'],
            [
                'name' => 'Demo User 1',
                'password' => 'password123',
            ]
        );

        $demo2 = User::updateOrCreate(
            ['email' => 'demo2@stagetracker.test'],
            [
                'name' => 'Demo User 2',
                'password' => 'password123',
            ]
        );

        $this->seedDemoData($demo1, [
            [
                'company' => 'Decathlon',
                'position' => 'Backend Intern',
                'location' => 'Lille',
                'status' => 'interview',
                'applied_at' => Carbon::now()->subDays(18)->toDateString(),
                'next_followup_at' => Carbon::now()->addDays(3)->toDateString(),
                'notes' => 'Applied via company careers page.',
                'followups' => [
                    [
                        'type' => 'email',
                        'done_at' => Carbon::now()->subDays(13)->toDateString(),
                        'notes' => 'Sent first follow-up email.',
                    ],
                    [
                        'type' => 'call',
                        'done_at' => Carbon::now()->subDays(6)->toDateString(),
                        'notes' => 'Recruiter confirmed interview slot.',
                    ],
                ],
            ],
            [
                'company' => 'OVHcloud',
                'position' => 'DevOps Intern',
                'location' => 'Roubaix',
                'status' => 'applied',
                'applied_at' => Carbon::now()->subDays(9)->toDateString(),
                'next_followup_at' => Carbon::now()->addDays(5)->toDateString(),
                'notes' => 'Application submitted with referral.',
                'followups' => [
                    [
                        'type' => 'linkedin',
                        'done_at' => Carbon::now()->subDays(4)->toDateString(),
                        'notes' => 'Connected with engineering manager.',
                    ],
                ],
            ],
            [
                'company' => 'Doctolib',
                'position' => 'Platform Intern',
                'location' => 'Paris',
                'status' => 'rejected',
                'applied_at' => Carbon::now()->subDays(30)->toDateString(),
                'next_followup_at' => null,
                'notes' => 'Rejected after online assessment.',
                'followups' => [
                    [
                        'type' => 'email',
                        'done_at' => Carbon::now()->subDays(23)->toDateString(),
                        'notes' => 'Received rejection email.',
                    ],
                ],
            ],
        ]);

        $this->seedDemoData($demo2, [
            [
                'company' => 'BlaBlaCar',
                'position' => 'Backend Intern',
                'location' => 'Paris',
                'status' => 'offer',
                'applied_at' => Carbon::now()->subDays(35)->toDateString(),
                'next_followup_at' => null,
                'notes' => 'Offer received after final interview.',
                'followups' => [
                    [
                        'type' => 'call',
                        'done_at' => Carbon::now()->subDays(10)->toDateString(),
                        'notes' => 'Discussed offer details with HR.',
                    ],
                ],
            ],
            [
                'company' => 'Alan',
                'position' => 'Fullstack Intern',
                'location' => 'Paris',
                'status' => 'applied',
                'applied_at' => Carbon::now()->subDays(7)->toDateString(),
                'next_followup_at' => Carbon::now()->addDays(7)->toDateString(),
                'notes' => 'Applied through welcome-to-the-jungle.',
                'followups' => [],
            ],
        ]);
    }

    /**
     * Seed demo applications/followups only when account has no data yet.
     */
    private function seedDemoData(User $user, array $applications): void
    {
        if ($user->applications()->exists()) {
            return;
        }

        foreach ($applications as $applicationData) {
            $followups = $applicationData['followups'] ?? [];
            unset($applicationData['followups']);

            $application = Application::create([
                ...$applicationData,
                'user_id' => $user->id,
            ]);

            foreach ($followups as $followupData) {
                Followup::create([
                    ...$followupData,
                    'application_id' => $application->id,
                ]);
            }
        }
    }
}
