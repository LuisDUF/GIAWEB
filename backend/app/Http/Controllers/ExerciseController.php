<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ExerciseRepository;
use Illuminate\Http\Request;
use Flash;

class ExerciseController extends AppBaseController
{
    /** @var ExerciseRepository $exerciseRepository*/
    private $exerciseRepository;

    public function __construct(ExerciseRepository $exerciseRepo)
    {
        $this->exerciseRepository = $exerciseRepo;
    }

    /**
     * Display a listing of the Exercise.
     */
    public function index(Request $request)
    {
        $exercises = $this->exerciseRepository->paginate(10);

        return view('exercises.index')
            ->with('exercises', $exercises);
    }

    /**
     * Show the form for creating a new Exercise.
     */
    public function create()
    {
        return view('exercises.create');
    }

    /**
     * Store a newly created Exercise in storage.
     */
    public function store(CreateExerciseRequest $request)
    {
        $input = $request->all();

        validator($input, [
        'name' => 'required|string',
        'muscle_group_id' => 'required',
        'description' => 'nullable|string',
        'image_url' => 'nullable|string',
        'animacion' => 'nullable|string',
        'equipment_type' => 'nullable|string',
        'maquina' => 'nullable|string',
        'con_asiento' => 'nullable|boolean',
        'con_respaldo' => 'nullable|boolean'
        ])->validate();

        $exercise = $this->exerciseRepository->create($input);

        Flash::success('Exercise saved successfully.');

        return redirect(route('exercises.index'));
    }

    /**
     * Display the specified Exercise.
     */
    public function show($id)
    {
        $exercise = $this->exerciseRepository->find($id);

        if (empty($exercise)) {
            Flash::error('Exercise not found');

            return redirect(route('exercises.index'));
        }

        return view('exercises.show')->with('exercise', $exercise);
    }

    /**
     * Show the form for editing the specified Exercise.
     */
    public function edit($id)
    {
        $exercise = $this->exerciseRepository->find($id);

        if (empty($exercise)) {
            Flash::error('Exercise not found');

            return redirect(route('exercises.index'));
        }

        return view('exercises.edit')->with('exercise', $exercise);
    }

    /**
     * Update the specified Exercise in storage.
     */
    public function update($id, UpdateExerciseRequest $request)
    {
        $exercise = $this->exerciseRepository->find($id);

        if (empty($exercise)) {
            Flash::error('Exercise not found');

            return redirect(route('exercises.index'));
        }

        $exercise = $this->exerciseRepository->update($request->all(), $id);

        Flash::success('Exercise updated successfully.');

        return redirect(route('exercises.index'));
    }

    /**
     * Remove the specified Exercise from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $exercise = $this->exerciseRepository->find($id);

        if (empty($exercise)) {
            Flash::error('Exercise not found');

            return redirect(route('exercises.index'));
        }

        $this->exerciseRepository->delete($id);

        Flash::success('Exercise deleted successfully.');

        return redirect(route('exercises.index'));
    }
}
