<!-- Header -->
@auth
<header class="fixed-top p-3 mb-6 w-full border-bottom" style="margin-left:33.9vh; background-color:#FFFFFF;">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-end">
        <div class="dropdown text-end flex flex-row items-center ms-auto justify-end gap-2">
            <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('img/pakvinsen.jpeg') }}" alt="mdo" width="32" height="32" class="rounded-circle">
            </a>
            <ul class="dropdown-menu text-small">
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Keluar</a></li>
            </ul>
        </div>
    </div>

</header>
@endauth