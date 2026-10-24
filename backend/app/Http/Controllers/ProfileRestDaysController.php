<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProfileRestDaysRequest;
use App\Http\Requests\UpdateProfileRestDaysRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ProfileRestDaysRepository;
use Illuminate\Http\Request;
use Flash;

class ProfileRestDaysController extends AppBaseController
{
    /** @var ProfileRestDaysRepository $profileRestDaysRepository*/
    private $profileRestDaysRepository;

    public function __construct(ProfileRestDaysRepository $profileRestDaysRepo)
    {
        $this->profileRestDaysRepository = $profileRestDaysRepo;
    }

    /**
     * Display a listing of the ProfileRestDays.
     */
    public function index(Request $request)
    {
        $profileRestDays = $this->profileRestDaysRepository->paginate(10);

        return view('profile_rest_days.index')
            ->with('profileRestDays', $profileRestDays);
    }

    /**
     * Show the form for creating a new ProfileRestDays.
     */
    public function create()
    {
        return view('profile_rest_days.create');
    }

    /**
     * Store a newly created ProfileRestDays in storage.
     */
    public function store(CreateProfileRestDaysRequest $request)
    {
        $input = $request->all();

        $profileRestDays = $this->profileRestDaysRepository->create($input);

        Flash::success('Profile Rest Days saved successfully.');

        return redirect(route('profileRestDays.index'));
    }

    /**
     * Display the specified ProfileRestDays.
     */
    public function show($id)
    {
        $profileRestDays = $this->profileRestDaysRepository->find($id);

        if (empty($profileRestDays)) {
            Flash::error('Profile Rest Days not found');

            return redirect(route('profileRestDays.index'));
        }

        return view('profile_rest_days.show')->with('profileRestDays', $profileRestDays);
    }

    /**
     * Show the form for editing the specified ProfileRestDays.
     */
    public function edit($id)
    {
        $profileRestDays = $this->profileRestDaysRepository->find($id);

        if (empty($profileRestDays)) {
            Flash::error('Profile Rest Days not found');

            return redirect(route('profileRestDays.index'));
        }

        return view('profile_rest_days.edit')->with('profileRestDays', $profileRestDays);
    }

    /**
     * Update the specified ProfileRestDays in storage.
     */
    public function update($id, UpdateProfileRestDaysRequest $request)
    {
        $profileRestDays = $this->profileRestDaysRepository->find($id);

        if (empty($profileRestDays)) {
            Flash::error('Profile Rest Days not found');

            return redirect(route('profileRestDays.index'));
        }

        $profileRestDays = $this->profileRestDaysRepository->update($request->all(), $id);

        Flash::success('Profile Rest Days updated successfully.');

        return redirect(route('profileRestDays.index'));
    }

    /**
     * Remove the specified ProfileRestDays from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $profileRestDays = $this->profileRestDaysRepository->find($id);

        if (empty($profileRestDays)) {
            Flash::error('Profile Rest Days not found');

            return redirect(route('profileRestDays.index'));
        }

        $this->profileRestDaysRepository->delete($id);

        Flash::success('Profile Rest Days deleted successfully.');

        return redirect(route('profileRestDays.index'));
    }
}
