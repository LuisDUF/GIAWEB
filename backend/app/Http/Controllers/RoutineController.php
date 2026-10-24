<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoutineRequest;
use App\Http\Requests\UpdateRoutineRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RoutineRepository;
use Illuminate\Http\Request;
use Flash;

class RoutineController extends AppBaseController
{
    /** @var RoutineRepository $routineRepository*/
    private $routineRepository;

    public function __construct(RoutineRepository $routineRepo)
    {
        $this->routineRepository = $routineRepo;
    }

    /**
     * Display a listing of the Routine.
     */
    public function index(Request $request)
    {
        $routines = $this->routineRepository->paginate(10);

        return view('routines.index')
            ->with('routines', $routines);
    }

    /**
     * Show the form for creating a new Routine.
     */
    public function create()
    {
        return view('routines.create');
    }

    /**
     * Store a newly created Routine in storage.
     */
    public function store(CreateRoutineRequest $request)
    {
        $input = $request->all();

        $routine = $this->routineRepository->create($input);

        Flash::success('Routine saved successfully.');

        return redirect(route('routines.index'));
    }

    /**
     * Display the specified Routine.
     */
    public function show($id)
    {
        $routine = $this->routineRepository->find($id);

        if (empty($routine)) {
            Flash::error('Routine not found');

            return redirect(route('routines.index'));
        }

        return view('routines.show')->with('routine', $routine);
    }

    /**
     * Show the form for editing the specified Routine.
     */
    public function edit($id)
    {
        $routine = $this->routineRepository->find($id);

        if (empty($routine)) {
            Flash::error('Routine not found');

            return redirect(route('routines.index'));
        }

        return view('routines.edit')->with('routine', $routine);
    }

    /**
     * Update the specified Routine in storage.
     */
    public function update($id, UpdateRoutineRequest $request)
    {
        $routine = $this->routineRepository->find($id);

        if (empty($routine)) {
            Flash::error('Routine not found');

            return redirect(route('routines.index'));
        }

        $routine = $this->routineRepository->update($request->all(), $id);

        Flash::success('Routine updated successfully.');

        return redirect(route('routines.index'));
    }

    /**
     * Remove the specified Routine from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $routine = $this->routineRepository->find($id);

        if (empty($routine)) {
            Flash::error('Routine not found');

            return redirect(route('routines.index'));
        }

        $this->routineRepository->delete($id);

        Flash::success('Routine deleted successfully.');

        return redirect(route('routines.index'));
    }
}
