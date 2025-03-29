<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BadgeController extends Controller
{
    public function listStudentBadges($id)
    {
        Log::info('Fetching student badges', ['student_id' => $id]);

        $student = User::find($id)->where('role', 'student')->with('badges')->first();
        if (!$student) {
            Log::error('Student not found', ['student_id' => $id]);
            return response()->json(['error' => 'Student not found'], 404);
        }
        if ($student->badges->isEmpty()) {
            Log::info('No badges found for student', ['student_id' => $id]);
            return response()->json(['message' => 'No badges found for this student'], 404);
        }
        Log::info('Badges retrieved', ['badges' => $student->badges]);
        return response()->json($student->badges);
    }


    public function store(Request $request)
    {
        Log::info('Creating new badge', ['data' => $request->all()]);

        $request->validate([
            'name' => 'required|string|unique:badges',
            'description' => 'required|string'
        ]);

        try {
            $badge = Badge::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            Log::info('Badge created', ['badge_id' => $badge->id]);
            return response()->json(['message' => 'Badge created successfully', 'badge' => $badge], 201);
        } catch (\Exception $e) {
            Log::error('Badge creation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create badge'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Updating badge', ['badge_id' => $id, 'data' => $request->all()]);

        $badge = Badge::find($id);

        if (!$badge) {
            Log::error('Badge update failed: Badge not found', ['badge_id' => $id]);
            return response()->json(['error' => 'Badge not found'], 404);
        }

        $badge->update($request->all());

        Log::info('Badge updated', ['badge_id' => $id]);
        return response()->json(['message' => 'Badge updated successfully', 'badge' => $badge]);
    }

    public function destroy($id)
    {
        Log::info('Deleting badge', ['badge_id' => $id]);

        $badge = Badge::find($id);

        if (!$badge) {
            Log::error('Badge deletion failed: Badge not found', ['badge_id' => $id]);
            return response()->json(['error' => 'Badge not found'], 404);
        }

        $badge->delete();

        Log::info('Badge deleted', ['badge_id' => $id]);
        return response()->json(['message' => 'Badge deleted successfully']);
    }
}
