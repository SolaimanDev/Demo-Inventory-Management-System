       <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Inventory System</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('products.index') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Products</span>
                </a>
                
            </li>
             <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" href="{{ route('sales.index') }}"> <i class="fas fa-fw fa-cog"></i>
                    <span>Sales</span></a>
                
            </li>
            <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.sales') }}"><i class="fas fa-fw fa-cog"></i>
                    <span>Sales Reports</span></a>
                
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.profit') }}">
                    <i class="fas fa-fw fa-table"></i>
                    <span> Profit Report</span></a>
            </li>



        </ul>