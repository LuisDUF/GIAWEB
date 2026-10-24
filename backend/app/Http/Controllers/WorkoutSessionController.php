<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkoutSessionRequest;
use App\Http\Requests\UpdateWorkoutSessionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\WorkoutSessionRepository;
use Illuminate\Http\Request;
use Flash;

class WorkoutSessionController extends AppBaseController
{
    /** @var WorkoutSessionRepository $workoutSessionRepository*/
    private $workoutSessionRepository;

    public function __construct(WorkoutSessionRepository $workoutSessionRepo)
    {
        $this->workoutSessionRepository = $workoutSessionRepo;
    }

    /**
     * Display a listing of the WorkoutSession.
     */
    public function index(Request $request)
    {
        $workoutSessions = $this->workoutSessionRepository->paginate(10);

        return view('workout_sessions.index')
            ->with('workoutSessions', $workoutSessions);
    }

    /**
     * Show the form for creating a new WorkoutSession.
     */
    public function create()
    {
        return view('workout_sessions.create');
    }

    /**
     * Store a newly created WorkoutSession in storage.
     */
    public function store(CreateWorkoutSessionRequest $request)
    {
        $input = $request->all();

        $workoutSession = $this->workoutSessionRepository->create($input);

        Flash::success('Workout Session saved successfully.');

        return redirect(route('workoutSessions.index'));
    }

    /**
     * Display the specified WorkoutSession.
     */
    public function show($id)
    {
        $workoutSession = $this->workoutSessionRepository->find($id);

        if (empty($workoutSession)) {
            Flash::error('Workout Session not found');

            return redirect(route('workoutSessions.index'));
        }

        return view('workout_sessions.show')->with('workoutSession', $workoutSession);
    }

    /**
     * Show the form for editing the specified WorkoutSession.
     */
    public function edit($id)
    {
        $workoutSession = $this->workoutSessionRepository->find($id);

        if (empty($workoutSession)) {
            Flash::error('Workout Session not found');

            return redirect(route('workoutSessions.index'));
        }

        return view('workout_sessions.edit')->with('workoutSession', $workoutSession);
    }

    /**
     * Update the specified WorkoutSession in storage.
     */
    public function update($id, UpdateWorkoutSessionRequest $request)
    {
        $workoutSession = $this->workoutSessionRepository->find($id);

        if (empty($workoutSession)) {
            Flash::error('Workout Session not found');

            return redirect(route('workoutSessions.index'));
        }

        $workoutSession = $this->workoutSessionRepository->update($request->all(), $id);

        Flash::success('Workout Session updated successfully.');

        return redirect(route('workoutSessions.index'));
    }

    /**
     * Remove the specified WorkoutSession from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $workoutSession = $this->workoutSessionRepository->find($id);

        if (empty($workoutSession)) {
            Flash::error('Workout Session not found');

            return redirect(route('workoutSessions.index'));
        }

        $this->workoutSessionRepository->delete($id);

        Flash::success('Workout Session deleted successfully.');

        return redirect(route('workoutSessions.index'));
    }
}
