<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoutineDayRequest;
use App\Http\Requests\UpdateRoutineDayRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RoutineDayRepository;
use Illuminate\Http\Request;
use Flash;

class RoutineDayController extends AppBaseController
{
    /** @var RoutineDayRepository $routineDayRepository*/
    private $routineDayRepository;

    public function __construct(RoutineDayRepository $routineDayRepo)
    {
        $this->routineDayRepository = $routineDayRepo;
    }

    /**
     * Display a listing of the RoutineDay.
     */
    public function index(Request $request)
    {
        $routineDays = $this->routineDayRepository->paginate(10);

        return view('routine_days.index')
            ->with('routineDays', $routineDays);
    }

    /**
     * Show the form for creating a new RoutineDay.
     */
    public function create()
    {
        return view('routine_days.create');
    }

    /**
     * Store a newly created RoutineDay in storage.
     */
    public function store(CreateRoutineDayRequest $request)
    {
        $input = $request->all();

        $routineDay = $this->routineDayRepository->create($input);

        Flash::success('Routine Day saved successfully.');

        return redirect(route('routineDays.index'));
    }

    /**
     * Display the specified RoutineDay.
     */
    public function show($id)
    {
        $routineDay = $this->routineDayRepository->find($id);

        if (empty($routineDay)) {
            Flash::error('Routine Day not found');

            return redirect(route('routineDays.index'));
        }

        return view('routine_days.show')->with('routineDay', $routineDay);
    }

    /**
     * Show the form for editing the specified RoutineDay.
     */
    public function edit($id)
    {
        $routineDay = $this->routineDayRepository->find($id);

        if (empty($routineDay)) {
            Flash::error('Routine Day not found');

            return redirect(route('routineDays.index'));
        }

        return view('routine_days.edit')->with('routineDay', $routineDay);
    }

    /**
     * Update the specified RoutineDay in storage.
     */
    public function update($id, UpdateRoutineDayRequest $request)
    {
        $routineDay = $this->routineDayRepository->find($id);

        if (empty($routineDay)) {
            Flash::error('Routine Day not found');

            return redirect(route('routineDays.index'));
        }

        $routineDay = $this->routineDayRepository->update($request->all(), $id);

        Flash::success('Routine Day updated successfully.');

        return redirect(route('routineDays.index'));
    }

    /**
     * Remove the specified RoutineDay from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $routineDay = $this->routineDayRepository->find($id);

        if (empty($routineDay)) {
            Flash::error('Routine Day not found');

            return redirect(route('routineDays.index'));
        }

        $this->routineDayRepository->delete($id);

        Flash::success('Routine Day deleted successfully.');

        return redirect(route('routineDays.index'));
    }
}
