<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- <li class="menu-title">MENU</li> --}}
                <li>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @can('category-list')
                <li>
                    <a href="{{ route('category') }}">
                        <i class="fas fa-pencil-alt"></i>
                        <span>Category</span>
                    </a>
                </li>
                @endcan
                @can('product-list')
                <li class="menu-title">PRODUCT</li>
                <li>
                    <a href="{{ route('products') }}">
                        <i class="fas fa-boxes"></i>
                        <span>Product</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders') }}">
                        <i class="fas fa-box"></i>
                        <span>Orders</span>
                    </a>
                </li>
                @endcan
                @can('transaction-list')
                <li class="menu-title">TRANSACTION</li>
                <li>
                    <a href="{{ route('transactions') }}">
                        <i class="fas fa-credit-card"></i>
                        <span>Transaction</span>
                    </a>
                </li>
                @endcan
                @can('management-list')
                <li class="menu-title">MANAGEMENT</li>
                    @can('user-list')
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class="far fa-user-circle"></i>
                            <span>User</span>
                        </a>
                    </li>
                    @endcan
                    @can('role-list')
                    <li>
                        <a href="{{ route('roles.index') }}">
                            <i class="fas fa-key"></i>
                            <span>Role</span>
                        </a>
                    </li>
                    @endcan
                    @can('permission-list')
                    <li>
                        <a href="{{ route('permissions') }}">
                            <i class="fas fa-key"></i>
                            <span>Permission</span>
                        </a>
                    </li>
                    @endcan
                @endcan
                {{-- <li class="menu-title" data-key="t-menu">@lang('translation.Menu')</li>

                <li>
                    <a href="index">
                        <i data-feather="home"></i>
                        <span class="badge rounded-pill bg-success-subtle text-success float-end">9+</span>
                        <span data-key="t-dashboard">@lang('translation.Dashboards')</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-apps">@lang('translation.Apps')</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="shopping-cart"></i>
                        <span data-key="t-ecommerce">@lang('translation.Ecommerce')</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="ecommerce-products" key="t-products">@lang('translation.Products')</a></li>
                        <li><a href="ecommerce-product-detail" data-key="t-product-detail">@lang('translation.Product_Detail')</a></li>
                        <li><a href="ecommerce-orders" data-key="t-orders">@lang('translation.Orders')</a></li>
                        <li><a href="ecommerce-customers" data-key="t-customers">@lang('translation.Customers')</a></li>
                        <li><a href="ecommerce-cart" data-key="t-cart">@lang('translation.Cart')</a></li>
                        <li><a href="ecommerce-checkout" data-key="t-checkout">@lang('translation.Checkout')</a></li>
                        <li><a href="ecommerce-shops" data-key="t-shops">@lang('translation.Shops')</a></li>
                        <li><a href="ecommerce-add-product" data-key="t-add-product">@lang('translation.Add_Product')</a></li>
                        <li><a href="ecommerce-seller" data-key="t-seller">@lang('translation.Seller')
                            <span class="badge rounded-pill bg-danger-subtle text-danger float-end">New</span>
                        </a></li>
                        <li><a href="ecommerce-sale-details" data-key="t-sale-details">@lang('translation.Sale_details')
                            <span class="badge rounded-pill bg-danger-subtle text-danger float-end">New</span>
                        </a></li>
                    </ul>
                </li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
