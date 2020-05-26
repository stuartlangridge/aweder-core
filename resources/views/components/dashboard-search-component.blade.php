<div class="dashboard__search">
    <form method="GET" action={{ route('admin.dashboard') }}>
        <input name="search" type="text" placeholder="Search...">
        <button type="submit">
            <span class="icon icon-search">@svg('search')</span>
        </button>
    </form>
</div>
