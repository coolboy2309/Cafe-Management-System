<base href="/public">
@include('css')
<header class="header">
    <nav class="navbar navbar-expand-lg">
        <div class="search-panel">
            <div class="search-inner d-flex align-items-center justify-content-center">
                <div class="close-btn">Close <i class="fa fa-close"></i></div>
            </div>
        </div>
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="navbar-header">
                <!-- Navbar Header--><a href="index.html" class="navbar-brand">
                    <div class="brand-text brand-big visible text-uppercase"><strong class="text">Dark Admin</strong></div>
                    
                </a>
                <!-- Sidebar Toggle Btn-->
                <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
            </div>
            <div class="right-menu list-inline no-margin-bottom">

                <!-- <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink1" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link messages-toggle"><i class="icon-email"></i><span class="badge dashbg-1">5</span></a>
                    <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages"><a href="#" class="dropdown-item message d-flex align-items-center">
                            <div class="profile"><img src="img/avatar-3.jpg" alt="..." class="img-fluid">
                                <div class="status online"></div>
                            </div>
                            <div class="content"> <strong class="d-block">Nadia Halsey</strong><span class="d-block">lorem ipsum dolor sit amit</span><small class="date d-block">9:30am</small></div>
                        </a>
                        <a href="#" class="dropdown-item message d-flex align-items-center">
                            <strong>See All Messages <i class="fa fa-angle-right"></i></strong></a>
                    </div>
                </div> -->
                <!-- Tasks-->
                <!-- <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink2" href="http://example.com" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link tasks-toggle"><i class="icon-new-file"></i><span class="badge dashbg-3">9</span></a>
                    <div aria-labelledby="navbarDropdownMenuLink2" class="dropdown-menu tasks-list"><a href="#" class="dropdown-item">
                            <div class="text d-flex justify-content-between"><strong>Task 1</strong><span>40% complete</span></div>
                            <div class="progress">
                                <div role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-1"></div>
                            </div>
                        </a><a href="#" class="dropdown-item">
                            <div class="text d-flex justify-content-between"><strong>Task 2</strong><span>20% complete</span></div>
                            <div class="progress">
                                <div role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-3"></div>
                            </div>
                        </a><a href="#" class="dropdown-item text-center"> <strong>See All Tasks <i class="fa fa-angle-right"></i></strong></a>
                    </div>
                </div> -->
                <!-- Tasks end-->
                <div class="list-inline-item ">

                    <a href="{{url('pro')}}" style="background-color:transparent; border:none; color:white;" class="nav-link"> <i class="fa fa-user"></i></a>

                </div>
                <div class="list-inline-item ">

                    <a href="{{url('Report')}}" style="background-color:transparent; border:none; color:white;" class="nav-link"> <i class="icon icon-chart"></i></a>

                </div>
                <!-- Log out-->
                <div class="list-inline-item logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="background-color:transparent; border:none; color:white;" class="nav-link">Logout <i class="icon-logout"></i></button>
                    </form>
                </div>
                <!-- Log out end-->
            </div>
        </div>
    </nav>
</header>