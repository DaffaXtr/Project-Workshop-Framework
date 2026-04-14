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
            <li class="nav-item {{ request()->routeIs('form-js.*') ? 'active show' : '' }}">
              <a class="nav-link" onclick="toggleSubmenu(event)" style="cursor: pointer;">
                <span class="menu-title">Form JS</span>
                <i class="mdi mdi-chevron-down menu-icon"></i>
              </a>
              <div class="collapse {{ request()->routeIs('form-js.*') ? 'show' : '' }}" id="formjs-menu">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index') ? 'active' : '' }}" href="{{ route('form-js.index') }}">Form JS</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index2') ? 'active' : '' }}" href="{{ route('form-js.index2') }}">Form JS 2</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index3') ? 'active' : '' }}" href="{{ route('form-js.index3') }}">Form JS 3</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index4') ? 'active' : '' }}" href="{{ route('form-js.index4') }}">Wilayah Ajax</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index5') ? 'active' : '' }}" href="{{ route('form-js.index5') }}">Wilayah Axios</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index6') ? 'active' : '' }}" href="{{ route('form-js.index6') }}">Barang Ajax</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('form-js.index7') ? 'active' : '' }}" href="{{ route('form-js.index7') }}">Barang Axios</a>
                  </li>
                </ul>
              </div>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.menu*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.menu.index') }}">
                <span class="menu-title">Menu</span>
                <i class="mdi mdi-book-open menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.pesanan*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.pesanan.index') }}">
                <span class="menu-title">Pesanan</span>
                <i class="mdi mdi-basket menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.vendor*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('admin.vendor.index') }}">
                <span class="menu-title">Vendor</span>
                <i class="mdi mdi-account-circle menu-icon"></i>
              </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.customer.*') ? 'active show' : '' }}">
              <a class="nav-link" onclick="toggleSubmenuCustomer(event)" style="cursor: pointer;">
                <span class="menu-title">Customer</span>
                <i class="mdi mdi-chevron-down menu-icon"></i>
              </a>
              <div class="collapse {{ request()->routeIs('admin.customer.*') ? 'show' : '' }}" id="customer-menu">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.customer.index') ? 'active' : '' }}" href="{{ route('admin.customer.index') }}">Data Customer</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('camera.blob.index') ? 'active' : '' }}" href="{{ route('camera.blob.index') }}">Tambah Customer 1</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('camera.path.index') ? 'active' : '' }}" href="{{ route('camera.path.index') }}">Tambah Customer 2</a>
                  </li>
                </ul>
              </div>
            </li>
          </ul>
        </nav>
        
        <script>
          function toggleSubmenu(event) {
            event.preventDefault();
            const submenu = document.getElementById('formjs-menu');
            submenu.classList.toggle('show');
          }
          function toggleSubmenuCustomer(event) {
            event.preventDefault();
            const submenu = document.getElementById('customer-menu');
            submenu.classList.toggle('show');
          }
        </script>