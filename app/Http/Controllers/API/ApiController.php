<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\User;
use App\Models\Workout;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->where('status', true)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json([
                'message'      => 'Login successful',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user,
            ], 200);
        } catch (\Exception $e) {
            // $this->handleException($e);
            return response()->json('An error occured while Login: ', 400, ['error' => $e->getMessage()]);
        }
    }


    public function index()
    {
        try {
            $workouts = Workout::get();
            return response()->json(['workouts' => $workouts], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch workouts', 'message' => $e->getMessage()], 500);
        }
    }


    public function dashboardDetails()
    {
        try {
            $workoutCount = Workout::count();
            $userCount = User::where('status', 1)->count();
            $topLifters = ProgressLog::leftJoin('workouts as w', 'progress_logs.workout_id', '=', 'w.id')
                ->leftJoin('users as u', 'progress_logs.user_id', '=', 'u.id')
                ->select(
                    'u.name as user_name',
                    'w.workout_name',
                    'progress_logs.weight',
                    'progress_logs.set_number',
                    'progress_logs.reps'
                )
                ->orderByDesc('progress_logs.weight')
                ->limit(5)
                ->get();

            return response()->json(['workoutCount' => $workoutCount, 'userCount' => $userCount, 'topLifters' => $topLifters], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch dashboard details', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $workoutData = new Workout();
            $workoutData->workout_name = $request->workout_name;
            $workoutData->body_part = $request->body_part;
            $workoutData->kcal_per_minute = $request->kcal_per_minute;
            //store in storage folder
            // $path = $request->file('image')->store('image', 'public');
            //store in public folder
            $image = $request->file('image');

            $filename = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('img'), $filename);

            $path = 'img/' . $filename;
            $workoutData->image = $path;
            $workoutData->save();

            return Response()->json(['text' => 'Workout Added Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while storing workouts', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $workoutData = Workout::findOrFail($id);
            $workoutData->delete();
            return Response()->json(['text' => 'Workout deleted Successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while destroy workouts', 'message' => $e->getMessage()], 500);
        }
    }

    public function getAllUsers()
    {
        try {
            $userData = User::get();
            return response()->json(['userData' => $userData], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch users', 'message' => $e->getMessage()], 500);
        }
    }

    public function userUpdateStatus(Request $request, int $id)
    {
        try {
            $userData = User::findOrFail($id);
            $userData->status = $request->status;
            $userData->save();
            return response()->json(['text' => 'Status Changed Successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update status', 'message' => $e->getMessage()], 500);
        }
    }
}
