<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f7f9;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #2f4f4f;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
        padding: 9px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    header a {
        text-decoration: none;
    }

    .logo {
        width: 70px;
        height: 70px;
        border-radius: 7px;
    }

    .menu-toggle {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
    }

    nav {
        display: flex;
        justify-content: end;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 18px;
        padding: 10px 15px;
        transition: color 0.3s ease-in-out;
        display: block;
    }

    nav ul li a i {
        margin-right: 8px;
    }

    nav ul li {
        position: relative;
    }

    nav ul li a:before {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
        border-radius: 5px;
        background-color: transparent;
        transition: background-color 0.3s ease-in-out;
    }

    nav ul li a:hover {
        color: #ff6347;
    }

    nav ul li a:hover:before {
        background-color: #ff6347;
    }

    button.menu-toggle {
        background-color: #2f4f4f;
    }

    .links {
        background-color: #2f4f4f;
        display: none;
        flex-direction: column;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        align-items: center;
        padding: 10px 30px;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .menu-toggle {
            display: block;
            position: absolute;
            right: 30px;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
        }

        header {
            padding: 5px 30px;
        }

        .logo {
            width: 45px;
            height: 45px;
        }

        .links.active {
            display: flex;
        }

        .links a {
            color: white;
            padding: 9px 15px;
            text-decoration: none;
        }

        .links a:hover {
            background-color: #ff6347;
        }

        .link {
            display: flex;
            align-items: center;
            background-color: #2f4f4f;
            color: #fff;
            padding: 12px 27px;
            margin: 5px 0;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            box-sizing: border-box;
        }

        .link i {
            margin-right: 12px;
            font-size: 24px;
        }

        nav ul {
            display: none;
        }

        .link:hover {
            background-color: #ff6347;
            transform: translateY(-2.5px);
        }
    }

    .swal2-popup {
        font-family: 'Arial', sans-serif;
    }

    .swal2-title {
        color: #2c3e50;
        font-size: 1.5rem;
    }

    .swal2-html-container {
        color: #34495e;
        font-size: 1rem;
        display: flex;
    }

    .swal2-confirm {
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5em 1em;
        margin-left: 10px;
    }

    .swal2-cancel {
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.5em 1em;
        margin-right: 10px;
    }

    .swal2-confirm:hover {
        background-color: #218838;
    }

    .swal2-cancel:hover {
        background-color: #c82333;
    }
</style>

<header>

    <a href="../">
        <img src="../images/logo.png" alt="logo" class="logo">
    </a>

    <button class="menu-toggle" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </button>

    <div class="links">

        <a href="../" class="link">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>

        <a href="../dictionary/" class="link">
            <i class="fas fa-language"></i>
            <span>Dictionary</span>
        </a>

        <a href="../sentences/" class="link">
            <i class="fas s fa-comment-dots"></i>
            <span>Sentences</span>
        </a>

        <a href="../exercise/" class="link">
            <i class="fas fa-brain"></i>
            <span>Exercise</span>
        </a>

        <a href="../settings/" class="link">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>

        <a href="#" class="link" onclick="confirmLogout(); return false;">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>

    <nav>
        <ul>
            <li><a href="../"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="../dictionary/"><i class="fas fa-language"></i> Dictionary</a></li>
            <li><a href="../sentences/"><i class="fas fa-comment-dots"></i> Sentences</a></li>
            <li><a href="../exercise/"><i class="fas fa-brain"></i> Exercise</a></li>
            <li><a href="../settings/"><i class="fa-solid fa-gear"></i> Settings</a></li>
            <li><a href="#" onclick="confirmLogout(); return false;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
        </ul>
    </nav>

</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const links = document.querySelector('.links');
        const icon = menuToggle.querySelector('i');

        menuToggle.addEventListener('click', function() {
            links.classList.toggle('active');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    });

    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, log out!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '..logout/';
            }
        });
    }
</script>