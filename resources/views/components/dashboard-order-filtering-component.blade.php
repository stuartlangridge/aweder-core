<nav class="dashboard__filters col col--lg-12-10">
    <ul class="dashboard__filters-list">
       <li @if($current === 'today') class="active" @endif>
           <a href="{{ route('admin.dashboard', ['period' => 'today']) }}">Today</a>
       </li>
        <li @if($current === 'this-week') class="active" @endif>
            <a href="{{ route('admin.dashboard', ['period' => 'this-week']) }}">This Week</a>
        </li>
        <li @if($current === 'this-month') class="active" @endif>
            <a href="{{ route('admin.dashboard', ['period' => 'this-month']) }}">This Month</a>
        </li>
    </ul>
</nav>
