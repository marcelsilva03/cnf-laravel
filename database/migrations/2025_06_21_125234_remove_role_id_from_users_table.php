<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, migrate existing role_id assignments to Spatie permissions
        $users = DB::table('users')
            ->whereNotNull('role_id')
            ->get();
            
        foreach ($users as $user) {
            // Get the role name from user_roles table
            $userRole = DB::table('user_roles')
                ->where('id', $user->role_id)
                ->first();
                
            if ($userRole) {
                // Check if Spatie role exists
                $spatieRole = DB::table('roles')
                    ->where('name', $userRole->name)
                    ->first();
                    
                if ($spatieRole) {
                    // Assign role using Spatie (check if not already assigned)
                    $existingAssignment = DB::table('model_has_roles')
                        ->where('model_type', 'App\\Models\\User')
                        ->where('model_id', $user->id)
                        ->where('role_id', $spatieRole->id)
                        ->first();
                        
                    if (!$existingAssignment) {
                        DB::table('model_has_roles')->insert([
                            'role_id' => $spatieRole->id,
                            'model_type' => 'App\\Models\\User',
                            'model_id' => $user->id,
                        ]);
                    }
                }
            }
        }
        
        // Now remove the role_id column and its constraint
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraint if it exists
            $table->dropForeign(['role_id']);
            
            // Drop the column
            $table->dropColumn('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the role_id column
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('status');
            $table->foreign('role_id')->references('id')->on('user_roles');
        });
        
        // Optionally migrate Spatie roles back to role_id
        $users = DB::table('model_has_roles')
            ->where('model_type', 'App\\Models\\User')
            ->get();
            
        foreach ($users as $modelRole) {
            $spatieRole = DB::table('roles')
                ->where('id', $modelRole->role_id)
                ->first();
                
            if ($spatieRole) {
                $userRole = DB::table('user_roles')
                    ->where('name', $spatieRole->name)
                    ->first();
                    
                if ($userRole) {
                    DB::table('users')
                        ->where('id', $modelRole->model_id)
                        ->update(['role_id' => $userRole->id]);
                }
            }
        }
    }
};
