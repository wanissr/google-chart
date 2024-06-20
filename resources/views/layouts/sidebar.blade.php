<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" alt="Header Avatar">
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a class="dropdown-item " href="javascript:void();"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                    key="t-logout">@lang('translation.logout')</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
              <li class="menu-title"><span>เมนู</span></li>
              <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                  <i class="ri-pie-chart-line"></i> <span>ผลงานตีพิมพ์</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarDashboards">
                    <ul class="nav nav-sm flex-column">
                      <li class="nav-item">
                        <a href="/" class="nav-link">ระดับชาติ</a>
                      </li>
                      <li class="nav-item">
                        <a href="/international" class="nav-link">ระดับนานาชาติ</a>
                      </li>
                    </ul>
                </div>
              </li>

              <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarCitation" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCitation">
                  <i class="ri-pie-chart-line"></i> <span>Citation</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarCitation">
                    <ul class="nav nav-sm flex-column">
                      <li class="nav-item">
                        <a href="/citation" class="nav-link">ระดับชาติ</a>
                      </li>
                      <li class="nav-item">
                        <a href="/citation-international" class="nav-link">ระดับนานาชาติ</a>
                      </li>
                    </ul>
                </div>
              </li>

              <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarCitation" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCitation">
                  <i class="ri-pie-chart-line"></i> <span>งบประมาณ</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarCitation">
                    <ul class="nav nav-sm flex-column">
                      <li class="nav-item">
                        <a href="/research-budget" class="nav-link">ด้านการวิจัย</a>
                      </li>
                      <li class="nav-item">
                        <a href="/service-budget" class="nav-link">บริการวิชาการ</a>
                      </li>
                    </ul>
                </div>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="/intellectual">
                  <i class="ri-pie-chart-line"></i> <span>ผลงานทรัพย์สินทางปัญญา</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/presenting">
                  <i class="ri-pie-chart-line"></i> <span>ร่วมนำเสนอผลงานวิชาการ</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/publishing-support">
                  <i class="ri-pie-chart-line"></i> <span>ค่าสนับสนุนการตีพิมพ์</span>
                </a>
              </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
<div class="vertical-overlay"></div>
