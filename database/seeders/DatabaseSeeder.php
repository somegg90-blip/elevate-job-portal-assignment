<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder
 *
 * Seeds the database with demo data so you can test the portal immediately.
 * Run with: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- ADMIN USER ----
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@elevate.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ---- COMPANY 1: TechNepal ----
        $companyUser1 = User::create([
            'name'     => 'TechNepal HR',
            'email'    => 'hr@technepal.com',
            'password' => Hash::make('password'),
            'role'     => 'company',
        ]);
        $company1 = Company::create([
            'user_id'      => $companyUser1->id,
            'name'         => 'TechNepal Pvt. Ltd.',
            'description'  => 'Leading software development company in Nepal.',
            'location'     => 'Kathmandu, Nepal',
            'industry'     => 'Technology',
            'company_size' => '50–200 employees',
            'website'      => 'https://technepal.com',
        ]);

        // ---- COMPANY 2: Himalayan Bank ----
        $companyUser2 = User::create([
            'name'     => 'HBL Recruitment',
            'email'    => 'recruitment@hbl.com',
            'password' => Hash::make('password'),
            'role'     => 'company',
        ]);
        $company2 = Company::create([
            'user_id'      => $companyUser2->id,
            'name'         => 'Himalayan Bank Limited',
            'description'  => 'One of Nepal\'s premier commercial banks.',
            'location'     => 'Thamel, Kathmandu',
            'industry'     => 'Finance & Banking',
            'company_size' => '500–1000 employees',
        ]);

        // ---- JOBSEEKER ----
        User::create([
            'name'     => 'Ram Bahadur',
            'email'    => 'ram@example.com',
            'password' => Hash::make('password'),
            'role'     => 'jobseeker',
        ]);

        // ---- SAMPLE JOBS ----
        $jobs = [
            [
                'company_id'   => $company1->id,
                'title'        => 'Senior Laravel Developer',
                'description'  => "We are looking for an experienced Laravel developer to join our team.\n\nYou will work on building scalable web applications for clients across South Asia. The role involves designing RESTful APIs, writing clean OOP code, and collaborating with front-end developers.",
                'requirements' => "- 3+ years of PHP/Laravel experience\n- Strong understanding of OOP and MVC architecture\n- Experience with MySQL and Eloquent ORM\n- Knowledge of Git, REST APIs\n- Good communication skills",
                'location'     => 'Kathmandu, Nepal',
                'type'         => 'full-time',
                'salary_range' => 'NPR 60,000 – 90,000/month',
                'category'     => 'Technology',
                'status'       => 'active',
                'deadline'     => now()->addDays(30),
            ],
            [
                'company_id'   => $company1->id,
                'title'        => 'UI/UX Designer',
                'description'  => "Join our creative team as a UI/UX Designer. You will be responsible for designing intuitive and beautiful user interfaces for web and mobile applications.",
                'requirements' => "- Proficiency in Figma or Adobe XD\n- 2+ years of UI/UX design experience\n- Portfolio demonstrating previous work\n- Basic understanding of HTML/CSS",
                'location'     => 'Kathmandu, Nepal',
                'type'         => 'full-time',
                'salary_range' => 'NPR 45,000 – 65,000/month',
                'category'     => 'Design',
                'status'       => 'active',
                'deadline'     => now()->addDays(20),
            ],
            [
                'company_id'   => $company1->id,
                'title'        => 'Junior React Developer (Internship)',
                'description'  => "Great opportunity for fresh graduates to gain industry experience working on real projects using React.js.",
                'requirements' => "- Knowledge of React.js basics\n- HTML, CSS, JavaScript\n- Eagerness to learn",
                'location'     => 'Remote',
                'type'         => 'internship',
                'salary_range' => 'NPR 15,000/month (Stipend)',
                'category'     => 'Technology',
                'status'       => 'active',
                'deadline'     => now()->addDays(15),
            ],
            [
                'company_id'   => $company2->id,
                'title'        => 'Banking Officer (Loans)',
                'description'  => "HBL is seeking a motivated Banking Officer to manage personal and business loan portfolios at our Thamel branch.",
                'requirements' => "- Bachelor's degree in Finance, Management or related field\n- 2+ years banking experience\n- Strong analytical skills\n- Good knowledge of banking regulations in Nepal",
                'location'     => 'Thamel, Kathmandu',
                'type'         => 'full-time',
                'salary_range' => 'As per bank policy',
                'category'     => 'Finance',
                'status'       => 'active',
                'deadline'     => now()->addDays(25),
            ],
            [
                'company_id'   => $company2->id,
                'title'        => 'Digital Marketing Specialist',
                'description'  => "We need a digital marketing expert to handle our social media presence, email campaigns, and online advertising for HBL's retail banking products.",
                'requirements' => "- 2+ years of digital marketing experience\n- Experience with Meta Ads, Google Ads\n- Content creation skills\n- Analytics tools proficiency",
                'location'     => 'Kathmandu, Nepal',
                'type'         => 'full-time',
                'salary_range' => 'NPR 40,000 – 55,000/month',
                'category'     => 'Marketing',
                'status'       => 'active',
            ],
        ];

        foreach ($jobs as $jobData) {
            Job::create($jobData);
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->info('   Admin:     admin@elevate.com / password');
        $this->command->info('   Company 1: hr@technepal.com / password');
        $this->command->info('   Company 2: recruitment@hbl.com / password');
        $this->command->info('   Jobseeker: ram@example.com / password');
    }
}
