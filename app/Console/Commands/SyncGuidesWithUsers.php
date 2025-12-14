<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Guide;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncGuidesWithUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guides:sync-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync guides with users table - create missing user accounts for guides';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting guides sync with users...');
        
        $guides = Guide::all();
        $created = 0;
        $updated = 0;
        $errors = 0;
        
        foreach ($guides as $guide) {
            try {
                $this->info("Processing guide: {$guide->full_name} (ID: {$guide->id})");
                
                // Check if guide has email
                if (empty($guide->email)) {
                    $this->warn("Guide {$guide->id} has no email, skipping...");
                    continue;
                }
                
                // Check if user already exists
                $user = User::where('email', $guide->email)->first();
                
                if (!$user) {
                    // Create new user
                    $password = Str::random(12);
                    
                    $user = User::create([
                        'name' => $guide->full_name ?? $guide->name ?? 'Guide',
                        'email' => $guide->email,
                        'password' => Hash::make($password),
                        'phone' => $guide->phone,
                        'role' => 'guide',
                    ]);
                    
                    $this->info("Created user for guide {$guide->id}: {$user->email}");
                    $created++;
                    
                    // Update guide with user_id
                    $guide->update(['user_id' => $user->id]);
                    
                } else {
                    // Update existing user role if needed
                    if ($user->role !== 'guide') {
                        $user->update(['role' => 'guide']);
                        $this->info("Updated role for user {$user->id}: {$user->email}");
                        $updated++;
                    }
                    
                    // Update guide with user_id if missing
                    if (!$guide->user_id) {
                        $guide->update(['user_id' => $user->id]);
                        $this->info("Linked guide {$guide->id} with user {$user->id}");
                    }
                }
                
                // Ensure user has guide role in roles table if using role system
                $guideRole = Role::where('name', 'guide')->first();
                if ($guideRole && !$user->roles()->where('role_id', $guideRole->id)->exists()) {
                    $user->roles()->attach($guideRole->id);
                    $this->info("Attached guide role to user {$user->id}");
                }
                
            } catch (\Exception $e) {
                $this->error("Error processing guide {$guide->id}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->info("Sync completed!");
        $this->info("Created: {$created} users");
        $this->info("Updated: {$updated} users");
        $this->info("Errors: {$errors}");
        
        // Test API after sync
        $this->info("Testing API...");
        $guideUsers = User::where('role', 'guide')->count();
        $this->info("Users with role 'guide': {$guideUsers}");
        
        return Command::SUCCESS;
    }
}