<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSessionExerciseRequest;
use App\Http\Requests\UpdateSessionExerciseRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SessionExerciseRepository;
use Illuminate\Http\Request;
use Flash;

class SessionExerciseController extends AppBaseController
{
    /** @var SessionExerciseRepository $sessionExerciseRepository*/
    private $sessionExerciseRepository;

    public function __construct(SessionExerciseRepository $sessionExerciseRepo)
    {
        $this->sessionExerciseRepository = $sessionExerciseRepo;
    }

    /**
     * Display a listing of the SessionExercise.
     */
    public function index(Request $request)
    {
        $sessionExercises = $this->sessionExerciseRepository->paginate(10);

        return view('session_exercises.index')
            ->with('sessionExercises', $sessionExercises);
    }

    /**
     * Show the form for creating a new SessionExercise.
     */
    public function create()
    {
        return view('session_exercises.create');
    }

    /**
     * Store a newly created SessionExercise in storage.
     */
    public function store(CreateSessionExerciseRequest $request)
    {
        $input = $request->all();

        $sessionExercise = $this->sessionExerciseRepository->create($input);

        Flash::success('Session Exercise saved successfully.');

        return redirect(route('sessionExercises.index'));
    }

    /**
     * Display the specified SessionExercise.
     */
    public function show($id)
    {
        $sessionExercise = $this->sessionExerciseRepository->find($id);

        if (empty($sessionExercise)) {
            Flash::error('Session Exercise not found');

            return redirect(route('sessionExercises.index'));
        }

        return view('session_exercises.show')->with('sessionExercise', $sessionExercise);
    }

    /**
     * Show the form for editing the specified SessionExercise.
     */
    public function edit($id)
    {
        $sessionExercise = $this->sessionExerciseRepository->find($id);

        if (empty($sessionExercise)) {
            Flash::error('Session Exercise not found');

            return redirect(route('sessionExercises.index'));
        }

        return view('session_exercises.edit')->with('sessionExercise', $sessionExercise);
    }

    /**
     * Update the specified SessionExercise in storage.
     */
    public function update($id, UpdateSessionExerciseRequest $request)
    {
        $sessionExercise = $this->sessionExerciseRepository->find($id);

        if (empty($sessionExercise)) {
            Flash::error('Session Exercise not found');

            return redirect(route('sessionExercises.index'));
        }

        $sessionExercise = $this->sessionExerciseRepository->update($request->all(), $id);

        Flash::success('Session Exercise updated successfully.');

        return redirect(route('sessionExercises.index'));
    }

    /**
     * Remove the specified SessionExercise from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $sessionExercise = $this->sessionExerciseRepository->find($id);

        if (empty($sessionExercise)) {
            Flash::error('Session Exercise not found');

            return redirect(route('sessionExercises.index'));
        }

        $this->sessionExerciseRepository->delete($id);

        Flash::success('Session Exercise deleted successfully.');

        return redirect(route('sessionExercises.index'));
    }
}
