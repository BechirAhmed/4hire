@if (Schema::hasTable('users'))
    @php
    $inner_header = '';
    @endphp
    @if (Schema::hasTable('pages') || Schema::hasTable('site_managements'))
        @php
            $settings = array();
            $pages = App\Page::all();
            $setting = \App\SiteManagement::getMetaValue('settings');
            $logo = !empty($setting[0]['logo']) ? Helper::getHeaderLogo($setting[0]['logo']) : '/images/logo.png';
            $inner_header = !empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' ? 'wt-headervtwo' : '';
            $type = Helper::getAccessType();
            $page_id='';
            if (!empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' && Route::getCurrentRoute()->uri() != 'home') {
                if (Request::segment(1) == 'page') {
                    $selected_page_data = APP\Page::getPageData(Request::segment(2));
                    $selected_page = !empty($selected_page_data) ? APP\Page::find($selected_page_data->id) : '';
                    $page_id = !empty($selected_page) ? $selected_page->id : '';
                }
            } else {
                $page_id = APP\SiteManagement::getMetaValue('homepage');
            }
            $slider = Helper::getPageSlider($page_id);
        $categories = App\Category::latest()->get()->take(8);
        @endphp
    @endif
    @if (!empty($slider) && $slider['index'] == 0) 
        @if (!empty($slider['style']) && $slider['style'] == 'style3')
            <header id="wt-header" class="wt-header wt-headervfour wt-haslayout">
                <div class="wt-navigationarea">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @if (!empty($logo) || Schema::hasTable('site_managements'))
                                    <strong class="wt-logo"><a href="{{{ url('/') }}}"><img src="{{{ asset($logo) }}}" alt="{{{ trans('Logo') }}}"></a></strong>
                                @endif
                                <div class="wt-rightarea">
                                    @guest
                                        <div class="wt-loginarea">
                                            <div class="wt-loginoption">
                                                <a href="javascript:void(0);" id="wt-loginbtn" class="wt-loginbtn">{{{trans('lang.login') }}}</a>
                                                <div class="wt-loginformhold" @if ($errors->has('email') || $errors->has('password')) style="display: block;" @endif>
                                                    <div class="wt-loginheader">
                                                        <span>{{{ trans('lang.login') }}}</span>
                                                        <a href="javascript:;"><i class="fa fa-times"></i></a>
                                                    </div>
                                                    <form method="POST" action="{{ route('login') }}" class="wt-formtheme wt-loginform do-login-form">
                                                        @csrf
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <input id="email" type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                    placeholder="Email" required autofocus>
                                                                @if ($errors->has('email'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <input id="password" type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                    placeholder="Password" required>
                                                                @if ($errors->has('password'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('password') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="wt-logininfo">
                                                                    <button type="submit" class="wt-btn do-login-button">{{{ trans('lang.login') }}}</button>
                                                                <span class="wt-checkbox">
                                                                    <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                                    <label for="remember">{{{ trans('lang.remember') }}}</label>
                                                                </span>
                                                            </div>
                                                        </fieldset>
                                                        <div class="wt-loginfooterinfo">
                                                            @if (Route::has('password.request'))
                                                                <a href="{{ route('password.request') }}" class="wt-forgot-password">{{{ trans('lang.forget_pass') }}}</a>
                                                            @endif
                                                            <a href="{{{ route('register') }}}">{{{ trans('lang.create_account') }}}</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <a href="{{{ route('register') }}}" class="wt-btn">{{{ trans('lang.join_now') }}}</a>
                                        </div>
                                    @endguest
                                    @auth
                                        @php
                                            $user = !empty(Auth::user()) ? Auth::user() : '';
                                            $role = !empty($user) ? $user->getRoleNames()->first() : array();
                                            $profile = \App\User::find(Auth::user()->id)->profile;
                                            $user_image = !empty($profile) ? $profile->avater : '';
                                            $employer_job = \App\Job::select('status')->where('user_id', Auth::user()->id)->first();
                                            $profile_image = !empty($user_image) ? '/uploads/users/'.$user->id.'/'.$user_image : 'images/user-login.png';
                                            $payment_settings = \App\SiteManagement::getMetaValue('commision');
                                            $payment_module = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
                                            $employer_payment_module = !empty($payment_settings) && !empty($payment_settings[0]['employer_package']) ? $payment_settings[0]['employer_package'] : 'true';
                                        @endphp
                                        <div class="wt-userlogedin">
                                            <figure class="wt-userimg">
                                                {{-- <img src="{{{ asset($profile_image) }}}" alt="{{{ trans('lang.user_avatar') }}}"> --}}
                                                <img src="{{{ asset(Helper::getImage('uploads/users/' . Auth::user()->id, $profile->avater, '' , 'user.jpg')) }}}" alt="{{{ trans('lang.user_avatar') }}}">
                                            </figure>
                                            <div class="wt-username">
                                                <h3>{{{ Helper::getUserName(Auth::user()->id) }}}</h3>
                                                <span>{{{ !empty(Auth::user()->profile->tagline) ? str_limit(Auth::user()->profile->tagline, 26, '') : Helper::getAuthRoleName() }}}</span>
                                            </div>
                                            @if (file_exists(resource_path('views/extend/back-end/includes/profile-menu.blade.php'))) 
                                                @include('extend.back-end.includes.profile-menu')
                                            @else 
                                                @include('back-end.includes.profile-menu')
                                            @endif
                                        </div>
                                    @endauth
                                </div>
                                @if (file_exists(resource_path('views/extend/includes/menu.blade.php'))) 
                                    @include('extend.includes.menu')
                                @else 
                                    @include('includes.menu')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @else
            <header id="wt-header" class="wt-header wt-haslayout {{$inner_header}}">
                <div class="wt-navigationarea">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @auth
                                    {{ Helper::displayEmailWarning() }}
                                @endauth
                                @if (!empty($logo) || Schema::hasTable('site_managements'))
                                    <strong class="wt-logo"><a href="{{{ url('/') }}}"><img src="{{{ asset($logo) }}}" alt="{{{ trans('Logo') }}}"></a></strong>
                                @endif
                                @if (!empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' && Route::getCurrentRoute()->uri() != 'home')
                                    <search-form
                                    :placeholder="'{{ trans('lang.looking_for') }}'"
                                    :freelancer_placeholder="'{{ trans('lang.search_filter_list.freelancer') }}'"
                                    :employer_placeholder="'{{ trans('lang.search_filter_list.employers') }}'"
                                    :job_placeholder="'{{ trans('lang.search_filter_list.jobs') }}'"
                                    :service_placeholder="'{{ trans('lang.search_filter_list.services') }}'"
                                    :no_record_message="'{{ trans('lang.no_record') }}'"
                                    >
                                    </search-form>
                                @endif
                                <div class="wt-rightarea">
                                    @if (file_exists(resource_path('views/extend/includes/menu.blade.php')))
                                        @include('extend.includes.menu')
                                    @else
                                        @include('includes.menu')
                                    @endif
                                    @guest
                                        <div class="wt-loginarea">
                                            <div class="wt-loginoption">
                                                <a href="javascript:void(0);" id="wt-loginbtn" class="wt-loginbtn">{{{trans('lang.login') }}}</a>
                                                <div class="wt-loginformhold" @if ($errors->has('email') || $errors->has('password')) style="display: block;" @endif>
                                                    <div class="wt-loginheader">
                                                        <span>{{{ trans('lang.login') }}}</span>
                                                        <a href="javascript:;"><i class="fa fa-times"></i></a>
                                                    </div>
                                                    <form method="POST" action="{{ route('login') }}" class="wt-formtheme wt-loginform do-login-form">
                                                        @csrf
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <input id="email" type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                    placeholder="Email" required autofocus>
                                                                @if ($errors->has('email'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <input id="password" type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                    placeholder="Password" required>
                                                                @if ($errors->has('password'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('password') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="wt-logininfo">
                                                                    <button type="submit" class="wt-btn do-login-button">{{{ trans('lang.login') }}}</button>
                                                                <span class="wt-checkbox">
                                                                    <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                                    <label for="remember">{{{ trans('lang.remember') }}}</label>
                                                                </span>
                                                            </div>
                                                        </fieldset>
                                                        <div class="wt-loginfooterinfo">
                                                            @if (Route::has('password.request'))
                                                                <a href="{{ route('password.request') }}" class="wt-forgot-password">{{{ trans('lang.forget_pass') }}}</a>
                                                            @endif
                                                            <a href="{{{ route('register') }}}">{{{ trans('lang.create_account') }}}</a>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <a href="{{{ route('register') }}}" class="wt-btn">{{{ trans('lang.join_now') }}}</a>
                                        </div>
                                    @endguest
                                    @auth
                                        @php
                                            $user = !empty(Auth::user()) ? Auth::user() : '';
                                            $role = !empty($user) ? $user->getRoleNames()->first() : array();
                                            $profile = \App\User::find(Auth::user()->id)->profile;
                                            $user_image = !empty($profile) ? $profile->avater : '';
                                            $employer_job = \App\Job::select('status')->where('user_id', Auth::user()->id)->first();
                                            $profile_image = !empty($user_image) ? '/uploads/users/'.$user->id.'/'.$user_image : 'images/user-login.png';
                                            $payment_settings = \App\SiteManagement::getMetaValue('commision');
                                            $payment_module = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
                                            $employer_payment_module = !empty($payment_settings) && !empty($payment_settings[0]['employer_package']) ? $payment_settings[0]['employer_package'] : 'true';
                                        @endphp
                                            <div class="wt-userlogedin">
                                                <figure class="wt-userimg">
                                                    {{-- <img src="{{{ asset($profile_image) }}}" alt="{{{ trans('lang.user_avatar') }}}"> --}}
                                                    <img src="{{{ asset(Helper::getImage('uploads/users/' . Auth::user()->id, $profile->avater, '' , 'user.jpg')) }}}" alt="{{{ trans('lang.user_avatar') }}}">
                                                </figure>
                                                <div class="wt-username">
                                                    <h3>{{{ Helper::getUserName(Auth::user()->id) }}}</h3>
                                                    <span>{{{ !empty(Auth::user()->profile->tagline) ? str_limit(Auth::user()->profile->tagline, 26, '') : Helper::getAuthRoleName() }}}</span>
                                                </div>
                                                @if (file_exists(resource_path('views/extend/back-end/includes/profile-menu.blade.php')))
                                                    @include('extend.back-end.includes.profile-menu')
                                                @else
                                                    @include('back-end.includes.profile-menu')
                                                @endif
                                            </div>
                                    @endauth
                                    @if ($slider['style'] == 'style1')
                                        @if (!empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' && Route::getCurrentRoute()->uri() != 'home')
                                            <div class="wt-respsonsive-search"><a href="javascript:;" class="wt-searchbtn"><i class="fa fa-search"></i></a></div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @endif
    @else
        <?php
                $get_cat = DB::table('categories')->get();
        ?>
        <header id="wt-header" class="wt-header wt-haslayout {{$inner_header}}">
            <div class="wt-navigationarea">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            @auth
                                {{ Helper::displayEmailWarning() }}
                            @endauth
                            @if (!empty($logo) || Schema::hasTable('site_managements'))
                                <strong class="wt-logo"><a href="{{{ url('/') }}}"><img src="{{{ asset($logo) }}}" alt="{{{ trans('Logo') }}}"></a></strong>
                            @endif
                            @if (!empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' && Route::getCurrentRoute()->uri() != 'home')
                                <search-form
                                :placeholder="'{{ trans('lang.looking_for') }}'"
                                :freelancer_placeholder="'{{ trans('lang.search_filter_list.freelancer') }}'"
                                :employer_placeholder="'{{ trans('lang.search_filter_list.employers') }}'"
                                :job_placeholder="'{{ trans('lang.search_filter_list.jobs') }}'"
                                :service_placeholder="'{{ trans('lang.search_filter_list.services') }}'"
                                :no_record_message="'{{ trans('lang.no_record') }}'"
                                >
                                </search-form>
                            @endif
                            <div class="wt-rightarea">
                                <nav id="wt-nav" class="wt-nav navbar-expand-lg">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                        <i class="lnr lnr-menu"></i>
                                    </button>
                                    <div class="collapse navbar-collapse wt-navigation" id="navbarNav">
                                        @if (file_exists(resource_path('views/extend/includes/menu.blade.php')))
                                            @include('extend.includes.menu')
                                        @else
                                            @include('includes.menu')
                                        @endif
                                    </div>
                                </nav>
                                <div class="wt-userlogedin">
                                    <a href="javascript:void(0);" id="wt-langbtn" class="wt-langbtn">
                                        {{-- {{{ app()->getLocale() }}} --}}
                                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M12.87,15.07L10.33,12.56L10.36,12.53C12.1,10.59 13.34,8.36 14.07,6H17V4H10V2H8V4H1V6H12.17C11.5,7.92 10.44,9.75 9,11.35C8.07,10.32 7.3,9.19 6.69,8H4.69C5.42,9.63 6.42,11.17 7.67,12.56L2.58,17.58L4,19L9,14L12.11,17.11L12.87,15.07M18.5,10H16.5L12,22H14L15.12,19H19.87L21,22H23L18.5,10M15.88,17L17.5,12.67L19.12,17H15.88Z" />
                                        </svg>
                                    </a>
                                    <nav class="wt-usernav">
                                        <ul>
                                            @foreach (config('app.locales') as $code => $name)
                                                <li>
                                                    <a href="{{ route('change_locale', ['locale' => $code]) }}" class="{{ app()->getLocale() == $code ? 'active' : '' }}">
                                                        {{ $name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </nav>
                                </div>
                                @guest
                                    <div class="wt-loginarea">
                                        <div class="wt-loginoption">
                                            <a href="javascript:void(0);" id="wt-loginbtn" class="wt-loginbtn">{{{trans('lang.login') }}}</a>
                                            <div class="wt-loginformhold" @if ($errors->has('email') || $errors->has('password')) style="display: block;" @endif>
                                                <div class="wt-loginheader">
                                                    <span>{{{ trans('lang.login') }}}</span>
                                                    <a href="javascript:;"><i class="fa fa-times"></i></a>
                                                </div>
                                                <form method="POST" action="{{ route('login') }}" class="wt-formtheme wt-loginform do-login-form">
                                                    @csrf
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <input id="email" type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                placeholder="Email" required autofocus>
                                                            @if ($errors->has('email'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('email') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <input id="password" type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                                placeholder="Password" required>
                                                            @if ($errors->has('password'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('password') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                        <div class="wt-logininfo">
                                                                <button type="submit" class="wt-btn do-login-button">{{{ trans('lang.login') }}}</button>
                                                            <span class="wt-checkbox">
                                                                <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                                <label for="remember">{{{ trans('lang.remember') }}}</label>
                                                            </span>
                                                        </div>
                                                    </fieldset>
                                                    <div class="wt-loginfooterinfo">
                                                        @if (Route::has('password.request'))
                                                            <a href="{{ route('password.request') }}" class="wt-forgot-password">{{{ trans('lang.forget_pass') }}}</a>
                                                        @endif
                                                        <a href="{{{ route('register') }}}">{{{ trans('lang.create_account') }}}</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <a href="{{{ route('register') }}}" class="wt-btn">{{{ trans('lang.join_now') }}}</a>
                                    </div>
                                @endguest
                                @auth
                                    @php
                                        $user = !empty(Auth::user()) ? Auth::user() : '';
                                        $role = !empty($user) ? $user->getRoleNames()->first() : array();
                                        $profile = \App\User::find(Auth::user()->id)->profile;
                                        $user_image = !empty($profile) ? $profile->avater : '';
                                        $employer_job = \App\Job::select('status')->where('user_id', Auth::user()->id)->first();
                                        $profile_image = !empty($user_image) ? '/uploads/users/'.$user->id.'/'.$user_image : 'images/user-login.png';
                                        $payment_settings = \App\SiteManagement::getMetaValue('commision');
                                        $payment_module = !empty($payment_settings) && !empty($payment_settings[0]['enable_packages']) ? $payment_settings[0]['enable_packages'] : 'true';
                                        $employer_payment_module = !empty($payment_settings) && !empty($payment_settings[0]['employer_package']) ? $payment_settings[0]['employer_package'] : 'true';
                                    @endphp
                                        <div class="wt-userlogedin">
                                            <figure class="wt-userimg">
                                                {{-- <img src="{{{ asset($profile_image) }}}" alt="{{{ trans('lang.user_avatar') }}}"> --}}
                                                <img src="{{{ asset(Helper::getImage('uploads/users/' . Auth::user()->id, $profile->avater, '' , 'user.jpg')) }}}" alt="{{{ trans('lang.user_avatar') }}}">
                                            </figure>
                                            <div class="wt-username">
                                                <h3>{{{ Helper::getUserName(Auth::user()->id) }}}</h3>
                                                <span>{{{ !empty(Auth::user()->profile->tagline) ? str_limit(Auth::user()->profile->tagline, 26, '') : Helper::getAuthRoleName() }}}</span>
                                            </div>
                                            @if (file_exists(resource_path('views/extend/back-end/includes/profile-menu.blade.php')))
                                                @include('extend.back-end.includes.profile-menu')
                                            @else
                                                @include('back-end.includes.profile-menu')
                                            @endif
                                        </div>
                                @endauth
                                @if (!empty(Route::getCurrentRoute()) && Route::getCurrentRoute()->uri() != '/' && Route::getCurrentRoute()->uri() != 'home')
                                    <div class="wt-respsonsive-search"><a href="javascript:;" class="wt-searchbtn"><i class="fa fa-search"></i></a></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="wt-categoriesnav-holder">
                <div class="container-fluid">
                    <div class="row">
                        <nav class="wt-categories-nav navbar-expand-lg">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavbar" aria-controls="navbarNavbar" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="lnr lnr-menu"></i>
                            </button>
                            <div class="wt-categories-navbar wt-navigation navbar-collapse collapse" id="navbarNavbar">
                                <ul id="menu-second-menu" class="">
                                    @foreach($categories as $category)
                                    <li id="menu-item-1310" class="menu-item menu-item-type-taxonomy menu-item-object-project_cat menu-item-1310">
                                        <a href="{{url('search-results?type='.$type.'&category%5B%5D='.$category->slug)}}">{{$category->title}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
    @endif
@endif

