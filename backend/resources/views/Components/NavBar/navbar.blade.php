<div class="col-md-2 bg-dark vh-80 position-fixed d-flex flex-column justify-content-center"
    style="z-index: 1050; inset: 0; display:flex;">
    <div class="d-flex align-items-center position-absolute" style="top: 20px; left: 20px;">
        <img src="/images/logo.jpg" alt="Logo" class="rounded-circle" style="width: 60px; height: 60px;">
        <p class="text-white mb-0 ms-3" style="font-size: 2rem; font-weight: bold;">Admin</p>
    </div>
    <nav class="navbar navbar-expand-md navbar-dark flex-column align-items-center">

        <ul class="navbar-nav flex-column w-100">
            {{-- <li class="nav-item d-flex justify-content-center">
                <a class="nav-link text-white" href="/home"><i class="fa-solid fa-house"></i>Home</a>
            </li> --}}
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2  text-white" href="/dashboard">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 text-white"
                    href="{{ route('product.index', ['user_id' => auth()->user()->id ?? 0]) }}">
                    <i class="fa-solid fa-whiskey-glass "></i>
                    <span>Beverages</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 text-white" href="/sales">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                    <span>Sales</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-3 text-white " href="/transaction/{{ Auth::id() }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Transactions</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-2 text-white" href="/users">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Create User</span>
                </a>
            </li>


            <!-- Logout Section -->
            <li class="nav-item d-flex justify-content-center align-items-center mt-auto"
                style="border-top: 1px solid grey; padding-top: 15px; padding-bottom:10px;">
                <a class="nav-link text-white" href="{{ route('admin.logout') }}">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.nav-item a');

        navItems.forEach(link => {
            if (link.href === window.location.href) {
                link.parentElement.classList.add('active');
            }
        });
    });
</script>

<style>
    nav {
        margin-top: 20vh;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: space-between;
        height: 70vh;
    }

    .nav-item.active {
        background-color: #57575a;
        border-radius: 5px;
        box-shadow: 0 4px 20px rgba(79, 78, 77, 0.279);
    }

    ul {
        flex-grow: 1;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    li {
        cursor: pointer;
        padding: 3px;

    }

    li i {
        margin-right: 15px;
        font-size: 1.5rem;
    }

    li a {
        text-decoration: none;
        font-size: 1rem;
        font-weight: bold;
    }

    li:hover {
        border-radius: 5px;
        background-color: #57575a;
    }

    li:not(:last-child) {
        margin-bottom: 15px;
        /* Add spacing between items */
    }

    li.mt-auto {
        margin-top: auto;
        /* Push logout to the bottom */
    }

    li.mt-auto a {
        color: white;
        font-weight: bold;
    }
</style>
