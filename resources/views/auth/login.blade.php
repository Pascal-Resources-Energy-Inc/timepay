@extends('layouts.app')

@section('content')
  <div class="content card">
    <div class="container d-flex align-items-center justify-content-center">
      <div class="row">
        <div class="col-md-6 img-front">
          <img src="{{asset('login_css/images/hera_front.png')}}" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 ">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
              {{-- <h3>Sign In to </h3> --}}
              <div class="title">Login</div>
              <p class="mb-4"><strong>{{ config('app.name', 'Laravel') }}</strong></p>
            </div>
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}" onsubmit='show()'>
                @csrf
              <div class="form-group first">
                <label for="email">Email</label>
                <input  class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
              
              </div>
              <div class="form-group last mb-4">
                <label for="password">Password</label>
                <div class="password-field">
                  <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="********" name="password" required>
                  <button
                    class="password-toggle"
                    id="password-toggle"
                    type="button"
                    aria-controls="password"
                    aria-label="Show password"
                    aria-pressed="false"
                  >
                    <span class="password-eye" aria-hidden="true"></span>
                  </button>
                </div>
              </div>
              <div class="pass mb-4"><a href="{{ route('password.request') }}" onclick='show()'>Forgot password?</a></div>
              {{-- <div class="d-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                  <input type="checkbox" checked="checked"/>
                  <div class="control__indicator"></div>
                </label>
                <br>
                <span class="ml-auto"><a  href="{{ route('password.request') }}" style="text-decoration:none;" onclick='show()' class="forgot-pass">Forgot Password?</a></span> 
              </div> --}}
              @if($errors->any())
                    <div class="form-group alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <strong>{{$errors->first()}}</strong>
                    </div>
                @endif
              <input type="submit" value="Log In" class="btn text-white btn-block btn-primary">

              @php
                $quickLinks = [
                  [
                    'label' => 'COE Request',
                    'url' => url('/external/coe-request'),
                    'icon' => 'bi-file-earmark-text',
                  ],
                  [
                    'label' => 'Area Distributorship Application',
                    'url' => route('ada.create'),
                    'icon' => 'bi-shop',
                  ],
                  [
                    'label' => 'Exit Interview',
                    'url' => route('exit-interview.public.create'),
                    'icon' => 'bi-box-arrow-right',
                  ],
                  [
                    'label' => 'Marketing Material Release',
                    'url' => route('marketing-material-releases.public.create'),
                    'icon' => 'bi-megaphone',
                  ],
                ];
                $visibleQuickLinks = array_slice($quickLinks, 0, 2);
                $collapsedQuickLinks = array_slice($quickLinks, 2);
              @endphp

              <!-- <nav class="quick-links" aria-labelledby="quick-links-title"> -->
              <!--   <div class="quick-links-title" id="quick-links-title">Quick Links</div> -->
              <!--   <div class="quick-links-list"> -->
              <!--     @foreach($visibleQuickLinks as $quickLink) -->
              <!--       <a class="quick-link" href="{{ $quickLink['url'] }}"> -->
              <!--         <i class="bi {{ $quickLink['icon'] }}" aria-hidden="true"></i> -->
              <!--         <span>{{ $quickLink['label'] }}</span> -->
              <!--         <i class="bi bi-chevron-right quick-link-chevron" aria-hidden="true"></i> -->
              <!--       </a> -->
              <!--     @endforeach -->
              <!---->
              <!--     @if(count($collapsedQuickLinks) > 0) -->
              <!--       <div class="collapse" id="quick-links-more"> -->
              <!--         @foreach($collapsedQuickLinks as $quickLink) -->
              <!--           <a class="quick-link" href="{{ $quickLink['url'] }}"> -->
              <!--             <i class="bi {{ $quickLink['icon'] }}" aria-hidden="true"></i> -->
              <!--             <span>{{ $quickLink['label'] }}</span> -->
              <!--             <i class="bi bi-chevron-right quick-link-chevron" aria-hidden="true"></i> -->
              <!--           </a> -->
              <!--         @endforeach -->
              <!--       </div> -->
              <!--       <button -->
              <!--         class="quick-links-toggle collapsed" -->
              <!--         type="button" -->
              <!--         data-toggle="collapse" -->
              <!--         data-target="#quick-links-more" -->
              <!--         aria-expanded="false" -->
              <!--         aria-controls="quick-links-more" -->
              <!--       > -->
              <!--         <span class="quick-links-toggle-more">Show {{ count($collapsedQuickLinks) }} more links</span> -->
              <!--         <span class="quick-links-toggle-less">Show fewer links</span> -->
              <!--         <i class="bi bi-chevron-down" aria-hidden="true"></i> -->
              <!--       </button> -->
              <!--     @endif -->
              <!--   </div> -->
              <!-- </nav> -->

              <!-- <span class="d-block text-left my-4 text-muted text-right"> or sign in with</span>
              
              <div class="social-login text-right">
                <a href="#" class="facebook">
                  <span class="icon-facebook mr-3"></span> 
                </a>
                <a href="#" class="twitter">
                  <span class="icon-twitter mr-3"></span> 
                </a>
                <a href="#" class="google">
                  <span class="icon-google mr-3"></span> 
                </a>
              </div> -->
            </form>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>
{{-- @include('auth.register_employee') --}}
<style>
  body{
    height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #edf5fd;
    position: relative;
  }
  body::before{
    content: '';
    position: absolute;
    height: 100%;
    width: 100%;
    left: 0;
    top: 0;
    background: #8ad9f1;
    clip-path: polygon(86% 0, 100% 0, 100% 100%, 60% 100%);
  }
  .img-front {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .title {
    font-size: 30px;
    font-weight: 600;
    margin: 20px 0 10px 0;
    position: relative;
  }
  .title:before{
    content: '';
    position: absolute;
    height: 4px;
    width: 33px;
    left: 0px;
    bottom: 3px;
    border-radius: 5px;
    background: linear-gradient(to right, #8ad9f1 0%, #42cff9 100%);
  }
  
  .pass a {
    color: #12c8ff;
    font-size: 17px;
    text-decoration: none;
  }
  .pass a:hover {
    text-decoration: underline;
  }
  .password-field {
    position: relative;
  }
  .password-field .form-control {
    padding-right: 3.75rem;
  }
  .password-toggle {
    position: absolute;
    top: 50%;
    right: 0.375rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.775rem;
    height: 1.775rem;
    padding: 0;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    background: transparent;
    color: #7b8794;
    cursor: pointer;
    transform: translateY(-50%);
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
  }
  .password-toggle:hover {
    color: #514dbb;
    background: rgba(81, 77, 187, 0.08);
  }
  .password-toggle:focus {
    color: #514dbb;
    outline: 0;
    border-color: rgba(81, 77, 187, 0.45);
    box-shadow: 0 0 0 3px rgba(81, 77, 187, 0.12);
  }
  .password-eye {
    position: relative;
    display: block;
    width: 1rem;
    height: 0.65rem;
    border: 2px solid currentColor;
    border-radius: 50%;
    box-sizing: border-box;
  }
  .password-eye::before {
    content: '';
    position: absolute;
    top: 48%;
    left: 52%;
    width: 0.3125rem;
    height: 0.3125rem;
    border: 0;
    border-radius: 50%;
    background: currentColor;
    box-shadow: 0 0 0 0.1rem rgba(81, 77, 187, 0.12);
    transform: translate(-52%, -48%);
    box-sizing: border-box;
  }
  .password-eye::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 1.2rem;
    height: 2px;
    background: currentColor;
    transform: translate(-50%, -50%) rotate(-35deg) scaleX(0);
    transition: transform 0.15s ease;
  }
  .password-eye.is-visible::after {
    transform: translate(-50%, -50%) rotate(-35deg) scaleX(1);
  }
  .quick-links {
    margin-top: 1.5rem;
  }
  .quick-links-title {
    padding: 0 0.125rem 0.625rem;
    color: #243447;
    font-size: 0.875rem;
    font-weight: 600;
  }
  .quick-links-list {
    border-top: 1px solid #dfe5e1;
  }
  .quick-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-height: 52px;
    padding: 0.75rem 0.125rem;
    border-bottom: 1px solid #dfe5e1;
    color: #263746;
    font-size: 0.875rem;
    line-height: 1.3;
    text-decoration: none;
    transition: background-color 0.15s ease, color 0.15s ease;
  }
  .quick-link:hover,
  .quick-link:focus {
    background: #f5f8f6;
    color: #111827;
    text-decoration: none;
  }
  .quick-link .bi {
    flex: 0 0 auto;
    width: 1.25rem;
    color: #6f8b7f;
    font-size: 1.125rem;
    text-align: center;
  }
  .quick-link span {
    flex: 1 1 auto;
  }
  .quick-link .quick-link-chevron {
    width: auto;
    color: #9aa7a0;
    font-size: 0.875rem;
  }
  .quick-links-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    min-height: 42px;
    padding: 0.625rem 0.125rem;
    border: 0;
    border-bottom: 1px solid #dfe5e1;
    background: transparent;
    color: #6f8b7f;
    font-size: 0.8125rem;
    cursor: pointer;
    transition: background-color 0.15s ease, color 0.15s ease;
  }
  .quick-links-toggle:hover,
  .quick-links-toggle:focus {
    background: #f5f8f6;
    color: #263746;
    outline: 0;
  }
  .quick-links-toggle-more {
    display: none;
  }
  .quick-links-toggle.collapsed .quick-links-toggle-more {
    display: inline;
  }
  .quick-links-toggle.collapsed .quick-links-toggle-less {
    display: none;
  }
  .quick-links-toggle .bi {
    transition: transform 0.15s ease;
  }
  .quick-links-toggle:not(.collapsed) .bi {
    transform: rotate(180deg);
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var passwordInput = document.getElementById('password');
    var passwordToggle = document.getElementById('password-toggle');

    if (!passwordInput || !passwordToggle) {
      return;
    }

    passwordToggle.addEventListener('click', function () {
      var willShowPassword = passwordInput.type === 'password';
      var eye = passwordToggle.querySelector('.password-eye');

      passwordInput.type = willShowPassword ? 'text' : 'password';
      passwordToggle.setAttribute('aria-pressed', willShowPassword ? 'true' : 'false');
      passwordToggle.setAttribute('aria-label', willShowPassword ? 'Hide password' : 'Show password');

      if (eye) {
        eye.classList.toggle('is-visible', willShowPassword);
      }
    });
  });
</script>
@endsection
