<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <button class="nav-link {{ request()->is('/*') ? 'active' : '' }}" onclick="window.location.href='{{ route('frontend') }}'">
                            <i class="uim uim-airplay"></i> Beranda
                        </button>
                    </li>
                    @php
                        $menu_categories = \App\Models\Category::orderBy('created_at','asc')->get();
                    @endphp
                    @foreach ($menu_categories as $menu_category)
                    <li class="nav-item dropdown">
                        <button class="nav-link {{ request()->is('product/'.$menu_category->slug.'*') ? 'active' : '' }}" onclick="window.location.href='{{ route('frontend.product',['category' => $menu_category->slug]) }}'">
                            {{ $menu_category->name }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">
                            @foreach ($menu_category->category_detail_list as $category_detail_list)
                            <a onclick="window.location.href='{{ route('frontend.product_category',['category' => $menu_category->slug, 'category_id' => $category_detail_list->id]) }}'" class="dropdown-item">{{ $category_detail_list->name }}</a>
                            @endforeach
                            {{-- <a href="apps-chat" class="dropdown-item">Chat</a>
                            <a href="apps-file-manager" class="dropdown-item">File Manager</a> --}}
                        </div>
                    </li>
                    @endforeach

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement"
                            role="button">
                            <i class="uim uim-layer-group"></i> UI Elements <div class="arrow-down"></div>
                        </a>

                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                            aria-labelledby="topnav-uielement">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-alerts" class="dropdown-item">Alerts</a>
                                        <a href="ui-buttons" class="dropdown-item">Buttons</a>
                                        <a href="ui-cards" class="dropdown-item">Cards</a>
                                        <a href="ui-carousel" class="dropdown-item">Carousel</a>
                                        <a href="ui-dropdowns" class="dropdown-item">Dropdowns</a>
                                        <a href="ui-grid" class="dropdown-item">Grid</a>
                                        <a href="ui-images" class="dropdown-item">Images</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-lightbox" class="dropdown-item">Lightbox</a>
                                        <a href="ui-modals" class="dropdown-item">Modals</a>
                                        <a href="ui-offcanvas" class="dropdown-item">Offcanvas</a>
                                        <a href="ui-rangeslider" class="dropdown-item">Range Slider</a>
                                        <a href="ui-roundslider" class="dropdown-item">Round slider</a>
                                        <a href="ui-session-timeout" class="dropdown-item">Session Timeout</a>
                                        <a href="ui-progressbars" class="dropdown-item">Progress Bars</a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-sweet-alert" class="dropdown-item">Sweetalert 2</a>
                                        <a href="ui-tabs-accordions" class="dropdown-item">Tabs &
                                            Accordions</a>
                                        <a href="ui-typography" class="dropdown-item">Typography</a>
                                        <a href="ui-video" class="dropdown-item">Video</a>
                                        <a href="ui-general" class="dropdown-item">General</a>
                                        <a href="ui-rating" class="dropdown-item">Rating</a>
                                        <a href="ui-notifications" class="dropdown-item">Notifications</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps"
                            role="button">
                            <i class="uim uim-comment-message"></i> Apps <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-email" role="button">
                                    Email <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a href="email-inbox" class="dropdown-item">Inbox</a>
                                    <a href="email-read" class="dropdown-item">Read Email</a>
                                </div>
                            </div>

                            <a href="calendar" class="dropdown-item">Calendar</a>
                            <a href="apps-chat" class="dropdown-item">Chat</a>
                            <a href="apps-file-manager" class="dropdown-item">File Manager</a>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-invoice" role="button">
                                    Invoice <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-invoice">
                                    <a href="invoices" class="dropdown-item">Invoices</a>
                                    <a href="invoice-detail" class="dropdown-item">Invoice Detail</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-users" role="button">
                                    Users <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-users">
                                    <a href="users-list" class="dropdown-item">Users List</a>
                                    <a href="users-detail" class="dropdown-item">Users Detail</a>
                                </div>
                            </div>

                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components"
                            role="button">
                            <i class="uim uim-layer-group"></i> Components <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-form" role="button">
                                    Forms <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                    <a href="form-elements" class="dropdown-item">Basic Elements</a>
                                    <a href="form-validation" class="dropdown-item">Validation</a>
                                    <a href="form-plugins" class="dropdown-item">Plugins</a>
                                    <a href="form-editors" class="dropdown-item">Editors</a>
                                    <a href="form-uploads" class="dropdown-item">File Upload</a>
                                    <a href="form-wizard" class="dropdown-item">Wizard</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-table" role="button">
                                    Tables <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-table">
                                    <a href="tables-bootstrap" class="dropdown-item">Bootstrap Tables</a>
                                    <a href="tables-datatable" class="dropdown-item">Data Tables</a>
                                    <a href="tables-editable" class="dropdown-item">Editable Table</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-charts" role="button">
                                    Charts <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-chart-1" role="button">
                                            Apexcharts Part 1 <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-chart-1">
                                            <a href="charts-line" class="dropdown-item">Line</a>
                                            <a href="charts-area" class="dropdown-item">Area</a>
                                            <a href="charts-column" class="dropdown-item">Column</a>
                                            <a href="charts-bar" class="dropdown-item">Bar</a>
                                            <a href="charts-mixed" class="dropdown-item">Mixed</a>
                                            <a href="charts-timeline" class="dropdown-item">Timeline</a>
                                            <a href="charts-candlestick" class="dropdown-item">Candlestick</a>
                                            <a href="charts-boxplot" class="dropdown-item">Boxplot</a>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                            id="topnav-chart-2" role="button">
                                            Apexcharts Part 2 <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-chart-2">
                                            <a href="charts-bubble" class="dropdown-item">Bubble</a>
                                            <a href="charts-scatter" class="dropdown-item">Scatter</a>
                                            <a href="charts-heatmap" class="dropdown-item">Heatmap</a>
                                            <a href="charts-treemap" class="dropdown-item">Treemap</a>
                                            <a href="charts-pie" class="dropdown-item">Pie</a>
                                            <a href="charts-radialbar" class="dropdown-item">Radialbar</a>
                                            <a href="charts-radar" class="dropdown-item">Radar</a>
                                            <a href="charts-polararea" class="dropdown-item">Polararea</a>
                                        </div>
                                    </div>

                                    <a href="charts-echart" class="dropdown-item">E Charts</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-icons" role="button">
                                    Icons <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                    <a href="icons-remix" class="dropdown-item">Remix Icons</a>
                                    <a href="icons-materialdesign" class="dropdown-item">Material Design</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-map" role="button">
                                    Maps <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-map">
                                    <a href="maps-google" class="dropdown-item">Google Maps</a>
                                    <a href="maps-vector" class="dropdown-item">Vector Maps</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more"
                            role="button">
                            <i class="uim uim-box"></i> Extra Pages <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-auth" role="button">
                                    Authentication <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="auth-login" class="dropdown-item">Login</a>
                                    <a href="auth-register" class="dropdown-item">Register</a>
                                    <a href="auth-recoverpw" class="dropdown-item">Recover Password</a>
                                    <a href="auth-lock-screen" class="dropdown-item">Lock Screen</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-extra-pages" role="button">
                                    Extra Pages <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-extra-pages">
                                    <a href="pages-starter" class="dropdown-item">Starter Page</a>
                                    <a href="pages-maintenance" class="dropdown-item">Maintenance</a>
                                    <a href="pages-comingsoon" class="dropdown-item">Coming Soon</a>
                                    <a href="pages-faqs" class="dropdown-item">FAQs</a>
                                    <a href="pages-profile" class="dropdown-item">Profile</a>
                                    <a href="pages-pricing" class="dropdown-item">Pricing</a>
                                    <a href="pages-404" class="dropdown-item">Error 404</a>
                                    <a href="pages-500" class="dropdown-item">Error 500</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout"
                            role="button">
                            <i class="uim uim-window-grid"></i> <span>Layouts</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-layout">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-layout-verti" role="button">
                                    <span key="t-vertical">Vertical</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-verti">
                                    <a href="layouts-dark-sidebar" class="dropdown-item">Dark Sidebar</a>
                                    <a href="layouts-light-sidebar" class="dropdown-item">Light Sidebar</a>
                                    <a href="layouts-compact-sidebar" class="dropdown-item">Compact
                                        Sidebar</a>
                                    <a href="layouts-icon-sidebar" class="dropdown-item">Icon Sidebar</a>
                                    <a href="layouts-boxed" class="dropdown-item">Boxed Width</a>
                                    <a href="layouts-preloader" class="dropdown-item">Preloader</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#"
                                    id="topnav-layout-hori" role="button">
                                    <span key="t-horizontal">Horizontal</span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="layouts-horizontal" class="dropdown-item">Horizontal</a>
                                    <a href="layouts-hori-topbar-dark" class="dropdown-item">Topbar Dark</a>
                                    <a href="layouts-hori-light-header" class="dropdown-item">Light Header</a>
                                    <a href="layouts-hori-boxed-width" class="dropdown-item">Boxed width</a>
                                    <a href="layouts-hori-preloader" class="dropdown-item">Preloader</a>
                                </div>
                            </div>
                        </div>
                    </li> --}}

                </ul>
            </div>
        </nav>
    </div>
</div>
