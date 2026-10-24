<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRoutineSubscriptionRequest;
use App\Http\Requests\UpdateUserRoutineSubscriptionRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserRoutineSubscriptionRepository;
use Illuminate\Http\Request;
use Flash;

class UserRoutineSubscriptionController extends AppBaseController
{
    /** @var UserRoutineSubscriptionRepository $userRoutineSubscriptionRepository*/
    private $userRoutineSubscriptionRepository;

    public function __construct(UserRoutineSubscriptionRepository $userRoutineSubscriptionRepo)
    {
        $this->userRoutineSubscriptionRepository = $userRoutineSubscriptionRepo;
    }

    /**
     * Display a listing of the UserRoutineSubscription.
     */
    public function index(Request $request)
    {
        $userRoutineSubscriptions = $this->userRoutineSubscriptionRepository->paginate(10);

        return view('user_routine_subscriptions.index')
            ->with('userRoutineSubscriptions', $userRoutineSubscriptions);
    }

    /**
     * Show the form for creating a new UserRoutineSubscription.
     */
    public function create()
    {
        return view('user_routine_subscriptions.create');
    }

    /**
     * Store a newly created UserRoutineSubscription in storage.
     */
    public function store(CreateUserRoutineSubscriptionRequest $request)
    {
        $input = $request->all();

        $userRoutineSubscription = $this->userRoutineSubscriptionRepository->create($input);

        Flash::success('User Routine Subscription saved successfully.');

        return redirect(route('userRoutineSubscriptions.index'));
    }

    /**
     * Display the specified UserRoutineSubscription.
     */
    public function show($id)
    {
        $userRoutineSubscription = $this->userRoutineSubscriptionRepository->find($id);

        if (empty($userRoutineSubscription)) {
            Flash::error('User Routine Subscription not found');

            return redirect(route('userRoutineSubscriptions.index'));
        }

        return view('user_routine_subscriptions.show')->with('userRoutineSubscription', $userRoutineSubscription);
    }

    /**
     * Show the form for editing the specified UserRoutineSubscription.
     */
    public function edit($id)
    {
        $userRoutineSubscription = $this->userRoutineSubscriptionRepository->find($id);

        if (empty($userRoutineSubscription)) {
            Flash::error('User Routine Subscription not found');

            return redirect(route('userRoutineSubscriptions.index'));
        }

        return view('user_routine_subscriptions.edit')->with('userRoutineSubscription', $userRoutineSubscription);
    }

    /**
     * Update the specified UserRoutineSubscription in storage.
     */
    public function update($id, UpdateUserRoutineSubscriptionRequest $request)
    {
        $userRoutineSubscription = $this->userRoutineSubscriptionRepository->find($id);

        if (empty($userRoutineSubscription)) {
            Flash::error('User Routine Subscription not found');

            return redirect(route('userRoutineSubscriptions.index'));
        }

        $userRoutineSubscription = $this->userRoutineSubscriptionRepository->update($request->all(), $id);

        Flash::success('User Routine Subscription updated successfully.');

        return redirect(route('userRoutineSubscriptions.index'));
    }

    /**
     * Remove the specified UserRoutineSubscription from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $userRoutineSubscription = $this->userRoutineSubscriptionRepository->find($id);

        if (empty($userRoutineSubscription)) {
            Flash::error('User Routine Subscription not found');

            return redirect(route('userRoutineSubscriptions.index'));
        }

        $this->userRoutineSubscriptionRepository->delete($id);

        Flash::success('User Routine Subscription deleted successfully.');

        return redirect(route('userRoutineSubscriptions.index'));
    }
}
