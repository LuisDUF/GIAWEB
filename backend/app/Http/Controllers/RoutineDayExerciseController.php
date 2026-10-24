<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoutineDayExerciseRequest;
use App\Http\Requests\UpdateRoutineDayExerciseRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RoutineDayExerciseRepository;
use Illuminate\Http\Request;
use Flash;

class RoutineDayExerciseController extends AppBaseController
{
    /** @var RoutineDayExerciseRepository $routineDayExerciseRepository*/
    private $routineDayExerciseRepository;

    public function __construct(RoutineDayExerciseRepository $routineDayExerciseRepo)
    {
        $this->routineDayExerciseRepository = $routineDayExerciseRepo;
    }

    /**
     * Display a listing of the RoutineDayExercise.
     */
    public function index(Request $request)
    {
        $routineDayExercises = $this->routineDayExerciseRepository->paginate(10);

        return view('routine_day_exercises.index')
            ->with('routineDayExercises', $routineDayExercises);
    }

    /**
     * Show the form for creating a new RoutineDayExercise.
     */
    public function create()
    {
        return view('routine_day_exercises.create');
    }

    /**
     * Store a newly created RoutineDayExercise in storage.
     */
    public function store(CreateRoutineDayExerciseRequest $request)
    {
        $input = $request->all();

        $routineDayExercise = $this->routineDayExerciseRepository->create($input);

        Flash::success('Routine Day Exercise saved successfully.');

        return redirect(route('routineDayExercises.index'));
    }

    /**
     * Display the specified RoutineDayExercise.
     */
    public function show($id)
    {
        $routineDayExercise = $this->routineDayExerciseRepository->find($id);

        if (empty($routineDayExercise)) {
            Flash::error('Routine Day Exercise not found');

            return redirect(route('routineDayExercises.index'));
        }

        return view('routine_day_exercises.show')->with('routineDayExercise', $routineDayExercise);
    }

    /**
     * Show the form for editing the specified RoutineDayExercise.
     */
    public function edit($id)
    {
        $routineDayExercise = $this->routineDayExerciseRepository->find($id);

        if (empty($routineDayExercise)) {
            Flash::error('Routine Day Exercise not found');

            return redirect(route('routineDayExercises.index'));
        }

        return view('routine_day_exercises.edit')->with('routineDayExercise', $routineDayExercise);
    }

    /**
     * Update the specified RoutineDayExercise in storage.
     */
    public function update($id, UpdateRoutineDayExerciseRequest $request)
    {
        $routineDayExercise = $this->routineDayExerciseRepository->find($id);

        if (empty($routineDayExercise)) {
            Flash::error('Routine Day Exercise not found');

            return redirect(route('routineDayExercises.index'));
        }

        $routineDayExercise = $this->routineDayExerciseRepository->update($request->all(), $id);

        Flash::success('Routine Day Exercise updated successfully.');

        return redirect(route('routineDayExercises.index'));
    }

    /**
     * Remove the specified RoutineDayExercise from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $routineDayExercise = $this->routineDayExerciseRepository->find($id);

        if (empty($routineDayExercise)) {
            Flash::error('Routine Day Exercise not found');

            return redirect(route('routineDayExercises.index'));
        }

        $this->routineDayExerciseRepository->delete($id);

        Flash::success('Routine Day Exercise deleted successfully.');

        return redirect(route('routineDayExercises.index'));
    }
}
