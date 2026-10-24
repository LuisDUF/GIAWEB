
<li class="nav-item">
    <a href="{{ route('exercises.index') }}" class="nav-link {{ Request::is('exercises*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Exercises</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('muscleGroups.index') }}" class="nav-link {{ Request::is('muscleGroups*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Muscle Groups</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('profileRestDays.index') }}" class="nav-link {{ Request::is('profileRestDays*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Profile Rest Days</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('profiles.index') }}" class="nav-link {{ Request::is('profiles*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Profiles</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('routines.index') }}" class="nav-link {{ Request::is('routines*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Routines</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('routineDays.index') }}" class="nav-link {{ Request::is('routineDays*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Routine Days</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('routineDayExercises.index') }}" class="nav-link {{ Request::is('routineDayExercises*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Routine Day Exercises</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('sessionExercises.index') }}" class="nav-link {{ Request::is('sessionExercises*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Session Exercises</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('sessionSets.index') }}" class="nav-link {{ Request::is('sessionSets*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Session Sets</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('userBodyLogs.index') }}" class="nav-link {{ Request::is('userBodyLogs*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>User Body Logs</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('userRoutineSubscriptions.index') }}" class="nav-link {{ Request::is('userRoutineSubscriptions*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>User Routine Subscriptions</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('weekDays.index') }}" class="nav-link {{ Request::is('weekDays*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Week Days</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('workoutSessionTags.index') }}" class="nav-link {{ Request::is('workoutSessionTags*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Workout Session Tags</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('workoutSessions.index') }}" class="nav-link {{ Request::is('workoutSessions*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Workout Sessions</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('workoutTags.index') }}" class="nav-link {{ Request::is('workoutTags*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Workout Tags</p>
    </a>
</li>
