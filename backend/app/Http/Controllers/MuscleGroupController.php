<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMuscleGroupRequest;
use App\Http\Requests\UpdateMuscleGroupRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\MuscleGroupRepository;
use Illuminate\Http\Request;
use Flash;

class MuscleGroupController extends AppBaseController
{
    /** @var MuscleGroupRepository $muscleGroupRepository*/
    private $muscleGroupRepository;

    public function __construct(MuscleGroupRepository $muscleGroupRepo)
    {
        $this->muscleGroupRepository = $muscleGroupRepo;
    }

    /**
     * Display a listing of the MuscleGroup.
     */
    public function index(Request $request)
    {
        $muscleGroups = $this->muscleGroupRepository->paginate(10);

        return view('muscle_groups.index')
            ->with('muscleGroups', $muscleGroups);
    }

    /**
     * Show the form for creating a new MuscleGroup.
     */
    public function create()
    {
        return view('muscle_groups.create');
    }

    /**
     * Store a newly created MuscleGroup in storage.
     */
    public function store(CreateMuscleGroupRequest $request)
    {
        $input = $request->all();

        $muscleGroup = $this->muscleGroupRepository->create($input);

        Flash::success('Muscle Group saved successfully.');

        return redirect(route('muscleGroups.index'));
    }

    /**
     * Display the specified MuscleGroup.
     */
    public function show($id)
    {
        $muscleGroup = $this->muscleGroupRepository->find($id);

        if (empty($muscleGroup)) {
            Flash::error('Muscle Group not found');

            return redirect(route('muscleGroups.index'));
        }

        return view('muscle_groups.show')->with('muscleGroup', $muscleGroup);
    }

    /**
     * Show the form for editing the specified MuscleGroup.
     */
    public function edit($id)
    {
        $muscleGroup = $this->muscleGroupRepository->find($id);

        if (empty($muscleGroup)) {
            Flash::error('Muscle Group not found');

            return redirect(route('muscleGroups.index'));
        }

        return view('muscle_groups.edit')->with('muscleGroup', $muscleGroup);
    }

    /**
     * Update the specified MuscleGroup in storage.
     */
    public function update($id, UpdateMuscleGroupRequest $request)
    {
        $muscleGroup = $this->muscleGroupRepository->find($id);

        if (empty($muscleGroup)) {
            Flash::error('Muscle Group not found');

            return redirect(route('muscleGroups.index'));
        }

        $muscleGroup = $this->muscleGroupRepository->update($request->all(), $id);

        Flash::success('Muscle Group updated successfully.');

        return redirect(route('muscleGroups.index'));
    }

    /**
     * Remove the specified MuscleGroup from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $muscleGroup = $this->muscleGroupRepository->find($id);

        if (empty($muscleGroup)) {
            Flash::error('Muscle Group not found');

            return redirect(route('muscleGroups.index'));
        }

        $this->muscleGroupRepository->delete($id);

        Flash::success('Muscle Group deleted successfully.');

        return redirect(route('muscleGroups.index'));
    }
}
