<nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="nav-profile-image">
                  <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
                  <span class="login-status online"></span>
                  <!--change to offline or busy as needed-->
                </div>
                <div class="nav-profile-text d-flex flex-column">
                  <span class="font-weight-bold mb-2">Daffa Sujianto</span>
                  <span class="text-secondary text-small">Project Manager</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-tag menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('pdf.*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('pdf.index') }}">
                <span class="menu-title">Generate PDF</span>
                <i class="mdi mdi-file menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Barang</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index') }}">
                <span class="menu-title">Form JS</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index2') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index2') }}">
                <span class="menu-title">Form JS 2</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index3') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index3') }}">
                <span class="menu-title">Form JS 3</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index4') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index4') }}">
                <span class="menu-title">Wilayah Ajax</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index5') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index5') }}">
                <span class="menu-title">Wilayah Axios</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index6') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index6') }}">
                <span class="menu-title">Barang Ajax</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('form-js.index7') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('form-js.index7') }}">
                <span class="menu-title">Barang Axios</span>
                <i class="mdi mdi-cube menu-icon"></i>
              </a>
            </li>
          </ul>
        </nav>