<header class="site-header">
    <div class="header-container">
        <div class="logo">
            <a href="/">Dnepr Shop</a>
        </div>
        <div class="menu_burger_cart">
            <button class="burger" id="burger">☰</button>
            <nav class="main-nav" id="mainNav">
                <ul class="lis_menu">
                    <li><a href="{{ route('catalog') }}" class="@active('catalog')">
                            Каталог
                        </a></li>
                    <li><a href="{{ route('about_us') }}" class="@active('about_us')">
                            Про нас
                        </a></li>
                    <li><a href="{{ route('contacts') }}" class="@active('contacts')">
                            Контакти
                        </a></li>
                </ul>
            </nav>
            <div class="cart-icon">
                <a href="#">
                    🛒 <span class="cart-count">2</span>
                </a>
            </div>
        </div>
    </div>
</header>