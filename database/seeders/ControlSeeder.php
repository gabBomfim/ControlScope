<?php

namespace Database\Seeders;

use App\Models\Control;
use Illuminate\Database\Seeder;

class ControlSeeder extends Seeder
{
    public function run(): void
    {
        $controls = [
            ['GV.OC', 'Govern', 'Organizational Context', 'Define security ownership', 'Security responsibilities are named and understood by the people who own risk decisions.', 'Assign a named owner for each security decision and review ownership quarterly.'],
            ['GV.RR', 'Govern', 'Roles and Responsibilities', 'Review access ownership', 'Access, risk, and incident responsibilities have clear accountable owners.', 'Document owners for privileged access, incident response, and vendor risk.'],
            ['ID.AM', 'Identify', 'Asset Management', 'Maintain an asset inventory', 'Critical applications, services, and data stores are listed with an accountable owner.', 'Create a small inventory with business owner, environment, and criticality fields.'],
            ['ID.RA', 'Identify', 'Risk Assessment', 'Track security risks', 'Security risks are recorded with likelihood, impact, and a planned treatment.', 'Review open risks monthly and record a decision or treatment for each one.'],
            ['PR.AA', 'Protect', 'Identity Management', 'Use strong authentication', 'Users and service accounts use appropriate authentication for the sensitivity of the system.', 'Require MFA for privileged access and remove shared accounts.'],
            ['PR.DS', 'Protect', 'Data Security', 'Protect sensitive data', 'Sensitive data has defined handling, retention, and access expectations.', 'Classify sensitive data and document encryption and retention requirements.'],
            ['PR.PS', 'Protect', 'Platform Security', 'Harden production platforms', 'Production platforms follow a repeatable baseline for updates and secure configuration.', 'Create a baseline checklist and record exceptions with an owner.'],
            ['DE.CM', 'Detect', 'Continuous Monitoring', 'Monitor important signals', 'Security and reliability signals are collected for important services and reviewed by a team.', 'Choose a small set of signals and define who responds when they change.'],
            ['DE.AE', 'Detect', 'Adverse Event Analysis', 'Triage suspicious events', 'Suspicious events are triaged with enough context to decide whether to escalate.', 'Write a short triage playbook with severity and escalation examples.'],
            ['RS.MA', 'Respond', 'Incident Management', 'Coordinate incident response', 'The organization can coordinate people, decisions, and updates during a security incident.', 'Run a tabletop exercise and keep the contact and escalation path current.'],
            ['RS.CO', 'Respond', 'Communications', 'Communicate during incidents', 'Internal and external incident communications have clear owners and approval paths.', 'Prepare templates for technical updates, leadership updates, and customer notices.'],
            ['RC.RP', 'Recover', 'Recovery Planning', 'Test service recovery', 'Critical services have a recovery path that is exercised and improved over time.', 'Test one recovery path each quarter and track lessons learned.'],
        ];

        foreach ($controls as [$code, $function, $category, $title, $description, $recommendation]) {
            Control::updateOrCreate(
                ['code' => $code],
                compact('function', 'category', 'title', 'description', 'recommendation')
            );
        }

        Control::whereIn('code', ['GV.OC', 'DE.CM'])->update(['status' => 'implemented']);
        Control::whereIn('code', ['ID.AM', 'PR.AA', 'RS.MA'])->update(['status' => 'partial']);
    }
}
