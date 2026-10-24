<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWorkoutTagRequest;
use App\Http\Requests\UpdateWorkoutTagRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\WorkoutTagRepository;
use Illuminate\Http\Request;
use Flash;

class WorkoutTagController extends AppBaseController
{
    /** @var WorkoutTagRepository $workoutTagRepository*/
    private $workoutTagRepository;

    public function __construct(WorkoutTagRepository $workoutTagRepo)
    {
        $this->workoutTagRepository = $workoutTagRepo;
    }

    /**
     * Display a listing of the WorkoutTag.
     */
    public function index(Request $request)
    {
        $workoutTags = $this->workoutTagRepository->paginate(10);

        return view('workout_tags.index')
            ->with('workoutTags', $workoutTags);
    }

    /**
     * Show the form for creating a new WorkoutTag.
     */
    public function create()
    {
        return view('workout_tags.create');
    }

    /**
     * Store a newly created WorkoutTag in storage.
     */
    public function store(CreateWorkoutTagRequest $request)
    {
        $input = $request->all();

        $workoutTag = $this->workoutTagRepository->create($input);

        Flash::success('Workout Tag saved successfully.');

        return redirect(route('workoutTags.index'));
    }

    /**
     * Display the specified WorkoutTag.
     */
    public function show($id)
    {
        $workoutTag = $this->workoutTagRepository->find($id);

        if (empty($workoutTag)) {
            Flash::error('Workout Tag not found');

            return redirect(route('workoutTags.index'));
        }

        return view('workout_tags.show')->with('workoutTag', $workoutTag);
    }

    /**
     * Show the form for editing the specified WorkoutTag.
     */
    public function edit($id)
    {
        $workoutTag = $this->workoutTagRepository->find($id);

        if (empty($workoutTag)) {
            Flash::error('Workout Tag not found');

            return redirect(route('workoutTags.index'));
        }

        return view('workout_tags.edit')->with('workoutTag', $workoutTag);
    }

    /**
     * Update the specified WorkoutTag in storage.
     */
    public function update($id, UpdateWorkoutTagRequest $request)
    {
        $workoutTag = $this->workoutTagRepository->find($id);

        if (empty($workoutTag)) {
            Flash::error('Workout Tag not found');

            return redirect(route('workoutTags.index'));
        }

        $workoutTag = $this->workoutTagRepository->update($request->all(), $id);

        Flash::success('Workout Tag updated successfully.');

        return redirect(route('workoutTags.index'));
    }

    /**
     * Remove the specified WorkoutTag from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $workoutTag = $this->workoutTagRepository->find($id);

        if (empty($workoutTag)) {
            Flash::error('Workout Tag not found');

            return redirect(route('workoutTags.index'));
        }

        $this->workoutTagRepository->delete($id);

        Flash::success('Workout Tag deleted successfully.');

        return redirect(route('workoutTags.index'));
    }
}
