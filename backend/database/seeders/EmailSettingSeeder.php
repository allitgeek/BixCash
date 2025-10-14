<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailSetting;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'smtp_host',
                'value' => 'smtp.mailtrap.io',
                'type' => 'text',
                'group' => 'smtp',
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'number',
                'group' => 'smtp',
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'type' => 'text',
                'group' => 'smtp',
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'type' => 'password',
                'group' => 'smtp',
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'tls',
                'type' => 'text',
                'group' => 'smtp',
            ],
            [
                'key' => 'from_address',
                'value' => 'noreply@bixcash.com',
                'type' => 'text',
                'group' => 'smtp',
            ],
            [
                'key' => 'from_name',
                'value' => 'BixCash',
                'type' => 'text',
                'group' => 'smtp',
            ],
            [
                'key' => 'admin_email',
                'value' => 'admin@bixcash.com',
                'type' => 'text',
                'group' => 'smtp',
            ],
        ];

        foreach ($settings as $setting) {
            EmailSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Email settings seeded successfully!');
    }
}
