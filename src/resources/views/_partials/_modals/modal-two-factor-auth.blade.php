<!-- Two Factor Auth Modal -->

<div class="modal fade" id="twoFactorAuth" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h3 class="mb-2">Select Authentication Method</h3>
          <p class="text-muted">You also need to select a method by which the proxy authenticates to the directory serve.</p>
        </div>
        <div class="row">
          <div class="col-12 mb-3">
            <div class="form-check custom-option custom-option-basic">
              <label class="form-check-label custom-option-content ps-3" for="customRadioTemp1" data-bs-target="#twoFactorAuthOne" data-bs-toggle="modal">
                <input name="customRadioTemp" class="form-check-input d-none" type="radio" value="" id="customRadioTemp1" />
                <span class="d-flex align-items-start">
                  <i class="ti ti-settings ti-xl me-3"></i>
                  <span>
                    <span class="custom-option-header">
                      <span class="h4 mb-2">Authenticator Apps</span>
                    </span>
                    <span class="custom-option-body">
                      <span class="mb-0">Get code from an app like Google Authenticator or Microsoft Authenticator.</span>
                    </span>
                  </span>
                </span>
              </label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-check custom-option custom-option-basic">
              <label class="form-check-label custom-option-content ps-3" for="customRadioTemp2" data-bs-target="#twoFactorAuthTwo" data-bs-toggle="modal">
                <input name="customRadioTemp" class="form-check-input d-none" type="radio" value="" id="customRadioTemp2" />
                <span class="d-flex align-items-start">
                  <i class="ti ti-message ti-xl me-3"></i>
                  <span>
                    <span class="custom-option-header">
                      <span class="h4 mb-2">SMS</span>
                    </span>
                    <span class="custom-option-body">
                      <span class="mb-0">We will send a code via SMS if you need to use your backup login method.</span>
                    </span>
                  </span>
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Authentication App -->
<div class="modal fade" id="twoFactorAuthOne" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-2">
          <h3 class="mb-0">Add Authenticator App</h3>
        </div>
        <h5 class="mb-2 pt-1 text-break">Authenticator Apps</h5>
        <p class="mb-4">Using an authenticator app like Google Authenticator, Microsoft Authenticator, Authy, or 1Password, scan the QR code. It will generate a 6-digit code for you to enter below.</p>
        <div class="text-center">
          <img src="{{asset('assets/img/icons/misc/authentication-QR.png') }}" alt="QR Code" width="140">
        </div>
        <div class="alert alert-warning alert-dismissible my-3" role="alert">
          <h5 class="alert-heading mb-2 text-break">ASDLKNASDA9AHS678dGhASD78AB</h5>
          <p class="mb-0">If you're having trouble using the QR code, select manual entry on your app</p>
        </div>
        <div class="mb-4">
          <input type="email" class="form-control" id="twoFactorAuthInput" placeholder="Enter Authentication Code">
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-label-secondary me-sm-3 me-1" data-bs-toggle="modal" data-bs-target="#twoFactorAuth"><i class="ti ti-arrow-left ti-xs me-1 scaleX-n1-rtl"></i><span class="align-middle d-none d-sm-inline-block">Back</span></button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close"><span class="align-middle d-none d-sm-inline-block">Continue</span><i class="ti ti-arrow-right ti-xs ms-1 scaleX-n1-rtl"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Authentication via SMS -->
<div class="modal fade" id="twoFactorAuthTwo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
    <div class="modal-content p-3 p-md-5">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <h5 class="mb-2 pt-1">Verify Your Mobile Number for SMS</h5>
        <p class="mb-4">Enter your mobile phone number with country code, and we will send you a verification code.</p>
        <div class="mb-4">
          <input type="text" class="form-control" id="twoFactorAuthInputSms" placeholder="747 875 3459">
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-label-secondary me-sm-3 me-1" data-bs-toggle="modal" data-bs-target="#twoFactorAuth"><i class="ti ti-arrow-left ti-xs me-1 scaleX-n1-rtl"></i><span class="align-middle d-none d-sm-inline-block">Back</span></button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close"><span class="align-middle d-none d-sm-inline-block">Continue</span><i class="ti ti-arrow-right ti-xs ms-1 scaleX-n1-rtl"></i></button>
        </div>
      </div>
    </div>
  </div>
</div>
<!--/ Two Factor Auth Modal -->
