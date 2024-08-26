<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark sidebar" style="height: 40cm; width: auto; background-color: #343a40;">
    <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
        <a href="" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5 d-none d-sm-inline mt-3">Menu</span>
        </a>
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
                <a href="{{route('workload.byTeam')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Team's workload</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('teams.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">My teams</span>
                </a>
            </li>
            <li>
                <a href="{{route('tasks.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">My tasks</span>
                </a>
              
            </li>
            <li class="nav-item">
                <a href="{{route('projects.index')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">My projects</span>
                </a>
            </li>
            <li>
                <a href="{{route('tasks.calendar')}}" class="nav-link align-middle px-0">
                    <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">My Calendar</span>
                </a>
              
            </li>
            
            <!-- Other menu items -->
        </ul>
        <hr>
      
    </div>
</div>