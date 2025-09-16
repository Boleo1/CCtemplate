<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Community;
use App\Models\User;
use App\Models\Events;

class CommunityAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create two test communities
        $pine = Community::create([
            'name' => 'Pine Community Center',
            'slug' => 'pine',
            'description' => 'Main gym and commons for the Pine area.',
            'subdomain' => 'pine.localcenter.test', // optional for local dev
            'is_active' => true,
        ]);

        $cedar = Community::create([
            'name' => 'Cedar Community Center',
            'slug' => 'cedar',
            'description' => 'Commons area for Cedar community events.',
            'subdomain' => 'cedar.localcenter.test',
            'is_active' => true,
        ]);

        // Create sample users
        $pineAdmin = User::create([
            'name' => 'Pine Admin',
            'email' => 'pine.admin@example.com',
            'password' => Hash::make('password'),
            'community_id' => $pine->id,
            'role' => 'admin',
        ]);

        $cedarStaff = User::create([
            'name' => 'Cedar Staff',
            'email' => 'cedar.staff@example.com',
            'password' => Hash::make('password'),
            'community_id' => $cedar->id,
            'role' => 'staff',
        ]);

        // Create sample events for Pine
        Events::create([
            'community_id' => $pine->id,
            'title' => 'Open Gym Night',
            'slug' => Str::slug('Open Gym Night') . '-' . Str::random(6),
            'description' => 'Basketball, volleyball, and open gym.',
            'start_at' => Carbon::now()->addDays(2)->setTime(18, 0),
            'end_at' => Carbon::now()->addDays(2)->setTime(20, 0),
            'all_day' => false,
            'status' => 'published',
            'created_by' => $pineAdmin->id,
        ]);

        // Create sample event for Cedar
        Events::create([
            'community_id' => $cedar->id,
            'title' => 'Elders Lunch',
            'slug' => Str::slug('Elders Lunch') . '-' . Str::random(6),
            'description' => 'Hosted by Cedar staff, lunch and social time.',
            'start_at' => Carbon::now()->addDays(3)->setTime(11, 0),
            'end_at' => Carbon::now()->addDays(3)->setTime(13, 0),
            'all_day' => false,
            'status' => 'published',
            'created_by' => $cedarStaff->id,
        ]);
    }
}
