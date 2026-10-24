<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSessionSetsRequest;
use App\Http\Requests\UpdateSessionSetsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\SessionSetsRepository;
use Illuminate\Http\Request;
use Flash;

class SessionSetsController extends AppBaseController
{
    /** @var SessionSetsRepository $sessionSetsRepository*/
    private $sessionSetsRepository;

    public function __construct(SessionSetsRepository $sessionSetsRepo)
    {
        $this->sessionSetsRepository = $sessionSetsRepo;
    }

    /**
     * Display a listing of the SessionSets.
     */
    public function index(Request $request)
    {
        $sessionSets = $this->sessionSetsRepository->paginate(10);

        return view('session_sets.index')
            ->with('sessionSets', $sessionSets);
    }

    /**
     * Show the form for creating a new SessionSets.
     */
    public function create()
    {
        return view('session_sets.create');
    }

    /**
     * Store a newly created SessionSets in storage.
     */
    public function store(CreateSessionSetsRequest $request)
    {
        $input = $request->all();

        $sessionSets = $this->sessionSetsRepository->create($input);

        Flash::success('Session Sets saved successfully.');

        return redirect(route('sessionSets.index'));
    }

    /**
     * Display the specified SessionSets.
     */
    public function show($id)
    {
        $sessionSets = $this->sessionSetsRepository->find($id);

        if (empty($sessionSets)) {
            Flash::error('Session Sets not found');

            return redirect(route('sessionSets.index'));
        }

        return view('session_sets.show')->with('sessionSets', $sessionSets);
    }

    /**
     * Show the form for editing the specified SessionSets.
     */
    public function edit($id)
    {
        $sessionSets = $this->sessionSetsRepository->find($id);

        if (empty($sessionSets)) {
            Flash::error('Session Sets not found');

            return redirect(route('sessionSets.index'));
        }

        return view('session_sets.edit')->with('sessionSets', $sessionSets);
    }

    /**
     * Update the specified SessionSets in storage.
     */
    public function update($id, UpdateSessionSetsRequest $request)
    {
        $sessionSets = $this->sessionSetsRepository->find($id);

        if (empty($sessionSets)) {
            Flash::error('Session Sets not found');

            return redirect(route('sessionSets.index'));
        }

        $sessionSets = $this->sessionSetsRepository->update($request->all(), $id);

        Flash::success('Session Sets updated successfully.');

        return redirect(route('sessionSets.index'));
    }

    /**
     * Remove the specified SessionSets from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $sessionSets = $this->sessionSetsRepository->find($id);

        if (empty($sessionSets)) {
            Flash::error('Session Sets not found');

            return redirect(route('sessionSets.index'));
        }

        $this->sessionSetsRepository->delete($id);

        Flash::success('Session Sets deleted successfully.');

        return redirect(route('sessionSets.index'));
    }
}
