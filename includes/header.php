<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="../css/sweetalert2.css">
<link rel="stylesheet" href="../css/header.css">

<script src="../js/sweetalert2.js"></script>
<script src="../js/jquery.min.js"></script>

<header>

    <a href="../">
        <img src="../images/logo.png" alt="logo" class="header-logo">
    </a>

    <a class="header-menu-toggle" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
    </a>

    <div class="header-links">

        <a href="../" class="header-link">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>

        <a href="../dictionary/" class="header-link">
            <i class="fas fa-language"></i>
            <span>Dictionary</span>
        </a>

        <a href="../sentences/" class="header-link">
            <i class="fas s fa-comment-dots"></i>
            <span>Sentences</span>
        </a>

        <a href="../texts/" class="header-link">
            <i class="fas fa-book link-icon"></i>
            <span class="link-text">Texts</span>
        </a>

        <a href="../exercise/" class="header-link">
            <i class="fas fa-brain"></i>
            <span>Exercise</span>
        </a>

        <a href="../settings/" class="header-link">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>

    </div>

    <nav class="header-nav">
        <ul>
            <li><a href="../"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="../dictionary/"><i class="fas fa-language"></i> Dictionary</a></li>
            <li><a href="../sentences/"><i class="fas fa-comment-dots"></i> Sentences</a></li>
            <li><a href="../texts/"><i class="fas fa-book link-icon"></i>Texts</a></li>
            <li><a href="../exercise/"><i class="fas fa-brain"></i> Exercise</a></li>
            <li><a href="../settings/"><i class="fa-solid fa-gear"></i> Settings</a></li>
        </ul>
    </nav>

</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.header-menu-toggle');
        const links = document.querySelector('.header-links');
        const icon = menuToggle.querySelector('i');

        menuToggle.addEventListener('click', function() {
            if (links.classList.contains('active')) {
                links.classList.remove('active');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                links.classList.add('active');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        document.addEventListener('click', function(event) {
            const isClickInsideMenu = links.contains(event.target);
            const isClickInsideToggle = menuToggle.contains(event.target);

            if (!isClickInsideMenu && !isClickInsideToggle && links.classList.contains('active')) {
                links.classList.remove('active');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    });
</script>