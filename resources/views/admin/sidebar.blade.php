      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{$activePage == 'announcements' ? 'active' : ''}}">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>
                Announcements
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('employees.index') }}" class="nav-link {{$activePage == 'employees' ? 'active' : ''}}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Employees
              </p>
            </a>
          </li>
          @if(auth()->user()->role == '0')
          <li class="nav-item">
            <a href="{{ route('payrolls.index') }}" class="nav-link {{$activePage == 'payrolls' ? 'active' : ''}}">
              <i class="nav-icon fas fa-list"></i>
              <p>
                My Payrolls
              </p>
            </a>
          </li>
          @elseif(auth()->user()->role == '3')
          <li class="nav-item">
            <a href="{{ route('filter-payroll') }}" class="nav-link {{$activePage == 'payrollsAll' ? 'active' : ''}}">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Payrolls
              </p>
            </a>
          </li>
          @elseif(auth()->user()->role =='2')
          <li class="nav-item">
            <a href="{{ route('filter-salary') }}" class="nav-link {{$activePage == 'salariesAll' ? 'active' : ''}}">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Salaries
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('filter-remittance') }}" class="nav-link {{$activePage == 'remittancesAll' ? 'active' : ''}}">
              <i class="nav-icon fas fa-money-bill"></i>
              <p>
                Remittances
              </p>
            </a>
          </li>
          @elseif(auth()->user()->role == '1')
		  <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                References
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('refs.users') }}" class="nav-link {{$activePage == 'refs.users' ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('refs.fundsources') }}" class="nav-link {{$activePage == 'refs.fundsources' ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Fund Sources</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('refs.signatories') }}" class="nav-link {{$activePage == 'refs.signatories' ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Signatories</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
		<li class="nav-item">
            <a href="{{ route('report.utilization') }}" class="nav-link {{$activePage == 'reports' ? 'active' : ''}}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                Reports
              </p>
            </a>
          </li>
      </nav>
      <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
      </aside>