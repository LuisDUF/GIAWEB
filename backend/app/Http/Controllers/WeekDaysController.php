<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWeekDaysRequest;
use App\Http\Requests\UpdateWeekDaysRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\WeekDaysRepository;
use Illuminate\Http\Request;
use Flash;

class WeekDaysController extends AppBaseController
{
    /** @var WeekDaysRepository $weekDaysRepository*/
    private $weekDaysRepository;

    public function __construct(WeekDaysRepository $weekDaysRepo)
    {
        $this->weekDaysRepository = $weekDaysRepo;
    }

    /**
     * Display a listing of the WeekDays.
     */
    public function index(Request $request)
    {
        $weekDays = $this->weekDaysRepository->paginate(10);

        return view('week_days.index')
            ->with('weekDays', $weekDays);
    }

    /**
     * Show the form for creating a new WeekDays.
     */
    public function create()
    {
        return view('week_days.create');
    }

    /**
     * Store a newly created WeekDays in storage.
     */
    public function store(CreateWeekDaysRequest $request)
    {
        $input = $request->all();

        $weekDays = $this->weekDaysRepository->create($input);

        Flash::success('Week Days saved successfully.');

        return redirect(route('weekDays.index'));
    }

    /**
     * Display the specified WeekDays.
     */
    public function show($id)
    {
        $weekDays = $this->weekDaysRepository->find($id);

        if (empty($weekDays)) {
            Flash::error('Week Days not found');

            return redirect(route('weekDays.index'));
        }

        return view('week_days.show')->with('weekDays', $weekDays);
    }

    /**
     * Show the form for editing the specified WeekDays.
     */
    public function edit($id)
    {
        $weekDays = $this->weekDaysRepository->find($id);

        if (empty($weekDays)) {
            Flash::error('Week Days not found');

            return redirect(route('weekDays.index'));
        }

        return view('week_days.edit')->with('weekDays', $weekDays);
    }

    /**
     * Update the specified WeekDays in storage.
     */
    public function update($id, UpdateWeekDaysRequest $request)
    {
        $weekDays = $this->weekDaysRepository->find($id);

        if (empty($weekDays)) {
            Flash::error('Week Days not found');

            return redirect(route('weekDays.index'));
        }

        $weekDays = $this->weekDaysRepository->update($request->all(), $id);

        Flash::success('Week Days updated successfully.');

        return redirect(route('weekDays.index'));
    }

    /**
     * Remove the specified WeekDays from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $weekDays = $this->weekDaysRepository->find($id);

        if (empty($weekDays)) {
            Flash::error('Week Days not found');

            return redirect(route('weekDays.index'));
        }

        $this->weekDaysRepository->delete($id);

        Flash::success('Week Days deleted successfully.');

        return redirect(route('weekDays.index'));
    }
}
