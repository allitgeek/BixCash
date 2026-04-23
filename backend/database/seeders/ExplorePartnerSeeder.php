<?php

namespace Database\Seeders;

use App\Models\ExplorePartner;
use App\Models\ExplorePartnerBranch;
use Illuminate\Database\Seeder;

class ExplorePartnerSeeder extends Seeder
{
    public function run(): void
    {
        // A reasonable default weekly schedule — admin can tweak per branch.
        $defaultSchedule = function (string $open = '10:00', string $close = '22:00'): array {
            $days = ['mon','tue','wed','thu','fri','sat','sun'];
            return collect($days)->mapWithKeys(fn($d) => [$d => ['closed' => false, 'open' => $open, 'close' => $close]])->all();
        };

        // ============ ONLINE ============
        $online = [
            ['Khaadi',             'Fashion & Apparel', 'Premium fusion wear blending traditional Pakistani craft with modern silhouettes. Free nationwide shipping on all orders.', 'https://www.khaadi.com/',         false, false],
            ['Sapphire',           'Fashion & Apparel', 'Elegant everyday luxury — unstitched, ready-to-wear, and accessories from Pakistan\'s leading lifestyle brand.',             'https://pk.sapphireonline.pk/',  true,  false],
            ['Junaid Jamshed',     'Fashion & Apparel', 'Timeless menswear and formal eastern collections trusted by professionals across Pakistan.',                                 'https://www.junaidjamshed.com/', false, false],
            ['Fresh Box',          'Groceries',         'Farm-fresh fruits, vegetables, and pantry staples delivered to your doorstep within 24 hours.',                              'https://freshbox.example.com/',  false, true],
            ['Almirah',            'Fashion & Apparel', 'Classic fabrics with contemporary cuts. Unstitched collections for every season and occasion.',                              'https://almirah.com.pk/',        false, false],
            ['Al Gardd Jewelers',  'Jewelry',           'Handcrafted gold and diamond jewelry with certified authenticity. Nationwide insured delivery.',                             'https://algardd.example.com/',   false, false],
        ];

        foreach ($online as $i => [$name, $cat, $desc, $url, $featured, $new]) {
            ExplorePartner::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name).'-online'],
                [
                    'name'              => $name,
                    'type'              => 'online',
                    'category_tag'      => $cat,
                    'short_description' => $desc,
                    'visit_url'         => $url,
                    'is_featured'       => $featured,
                    'is_new'            => $new,
                    'is_active'         => true,
                    'display_order'     => $i,
                ]
            );
        }

        // ============ OFFLINE + branches ============
        $offline = [
            [
                'name' => 'KFC',
                'category' => 'Food & Beverage',
                'description' => 'World-famous fried chicken — fresh, crunchy, and flame-grilled specials across 42 branches in Pakistan.',
                'branch_count_label' => '42 branches',
                'is_featured' => true,
                'is_new' => false,
                'branches' => [
                    ['KFC F-10 Markaz',     'Plaza 7, F-10 Markaz',        'Islamabad',  '051-111-532-532', 33.6808, 73.0170, '11:00', '00:00'],
                    ['KFC MM Alam Road',    'MM Alam Road, Gulberg III',   'Lahore',     '042-111-532-532', 31.5197, 74.3436, '11:00', '01:00'],
                    ['KFC Dolmen Clifton',  'Dolmen Mall, Clifton Block 4', 'Karachi',   '021-111-532-532', 24.8156, 67.0291, '11:00', '00:00'],
                    ['KFC Saddar',          'The Mall Road, Saddar',       'Rawalpindi', '051-111-532-532', 33.5950, 73.0460, '11:00', '23:00'],
                    ['KFC Jail Road',       'Jail Road, Gulberg',          'Lahore',     '042-111-532-532', 31.5203, 74.3580, '11:00', '01:00'],
                    ['KFC Blue Area',       'Jinnah Avenue, Blue Area',    'Islamabad',  '051-111-532-532', 33.7125, 73.0662, '10:00', '00:00'],
                ],
            ],
            [
                'name' => 'Khaadi',
                'category' => 'Fashion & Apparel',
                'description' => 'Visit our flagship stores for the full in-store experience — summer lawn, pret, and accessories.',
                'branch_count_label' => '68 outlets',
                'is_featured' => false,
                'is_new' => false,
                'branches' => [
                    ['Khaadi Centaurus Mall', 'The Centaurus Mall',      'Islamabad', '051-111-542-342', 33.7081, 73.0500, '10:00', '22:00'],
                    ['Khaadi Dolmen Mall',    'Dolmen Mall Clifton',     'Karachi',   '021-111-542-342', 24.8156, 67.0291, '10:00', '22:00'],
                    ['Khaadi Emporium',       'Emporium Mall, Johar Town', 'Lahore',  '042-111-542-342', 31.4680, 74.2666, '10:00', '22:00'],
                    ['Khaadi Giga Mall',      'Giga Mall, DHA Phase II', 'Islamabad', '051-111-542-342', 33.5328, 73.0879, '10:00', '22:00'],
                ],
            ],
            [
                'name' => 'Sapphire',
                'category' => 'Fashion & Apparel',
                'description' => 'Elegant in-store shopping at Sapphire flagships across major cities of Pakistan.',
                'branch_count_label' => '52 stores',
                'is_featured' => false,
                'is_new' => false,
                'branches' => [
                    ['Sapphire Packages Mall', 'Packages Mall, Walton Road', 'Lahore',    '0800-22772', 31.5097, 74.3436, '11:00', '23:00'],
                    ['Sapphire Centaurus',     'The Centaurus Mall',         'Islamabad', '0800-22772', 33.7081, 73.0500, '11:00', '23:00'],
                    ['Sapphire Dolmen',        'Dolmen Mall Clifton',        'Karachi',   '0800-22772', 24.8156, 67.0291, '11:00', '23:00'],
                ],
            ],
            [
                'name' => 'Al Gardd Jewelers',
                'category' => 'Jewelry',
                'description' => 'Private showroom experience for bridal and heirloom pieces. Walk in for consultation.',
                'branch_count_label' => '8 showrooms',
                'is_featured' => false,
                'is_new' => false,
                'branches' => [
                    ['Al Gardd Tariq Road',    'Tariq Road',           'Karachi',   '021-3456-7890', 24.8671, 67.0670, '11:00', '21:00'],
                    ['Al Gardd Liberty',       'Liberty Market, Gulberg', 'Lahore', '042-3456-7890', 31.5300, 74.3430, '11:00', '21:00'],
                    ['Al Gardd F-7',           'F-7 Markaz',           'Islamabad', '051-3456-7890', 33.7215, 73.0433, '11:00', '21:00'],
                ],
            ],
            [
                'name' => 'Jeeva Supermart',
                'category' => 'Groceries',
                'description' => 'Fresh produce, pantry staples, and household items at neighborhood-friendly prices.',
                'branch_count_label' => '24 stores',
                'is_featured' => false,
                'is_new' => true,
                'branches' => [
                    ['Jeeva Johar Town',  'Johar Town Block H', 'Lahore',     '0304-111-5555', 31.4718, 74.2668, '08:00', '23:00'],
                    ['Jeeva Bahria',      'Bahria Town Phase 4', 'Rawalpindi', '0304-111-5555', 33.5225, 73.0830, '08:00', '23:00'],
                    ['Jeeva Hayatabad',   'Hayatabad Phase 3',  'Peshawar',   '0304-111-5555', 34.0151, 71.4740, '08:00', '23:00'],
                ],
            ],
            [
                'name' => 'Fresh Box Express',
                'category' => 'Groceries',
                'description' => 'Quick-stop neighborhood grocer — fresh produce, bakery and snacks, close to where you live.',
                'branch_count_label' => '15 stores',
                'is_featured' => false,
                'is_new' => false,
                'branches' => [
                    ['Fresh Box G-11',       'G-11 Markaz',          'Islamabad', '0312-888-6666', 33.6650, 72.9920, '09:00', '22:00'],
                    ['Fresh Box DHA Phase 5', 'Y-Block, DHA Phase 5', 'Lahore',    '0312-888-6666', 31.4710, 74.4060, '09:00', '22:00'],
                ],
            ],
        ];

        foreach ($offline as $i => $row) {
            $partner = ExplorePartner::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($row['name']).'-offline'],
                [
                    'name'               => $row['name'],
                    'type'               => 'offline',
                    'category_tag'       => $row['category'],
                    'short_description'  => $row['description'],
                    'branch_count_label' => $row['branch_count_label'],
                    'is_featured'        => $row['is_featured'],
                    'is_new'             => $row['is_new'],
                    'is_active'          => true,
                    'display_order'      => $i,
                ]
            );

            // Wipe and re-seed branches for idempotency
            $partner->branches()->delete();

            foreach ($row['branches'] as $j => [$name, $addr, $city, $phone, $lat, $lng, $open, $close]) {
                ExplorePartnerBranch::create([
                    'explore_partner_id' => $partner->id,
                    'name'               => $name,
                    'address'            => $addr,
                    'city'               => $city,
                    'phone'              => $phone,
                    'lat'                => $lat,
                    'lng'                => $lng,
                    'weekly_schedule'    => $defaultSchedule($open, $close),
                    'display_order'      => $j,
                    'is_active'          => true,
                ]);
            }
        }
    }
}
