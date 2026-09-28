@extends('layouts.app')
@section('content')
<div class="content card">
    <div class="container container d-flex align-items-center justify-content-center">
      <div class="row">
        <div class="col-md-6 img-front">
          {{-- <img src="{{asset('login_css/images/present.png')}}" alt="Image" class="img-fluid"> --}}
          <img src="{{asset('login_css/images/hera_front.png')}}" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6 ">
          <div class="row justify-content-center">
            <div class="col-md-8">
              <div class="mb-4">
              {{-- <h3>Reset Password </h3> --}}
              <div class="title">Reset Password</div>
              <p class="mb-4"><strong>{{ config('app.name', 'Laravel') }}</strong></p>
            </div>
            <form method="POST" action="{{ route('password.email') }}" onsubmit='show()'>
                @csrf
              <div class="form-group first">
                <label for="email">Email</label>
                <input  class="form-control" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email Address" required autofocus>
              
              </div>
              @if($errors->any())
                    <div class="form-group alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <strong>{{$errors->first()}}</strong>
                    </div>
                @endif
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
              @endif
              <input type="submit" value="{{ __('Send Password Reset Link') }}" class="btn text-white btn-block btn-primary">
              <br>
              <div class="d-flex mb-5 align-items-center">
                {{-- <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                  <input type="checkbox" checked="checked"/>
                  <div class="control__indicator"></div>
                </label> --}}
                <span class="ml-auto"><a  href="{{ url('/login') }}" onclick='show()' class="forgot-pass">Back to Log in</a></span> 
              </div>
              {{-- <span class="d-block text-left my-4 text-muted text-right"> or sign in with</span>
              
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
              </div> --}}
            </form>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>

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
</style>
@endsection
