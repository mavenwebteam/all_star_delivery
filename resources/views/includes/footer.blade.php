 <div class="container">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                <div class="footer-logo">
				
                 
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-6 col-lg-5">
                <div class="footer-menu">
                  <h4>Locations</h4>
                  <ul class="list">
                    <li><a href="#">Lidl</a></li>
                    <li><a href="#">Poundland</a></li>
                    <li><a href="#">Iceland</a></li>
                    <li><a href="#">WHSmith</a></li>
                    <li><a href="#">CoOp</a></li>
                  </ul>
                </div>
                <div class="footer-menu">
                  <h4>Quick Links</h4>
                  <ul class="list">
                    <li><a href="{{ URL::to('/pages/about-us') }}">About us</a></li>
                    <li><a href="{{ URL::to('/contact-us') }}">Contact us</a></li>
                    <li><a href="{{ URL::to('/pages/shop') }}">Shops </a></li>
                    <li><a href="{{ URL::to('/pages/faq') }}">FAQ</a></li>
                    <li><a href="{{ URL::to('/pages/user-agreement') }}">User Agreement</a></li>
                  </ul>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-3">
                <div class="social-footer">
                  <h4>Social With Us</h4>
                  <ul class="social-link">
                        <li><a target="_blank" href="{{Config::get('Site.facebook')}}"><i class="fab fa-facebook-f"></i></a></li>
                      <li><a target="_blank" href='{{Config::get("Site.twitter")}}'><i class="fab fa-twitter"></i></a></li>
                      <li><a target="_blank" href='{{Config::get("Site.youtube")}}'><i class="fab fa-youtube"></i></a></li>
                      <li><a target="_blank" href='{{Config::get("Site.linkedin_url")}}'><i class="fab fa-linkedin-in"></i></a></li>               
                  </ul>
                </div>
              </div>
              <div class="col-md-12">
                <div class="payment-methods">
                  <h4>Payment Methods</h4>
                  <div class="payment-methods-type">
                    <span><a href="#"><img src="{{asset('/assets/img/methods-1.png') }}" alt=""></a></span>
                    <span><a href="#"><img src="{{asset('/assets/img//methods-2.png') }}" alt=""></a></span>
                    <span><a href="#"><img src="{{asset('/assets/img/methods-3.png') }} " alt=""></a></span>
                    <span><a href="#"><img src="{{asset('/assets/img/methods-4.png') }}" alt=""></a></span>
                    <span><a href="#"><img src="{{asset('/assets/img/methods-5.png') }} " alt=""></a></span>
                  </div>
                </div>
              </div>
              <div class="col-md-12"><hr></div>
            </div>
            <div class="row">
              <div class="copy-right">
                <ul>
                  <li><a href="{{ URL::to('/pages/terms-of-use') }}">Terms of Service</a></li>
                  <li><a href="{{ URL::to('/pages/privacy-policy') }}">Privacy Policy</a></li>
                  <li><a href="{{ URL::to('/pages/cookie-policy') }}">Cookie Policy</a></li>
                </ul>
                <p>{{Config::get("Site.copy_right")}}</p>
              </div>
            </div>
            
        </div>