<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkoutSessionTagRequest;
use App\Http\Requests\UpdateWorkoutSessionTagRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\WorkoutSessionTagRepository;
use Illuminate\Http\Request;
use Flash;

class WorkoutSessionTagController extends AppBaseController
{
    /** @var WorkoutSessionTagRepository $workoutSessionTagRepository*/
    private $workoutSessionTagRepository;

    public function __construct(WorkoutSessionTagRepository $workoutSessionTagRepo)
    {
        $this->workoutSessionTagRepository = $workoutSessionTagRepo;
    }

    /**
     * Display a listing of the WorkoutSessionTag.
     */
    public function index(Request $request)
    {
        $workoutSessionTags = $this->workoutSessionTagRepository->paginate(10);

        return view('workout_session_tags.index')
            ->with('workoutSessionTags', $workoutSessionTags);
    }

    /**
     * Show the form for creating a new WorkoutSessionTag.
     */
    public function create()
    {
        return view('workout_session_tags.create');
    }

    /**
     * Store a newly created WorkoutSessionTag in storage.
     */
    public function store(CreateWorkoutSessionTagRequest $request)
    {
        $input = $request->all();

        $workoutSessionTag = $this->workoutSessionTagRepository->create($input);

        Flash::success('Workout Session Tag saved successfully.');

        return redirect(route('workoutSessionTags.index'));
    }

    /**
     * Display the specified WorkoutSessionTag.
     */
    public function show($id)
    {
        $workoutSessionTag = $this->workoutSessionTagRepository->find($id);

        if (empty($workoutSessionTag)) {
            Flash::error('Workout Session Tag not found');

            return redirect(route('workoutSessionTags.index'));
        }

        return view('workout_session_tags.show')->with('workoutSessionTag', $workoutSessionTag);
    }

    /**
     * Show the form for editing the specified WorkoutSessionTag.
     */
    public function edit($id)
    {
        $workoutSessionTag = $this->workoutSessionTagRepository->find($id);

        if (empty($workoutSessionTag)) {
            Flash::error('Workout Session Tag not found');

            return redirect(route('workoutSessionTags.index'));
        }

        return view('workout_session_tags.edit')->with('workoutSessionTag', $workoutSessionTag);
    }

    /**
     * Update the specified WorkoutSessionTag in storage.
     */
    public function update($id, UpdateWorkoutSessionTagRequest $request)
    {
        $workoutSessionTag = $this->workoutSessionTagRepository->find($id);

        if (empty($workoutSessionTag)) {
            Flash::error('Workout Session Tag not found');

            return redirect(route('workoutSessionTags.index'));
        }

        $workoutSessionTag = $this->workoutSessionTagRepository->update($request->all(), $id);

        Flash::success('Workout Session Tag updated successfully.');

        return redirect(route('workoutSessionTags.index'));
    }

    /**
     * Remove the specified WorkoutSessionTag from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $workoutSessionTag = $this->workoutSessionTagRepository->find($id);

        if (empty($workoutSessionTag)) {
            Flash::error('Workout Session Tag not found');

            return redirect(route('workoutSessionTags.index'));
        }

        $this->workoutSessionTagRepository->delete($id);

        Flash::success('Workout Session Tag deleted successfully.');

        return redirect(route('workoutSessionTags.index'));
    }
}
