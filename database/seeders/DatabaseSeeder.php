<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\Vote;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $departments = [
            'Accounts',
            'Accounts Vault',
            'Audiology/School Health',
            'Business Unit II',
            'Central Stores (C-40)',
            'Check Dispatch',
            'Chemistry Foods & Drugs Division',
            'Corporate Communications',
            'Couva Medical and Multi-Training Facility',
            'Directorate of Women\'s Health',
            'Disaster Preparedness Coordinating Unit',
            'Drug Inspectorate Division',
            'Environmental Health',
            'Epidemiology Division',
            'Expanded Programme on Immunization',
            'External Patient Programme',
            'Facilities Management',
            'General Administration',
            'General Administration & Vertical Services',
            'Hansen\'s Disease Control Unit',
            'Health Education',
            'Health Policy Research and Planning',
            'Health Sector Advisory Unit',
            'Health Sector Human Resource Planning',
            'Health Services Support Program',
            'HIV/AIDS Coordinating Unit',
            'Human Resources Management Division',
            'Human Resource Development Unit',
            'ICT',
            'Insect Vector Control Division',
            'Industrial Relations/Employee Relations (Human Resource)',
            'Internal Audit',
            'International Cooperation Desk',
            'JSAC Record Management Facility',
            'Legal Services',
            'Ministry of Health, Head Office',
            'Medical Section',
            'National Blood Transfusion Services',
            'National Alcohol and Drug Abuse Prevention Programme',
            'National Emergency Ambulance Service Authority',
            'New Corporate Headquarters',
            'Non-Communicable Diseases',
            'Occupational Safety Health',
            'Office of the Chief Medical Officer',
            'Office Management',
            'Office of the Deputy Permanent Secretary',
            'Office of the Minister of Health',
            'Office of the Permanent Secretary',
            'Office of the Senior Health System Adviser',
            'Permanent Secretary Secretariat',
            'PMO\'s Office',
            'Population Programme Unit',
            'Procurement Unit',
            'Project Management Unit',
            'Quality Management',
            'Queen\'s Park Counseling Centre & Clinic',
            'Recruitment Unit',
            'Registry',
            'Special Programmes and Services Unit',
            'Terminal Benefits Section',
            'Tobacco Control Unit',
            'Tradezone',
            'Trinidad Public Health Laboratory',
            'Vertical Services',
            'Veterinary Public Health',
        ];

        foreach ($departments as $department) {
            Department::create(['name' => $department]);
        }

        $roles = [
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }


        $users = [
            [
                'name' => 'Jardel Regis',
                'email' => 'jardel.regis@health.gov.tt',
                'username' => 'jardel.regis',
                'department' => 'ICT',
                'role_id' => 1,
            ],
            [
                'name' => 'Varma Maharaj',
                'email' => 'varma.maharaj@health.gov.tt',
                'username' => 'varma.maharaj',
                'department' => 'ICT',
                'role_id' => 1,
            ],
            [
                'name' => 'Parbatie Boochoon',
                'email' => 'parbatie.boochoon@health.gov.tt',
                'username' => 'parbatie.boochoon',
                'department' => 'ICT',
                'role_id' => 1,
            ],
            [
                'name' => 'Aviann Boodoo',
                'email' => 'aviann.boodoo@health.gov.tt',
                'username' => 'aviann.boodoo',
                'department' => 'Procurement',
                'role_id' => 3,
            ],
            [
                'name' => 'Maryann Basdeo',
                'email' => 'maryann.basdeo@health.gov.tt',
                'username' => 'maryann.basdeo',
                'department' => 'Procurement',
                'role_id' => 2,
            ],
            [
                'name' => 'Kevin Badal',
                'email' => 'kevin.badal@health.gov.tt',
                'username' => 'kevin.badal',
                'department' => 'Procurement',
                'role_id' => 3,
            ],
            [
                'name' => 'Crystal Ann Sahibram-Bickram',
                'email' => 'crystal.bickram@health.gov.tt',
                'username' => 'crystal.bickram',
                'department' => 'Cost & Budgeting',
                'role_id' => 3,
            ],
            [
                'name' => 'Kamanie Alexander',
                'email' => 'kamanie.alexander@health.gov.tt',
                'username' => 'kamanie.alexander',
                'department' => 'Cost & Budgeting',
                'role_id' => 3,
            ],
            [
                'name' => 'Salisha Shah',
                'email' => 'salisha.shah@health.gov.tt',
                'username' => 'salisha.shah',
                'department' => 'Vote Control',
                'role_id' => 3,
            ],
            [
                'name' => 'Candise Morris',
                'email' => 'candise.morris@health.gov.tt',
                'username' => 'candise.morris',
                'department' => 'Check Staff',
                'role_id' => 3,
            ],
            [
                'name' => 'Cheryl Keen',
                'email' => 'cheryl.keen@health.gov.tt',
                'username' => 'cheryl.keen',
                'department' => 'Check Staff',
                'role_id' => 3,
            ],
            [
                'name' => 'Vicky Kissoondath',
                'email' => 'vicky.kissoondath@health.gov.tt',
                'username' => 'vicky.kissoondath',
                'department' => 'Check Staff',
                'role_id' => 3,
            ],
            [
                'name' => 'David Rooplal',
                'email' => 'david.rooplal@health.gov.tt',
                'username' => 'david.rooplal',
                'department' => 'Cheque Processing',
                'role_id' => 3,
            ],
            [
                'name' => 'Rohini Lodie',
                'email' => 'rohini.lodie@health.gov.tt',
                'username' => 'rohini.lodie',
                'department' => 'Cheque Processing',
                'role_id' => 3,
            ],
            [
                'name' => 'Adrian Rampersad',
                'email' => 'adrian.rampersad@health.gov.tt',
                'username' => 'adrian.rampersad',
                'department' => 'Cheque Processing',
                'role_id' => 3,
            ],

        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $votes = [
            // General Administration
            ['number' => '02/001/10', 'name' => 'Office Stationery and Supplies'],
            ['number' => '02/001/12', 'name' => 'Materials and Supplies'],
            ['number' => '02/001/13', 'name' => 'Maintenance of Vehicles'],
            ['number' => '02/001/15', 'name' => 'Repairs & Maintenance - Equipment'],
            ['number' => '02/001/21', 'name' => 'Repairs & Maintenance - Buildings'],
            ['number' => '02/001/23', 'name' => 'Fees'],
            ['number' => '02/001/27', 'name' => 'Official Overseas Travel'],
            ['number' => '02/001/37', 'name' => 'Janitorial Services'],
            ['number' => '02/001/57', 'name' => 'Postage'],
            ['number' => '02/001/62', 'name' => 'Promotions, Publicity & Printing'],
            ['number' => '02/001/66', 'name' => 'Hosting Conferences, Seminars & other Functions'],

            // Vertical Services
            ['number' => '02/004/10', 'name' => 'Office Stationery and Supplies'],
            ['number' => '02/004/12', 'name' => 'Materials and Supplies'],
            ['number' => '02/004/13', 'name' => 'Maintenance of Vehicles'],
            ['number' => '02/004/15', 'name' => 'Repairs & Maintenance - Equipment'],
            ['number' => '02/004/21', 'name' => 'Repairs & Maintenance - Buildings'],
            ['number' => '02/004/37', 'name' => 'Janitorial Services'],
            ['number' => '02/004/57', 'name' => 'Postage'],
            ['number' => '02/004/62', 'name' => 'Promotions, Publicity & Printing'],
            ['number' => '02/004/66', 'name' => 'Hosting of Conferences, Seminars and Other Functions'],

            // Nadapp
            ['number' => '02/009/10', 'name' => 'Office Stationery and Supplies'],
            ['number' => '02/009/11', 'name' => 'Books and Periodicals'],
            ['number' => '02/009/12', 'name' => 'Materials and Supplies'],
            ['number' => '02/009/13', 'name' => 'Maintenance of Vehicles'],
            ['number' => '02/009/15', 'name' => 'Repairs & Maintenance - Equipment'],
            ['number' => '02/009/21', 'name' => 'Repairs and Maintenance - Buildings'],
            ['number' => '02/009/37', 'name' => 'Janitorial Services'],
            ['number' => '02/009/57', 'name' => 'Postage'],
            ['number' => '02/009/62', 'name' => 'Promotions, Publicity & Printing'],
            ['number' => '02/009/66', 'name' => 'Hosting of Conferences, Seminars and Other Functions'],

            // Minor Equipment (General Administration)
            ['number' => '03/001/01', 'name' => 'Vehicles (Replacement)'],
            ['number' => '03/001/02', 'name' => 'Office Equipment'],
            ['number' => '03/001/03', 'name' => 'Furniture & Furnishing'],
            ['number' => '03/001/04', 'name' => 'Other Minor Equipment'],

            // Minor Equipment (Vertical Services)
            ['number' => '03/004/01', 'name' => 'Vehicles (Replacement)'],
            ['number' => '03/004/02', 'name' => 'Office Equipment'],
            ['number' => '03/004/03', 'name' => 'Furniture & Furnishing'],
            ['number' => '03/004/04', 'name' => 'Other Minor Equipment'],

            // Minor Equipment (Nadapp)
            ['number' => '03/009/01', 'name' => 'Vehicles (Replacement)'],
            ['number' => '03/009/02', 'name' => 'Office Equipment'],
            ['number' => '03/009/03', 'name' => 'Furniture & Furnishing'],
            ['number' => '03/009/04', 'name' => 'Other Minor Equipment'],

            // Development Programme
            ['number' => '09/005/06/C/240', 'name' => 'Information Systems (Equipment and Software)'],
            ['number' => '09/005/06/G/002', 'name' => 'Equipping of the Chemistry Food and Drugs'],
            ['number' => '09/005/06/F/001', 'name' => 'Refurbishment and Improvement for the Vertical Division of the Ministry of Health'],
            ['number' => '09/005/06/A/002', 'name' => 'Disaster Preparedness Coordinating Unit'],
        ];

        foreach ($votes as $vote) {
            Vote::create($vote);
        }
    }
}
