<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        @foreach ( $user->user_modules as $key => $value )
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>{{ $value->access_modules->modules->modules_name }}</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
            @foreach ( $config_sidebar[strtolower($value->access_modules->modules->modules_name)] as $key_menu => $value_menu )
              <li><a href="{{ url('/').$value_menu}}"><i class="fa fa-circle-o"></i> {{ $key_menu }} </a></li>
            @endforeach
          </ul>
        </li>
        @endforeach
        <li><a href="{{ url('/')}}/access/logout"><i class="fa fa-circle-o"></i> Logout </a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>