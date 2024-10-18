<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
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


        $users = [
            [
                'name' => 'Jardel Regis',
                'email' => 'jardel.regis@health.gov.tt',
                'department' => 'ICT',
            ],
            [
                'name' => 'Varma Maharaj',
                'email' => 'varma.maharaj@health.gov.tt',
                'department' => 'ICT',
            ],
            [
                'name' => 'Aviann Boodoo',
                'email' => 'aviann.boodoo@health.gov.tt',
                'department' => 'Procurement',
            ],
            [
                'name' => 'Maryann Basdeo',
                'email' => 'maryann.basdeo@health.gov.tt',
                'department' => 'Procurement',
            ],
            [
                'name' => 'Kevin Badal',
                'email' => 'kevin.badal@health.gov.tt',
                'department' => 'Procurement',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
