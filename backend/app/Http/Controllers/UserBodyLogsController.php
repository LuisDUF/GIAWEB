<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserBodyLogsRequest;
use App\Http\Requests\UpdateUserBodyLogsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserBodyLogsRepository;
use Illuminate\Http\Request;
use Flash;

class UserBodyLogsController extends AppBaseController
{
    /** @var UserBodyLogsRepository $userBodyLogsRepository*/
    private $userBodyLogsRepository;

    public function __construct(UserBodyLogsRepository $userBodyLogsRepo)
    {
        $this->userBodyLogsRepository = $userBodyLogsRepo;
    }

    /**
     * Display a listing of the UserBodyLogs.
     */
    public function index(Request $request)
    {
        $userBodyLogs = $this->userBodyLogsRepository->paginate(10);

        return view('user_body_logs.index')
            ->with('userBodyLogs', $userBodyLogs);
    }

    /**
     * Show the form for creating a new UserBodyLogs.
     */
    public function create()
    {
        return view('user_body_logs.create');
    }

    /**
     * Store a newly created UserBodyLogs in storage.
     */
    public function store(CreateUserBodyLogsRequest $request)
    {
        $input = $request->all();

        $userBodyLogs = $this->userBodyLogsRepository->create($input);

        Flash::success('User Body Logs saved successfully.');

        return redirect(route('userBodyLogs.index'));
    }

    /**
     * Display the specified UserBodyLogs.
     */
    public function show($id)
    {
        $userBodyLogs = $this->userBodyLogsRepository->find($id);

        if (empty($userBodyLogs)) {
            Flash::error('User Body Logs not found');

            return redirect(route('userBodyLogs.index'));
        }

        return view('user_body_logs.show')->with('userBodyLogs', $userBodyLogs);
    }

    /**
     * Show the form for editing the specified UserBodyLogs.
     */
    public function edit($id)
    {
        $userBodyLogs = $this->userBodyLogsRepository->find($id);

        if (empty($userBodyLogs)) {
            Flash::error('User Body Logs not found');

            return redirect(route('userBodyLogs.index'));
        }

        return view('user_body_logs.edit')->with('userBodyLogs', $userBodyLogs);
    }

    /**
     * Update the specified UserBodyLogs in storage.
     */
    public function update($id, UpdateUserBodyLogsRequest $request)
    {
        $userBodyLogs = $this->userBodyLogsRepository->find($id);

        if (empty($userBodyLogs)) {
            Flash::error('User Body Logs not found');

            return redirect(route('userBodyLogs.index'));
        }

        $userBodyLogs = $this->userBodyLogsRepository->update($request->all(), $id);

        Flash::success('User Body Logs updated successfully.');

        return redirect(route('userBodyLogs.index'));
    }

    /**
     * Remove the specified UserBodyLogs from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userBodyLogs = $this->userBodyLogsRepository->find($id);

        if (empty($userBodyLogs)) {
            Flash::error('User Body Logs not found');

            return redirect(route('userBodyLogs.index'));
        }

        $this->userBodyLogsRepository->delete($id);

        Flash::success('User Body Logs deleted successfully.');

        return redirect(route('userBodyLogs.index'));
    }
}
