<?php

namespace Database\Seeders\DemoDataSeeder;

use App\Models\Tenant\UserHasProject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    protected $domainType;
    protected $insertedIds = [];
    protected $users;
    protected $domainData;
    protected $jsonData;

    public function __construct($domainType = null)
    {
        $this->domainType = $domainType;
        $path = base_path('storage/app/public/demo-data.json');

        if (file_exists($path)) {
            $this->jsonData = json_decode(file_get_contents($path), true);
            $this->users = $this->jsonData['users']; // array of users


        } else {
            abort(404, 'demo-data.json file not found.');
        }
    }
    public function run()
    {
        // read the json file from storage\app\public\demo-data.json
        try {
            $this->domainData = $this->jsonData[$this->domainType]; // domain data
            if (empty($this->domainData)) {
                abort(404, 'domain data not found.');
            }
            //user data seeding
            $userModels = [];
            foreach ($this->users as $userData) {
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'password' => bcrypt($userData['password']),
                        'phone' => $userData['phone'] ?? null,
                        'gender' => $userData['gender'] ?? null,
                        'created_at' => now(),
                    ]
                );
                $userModels[$user->name] = $user;
                $this->insertedIds['users'][] = $user->id;
            }
            DB::beginTransaction();

            //project data seeding
            foreach ($this->domainData['projects'] as $projectData) {
                $project = \App\Models\Tenant\Project::firstOrCreate(
                    ['name' => $projectData['name']],
                    [
                        'code' => $projectData['code'],
                        'description' => $projectData['description'],
                        'status' => 'active',
                        'created_at' => now(),
                    ]
                );
                $projectModels[$project->name] = $project;
                $projectLead = $userModels[$projectData['project_lead']];

                if (!$project->users()->where('user_id', $projectLead->id)->exists()) {
                    $project->users()->attach($projectLead->id, ['is_lead' => 1, 'role_id' => 1]);
                }
                //assign users to project
                foreach ($this->users as $user) {

                    $userId = $userModels[$user['name']]->id;
                    if (!$project->users()->where('user_id', $userId)->exists()) {
                        $mapUser = UserHasProject::create([
                            'user_id' => $userId,
                            'project_id' => $project->id,
                            'is_lead' => 0,
                            'role_id' => $user['role_id'],
                            'created_by' => auth()->user()->id,
                            'created_at' => now(),
                        ]);
                        $this->insertedIds['user_has_project'][] = $mapUser->id;
                    }
                }
                $this->insertedIds['projects'][] = $project->id;
            }

            //issueData seeding
            foreach ($this->domainData['issues'] as $issueData) {

                $issue = \App\Models\Tenant\Issue::create([
                    'summery' => $issueData['title'],
                    'description' => $issueData['description'],
                    'status' => 'open',
                    'project_id' =>   $projectModels[$issueData['project_name']]->id,
                    'assigned_to' => $userModels[$issueData['assignee']]->id,
                    'created_by' => $userModels[$issueData['user']]->id,
                    'severity' => $issueData['severity'],
                    'issue_type' => $issueData['type'],
                    'created_at' => now(),
                ]);
                $issueModels[$issue->summery] = $issue;
                $this->insertedIds['issues'][] = $issue->id;
            }

            //comments seeding 
            foreach ($this->domainData['comments'] as $commentData) {
                $comment = \App\Models\Tenant\Comment::create([
                    'description' => $commentData['description'],
                    'issue_id' => $issueModels[$commentData['issue_title']]->id,
                    'comment_by' => $userModels[$commentData['comment_by']]->id,
                    'created_at' => now(),
                ]);
                $this->insertedIds['comments'][] = $comment->id;
            }
            DB::commit();
            file_put_contents(
                base_path('storage/app/public/demo_ids.json'),
                json_encode($this->insertedIds, JSON_PRETTY_PRINT)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function cleanup()
    {
        DB::beginTransaction();
        try {
            $ids = $this->getseededData();

            if (empty($ids)) {
                return "No demo data found.";
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            if (!empty($ids['comments'])) {
                DB::table('comments')->whereIn('id', $ids['comments'])->delete();
            }

            if (!empty($ids['issues'])) {
                DB::table('issues')->whereIn('id', $ids['issues'])->delete();
            }

            if (!empty($ids['user_has_project'])) {
                DB::table('user_has_project')->whereIn('id', $ids['user_has_project'])->delete();
            }

            if (!empty($ids['projects'])) {
                DB::table('projects')->whereIn('id', $ids['projects'])->delete();
            }
            if (!empty($ids['users'])) {
                DB::table('users')->whereIn('id', $ids['users'])->delete();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Commit the database changes
            DB::commit();

            // Only clear the file after commit
            $path = base_path('storage/app/public/demo_ids.json');
            if (!file_exists($path)) {
                touch($path);
                chmod($path, 0777);
            }
            file_put_contents(
                $path,
                json_encode([], JSON_PRETTY_PRINT)
            );

            return "Demo data deleted successfully.";
        } catch (\Throwable $th) {
            DB::rollBack(); // Revert DB changes on error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return "Error while deleting demo data: " . $th->getMessage();
        }
        // unlink($path); // delete file after cleanup (optional)
    }
    public function getDomainType()
    {
        $isDemoDataExist = false;
        $ids = $this->getseededData();
        if (!empty($ids)) {
            $isDemoDataExist = true;
        }
        $domains = [];
        foreach ($this->jsonData as $key => $value) {
            if ($key == 'users') {
                continue;
            }
            $domains[] = $key;
        }
        return response()->json([
            'isDemoDataExist' => $isDemoDataExist,
            'domains' => $domains
        ], 200);
    }
    private function getseededData()
    {
        $path = base_path('storage/app/public/demo_ids.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        } else {
            throw new \Exception("Demo IDs file not found.");
        }
    }
}
